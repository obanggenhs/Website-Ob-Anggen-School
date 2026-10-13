@props(['name', 'label', 'checked' => false, 'help' => null, 'col' => 'col-12'])
@php $id = 'f_'.$name; @endphp
<div class="{{ $col }}">
    <div class="form-check form-switch">
        <input class="form-check-input" type="checkbox" role="switch" name="{{ $name }}" id="{{ $id }}" value="1" @checked(old($name, $checked))>
        <label class="form-check-label" for="{{ $id }}">{{ $label }}</label>
    </div>
    @if ($help)<div class="form-text ms-5 mt-0">{{ $help }}</div>@endif
</div>
