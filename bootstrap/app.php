<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\PostTooLargeException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role'          => \App\Http\Middleware\RoleMiddleware::class,
            'module'        => \App\Http\Middleware\ModuleAccess::class,
            'public.module' => \App\Http\Middleware\PublicModule::class,
            'active'        => \App\Http\Middleware\EnsureUserIsActive::class,
        ]);

        // Unggah bertahap dari dashboard: potongan file disatukan lagi menjadi file biasa (tanpa batas ukuran)
        // Setelah Struktur & Konten disimpan, data menu buatan admin di tabel menu_contents ikut diperbarui
        $middleware->web(append: [
            \App\Http\Middleware\AttachChunkedUploads::class,
            \App\Http\Middleware\SyncMenuArchive::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route('dashboard.home'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // File yang diunggah langsung (tanpa unggah bertahap, mis. formulir pendaftaran publik) melebihi post_max_size
        $exceptions->render(function (PostTooLargeException $e, $request) {
            $message = 'Ukuran file terlalu besar untuk server (batas: '.ini_get('post_max_size').'). Kecilkan file lalu coba lagi.';

            return $request->expectsJson()
                ? response()->json(['message' => $message], 413)
                : back()->with('error', $message);
        });
    })->create();