{{-- Menu bertipe modul: ringkasan data + jalan pintas ke pengelolaan datanya --}}
<div class="card dash-card mb-4">
    <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h2 class="dash-card__title">Isi otomatis dari modul {{ $summary['module'] ?? $menu->type_label }}</h2>
            <small class="text-muted-d">Halaman ini menampilkan data modul secara otomatis{{ $menu->param_label ? ' (filter: '.$menu->param_label.')' : '' }}.</small>
        </div>
        @if ($summary && $summary['can_manage'] && $summary['manage_url'])
            <a href="{{ $summary['manage_url'] }}" class="btn btn-primary btn-sm"><i class="bi bi-database-gear"></i> Kelola data {{ \Illuminate\Support\Str::lower($summary['module']) }}</a>
        @endif
    </div>
    <div class="card-body">
        @if ($summary)
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="dash-stat flex-fill" style="box-shadow:none;border:1px solid var(--dash-line)">
                    <i class="bi {{ $menu->type_icon }}"></i>
                    <span><strong>{{ $summary['count'] }}</strong><small>{{ $summary['noun'] }} tersimpan</small></span>
                </div>
            </div>
            <ul class="dash-list">
                @forelse ($summary['items'] as $row)
                    <li>
                        <span class="min-w-0 flex-fill">
                            @if ($summary['can_manage'])
                                <a href="{{ $row['url'] }}" class="dash-list__title d-block text-truncate">{{ $row['title'] }}</a>
                            @else
                                <span class="dash-list__title d-block text-truncate">{{ $row['title'] }}</span>
                            @endif
                            <span class="dash-list__meta">{{ $row['meta'] }}</span>
                        </span>
                    </li>
                @empty
                    <li class="small text-muted-d">Belum ada data.</li>
                @endforelse
            </ul>
            @unless ($summary['can_manage'])
                <div class="alert alert-light border small mt-3 mb-0">Data modul ini dikelola admin lain. Anda dapat mengubah header & teks pembuka halaman di bawah.</div>
            @endunless
        @endif
    </div>
</div>
