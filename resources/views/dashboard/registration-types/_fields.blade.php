{{-- Pembuat formulir pendaftaran --}}
<div class="row g-4">
    <div class="col-xl-7">
        <div class="card dash-card">
            <div class="card-header">
                <h2>Isian formulir</h2>
                <small class="text-muted-d">Nama lengkap, email, dan No. HP selalu ada otomatis di awal formulir.</small>
            </div>
            <div class="card-body">
                @foreach (\App\Models\RegistrationField::SECTIONS as $sectionKey => $sectionLabel)
                    @php $items = $type->fields->where('section', $sectionKey); @endphp
                    @if ($items->isNotEmpty())
                        <h3 class="h6 text-muted-d mt-2 mb-2">{{ $sectionLabel }}</h3>
                        @foreach ($items as $field)
                            <div class="block-item">
                                <span class="block-item__icon"><i class="bi {{ $field->type === 'file' ? 'bi-paperclip' : ($field->type === 'select' || $field->type === 'radio' ? 'bi-ui-radios' : 'bi-input-cursor-text') }}"></i></span>
                                <div class="min-w-0 flex-fill">
                                    <div class="block-item__title">{{ $field->label }} @if ($field->is_required)<span class="text-danger">*</span>@endif</div>
                                    <div class="block-item__preview">{{ \App\Models\RegistrationField::TYPES[$field->type] }} · lebar {{ ['12' => 'penuh', '6' => 'setengah', '4' => 'sepertiga'][$field->width] ?? $field->width }}{{ $field->options ? ' · '.implode(', ', $field->optionList()) : '' }}</div>
                                </div>
                                <div class="dash-actions">
                                    <form method="POST" action="{{ route('dashboard.fields.move', [$type, $field, 'naik']) }}">@csrf<button class="btn btn-sm btn-light btn-icon"><i class="bi bi-arrow-up"></i></button></form>
                                    <form method="POST" action="{{ route('dashboard.fields.move', [$type, $field, 'turun']) }}">@csrf<button class="btn btn-sm btn-light btn-icon"><i class="bi bi-arrow-down"></i></button></form>
                                    <button type="button" class="btn btn-sm btn-soft btn-icon" data-bs-toggle="modal" data-bs-target="#field{{ $field->id }}"><i class="bi bi-pencil"></i></button>
                                    @include('dashboard.partials.delete', ['url' => route('dashboard.fields.destroy', [$type, $field]), 'label' => 'isian '.$field->label])
                                </div>
                            </div>

                            <div class="modal fade" id="field{{ $field->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST" action="{{ route('dashboard.fields.update', [$type, $field]) }}" class="modal-content" data-field-form>
                                        @csrf @method('PUT')
                                        <div class="modal-header"><h3 class="modal-title fs-6">Ubah isian</h3><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                        <div class="modal-body">@include('dashboard.registration-types._field-inputs', ['f' => $field])</div>
                                        <div class="modal-footer"><button class="btn btn-primary">Simpan</button></div>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-xl-5">
        <form method="POST" action="{{ route('dashboard.fields.store', $type) }}" class="card dash-card" data-field-form>
            @csrf
            <div class="card-header"><h2><i class="bi bi-plus-square me-1"></i> Tambah isian</h2></div>
            <div class="card-body">@include('dashboard.registration-types._field-inputs', ['f' => new \App\Models\RegistrationField(['type' => 'text', 'section' => 'data_diri', 'width' => 6])])</div>
            <div class="card-footer text-end"><button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambahkan ke formulir</button></div>
        </form>
        <div class="alert alert-light border small mt-3">
            <i class="bi bi-lightbulb text-warning"></i> Isian bertipe <strong>Unggah dokumen</strong> otomatis masuk bagian Dokumen persyaratan dan menerima PDF/JPG/PNG maksimal 4 MB.
        </div>
    </div>
</div>
