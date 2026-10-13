@extends('layouts.dashboard')

@php $editing = $user->exists; @endphp
@section('title', $editing ? 'Ubah akun' : 'Buat akun')
@section('page_title', $editing ? 'Ubah akun '.$user->name : 'Buat akun admin')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.users.index') }}">Akun Admin</a></li>
    <li class="breadcrumb-item active">{{ $editing ? 'Ubah' : 'Buat' }}</li>
@endsection

@section('content')
    <form method="POST" action="{{ $editing ? route('dashboard.users.update', $user) : route('dashboard.users.store') }}" class="card dash-card" style="max-width:860px">
        @csrf @if ($editing) @method('PUT') @endif
        <div class="card-body">
            <label class="form-label">Role / dashboard</label>
            <div class="row g-2 mb-4">
                @foreach (\App\Models\User::ROLES as $key => $label)
                    @php $t = \App\Support\DashboardMenu::theme($key); @endphp
                    <div class="col-md-4">
                        <label class="access-card d-block" style="--access: {{ $t['color'] }}" for="role_{{ $key }}">
                            <div class="d-flex justify-content-between">
                                <span class="access-card__icon"><i class="bi {{ $t['icon'] }}"></i></span>
                                <input class="form-check-input" type="radio" name="role" id="role_{{ $key }}" value="{{ $key }}" @checked(old('role', $user->role) === $key)>
                            </div>
                            <strong class="d-block mt-2">{{ $label }}</strong>
                            <small class="text-muted-d">{{ $t['panel'] }}</small>
                        </label>
                    </div>
                @endforeach
            </div>
            <div class="row g-3">
                <x-form.input name="name" label="Nama" :value="$user->name" required col="col-md-6" />
                <x-form.input name="phone" label="No. HP" :value="$user->phone" col="col-md-6" />
                <x-form.input name="email" label="Email (untuk login)" type="email" :value="$user->email" required />
                <x-form.input name="password" :label="$editing ? 'Kata sandi baru (kosongkan bila tidak diganti)' : 'Kata sandi'" type="password" :required="! $editing" col="col-md-6" autocomplete="new-password" help="Minimal 8 karakter." />
                <x-form.input name="password_confirmation" label="Ulangi kata sandi" type="password" :required="! $editing" col="col-md-6" autocomplete="new-password" />
                @unless ($editing && $user->is(auth()->user()))
                    <x-form.toggle name="is_active" label="Akun aktif (boleh masuk dashboard)" :checked="$user->is_active ?? true" />
                @endunless
            </div>
            <x-form.buttons :back="route('dashboard.users.index')" :label="$editing ? 'Simpan akun' : 'Buat akun'" />
        </div>
    </form>
@endsection
