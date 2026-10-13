@props(['name', 'label' => 'Gambar', 'value' => null, 'help' => 'JPG, PNG, atau WEBP. Tanpa batas ukuran.', 'col' => 'col-12', 'remove' => null, 'logo' => false])
@php $id = 'f_'.$name; @endphp
<div class="{{ $col }}">
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
    <img src="{{ $value ? media_url($value) : '' }}" id="{{ $id }}_preview" alt="" class="img-preview {{ $logo ? 'img-preview--logo' : '' }} mb-2 {{ $value ? '' : 'd-none' }}">
    <input type="file" name="{{ $name }}" id="{{ $id }}" accept="image/*" data-preview="{{ $id }}_preview" class="form-control @error($name) is-invalid @enderror">
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
    @if ($remove && $value)
        <div class="form-check mt-1">
            <input class="form-check-input" type="checkbox" name="{{ $remove }}" value="1" id="{{ $id }}_remove">
            <label class="form-check-label small" for="{{ $id }}_remove">Hapus gambar ini</label>
        </div>
    @endif
    @if ($help)<div class="form-text">{{ $help }}</div>@endif
</div>
