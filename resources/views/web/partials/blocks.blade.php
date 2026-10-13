{{--
    Semua blok satu halaman, tersusun per baris (dipakai di dalam <div class="row oa-blocks">).
    Blok berpasangan (Di kanan / Di kiri blok sebelumnya) dan blok "Di bawah" tampil di barisnya sendiri;
    blok "Otomatis" tampil seperti biasa mengikuti lebar & posisinya. Lihat App\Models\Block::arrange().
--}}
@foreach (\App\Models\Block::arrange($list) as $row)
    @if ($row['group'])
        <div class="col-12 oa-block-row">
            <div class="row oa-blocks justify-content-{{ $row['justify'] }}">
                @foreach ($row['blocks'] as $b)
                    @include('web.partials.block', ['b' => $b])
                @endforeach
            </div>
        </div>
    @else
        @include('web.partials.block', ['b' => $row['blocks'][0]])
    @endif
@endforeach
