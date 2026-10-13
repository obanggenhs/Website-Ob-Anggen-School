@extends('layouts.dashboard')

@section('title', 'Koneksi WhatsApp')
@section('page_title', 'Koneksi WhatsApp')
@section('page_subtitle', 'Hubungkan gateway WhatsApp agar status & pesan admin terkirim otomatis ke nomor pendaftar.')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard.applicants.index') }}">Data Pendaftar</a></li>
    <li class="breadcrumb-item active">Koneksi WhatsApp</li>
@endsection

@section('content')
    <div class="row g-4">
        <div class="col-xl-7">
            <form method="POST" action="{{ route('dashboard.whatsapp.update') }}" class="card dash-card">
                @csrf @method('PUT')
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2><i class="bi bi-whatsapp text-success me-1"></i> Hubungkan gateway</h2>
                    @if ($connected)
                        <span class="badge text-bg-success"><i class="bi bi-check-circle"></i> Terhubung</span>
                    @else
                        <span class="badge text-bg-warning"><i class="bi bi-exclamation-triangle"></i> Belum terhubung</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="wa_api_url">Alamat API</label>
                        <input type="url" name="wa_api_url" id="wa_api_url" class="form-control @error('wa_api_url') is-invalid @enderror" value="{{ old('wa_api_url', setting('wa_api_url')) }}" placeholder="https://api.fonnte.com/send" required>
                        <div class="form-text">Untuk Fonnte: <code>https://api.fonnte.com/send</code></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="wa_token">Token</label>
                        <div class="input-group">
                            <input type="password" name="wa_token" id="wa_token" class="form-control @error('wa_token') is-invalid @enderror" value="" autocomplete="off"
                                   placeholder="{{ filled(setting('wa_token')) ? 'Token sudah tersimpan — kosongkan jika tidak diganti' : 'Tempel token dari akun gateway' }}">
                            <button type="button" class="btn btn-light" data-wa-reveal title="Tampilkan / sembunyikan"><i class="bi bi-eye"></i></button>
                        </div>
                        <div class="form-text">Token ada di akun gateway (Fonnte › menu Device › Token).</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="reg_notify_wa">No. WhatsApp panitia <span class="text-muted-d fw-normal">(menerima kabar pendaftar baru)</span></label>
                        <input type="text" name="reg_notify_wa" id="reg_notify_wa" class="form-control" value="{{ old('reg_notify_wa', setting('reg_notify_wa')) }}" placeholder="0812xxxxxxx">
                    </div>
                    <div class="form-check form-switch">
                        <input type="hidden" name="reg_send_wa" value="0">
                        <input class="form-check-input" type="checkbox" role="switch" name="reg_send_wa" id="reg_send_wa" value="1" @checked(old('reg_send_wa', setting('reg_send_wa')))>
                        <label class="form-check-label" for="reg_send_wa">Kirim WhatsApp otomatis (ke pendaftar saat status berubah, dan ke panitia saat ada pendaftar baru)</label>
                    </div>
                </div>
                <div class="card-footer text-end"><button class="btn btn-primary"><i class="bi bi-plug me-1"></i> Simpan &amp; hubungkan</button></div>
            </form>

            <form method="POST" action="{{ route('dashboard.whatsapp.test') }}" class="card dash-card mt-4">
                @csrf
                <div class="card-header"><h2><i class="bi bi-lightning-charge me-1"></i> Kirim pesan tes</h2></div>
                <div class="card-body">
                    <p class="small text-muted-d">Simpan dulu isian di atas, lalu kirim pesan percobaan untuk memastikan sudah terhubung. Bila gagal, alasannya tampil di layar.</p>
                    <label class="form-label" for="to">Nomor tujuan</label>
                    <div class="input-group">
                        <input type="text" name="to" id="to" class="form-control" value="{{ old('to', setting('reg_notify_wa')) }}" placeholder="0812xxxxxxx" required>
                        <button class="btn btn-success"><i class="bi bi-whatsapp"></i> Kirim tes</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-xl-5">
            <div class="card dash-card">
                <div class="card-header"><h2>Cara menghubungkan (Fonnte)</h2></div>
                <div class="card-body small">
                    <ol class="ps-3 mb-3">
                        <li class="mb-2">Daftar di <strong>fonnte.com</strong>, lalu tambahkan <em>Device</em> dengan nomor WhatsApp sekolah.</li>
                        <li class="mb-2">Scan kode QR memakai WhatsApp di HP sekolah sampai statusnya <strong>Connected</strong>.</li>
                        <li class="mb-2">Salin <strong>Token</strong> device, tempel di kolom Token di samping.</li>
                        <li class="mb-2">Klik <strong>Simpan &amp; hubungkan</strong>, lalu <strong>Kirim tes</strong>.</li>
                    </ol>
                    <p class="text-muted-d mb-0">Setelah terhubung, setiap kali admin menekan <strong>Simpan status</strong> atau <strong>Simpan jadwal</strong>, pesan terkirim otomatis ke nomor WhatsApp yang didaftarkan pendaftar. Hasilnya tercatat di halaman detail pendaftar. Pengaturan ini sama dengan <strong>Pengaturan Website › Email &amp; WhatsApp</strong>.</p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.querySelector('[data-wa-reveal]')?.addEventListener('click', function () {
                var input = document.getElementById('wa_token');
                input.type = input.type === 'password' ? 'text' : 'password';
            });
        </script>
    @endpush
@endsection
