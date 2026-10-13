@extends('layouts.dashboard')

@section('title', $menu->title)

@php
    $isMaster = auth()->user()->isMaster();
    $hasPage = ! in_array($menu->type, ['dropdown', 'tautan'], true);
@endphp

@section('content')
    <div class="row g-4">
        @if ($isMaster)
            <div class="col-xl-4 order-2 order-xl-1">
                @include('dashboard.studio._tree')
            </div>
        @endif

        <div class="{{ $isMaster ? 'col-xl-8 order-1 order-xl-2' : 'col-12' }}">
            <div class="card dash-card">
                <div class="card-body pb-3">
                    <div class="workspace-head">
                        <div class="d-flex gap-3 min-w-0">
                            <span class="workspace-icon"><i class="bi {{ $menu->type_icon }}"></i></span>
                            <div class="min-w-0">
                                <div class="small text-muted-d">
                                    {{ $menu->parent ? $menu->parent->title.' ›' : 'Menu utama ·' }} {{ $menu->type_label }}{{ $menu->param_label ? ' · '.$menu->param_label : '' }}
                                </div>
                                <h1 class="h4 mb-1 text-truncate">{{ $menu->title }}</h1>
                                <div class="d-flex flex-wrap gap-1 align-items-center">
                                    @include('dashboard.partials.status', ['active' => $menu->isVisible()])
                                    @foreach ($menu->roleBadges() as $role)
                                        <span class="role-chip role-chip--{{ $role }}">{{ $role === 'publikasi' ? 'Admin Publikasi' : 'Admin Pendaftaran' }}</span>
                                    @endforeach
                                    @if ($menu->is_custom)
                                        <a href="{{ route('dashboard.archive.index', ['menu' => $menu->id]) }}" class="badge text-bg-light border text-decoration-none fw-semibold" title="Isi menu ini tercatat di database sekolah (tabel menu_contents)">
                                            <i class="bi bi-database-check text-success"></i> Menu buatan admin · tersimpan di database
                                        </a>
                                    @endif
                                    @if ($menu->updated_at)
                                        <small class="text-muted-d ms-1">Diubah {{ $menu->updated_at->diffForHumans() }}{{ $menu->editor ? ' oleh '.$menu->editor->name : '' }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            @if ($menu->type !== 'dropdown')
                                <a href="{{ $menu->link }}" target="_blank" class="btn btn-sm btn-soft"><i class="bi bi-box-arrow-up-right"></i> Lihat di website</a>
                            @endif
                            @if ($isMaster)
                                <form method="POST" action="{{ route('dashboard.studio.toggle', $menu) }}">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-sm {{ $menu->is_active ? 'btn-outline-secondary' : 'btn-success' }}">
                                        <i class="bi {{ $menu->is_active ? 'bi-eye-slash' : 'bi-eye' }}"></i> {{ $menu->is_active ? 'Sembunyikan' : 'Tampilkan' }}
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>

                @if ($isMaster)
                    <ul class="nav workspace-tabs">
                        <li class="nav-item"><a class="nav-link {{ $tab === 'konten' ? 'active' : '' }}" href="{{ route('dashboard.studio.show', $menu) }}"><i class="bi bi-pencil-square me-1"></i> Isi Konten</a></li>
                        <li class="nav-item"><a class="nav-link {{ $tab === 'pengaturan' ? 'active' : '' }}" href="{{ route('dashboard.studio.show', [$menu, 'tab' => 'pengaturan']) }}"><i class="bi bi-gear me-1"></i> Pengaturan Menu</a></li>
                        <li class="nav-item"><a class="nav-link {{ $tab === 'akses' ? 'active' : '' }}" href="{{ route('dashboard.studio.show', [$menu, 'tab' => 'akses']) }}"><i class="bi bi-people me-1"></i> Akses Dashboard</a></li>
                    </ul>
                @else
                    <div class="px-3 pb-3">
                        <div class="alert alert-light border small mb-0"><i class="bi bi-info-circle me-1"></i> Menu ini ditugaskan Master Admin kepada Anda. Anda bisa mengubah isinya; struktur menu tetap diatur Master Admin.</div>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                @if ($tab === 'pengaturan')
                    @include('dashboard.studio._settings')
                @elseif ($tab === 'akses')
                    @include('dashboard.studio._access')
                @else
                    @switch($menu->type)
                        @case('beranda')
                            @include('dashboard.studio._content-header')
                            @include('dashboard.studio._content-home')
                            @include('dashboard.studio._content-blocks', ['blocksTitle' => 'Blok tambahan di Beranda'])
                            @include('dashboard.studio._content-options')
                            @break
                        @case('halaman')
                            @include('dashboard.studio._content-blocks')
                            @include('dashboard.studio._content-options')
                            @include('dashboard.studio._content-header')
                            @break
                        @case('dropdown')
                            @include('dashboard.studio._content-group')
                            @break
                        @case('tautan')
                            <div class="card dash-card"><div class="card-body">
                                <h2 class="h6">Tautan luar</h2>
                                <p class="mb-2">Menu ini mengarah ke: <a href="{{ $menu->url }}" target="_blank">{{ $menu->url ?: '(belum diisi)' }}</a></p>
                                @if ($isMaster)<a href="{{ route('dashboard.studio.show', [$menu, 'tab' => 'pengaturan']) }}" class="btn btn-sm btn-soft">Ubah alamat tautan</a>@endif
                            </div></div>
                            @break
                        @default
                            @include('dashboard.studio._content-module')
                            @if (\App\Support\PageOptions::supports($menu->type))
                                @include('dashboard.studio._content-options')
                            @endif
                            @include('dashboard.studio._content-blocks', ['blocksTitle' => 'Isi tambahan halaman', 'extra' => true])
                            @include('dashboard.studio._content-header')
                    @endswitch
                @endif
            </div>
        </div>
    </div>
@endsection

@push('modals')
    @if ($isMaster)
        @include('dashboard.studio._create-modal')
    @endif
@endpush