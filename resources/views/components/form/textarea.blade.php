@props(['name', 'label' => null, 'value' => null, 'required' => false, 'help' => null, 'col' => 'col-12', 'rows' => 3])
@php $id = 'f_'.$name; @endphp
<div class="{{ $col }}">
    @if ($label)<label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>@endif
    <textarea name="{{ $name }}" id="{{ $id }}" rows="{{ $rows }}" {{ $attributes->merge(['class' => 'form-control'.($errors->has($name) ? ' is-invalid' : '')]) }} @required($required)>{{ old($name, $value) }}</textarea>
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
    @if ($help)<div class="form-text">{{ $help }}</div>@endif
</div>
