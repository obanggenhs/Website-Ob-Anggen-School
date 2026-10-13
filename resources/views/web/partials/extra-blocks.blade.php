{{--
    Isi tambahan di halaman modul (Berita, Galeri, Kelulusan, Guru & Staf, Fasilitas, Kalender, Pendaftaran, dll.).
    Diatur admin di Struktur & Konten › [menu] › Isi tambahan halaman, tampil di atas atau di bawah daftar.
    Halaman Konten & Beranda tidak memakai bagian ini karena bloknya sudah menjadi isi utama.
    Tidak tampil di halaman detail (yang memakai section no_intro).
--}}
@php
    $extraMenu = isset($menu) && $menu instanceof \App\Models\Menu ? $menu : null;
    $extraBlocks = collect();

    if ($extraMenu && ! $extraMenu->isPage() && $extraMenu->supportsBlocks()
        && ! View::hasSection('no_intro') && $extraMenu->blocksAt() === $place) {
        $extraBlocks = $extraMenu->blocks()->where('is_active', true)->where('type', '!=', 'koleksi')->get();
        $extraBlocks->each->setRelation('menu', $extraMenu);
    }
@endphp

@if ($extraBlocks->isNotEmpty())
    <div class="row oa-blocks oa-blocks--extra oa-blocks--{{ $place }}">
        @include('web.partials.blocks', ['list' => $extraBlocks])
    </div>
@endif
