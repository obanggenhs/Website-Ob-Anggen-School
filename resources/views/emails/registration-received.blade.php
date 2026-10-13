@extends('emails.layout')

@section('body')
    @if ($forCommittee)
        <p>Ada pendaftar baru yang perlu diproses:</p>
    @else
        <p>Halo <strong>{{ $registration->full_name }}</strong>,</p>
        <p>Terima kasih, formulir pendaftaran Anda sudah kami terima.</p>
    @endif

    <table cellpadding="6" style="border-collapse:collapse;font-size:14px">
        <tr><td style="color:#7a6d69">Nomor</td><td><strong>{{ $registration->number }}</strong></td></tr>
        <tr><td style="color:#7a6d69">Nama</td><td>{{ $registration->full_name }}</td></tr>
        <tr><td style="color:#7a6d69">Jalur</td><td>{{ $registration->type->name }}</td></tr>
        <tr><td style="color:#7a6d69">Tanggal</td><td>{{ tgl($registration->created_at, 'd F Y, H.i') }} WIT</td></tr>
        @if ($forCommittee)
            <tr><td style="color:#7a6d69">Email / HP</td><td>{{ $registration->email }} / {{ $registration->phone }}</td></tr>
        @endif
    </table>

    @if ($forCommittee)
        <p><a href="{{ route('dashboard.applicants.show', $registration) }}" style="color:#7A2B25">Buka di dashboard</a></p>
    @else
        <p>Pantau status seleksi melalui <a href="{{ route('registration.status', ['nomor' => $registration->number, 'email' => $registration->email]) }}" style="color:#7A2B25">halaman Cek Status</a>.</p>
    @endif
@endsection
