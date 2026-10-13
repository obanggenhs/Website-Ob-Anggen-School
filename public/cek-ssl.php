<?php
header('Content-Type: text/plain; charset=utf-8');

function tes($label, $url, $verify) {
    $ctx = stream_context_create(['ssl' => [
        'capture_peer_cert' => true,
        'verify_peer' => $verify,
        'verify_peer_name' => $verify,
    ]]);
    $fp = @stream_socket_client($url, $en, $es, 10, STREAM_CLIENT_CONNECT, $ctx);
    echo "$label: ";
    if ($fp) {
        $p = stream_context_get_params($fp);
        $c = openssl_x509_parse($p['options']['ssl']['peer_certificate']);
        echo "OK, penerbit: " . ($c['issuer']['O'] ?? '-') . " / " . ($c['issuer']['CN'] ?? '-') . "\n";
    } else {
        $e = error_get_last();
        echo "GAGAL ($en) $es | " . ($e['message'] ?? '') . "\n";
    }
}

tes('Gmail 587 (tanpa verifikasi)', 'tcp://smtp.gmail.com:587', false);
tes('Gmail 465 (tanpa verifikasi)', 'ssl://smtp.gmail.com:465', false);
tes('Gmail 465 (verifikasi)      ', 'ssl://smtp.gmail.com:465', true);
tes('Fonnte 443 (tanpa verifikasi)', 'ssl://api.fonnte.com:443', false);
tes('Fonnte 443 (verifikasi)      ', 'ssl://api.fonnte.com:443', true);