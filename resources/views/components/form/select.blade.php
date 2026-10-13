@props(['name', 'label' => null, 'options' => [], 'value' => null, 'required' => false, 'help' => null, 'col' => 'col-12', 'placeholder' => null])
@php $id = 'f_'.$name; $current = (string) old($name, $value); @endphp
<div class="{{ $col }}">
    @if ($label)<label for="{{ $id }}" class="form-label">{{ $label }} @if ($required)<span class="text-danger">*</span>@endif</label>@endif
    <select name="{{ $name }}" id="{{ $id }}" {{ $attributes->merge(['class' => 'form-select'.($errors->has($name) ? ' is-invalid' : '')]) }} @required($required)>
        @if ($placeholder !== null)<option value="">{{ $placeholder }}</option>@endif
        @foreach ($options as $key => $text)
            <option value="{{ $key }}" @selected($current === (string) $key)>{{ $text }}</option>
        @endforeach
    </select>
    @error($name)<div class="invalid-feedback">{{ $message }}</div>@enderror
    @if ($help)<div class="form-text">{{ $help }}</div>@endif
</div>
