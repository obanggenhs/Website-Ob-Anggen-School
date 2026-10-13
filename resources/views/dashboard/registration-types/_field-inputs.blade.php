<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Nama isian <span class="text-danger">*</span></label>
        <input type="text" name="label" value="{{ $f->label }}" class="form-control" required maxlength="120" placeholder="mis. Nama ayah">
    </div>
    <div class="col-md-6">
        <label class="form-label">Jenis</label>
        <select name="type" class="form-select">
            @foreach (\App\Models\RegistrationField::TYPES as $k => $v)<option value="{{ $k }}" @selected($f->type === $k)>{{ $v }}</option>@endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Bagian</label>
        <select name="section" class="form-select">
            @foreach (\App\Models\RegistrationField::SECTIONS as $k => $v)<option value="{{ $k }}" @selected($f->section === $k)>{{ $v }}</option>@endforeach
        </select>
    </div>
    <div class="col-12 d-none" data-options-wrap>
        <label class="form-label">Pilihan jawaban (satu baris satu pilihan)</label>
        <textarea name="options" rows="3" class="form-control">{{ $f->options }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Contoh isian</label>
        <input type="text" name="placeholder" value="{{ $f->placeholder }}" class="form-control">
    </div>
    <div class="col-md-6">
        <label class="form-label">Lebar</label>
        <select name="width" class="form-select">
            @foreach (['12' => 'Penuh', '6' => 'Setengah', '4' => 'Sepertiga'] as $k => $v)<option value="{{ $k }}" @selected((string) $f->width === $k)>{{ $v }}</option>@endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Petunjuk singkat</label>
        <input type="text" name="help_text" value="{{ $f->help_text }}" class="form-control">
    </div>
    <div class="col-12">
        <div class="form-check form-switch">
            <input class="form-check-input" type="checkbox" name="is_required" value="1" id="req{{ $f->id ?? 'new' }}" @checked($f->is_required)>
            <label class="form-check-label" for="req{{ $f->id ?? 'new' }}">Wajib diisi</label>
        </div>
    </div>
</div>
