@extends('layouts.dashboard')

@section('title', 'Struktur & Konten')
@section('page_title', 'Studio Struktur & Konten')
@section('page_subtitle', 'Satu tempat untuk menyusun menu website dan mengisi konten setiap menu.')

@section('content')
    <div class="row g-4">
        <div class="col-xl-4">
            @include('dashboard.studio._tree')
        </div>
        <div class="col-xl-8">
            <div class="card dash-card mb-4">
                <div class="card-body p-4">
                    <h2 class="h5">Cara kerja Studio</h2>
                    <p class="text-muted-d">Setiap menu di kiri adalah satu bagian website. Pilih menu untuk membuka ruang kerjanya.</p>
                    <div class="row g-3 mt-1">
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <i class="bi bi-1-circle-fill text-primary fs-4"></i>
                                <h3 class="h6 mt-2">Susun menu</h3>
                                <p class="small text-muted-d mb-0">Tambah Menu Utama atau Submenu, lalu seret untuk mengatur urutan di navbar.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <i class="bi bi-2-circle-fill text-primary fs-4"></i>
                                <h3 class="h6 mt-2">Isi kontennya</h3>
                                <p class="small text-muted-d mb-0">Halaman disusun dari blok. Menu modul (berita, galeri, dsb.) cukup diberi header & teks pembuka.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-3 p-3 h-100">
                                <i class="bi bi-3-circle-fill text-primary fs-4"></i>
                                <h3 class="h6 mt-2">Bagikan tugas</h3>
                                <p class="small text-muted-d mb-0">Di tab Akses Dashboard, pilih apakah menu ini juga dikelola Admin Publikasi atau Admin Pendaftaran.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card dash-card">
                <div class="card-header"><h2>Jenis isi yang tersedia</h2></div>
                <div class="card-body">
                    <div class="type-grid">
                        @foreach ($types as $key => $t)
                            <div class="type-option">
                                <label class="pe-none">
                                    <i class="bi {{ $t['icon'] }}"></i>
                                    <span><strong>{{ $t['label'] }}</strong><small>{{ $t['desc'] }}</small></span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('modals')
    @include('dashboard.studio._create-modal')
@endpush