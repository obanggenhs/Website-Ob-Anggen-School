@extends('layouts.auth')

@php
    // $preview = true saat Master Admin membuka pratinjau dari Pengaturan Website > Halaman Login
    $preview = $preview ?? false;
@endphp

@section('title', $preview ? 'Pratinjau Halaman Login' : 'Masuk Dashboard')

@section('content')
    <div class="auth-card">
        @if ($preview)
            <div class="alert alert-warning d-flex gap-2 align-items-start py-2 small">
                <i class="bi bi-eye"></i>
                <div>
                    <strong>Mode pratinjau.</strong> Beginilah tampilan halaman login bagi pengunjung. Tombol Masuk dinonaktifkan.
                    <a href="{{ route('dashboard.settings.edit', 'login') }}" class="d-block mt-1">Kembali ke Pengaturan Halaman Login</a>
                </div>
            </div>
        @endif

        <a href="{{ route('home') }}" class="small text-decoration-none"><i class="bi bi-arrow-left"></i> Kembali ke website</a>
        <h2 class="mt-4 mb-1">Masuk ke dashboard</h2>
        <p class="text-secondary mb-4">Gunakan akun yang diberikan oleh Master Admin.</p>

        @if (session('success'))<div class="alert alert-success py-2">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="alert alert-danger py-2">{{ session('error') }}</div>@endif

        <form method="POST" action="{{ $preview ? '#' : route('login.attempt') }}" @if ($preview) onsubmit="return false" @endif>
            @csrf
            <fieldset @disabled($preview)>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required @unless ($preview) autofocus @endunless autocomplete="username">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Kata sandi</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" class="form-control" required autocomplete="current-password">
                        <button type="button" class="btn btn-outline-secondary" data-toggle-password="password" aria-label="Lihat kata sandi"><i class="bi bi-eye"></i></button>
                    </div>
                </div>
                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                    <label class="form-check-label" for="remember">Ingat saya di perangkat ini</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</button>
            </fieldset>
        </form>
    </div>
@endsection