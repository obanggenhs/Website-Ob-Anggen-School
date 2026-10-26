<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RegistrationType;
use App\Support\RegistrantNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Proses pendaftar: verifikasi berkas → jadwal tes/wawancara → keputusan diterima/tidak.
 */
class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $registrations = $this->filtered($request)->with('type')->latest()->paginate(15)->withQueryString();

        $counts = Registration::selectRaw('status, count(*) as total')
            ->when($request->filled('jalur'), fn ($q) => $q->where('registration_type_id', $request->jalur))
            ->groupBy('status')->pluck('total', 'status');

        $types = RegistrationType::orderBy('position')->get();

        return view('dashboard.applicants.index', compact('registrations', 'counts', 'types'));
    }

    public function show(Registration $applicant)
    {
        $applicant->load(['type.fields', 'logs.user', 'handler', 'notifications.user']);

        return view('dashboard.applicants.show', ['registration' => $applicant]);
    }

    /** Ubah status + pesan untuk pendaftar (tampil di halaman cek status & email). */
    public function status(Request $request, Registration $applicant)
    {
        $data = $request->validate([
            'status'      => ['required', Rule::in(array_keys(Registration::STATUSES))],
            'public_note' => ['nullable', 'string', 'max:2000'],
        ], [], ['public_note' => 'pesan untuk pendaftar']);

        $applicant->update([
            'status'      => $data['status'],
            'public_note' => $data['public_note'] ?: $applicant->public_note,
            'handled_by'  => $request->user()->id,
            'decided_at'  => in_array($data['status'], ['diterima', 'ditolak'], true) ? now() : null,
        ]);

        $applicant->logs()->create([
            'user_id' => $request->user()->id, 'status' => $data['status'],
            'title' => 'Status: '.$applicant->status_label, 'note' => $data['public_note'] ?? null, 'is_public' => true,
        ]);

        $sent = $this->notify($request, $applicant, $data['public_note'] ?? null, 'status');
        activity_log('mengubah status pendaftar', $applicant->number.' → '.$applicant->status_label);

        return $this->backWithResult('Status diubah menjadi "'.$applicant->status_label.'".', $sent);
    }

    public function schedule(Request $request, Registration $applicant)
    {
        $data = $request->validate([
            'schedule_at'    => ['required', 'date'],
            'schedule_place' => ['required', 'string', 'max:200'],
        ], [], ['schedule_at' => 'tanggal & jam', 'schedule_place' => 'tempat']);

        $applicant->update($data + [
            'status'     => in_array($applicant->status, ['baru', 'verifikasi', 'perbaikan'], true) ? 'seleksi' : $applicant->status,
            'handled_by' => $request->user()->id,
        ]);

        $note = tgl($applicant->schedule_at, 'l, d F Y').' pukul '.$applicant->schedule_at->format('H.i').' WIT · '.$applicant->schedule_place;
        $applicant->logs()->create([
            'user_id' => $request->user()->id, 'status' => $applicant->status,
            'title' => 'Jadwal tes / wawancara ditetapkan', 'note' => $note, 'is_public' => true,
        ]);

        $sent = $this->notify($request, $applicant, 'Jadwal tes/wawancara: '.$note, 'jadwal');
        activity_log('menjadwalkan seleksi', $applicant->number);

        return $this->backWithResult('Jadwal disimpan.', $sent);
    }

    /** Catatan internal (tidak terlihat pendaftar). */
    public function note(Request $request, Registration $applicant)
    {
        $data = $request->validate(['note' => ['required', 'string', 'max:2000']], [], ['note' => 'catatan']);

        $applicant->logs()->create([
            'user_id' => $request->user()->id, 'title' => 'Catatan internal', 'note' => $data['note'], 'is_public' => false,
        ]);
        $applicant->update(['internal_note' => $data['note']]);

        return back()->with('success', 'Catatan disimpan.');
    }

    /** Kirim ulang kabar status terbaru lewat satu saluran (email / whatsapp). */
    public function resend(Request $request, Registration $applicant, string $channel)
    {
        $sent = RegistrantNotifier::send(
            $applicant, $applicant->public_note, $request->user(),
            email: $channel === 'email', whatsapp: $channel === 'whatsapp', event: 'ulang',
        );

        if (! $sent) {
            return back()->with('error', 'Saluran '.($channel === 'email' ? 'email' : 'WhatsApp').' dimatikan di Pengaturan Website › Email & WhatsApp.');
        }

        activity_log('mengirim ulang pesan pendaftar', $applicant->number.' ('.$channel.')');

        return $this->backWithResult('', $sent);
    }

    public function file(Registration $applicant, string $key)
    {
        $file = $applicant->files[$key] ?? null;
        abort_unless($file && Storage::disk('local')->exists($file['path']), 404);

        return Storage::disk('local')->response($file['path'], $file['name'] ?? basename($file['path']));
    }

    public function print(Registration $applicant)
    {
        $applicant->load('type.fields');

        return view('dashboard.applicants.print', ['registration' => $applicant]);
    }

    public function export(Request $request): StreamedResponse
    {
        $rows = $this->filtered($request)->with('type.fields')->oldest()->get();
        $type = $request->filled('jalur') ? RegistrationType::with('fields')->find($request->jalur) : null;
        $extra = $type ? $type->fields->where('type', '!=', 'file') : collect();

        activity_log('mengunduh data pendaftar', $rows->count().' baris');

        return response()->streamDownload(function () use ($rows, $extra) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");   // agar Excel membaca UTF-8
            fputcsv($out, array_merge(['Nomor', 'Tanggal daftar', 'Jalur', 'Nama lengkap', 'Email', 'No. HP', 'Status', 'Jadwal', 'Tempat'], $extra->pluck('label')->all()), ';');

            foreach ($rows as $r) {
                fputcsv($out, array_merge([
                    $r->number, $r->created_at->format('d/m/Y H:i'), $r->type->name, $r->full_name, $r->email, $r->phone,
                    $r->status_label, $r->schedule_at?->format('d/m/Y H:i'), $r->schedule_place,
                ], $extra->map(fn ($f) => $r->data[$f->name] ?? '')->all()), ';');
            }
            fclose($out);
        }, 'pendaftar-'.now()->format('Ymd-His').'.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function destroy(Registration $applicant)
    {
        Storage::disk('local')->deleteDirectory('registrations/'.$applicant->id);
        $applicant->delete();
        activity_log('menghapus pendaftar', $applicant->number.' '.$applicant->full_name);

        return redirect()->route('dashboard.applicants.index')->with('success', 'Data pendaftar dihapus.');
    }

    /* ------------------------------------------------------------------ */

    protected function filtered(Request $request)
    {
        return Registration::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('jalur'), fn ($q) => $q->where('registration_type_id', $request->jalur))
            ->when($request->filled('cari'), fn ($q) => $q->where(fn ($w) => $w
                ->where('full_name', 'like', '%'.$request->cari.'%')
                ->orWhere('number', 'like', '%'.$request->cari.'%')
                ->orWhere('email', 'like', '%'.$request->cari.'%')));
    }

    /** Kirim status ke email + WhatsApp pendaftar (otomatis, kecuali admin mematikan salah satunya). */
    protected function notify(Request $request, Registration $registration, ?string $note, string $event): array
    {
        return RegistrantNotifier::send(
            $registration, $note, $request->user(),
            email: $request->boolean('send_email', true),
            whatsapp: $request->boolean('send_wa', true),
            event: $event,
        );
    }

    protected function backWithResult(string $prefix, array $sent)
    {
        [$ok, $fail] = RegistrantNotifier::summarize($sent);

        $redirect = back();
        if (trim($prefix.' '.$ok) !== '') {
            $redirect->with('success', trim($prefix.' '.$ok));
        }
        if ($fail !== '') {
            $redirect->with('error', $fail);
        }

        return $redirect;
    }
}
