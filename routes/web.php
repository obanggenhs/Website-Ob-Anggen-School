<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Web;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Website publik
|--------------------------------------------------------------------------
| Halaman modul (berita, galeri, dst.) hanya terbuka bila menu bertipe itu
| sedang tampil di struktur website yang disusun Master Admin.
*/

Route::get('/', [Web\HomeController::class, 'index'])->name('home');
Route::get('/halaman/{slug}', [Web\PageController::class, 'show'])->name('page.show');
Route::get('/halaman/{slug}/{item}', [Web\PageController::class, 'item'])->name('page.item');   // detail kartu Koleksi Kartu

Route::middleware('public.module:berita')->group(function () {
    Route::get('/berita', [Web\NewsController::class, 'index'])->name('news.index');
    Route::get('/berita/{slug}', [Web\NewsController::class, 'show'])->name('news.show');
});

Route::middleware('public.module:galeri')->group(function () {
    Route::get('/galeri', [Web\GalleryController::class, 'index'])->name('gallery.index');
    Route::get('/galeri/{slug}', [Web\GalleryController::class, 'show'])->name('gallery.show');
});

Route::middleware('public.module:kelulusan')->group(function () {
    Route::get('/kelulusan', [Web\GraduateController::class, 'index'])->name('graduates.index');
    Route::get('/kelulusan/{slug}', [Web\GraduateController::class, 'show'])->name('graduates.show');
});

Route::get('/kalender-akademik', [Web\CalendarController::class, 'index'])->middleware('public.module:kalender')->name('calendar.index');

Route::middleware('public.module:guru_staf')->group(function () {
    Route::get('/guru-staf', [Web\StaffController::class, 'index'])->name('staff.index');
    Route::get('/guru-staf/{slug}', [Web\StaffController::class, 'show'])->name('staff.show');
});

Route::middleware('public.module:keunikan')->group(function () {
    Route::get('/keunikan', [Web\UniquenessController::class, 'index'])->name('uniqueness.index');
    Route::get('/keunikan/{slug}', [Web\UniquenessController::class, 'show'])->name('uniqueness.show');
});

Route::middleware('public.module:fasilitas')->group(function () {
    Route::get('/fasilitas/detail/{slug}', [Web\FacilityController::class, 'show'])->name('facilities.show');
    Route::get('/fasilitas/{jenis}', [Web\FacilityController::class, 'index'])->whereIn('jenis', ['gedung', 'asrama'])->name('facilities.index');
});

Route::prefix('pendaftaran')->name('registration.')->group(function () {
    Route::get('/cek-status', [Web\RegistrationController::class, 'status'])->middleware(['public.module:cek_status', 'throttle:30,1'])->name('status');

    Route::middleware('public.module:pendaftaran')->group(function () {
        Route::get('/', [Web\RegistrationController::class, 'index'])->name('index');
        Route::get('/berhasil/{number}', [Web\RegistrationController::class, 'success'])->name('success');
        Route::get('/{slug}', [Web\RegistrationController::class, 'show'])->name('show');
        Route::get('/{slug}/formulir', [Web\RegistrationController::class, 'form'])->name('form');
        Route::post('/{slug}/formulir', [Web\RegistrationController::class, 'submit'])->middleware('throttle:6,1')->name('submit');
    });
});

// Cadangan bila "php artisan storage:link" gagal (sering terjadi di Windows)
Route::get('/storage/{path}', function (string $path) {
    abort_if(str_contains($path, '..'), 404);
    abort_unless(Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
})->where('path', '.*')->name('storage.fallback');

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:8,1')->name('login.attempt');
});
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard (satu layout untuk Master, Publikasi, dan Pendaftaran)
|--------------------------------------------------------------------------
*/

