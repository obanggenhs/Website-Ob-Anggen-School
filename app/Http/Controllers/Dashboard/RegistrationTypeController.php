<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\RegistrationField;
use App\Models\RegistrationType;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Jalur pendaftaran (buka/tutup, kuota, persyaratan) + pembuat formulir.
 */
class RegistrationTypeController extends Controller
{
    public function index()
    {
        $types = RegistrationType::withCount([
            'registrations', 'fields',
            'registrations as new_count' => fn ($q) => $q->where('status', 'baru'),
        ])->orderBy('position')->get();

        return view('dashboard.registration-types.index', compact('types'));
    }

    public function create()
    {
        return view('dashboard.registration-types.form', ['type' => new RegistrationType([
            'audience' => 'siswa', 'is_open' => false, 'position' => (int) RegistrationType::max('position') + 1,
        ])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->slug($data['name']);
        $data['image'] = Upload::store($request->file('image'), 'registration');

        $type = RegistrationType::create($data);
        $type->fields()->createMany(static::defaultFields($type->audience));
        activity_log('membuat jalur pendaftaran', $type->name);

        return redirect()->route('dashboard.registration-types.edit', [$type, 'tab' => 'formulir'])
            ->with('success', 'Jalur dibuat dengan formulir bawaan. Sesuaikan isian formulir di bawah.');
    }

    public function edit(Request $request, RegistrationType $registrationType)
    {
        return view('dashboard.registration-types.form', [
            'type' => $registrationType->load('fields'),
            'tab'  => $request->query('tab') === 'formulir' ? 'formulir' : 'info',
        ]);
    }

    public function update(Request $request, RegistrationType $registrationType)
    {
        $data = $this->validated($request);
        $data['image'] = Upload::replace($request->file('image'), $registrationType->image, 'registration');

        $registrationType->update($data);
        activity_log('mengubah jalur pendaftaran', $registrationType->name);

        return back()->with('success', 'Informasi jalur disimpan.');
    }

    public function toggle(RegistrationType $registrationType)
    {
        $registrationType->update(['is_open' => ! $registrationType->is_open]);
        activity_log($registrationType->is_open ? 'membuka pendaftaran' : 'menutup pendaftaran', $registrationType->name);

        return back()->with('success', $registrationType->name.' sekarang '.($registrationType->is_open ? 'DIBUKA' : 'DITUTUP').'.');
    }

    public function destroy(RegistrationType $registrationType)
    {
        if ($registrationType->registrations()->exists()) {
            return back()->with('error', 'Jalur ini sudah punya pendaftar. Tutup saja pendaftarannya, data pendaftar tetap aman.');
        }

        Upload::delete($registrationType->image);
        $registrationType->delete();
        activity_log('menghapus jalur pendaftaran', $registrationType->name);

        return redirect()->route('dashboard.registration-types.index')->with('success', 'Jalur dihapus.');
    }

    /* ---------------- Pembuat formulir ---------------- */

    public function storeField(Request $request, RegistrationType $registrationType)
    {
        $data = $this->validatedField($request);
        $data['name'] = $this->fieldName($registrationType, $data['label']);
        $data['position'] = (int) $registrationType->fields()->max('position') + 1;

        $registrationType->fields()->create($data);

        return redirect()->route('dashboard.registration-types.edit', [$registrationType, 'tab' => 'formulir'])
            ->with('success', 'Isian "'.$data['label'].'" ditambahkan.');
    }

    public function updateField(Request $request, RegistrationType $registrationType, RegistrationField $field)
    {
        abort_unless($field->registration_type_id === $registrationType->id, 404);
        $field->update($this->validatedField($request));

        return redirect()->route('dashboard.registration-types.edit', [$registrationType, 'tab' => 'formulir'])
            ->with('success', 'Isian "'.$field->label.'" diperbarui.');
    }

    public function moveField(RegistrationType $registrationType, RegistrationField $field, string $direction)
    {
        abort_unless($field->registration_type_id === $registrationType->id, 404);

        $fields = $registrationType->fields()->get()->values();
        $index = $fields->search(fn ($f) => $f->id === $field->id);
        $target = $direction === 'naik' ? $index - 1 : $index + 1;

        if ($fields->has($target)) {
            $moved = $fields->pull($index);
            $fields->splice($target, 0, [$moved]);
            $fields->values()->each(fn ($f, $i) => $f->update(['position' => $i + 1]));
        }

        return redirect()->route('dashboard.registration-types.edit', [$registrationType, 'tab' => 'formulir']);
    }

    public function destroyField(RegistrationType $registrationType, RegistrationField $field)
    {
        abort_unless($field->registration_type_id === $registrationType->id, 404);
        $field->delete();

        return redirect()->route('dashboard.registration-types.edit', [$registrationType, 'tab' => 'formulir'])
            ->with('success', 'Isian dihapus dari formulir.');
    }

    /* ------------------------------------------------------------------ */

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'name'             => ['required', 'string', 'max:120'],
            'audience'         => ['required', Rule::in(array_keys(RegistrationType::AUDIENCES))],
            'level_label'      => ['nullable', 'string', 'max:60'],
            'academic_year'    => ['nullable', 'regex:/^\d{4}\/\d{4}$/'],
            'summary'          => ['nullable', 'string', 'max:400'],
            'description'      => ['nullable', 'string'],
            'requirements'     => ['nullable', 'string', 'max:3000'],
            'steps'            => ['nullable', 'string', 'max:3000'],
            'accepted_message' => ['nullable', 'string', 'max:2000'],
            'open_at'          => ['nullable', 'date'],
            'close_at'         => ['nullable', 'date', 'after_or_equal:open_at'],
            'quota'            => ['nullable', 'integer', 'min:1'],
            'position'         => ['nullable', 'integer', 'min:0'],
            'image'            => ['nullable', 'image'],
        ], ['academic_year.regex' => 'Format tahun ajaran: 2027/2028'], ['name' => 'nama jalur', 'close_at' => 'tanggal tutup']);

