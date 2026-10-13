@php
    $key = 'f.'.$field->name;
    $input = 'f['.$field->name.']';
    $id = 'f_'.$field->name;
    $value = old($key);
@endphp
<div class="col-md-{{ $field->width }}">
    <label for="{{ $id }}" class="form-label">{{ $field->label }} @if ($field->is_required)<span class="text-danger">*</span>@endif</label>

    @switch($field->type)
        @case('textarea')
            <textarea name="{{ $input }}" id="{{ $id }}" rows="3" class="form-control @error($key) is-invalid @enderror" placeholder="{{ $field->placeholder }}" @required($field->is_required)>{{ $value }}</textarea>
            @break
        @case('select')
            <select name="{{ $input }}" id="{{ $id }}" class="form-select @error($key) is-invalid @enderror" @required($field->is_required)>
                <option value="">— Pilih —</option>
                @foreach ($field->optionList() as $opt)<option value="{{ $opt }}" @selected($value === $opt)>{{ $opt }}</option>@endforeach
            </select>
            @break
        @case('radio')
            <div class="d-flex flex-wrap gap-3 pt-1">
                @foreach ($field->optionList() as $i => $opt)
                    <div class="form-check">
                        <input class="form-check-input @error($key) is-invalid @enderror" type="radio" name="{{ $input }}" id="{{ $id }}_{{ $i }}" value="{{ $opt }}" @checked($value === $opt) @required($field->is_required)>
                        <label class="form-check-label" for="{{ $id }}_{{ $i }}">{{ $opt }}</label>
                    </div>
                @endforeach
            </div>
            @break
        @case('file')
            <input type="file" name="{{ $input }}" id="{{ $id }}" class="form-control @error($key) is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png,.webp" data-filename="{{ $id }}_name" @required($field->is_required)>
            <div class="form-text" id="{{ $id }}_name"></div>
            @break
        @default
            <input type="{{ $field->type }}" name="{{ $input }}" id="{{ $id }}" value="{{ $value }}" class="form-control @error($key) is-invalid @enderror" placeholder="{{ $field->placeholder }}" @required($field->is_required)>
    @endswitch

    @error($key)<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
    @if ($field->help_text)<div class="form-text">{{ $field->help_text }}</div>@endif
</div>
