<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\News;
use App\Models\Registration;
use App\Models\RegistrationType;
use App\Support\RegistrantNotifier;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $audience = in_array($request->untuk, ['siswa', 'staf'], true) ? $request->untuk : null;

        $types = RegistrationType::when($audience, fn ($q) => $q->where('audience', $audience))
            ->orderByDesc('is_open')->orderBy('position')->get();

        $announcements = News::published()->where('channel', 'pendaftaran')->latest('published_at')->take(4)->get();

        $menu = Menu::forModule('pendaftaran', $audience);

        return view('web.registration.index', compact('types', 'audience', 'announcements', 'menu'));
    }

    public function show(string $slug)
    {
        $type = RegistrationType::where('slug', $slug)->firstOrFail();
        $menu = Menu::forModule('pendaftaran', $type->audience);
        $others = RegistrationType::where('id', '!=', $type->id)->where('audience', $type->audience)->orderBy('position')->get();

        return view('web.registration.show', compact('type', 'menu', 'others'));
    }

    public function form(string $slug)
    {
        $type = RegistrationType::with('fields')->where('slug', $slug)->firstOrFail();

        if (! $type->isAccepting()) {
            return redirect()->route('registration.show', $type->slug)->with('error', 'Pendaftaran untuk jalur ini sedang ditutup.');
        }

        $sections = $type->fields->groupBy('section');
        $menu = Menu::forModule('pendaftaran', $type->audience);

        return view('web.registration.form', compact('type', 'sections', 'menu'));
    }

    public function submit(Request $request, string $slug)
    {
        $type = RegistrationType::with('fields')->where('slug', $slug)->firstOrFail();

        if (! $type->isAccepting()) {
            return redirect()->route('registration.show', $type->slug)->with('error', 'Maaf, pendaftaran untuk jalur ini baru saja ditutup.');
        }

        $rules = [
            'full_name' => ['required', 'string', 'max:150'],
            'email'     => ['required', 'email', 'max:150'],
            'phone'     => ['required', 'string', 'max:30'],
            'consent'   => ['accepted'],
        ];
        $names = ['full_name' => 'nama lengkap', 'email' => 'email', 'phone' => 'nomor HP/WhatsApp', 'consent' => 'persetujuan'];

        foreach ($type->fields as $field) {
            $rules['f.'.$field->name] = $field->rules();
            $names['f.'.$field->name] = strtolower($field->label);
        }

        $data = $request->validate($rules, [], $names);

        $existing = Registration::where('registration_type_id', $type->id)
            ->where('email', $data['email'])->where('status', '!=', 'ditolak')->first();

        if ($existing) {
            return back()->withInput()->with('error', 'Email ini sudah terdaftar di jalur yang sama dengan nomor '.$existing->number.'. Gunakan menu Cek Status untuk memantau.');
        }

        $values = [];
        foreach ($type->fields->where('type', '!=', 'file') as $field) {
            $values[$field->name] = $data['f'][$field->name] ?? null;
        }

        $registration = $this->createWithNumber([
            'registration_type_id' => $type->id,
            'full_name'  => $data['full_name'],
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'data'       => $values,
            'status'     => 'baru',
            'ip_address' => $request->ip(),
        ]);

        // Dokumen disimpan di storage privat (tidak bisa dibuka publik)
        $files = [];
        foreach ($type->fields->where('type', 'file') as $field) {
            if ($file = $request->file('f.'.$field->name)) {
                $files[$field->name] = [
                    'path' => $file->store('registrations/'.$registration->id, 'local'),
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }
        }
        $registration->update(['files' => $files]);

        $registration->logs()->create([
            'status' => 'baru', 'title' => 'Formulir pendaftaran dikirim', 'is_public' => true,
        ]);

        // Notifikasi: email + WhatsApp ke panitia, dan konfirmasi ke pendaftar (kegagalan tidak mengganggu pendaftar)
        try {
            RegistrantNotifier::registrationReceived($registration);
        } catch (\Throwable $e) {
            report($e);
        }

        session()->put('registration_done', $registration->number);

        return redirect()->route('registration.success', $registration->number);
    }

    public function success(string $number)
    {
        // Halaman bukti hanya untuk browser yang baru saja mengirim formulir
        if (session('registration_done') !== $number) {
            return redirect()->route('registration.status', ['nomor' => $number]);
        }

        $registration = Registration::with('type')->where('number', $number)->firstOrFail();
        $menu = Menu::forModule('pendaftaran', $registration->type->audience);

        return view('web.registration.success', compact('registration', 'menu'));
    }

    public function status(Request $request)
    {
        $registration = null;
        $searched = $request->filled('nomor');

        if ($searched) {
            $request->validate(['nomor' => 'required|string|max:30', 'email' => 'required|string|max:150']);

            $registration = Registration::with(['type', 'logs' => fn ($q) => $q->where('is_public', true)])
                ->where('number', strtoupper(trim($request->nomor)))
                ->where(fn ($q) => $q->where('email', trim($request->email))->orWhere('phone', trim($request->email)))
                ->first();
        }

        $menu = Menu::forModule('cek_status');

        return view('web.registration.status', compact('registration', 'searched', 'menu'));
    }

    /** Membuat data pendaftar dengan nomor unik (diulang bila bentrok). */
    protected function createWithNumber(array $attributes): Registration
    {
        for ($try = 0; $try < 5; $try++) {
            try {
                return DB::transaction(fn () => Registration::create($attributes + ['number' => Registration::generateNumber()]));
            } catch (QueryException $e) {
                if ($try === 4) {
                    throw $e;
                }
            }
        }

        throw new \RuntimeException('Gagal membuat nomor pendaftaran.');
    }
}