        unset($data['image']);
        $data['description'] = clean_html($data['description'] ?? '');
        $data['position'] = $data['position'] ?? 0;
        $data['is_open'] = $request->boolean('is_open');

        return $data;
    }

    protected function validatedField(Request $request): array
    {
        $data = $request->validate([
            'label'       => ['required', 'string', 'max:120'],
            'type'        => ['required', Rule::in(array_keys(RegistrationField::TYPES))],
            'section'     => ['required', Rule::in(array_keys(RegistrationField::SECTIONS))],
            'options'     => ['nullable', 'required_if:type,select,radio', 'string', 'max:2000'],
            'placeholder' => ['nullable', 'string', 'max:150'],
            'help_text'   => ['nullable', 'string', 'max:200'],
            'width'       => ['required', Rule::in([12, 6, 4])],
        ], ['options.required_if' => 'Isi pilihan jawaban (satu pilihan per baris).'], ['label' => 'nama isian']);

        $data['is_required'] = $request->boolean('is_required');
        if ($data['type'] === 'file') {
            $data['section'] = 'dokumen';
        }

        return $data;
    }

    protected function fieldName(RegistrationType $type, string $label): string
    {
        $base = Str::limit(Str::slug($label, '_'), 50, '') ?: 'isian';
        $name = $base;
        $i = 2;
        while ($type->fields()->where('name', $name)->exists() || in_array($name, ['full_name', 'email', 'phone'], true)) {
            $name = $base.'_'.$i++;
        }

        return $name;
    }

    protected function slug(string $name): string
    {
        $base = Str::slug($name) ?: 'jalur';
        $slug = $base;
        $i = 2;
        // hindari bentrok dengan alamat tetap /pendaftaran/cek-status & /pendaftaran/berhasil
        while (RegistrationType::where('slug', $slug)->exists() || in_array($slug, ['cek-status', 'berhasil'], true)) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    /** Isian formulir bawaan saat jalur baru dibuat. */
    public static function defaultFields(string $audience): array
    {
        $fields = [
            ['label' => 'Tempat lahir', 'name' => 'tempat_lahir', 'type' => 'text', 'section' => 'data_diri', 'is_required' => true, 'width' => 6],
            ['label' => 'Tanggal lahir', 'name' => 'tanggal_lahir', 'type' => 'date', 'section' => 'data_diri', 'is_required' => true, 'width' => 6],
            ['label' => 'Jenis kelamin', 'name' => 'jenis_kelamin', 'type' => 'radio', 'options' => "Laki-laki\nPerempuan", 'section' => 'data_diri', 'is_required' => true, 'width' => 6],
            ['label' => 'Alamat tinggal', 'name' => 'alamat', 'type' => 'textarea', 'section' => 'data_diri', 'is_required' => true, 'width' => 12],
        ];

        if ($audience === 'siswa') {
            $fields = array_merge($fields, [
                ['label' => 'Asal sekolah', 'name' => 'asal_sekolah', 'type' => 'text', 'section' => 'data_diri', 'is_required' => false, 'width' => 6],
                ['label' => 'Kampus pilihan', 'name' => 'kampus', 'type' => 'select', 'options' => "Bokondini\nEragayam\nDogobak", 'section' => 'data_diri', 'is_required' => true, 'width' => 6],
                ['label' => 'Nama orang tua / wali', 'name' => 'nama_orang_tua', 'type' => 'text', 'section' => 'orang_tua', 'is_required' => true, 'width' => 6],
                ['label' => 'No. HP orang tua', 'name' => 'hp_orang_tua', 'type' => 'tel', 'section' => 'orang_tua', 'is_required' => true, 'width' => 6],
                ['label' => 'Pekerjaan orang tua', 'name' => 'pekerjaan_orang_tua', 'type' => 'text', 'section' => 'orang_tua', 'is_required' => false, 'width' => 6],
                ['label' => 'Kartu Keluarga (KK)', 'name' => 'file_kk', 'type' => 'file', 'section' => 'dokumen', 'is_required' => true, 'width' => 6, 'help_text' => 'PDF/JPG/PNG, maks. 4 MB'],
                ['label' => 'Akta kelahiran', 'name' => 'file_akta', 'type' => 'file', 'section' => 'dokumen', 'is_required' => false, 'width' => 6, 'help_text' => 'PDF/JPG/PNG, maks. 4 MB'],
                ['label' => 'Pasfoto terbaru', 'name' => 'file_foto', 'type' => 'file', 'section' => 'dokumen', 'is_required' => true, 'width' => 6, 'help_text' => 'JPG/PNG, maks. 4 MB'],
            ]);
        } else {
            $fields = array_merge($fields, [
                ['label' => 'Posisi yang dilamar', 'name' => 'posisi', 'type' => 'text', 'section' => 'data_diri', 'is_required' => true, 'width' => 6],
                ['label' => 'Pendidikan terakhir', 'name' => 'pendidikan', 'type' => 'select', 'options' => "SMA/SMK\nD3\nS1\nS2", 'section' => 'data_diri', 'is_required' => true, 'width' => 6],
                ['label' => 'Pengalaman kerja', 'name' => 'pengalaman', 'type' => 'textarea', 'section' => 'lainnya', 'is_required' => false, 'width' => 12],
                ['label' => 'Gereja asal', 'name' => 'gereja', 'type' => 'text', 'section' => 'lainnya', 'is_required' => false, 'width' => 6],
                ['label' => 'Curriculum Vitae (CV)', 'name' => 'file_cv', 'type' => 'file', 'section' => 'dokumen', 'is_required' => true, 'width' => 6, 'help_text' => 'PDF, maks. 4 MB'],
                ['label' => 'Scan KTP', 'name' => 'file_ktp', 'type' => 'file', 'section' => 'dokumen', 'is_required' => true, 'width' => 6, 'help_text' => 'PDF/JPG/PNG, maks. 4 MB'],
                ['label' => 'Ijazah terakhir', 'name' => 'file_ijazah', 'type' => 'file', 'section' => 'dokumen', 'is_required' => false, 'width' => 6, 'help_text' => 'PDF/JPG/PNG, maks. 4 MB'],
            ]);
        }

        foreach ($fields as $i => &$field) {
            $field['position'] = $i + 1;
        }

        return $fields;
    }
}
