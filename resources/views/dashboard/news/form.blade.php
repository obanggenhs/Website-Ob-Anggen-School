@extends('layouts.dashboard')

@php $editing = $post->exists; @endphp

@section('title', $editing ? 'Ubah berita' : 'Tulis berita')
@section('page_title', $editing ? 'Ubah berita' : 'Tulis berita baru')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.news.index', ['kanal' => $post->channel]) }}">{{ $post->channel === 'pendaftaran' ? 'Berita Pendaftaran' : 'Berita' }}</a></li>
    <li class="breadcrumb-item active">{{ $editing ? 'Ubah' : 'Tulis' }}</li>
@endsection

@section('content')
    <form method="POST" action="{{ $editing ? route('dashboard.news.update', $post) : route('dashboard.news.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($editing) @method('PUT') @endif
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card dash-card">
                    <div class="card-body">
                        <div class="row g-3">
                            <x-form.input name="title" label="Judul berita" :value="$post->title" required class="form-control-lg" />
                            <x-form.textarea name="excerpt" label="Ringkasan singkat" :value="$post->excerpt" rows="2" help="Tampil di kartu berita. Kosongkan untuk diambil otomatis dari isi." />
                            <x-form.editor name="body" label="Isi berita" :value="$post->body" required :height="380" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card dash-card mb-4">
                    <div class="card-header"><h2 class="dash-card__title">Penerbitan</h2></div>
                    <div class="card-body">
                        <div class="row g-3">
                            @if (count($channels) > 1)
                                <x-form.select name="channel" label="Jenis berita" :options="collect($channels)->mapWithKeys(fn ($c) => [$c => \App\Models\News::CHANNELS[$c]])->all()" :value="$post->channel" />
                            @else
                                <input type="hidden" name="channel" value="{{ $channels[0] }}">
                            @endif
                            <x-form.select name="status" label="Status" :options="['published' => 'Terbitkan', 'draft' => 'Simpan sebagai draf']" :value="$post->status" />
                            <x-form.input name="published_at" label="Tanggal terbit" type="datetime-local" :value="$post->published_at?->format('Y-m-d\TH:i')" help="Isi tanggal mendatang untuk menjadwalkan." />
                            <x-form.select name="category_id" label="Kategori" :options="$categories->pluck('name', 'id')->all()" :value="$post->category_id" placeholder="— Tanpa kategori —" />
                            <x-form.toggle name="is_featured" label="Tandai sebagai berita penting" :checked="$post->is_featured" help="Ditampilkan paling atas." />
                        </div>
                    </div>
                </div>
                <div class="card dash-card mb-4">
                    <div class="card-header"><h2 class="dash-card__title">Gambar sampul</h2></div>
                    <div class="card-body"><div class="row"><x-form.image name="cover" label="" :value="$post->cover" remove="remove_cover" /></div></div>
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-primary"><i class="bi bi-check2-circle me-1"></i> {{ $editing ? 'Simpan perubahan' : 'Simpan berita' }}</button>
                    <a href="{{ route('dashboard.news.index', ['kanal' => $post->channel]) }}" class="btn btn-light">Batal</a>
                </div>
            </div>
        </div>
    </form>
@endsection
