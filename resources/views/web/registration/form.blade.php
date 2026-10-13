@extends('layouts.web-full')

@section('title', 'Formulir '.$type->name)
@section('page_title', 'Formulir '.$type->name)
@section('page_subtitle', 'Lengkapi data berikut dengan benar. Tanda * wajib diisi.')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('registration.index') }}">Pendaftaran</a></li>
    <li class="breadcrumb-item"><a href="{{ route('registration.show', $type->slug) }}">{{ $type->name }}</a></li>
    <li class="breadcrumb-item active">Formulir</li>
@endsection
@section('no_intro', true)

@section('page')
    <div class="row justify-content-center">
        <div class="col-lg-9">
            @if ($errors->any())
                <div class="alert alert-danger"><i class="bi bi-exclamation-triangle me-1"></i> Ada {{ $errors->count() }} isian yang perlu diperbaiki. Periksa tanda merah di bawah.</div>
            @endif
            @if (session('error'))
                <div class="alert alert-warning">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('registration.submit', $type->slug) }}" enctype="multipart/form-data" data-once>
                @csrf

                <div class="oa-form-section">
                    <h2 class="oa-form-section__title"><span>1</span> Kontak utama</h2>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="full_name" class="form-label">Nama lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" id="full_name" value="{{ old('full_name') }}" class="form-control @error('full_name') is-invalid @enderror" required>
                            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Dipakai untuk cek status & menerima pemberitahuan.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="phone" class="form-label">No. HP / WhatsApp <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" placeholder="08xxxxxxxxxx" required>
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                @php $no = 2; @endphp
                @foreach (\App\Models\RegistrationField::SECTIONS as $sectionKey => $sectionLabel)
                    @if ($sections->has($sectionKey))
                        <div class="oa-form-section">
                            <h2 class="oa-form-section__title"><span>{{ $no++ }}</span> {{ $sectionLabel }}</h2>
                            <div class="row g-3">
                                @foreach ($sections[$sectionKey] as $field)
                                    @include('web.registration._field', ['field' => $field])
                                @endforeach
                            </div>
                        </div>
                    @endif
                @endforeach

                <div class="oa-form-section">
                    <div class="form-check">
                        <input class="form-check-input @error('consent') is-invalid @enderror" type="checkbox" name="consent" id="consent" value="1" @checked(old('consent')) required>
                        <label class="form-check-label" for="consent">
                            Saya menyatakan data yang diisi adalah benar dan menyetujui data ini digunakan hanya untuk keperluan seleksi {{ setting('site_name') }}.
                        </label>
                        @error('consent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mt-4">
                        <a href="{{ route('registration.show', $type->slug) }}" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Kembali</a>
                        <button type="submit" class="btn btn-accent btn-lg"><i class="bi bi-send me-1"></i> Kirim pendaftaran</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
