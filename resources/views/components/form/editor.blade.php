@props(['name', 'label' => null, 'value' => null, 'required' => false, 'help' => null, 'col' => 'col-12', 'height' => 220])
<div class="{{ $col }}">
    @if ($label)<label class="form-label">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>@endif
    <textarea name="{{ $name }}" class="form-control js-editor @error($name) is-invalid @enderror" data-height="{{ $height }}">{{ old($name, $value) }}</textarea>
    @error($name)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    @if ($help)<div class="form-text">{{ $help }}</div>@endif
</div>