Route::prefix('dashboard')->name('dashboard.')->middleware(['auth', 'active'])->group(function () {

    Route::get('/', [Dashboard\HomeController::class, 'index'])->name('home');

    Route::get('/profil', [Dashboard\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [Dashboard\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/kata-sandi', [Dashboard\ProfileController::class, 'password'])->name('profile.password');

    // Unggah file besar sepotong demi sepotong (semua admin; dipakai otomatis oleh dashboard.js)
    Route::post('/unggah', [Dashboard\UploadController::class, 'chunk'])->name('upload.chunk');

    // Data menu buatan admin di database sekolah (tabel menu_contents)
    Route::get('/data-menu', [Dashboard\MenuArchiveController::class, 'index'])->name('archive.index');
    Route::get('/data-menu/unduh', [Dashboard\MenuArchiveController::class, 'export'])->name('archive.export');
    Route::post('/data-menu/perbarui', [Dashboard\MenuArchiveController::class, 'rebuild'])->middleware('role:master')->name('archive.rebuild');

    /* ---------- Studio: Struktur & Konten ---------- */
    Route::middleware('role:master')->group(function () {
        Route::get('/studio', [Dashboard\StudioController::class, 'index'])->name('studio.index');
        Route::post('/studio', [Dashboard\StudioController::class, 'store'])->name('studio.store');
        Route::post('/studio/urutan', [Dashboard\StudioController::class, 'reorder'])->name('studio.reorder');
        Route::put('/studio/{menu}/pengaturan', [Dashboard\StudioController::class, 'update'])->name('studio.update');
        Route::put('/studio/{menu}/akses', [Dashboard\StudioController::class, 'access'])->name('studio.access');
        Route::patch('/studio/{menu}/tampil', [Dashboard\StudioController::class, 'toggle'])->name('studio.toggle');
        Route::post('/studio/{menu}/geser/{direction}', [Dashboard\StudioController::class, 'move'])->whereIn('direction', ['naik', 'turun'])->name('studio.move');
        Route::delete('/studio/{menu}', [Dashboard\StudioController::class, 'destroy'])->name('studio.destroy');
    });

    // Isi konten: Master + role yang ditugaskan (dicek di controller)
    Route::get('/studio/{menu}', [Dashboard\StudioController::class, 'show'])->name('studio.show');
    Route::put('/studio/{menu}/konten', [Dashboard\StudioController::class, 'content'])->name('studio.content');
    Route::put('/studio/{menu}/beranda', [Dashboard\StudioController::class, 'home'])->name('studio.home');
    Route::put('/studio/{menu}/fitur', [Dashboard\StudioController::class, 'options'])->name('studio.options');   // fitur bantuan pengunjung
    Route::put('/studio/{menu}/letak-isi', [Dashboard\StudioController::class, 'blocksPlace'])->name('studio.blocks-place');   // letak isi tambahan di halaman modul

    Route::scopeBindings()->prefix('/studio/{menu}/blok')->name('blocks.')->group(function () {
        Route::get('/tambah', [Dashboard\BlockController::class, 'create'])->name('create');
        Route::post('/', [Dashboard\BlockController::class, 'store'])->name('store');
        Route::post('/urutan', [Dashboard\BlockController::class, 'reorder'])->name('reorder');
        Route::get('/{block}/ubah', [Dashboard\BlockController::class, 'edit'])->name('edit');
        Route::put('/{block}', [Dashboard\BlockController::class, 'update'])->name('update');
        Route::patch('/{block}/tampil', [Dashboard\BlockController::class, 'toggle'])->name('toggle');
        Route::post('/{block}/geser/{direction}', [Dashboard\BlockController::class, 'move'])->whereIn('direction', ['naik', 'turun'])->name('move');
        Route::delete('/{block}', [Dashboard\BlockController::class, 'destroy'])->name('destroy');
    });

    // Kartu di dalam blok "Koleksi Kartu" (Master + role yang ditugaskan, dicek di controller)
    Route::scopeBindings()->prefix('/studio/{menu}/blok/{block}/kartu')->name('cards.')->group(function () {
        Route::get('/', [Dashboard\BlockItemController::class, 'index'])->name('index');
        Route::get('/tambah', [Dashboard\BlockItemController::class, 'create'])->name('create');
        Route::post('/', [Dashboard\BlockItemController::class, 'store'])->name('store');
        Route::post('/urutan', [Dashboard\BlockItemController::class, 'reorder'])->name('reorder');
        Route::get('/{card}/ubah', [Dashboard\BlockItemController::class, 'edit'])->name('edit');
        Route::put('/{card}', [Dashboard\BlockItemController::class, 'update'])->name('update');
        Route::patch('/{card}/tampil', [Dashboard\BlockItemController::class, 'toggle'])->name('toggle');
        Route::post('/{card}/geser/{direction}', [Dashboard\BlockItemController::class, 'move'])->whereIn('direction', ['naik', 'turun'])->name('move');
        Route::delete('/{card}', [Dashboard\BlockItemController::class, 'destroy'])->name('destroy');
    });

    /* ---------- Publikasi ---------- */
    Route::middleware('module:berita,berita_pendaftaran')->group(function () {
        Route::patch('/berita/{news}/status', [Dashboard\NewsController::class, 'status'])->name('news.status');
        Route::resource('berita', Dashboard\NewsController::class)->parameters(['berita' => 'news'])->names('news')->except('show');
    });

    Route::middleware('module:berita,berita_pendaftaran,galeri')->group(function () {
        Route::resource('kategori', Dashboard\CategoryController::class)->parameters(['kategori' => 'category'])->names('categories')->only(['index', 'store', 'update', 'destroy']);
    });

    Route::middleware('module:galeri')->group(function () {
        Route::post('/galeri/{gallery}/sampul/{photo}', [Dashboard\GalleryController::class, 'cover'])->name('gallery.cover');
        Route::delete('/galeri/{gallery}/foto/{photo}', [Dashboard\GalleryController::class, 'destroyPhoto'])->name('gallery.photo.destroy');
        Route::resource('galeri', Dashboard\GalleryController::class)->parameters(['galeri' => 'gallery'])->names('gallery')->except('show');
    });

    Route::resource('kelulusan', Dashboard\GraduateController::class)->parameters(['kelulusan' => 'graduate'])->names('graduates')->except('show')->middleware('module:kelulusan');
    Route::resource('kalender', Dashboard\CalendarController::class)->parameters(['kalender' => 'calendar'])->names('calendar')->except('show')->middleware('module:kalender');

    /* ---------- Profil sekolah ---------- */
    Route::resource('guru-staf', Dashboard\StaffController::class)->parameters(['guru-staf' => 'staff'])->names('staff')->except('show')->middleware('module:guru_staf');
    Route::resource('keunikan', Dashboard\UniquenessController::class)->parameters(['keunikan' => 'uniqueness'])->names('uniqueness')->except('show')->middleware('module:keunikan');
    Route::resource('testimoni', Dashboard\TestimonialController::class)->parameters(['testimoni' => 'testimonial'])->names('testimonials')->except('show')->middleware('module:testimoni');
    Route::resource('kampus', Dashboard\BranchController::class)->parameters(['kampus' => 'branch'])->names('branches')->except('show')->middleware('module:kampus');
    Route::resource('fasilitas', Dashboard\FacilityController::class)->parameters(['fasilitas' => 'facility'])->names('facilities')->except('show')->middleware('module:fasilitas');

    /* ---------- Pendaftaran ---------- */
    Route::middleware('module:pendaftaran')->group(function () {
        Route::get('/koneksi-whatsapp', [Dashboard\WhatsAppController::class, 'edit'])->name('whatsapp.edit');
        Route::put('/koneksi-whatsapp', [Dashboard\WhatsAppController::class, 'update'])->name('whatsapp.update');
        Route::post('/koneksi-whatsapp/tes', [Dashboard\WhatsAppController::class, 'test'])->name('whatsapp.test');
        Route::get('/pendaftar', [Dashboard\ApplicantController::class, 'index'])->name('applicants.index');
        Route::get('/pendaftar/unduh', [Dashboard\ApplicantController::class, 'export'])->name('applicants.export');
        Route::get('/pendaftar/{applicant}', [Dashboard\ApplicantController::class, 'show'])->name('applicants.show');
        Route::get('/pendaftar/{applicant}/cetak', [Dashboard\ApplicantController::class, 'print'])->name('applicants.print');
        Route::get('/pendaftar/{applicant}/berkas/{key}', [Dashboard\ApplicantController::class, 'file'])->name('applicants.file');
        Route::post('/pendaftar/{applicant}/status', [Dashboard\ApplicantController::class, 'status'])->name('applicants.status');
        Route::post('/pendaftar/{applicant}/jadwal', [Dashboard\ApplicantController::class, 'schedule'])->name('applicants.schedule');
        Route::post('/pendaftar/{applicant}/catatan', [Dashboard\ApplicantController::class, 'note'])->name('applicants.note');
        Route::post('/pendaftar/{applicant}/kirim-ulang/{channel}', [Dashboard\ApplicantController::class, 'resend'])->whereIn('channel', ['email', 'whatsapp'])->name('applicants.resend');
        Route::delete('/pendaftar/{applicant}', [Dashboard\ApplicantController::class, 'destroy'])->name('applicants.destroy');

        Route::patch('/jalur-pendaftaran/{registrationType}/buka-tutup', [Dashboard\RegistrationTypeController::class, 'toggle'])->name('registration-types.toggle');
        Route::post('/jalur-pendaftaran/{registrationType}/isian', [Dashboard\RegistrationTypeController::class, 'storeField'])->name('fields.store');
        Route::put('/jalur-pendaftaran/{registrationType}/isian/{field}', [Dashboard\RegistrationTypeController::class, 'updateField'])->name('fields.update');
        Route::post('/jalur-pendaftaran/{registrationType}/isian/{field}/geser/{direction}', [Dashboard\RegistrationTypeController::class, 'moveField'])->whereIn('direction', ['naik', 'turun'])->name('fields.move');
        Route::delete('/jalur-pendaftaran/{registrationType}/isian/{field}', [Dashboard\RegistrationTypeController::class, 'destroyField'])->name('fields.destroy');
        Route::resource('jalur-pendaftaran', Dashboard\RegistrationTypeController::class)->parameters(['jalur-pendaftaran' => 'registrationType'])->names('registration-types')->except('show');
    });

    /* ---------- Sistem (Master Admin) ---------- */
    Route::middleware('role:master')->group(function () {
        Route::post('/pengaturan/tes-kirim/{channel}', [Dashboard\SettingController::class, 'testSend'])->whereIn('channel', ['email', 'whatsapp'])->name('settings.test');
        Route::get('/pengaturan/login/pratinjau', [Dashboard\SettingController::class, 'loginPreview'])->name('settings.login-preview');
        Route::get('/pengaturan/{tab?}', [Dashboard\SettingController::class, 'edit'])->name('settings.edit');
        Route::put('/pengaturan/{tab}', [Dashboard\SettingController::class, 'update'])->name('settings.update');

        Route::patch('/akun/{user}/aktif', [Dashboard\UserController::class, 'toggle'])->name('users.toggle');
        Route::resource('akun', Dashboard\UserController::class)->parameters(['akun' => 'user'])->names('users')->except('show');

        Route::get('/log-aktivitas', [Dashboard\ActivityController::class, 'index'])->name('activity.index');
    });
});
