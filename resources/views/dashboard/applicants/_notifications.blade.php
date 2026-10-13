{{-- Riwayat pengiriman pesan otomatis (email & WhatsApp) ke pendaftar + tombol kirim ulang --}}
<div class="card dash-card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h2>Pesan ke pendaftar</h2>
        <div class="d-flex gap-1">
            <form method="POST" action="{{ route('dashboard.applicants.resend', [$r, 'email']) }}">@csrf
                <button class="btn btn-sm btn-light" title="Kirim ulang status terbaru ke {{ $r->email }}"><i class="bi bi-envelope-arrow-up"></i> Kirim ulang email</button>
            </form>
            <form method="POST" action="{{ route('dashboard.applicants.resend', [$r, 'whatsapp']) }}">@csrf
                <button class="btn btn-sm btn-light" title="Kirim ulang status terbaru ke {{ $r->phone }}"><i class="bi bi-whatsapp text-success"></i> Kirim ulang WA</button>
            </form>
        </div>
    </div>
    <div class="card-body">
        @unless (\App\Support\WhatsAppSender::enabled() && \App\Support\WhatsAppSender::configured())
            <div class="alert alert-warning small py-2 d-flex gap-2 align-items-start">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div>WhatsApp belum terhubung, jadi pesan ke pendaftar hanya lewat email. <a href="{{ route('dashboard.whatsapp.edit') }}" class="alert-link">Hubungkan WhatsApp</a></div>
            </div>
        @endunless
        @forelse ($r->notifications->take(12) as $n)
            <div class="d-flex gap-2 align-items-start {{ $loop->last ? '' : 'mb-2 pb-2 border-bottom' }}">
                <i class="bi {{ $n->channel === 'email' ? 'bi-envelope' : 'bi-whatsapp' }} mt-1"></i>
                <div class="min-w-0 small">
                    <strong>{{ $n->channel_label }}</strong> ke {{ $n->target }} <span class="text-muted-d">· {{ $n->event_label }}</span>
                    <span class="badge text-bg-{{ $n->isSent() ? 'success' : 'danger' }}">{{ $n->isSent() ? 'Terkirim' : 'Gagal' }}</span>
                    @if ($n->error)<div class="text-danger">{{ $n->error }}</div>@endif
                    <div class="text-muted-d">{{ tgl($n->created_at, 'd M Y, H.i') }} · {{ $n->user?->name ?? 'sistem' }}</div>
                </div>
            </div>
        @empty
            <p class="small text-muted-d mb-0">Belum ada pesan otomatis. Setiap kali status atau jadwal disimpan, kabar dikirim ke email dan WhatsApp pendaftar, lalu hasilnya tercatat di sini.</p>
        @endforelse
    </div>
</div>
