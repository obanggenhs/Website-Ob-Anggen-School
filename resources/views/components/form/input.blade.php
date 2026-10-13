@props(['name', 'label' => null, 'type' => 'text', 'value' => null, 'required' => false, 'help' => null, 'col' => 'col-12', 'prefix' => null])
@php
    $key = str_replace(['[', ']'], ['.', ''], $name);
    $id = 'f_'.str_replace('.', '_', $key);
@endphp
<div class="{{ $col }}">
    @if ($label)<label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>@endif
    @if ($prefix)<div class="input-group"><span class="input-group-text small">{{ $prefix }}</span>@endif
    <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}" value="{{ $type === 'password' ? '' : old($key, $value) }}"
           {{ $attributes->merge(['class' => 'form-control'.($errors->has($key) ? ' is-invalid' : '')]) }} @required($required)>
    @if ($prefix)</div>@endif
    @error($key)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    @if ($help)<div class="form-text">{{ $help }}</div>@endif
</div>
