@extends('layouts.dashboard')

@section('title', 'Profil Saya')
@section('page_title', 'Profil Saya')

@section('content')
    <div class="row g-4">
        <div class="col-lg-7">
            <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data" class="card dash-card">
                @csrf @method('PUT')
                <div class="card-header"><h2>Data diri</h2></div>
                <div class="card-body">
                    <div class="row g-3">
                        <x-form.input name="name" label="Nama" :value="$user->name" required col="col-md-6" />
                        <x-form.input name="phone" label="No. HP" :value="$user->phone" col="col-md-6" />
                        <x-form.input name="email" label="Email" type="email" :value="$user->email" required />
                        <x-form.image name="avatar" label="Foto profil" :value="$user->avatar" col="col-md-6" :logo="true" help="JPG, PNG, atau WEBP." />
                        <div class="col-md-6">
                            <label class="form-label">Role</label>
                            <div><span class="role-chip role-chip--{{ $user->role }} fs-6">{{ $user->role_label }}</span></div>
                            <div class="form-text">Role hanya bisa diubah oleh Master Admin.</div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end"><button class="btn btn-primary">Simpan profil</button></div>
            </form>
        </div>
        <div class="col-lg-5">
            <form method="POST" action="{{ route('dashboard.profile.password') }}" class="card dash-card">
                @csrf @method('PUT')
                <div class="card-header"><h2>Ganti kata sandi</h2></div>
                <div class="card-body">
                    <div class="row g-3">
                        <x-form.input name="current_password" label="Kata sandi lama" type="password" required autocomplete="current-password" />
                        <x-form.input name="password" label="Kata sandi baru" type="password" required autocomplete="new-password" help="Minimal 8 karakter." />
                        <x-form.input name="password_confirmation" label="Ulangi kata sandi baru" type="password" required autocomplete="new-password" />
                    </div>
                </div>
                <div class="card-footer text-end"><button class="btn btn-primary">Ganti kata sandi</button></div>
            </form>
        </div>
    </div>
@endsection
