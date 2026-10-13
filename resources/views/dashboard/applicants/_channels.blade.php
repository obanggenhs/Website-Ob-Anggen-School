{{-- Pilihan saluran pesan otomatis ke pendaftar: email + WhatsApp (default tercentang = terkirim otomatis) --}}
@php
    $mailOn = (bool) setting('reg_send_mail');
    $waOn = \App\Support\WhatsAppSender::enabled();
@endphp
<div class="mb-3">
    <div class="form-check">
        <input type="hidden" name="send_email" value="0">
        <input class="form-check-input" type="checkbox" name="send_email" id="send_email{{ $n }}" value="1" @checked($mailOn) @disabled(! $mailOn)>
        <label class="form-check-label small" for="send_email{{ $n }}">
            {{ $mailText }} <strong>{{ $r->email }}</strong>
            @unless ($mailOn)<span class="text-danger">(dimatikan di Pengaturan)</span>@endunless
        </label>
    </div>
    <div class="form-check">
        <input type="hidden" name="send_wa" value="0">
        <input class="form-check-input" type="checkbox" name="send_wa" id="send_wa{{ $n }}" value="1" @checked($waOn) @disabled(! $waOn)>
        <label class="form-check-label small" for="send_wa{{ $n }}">
            {{ $waText }} <strong>{{ $r->phone }}</strong>
            @unless ($waOn)<span class="text-danger">(dimatikan di Pengaturan)</span>@endunless
        </label>
    </div>
</div>
