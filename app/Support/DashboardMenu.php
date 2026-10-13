<?php

namespace App\Support;

use App\Models\Menu;
use App\Models\Registration;
use App\Models\User;

/**
 * Menyusun sidebar dashboard. Satu layout dipakai tiga role,
 * isi sidebar berbeda sesuai role + menu yang ditugaskan Master Admin.
 */
class DashboardMenu
{
    public static function for(User $user): array
    {
        $newApplicants = $user->canManage('pendaftaran')
            ? Registration::where('status', 'baru')->count()
            : 0;

        $groups = [];

        $groups[] = ['label' => null, 'items' => [
            static::item('Beranda Dashboard', 'bi-speedometer2', 'dashboard.home', [], 'dashboard.home'),
        ]];

        if ($user->isMaster()) {
            $groups[] = ['label' => 'Website', 'items' => [
                static::item('Struktur & Konten', 'bi-diagram-3', 'dashboard.studio.index', [], 'dashboard.studio.*|dashboard.blocks.*'),
                static::item('Data Menu Baru', 'bi-database', 'dashboard.archive.index', [], 'dashboard.archive.*'),
                static::item('Pengaturan Website', 'bi-sliders', 'dashboard.settings.edit', [], 'dashboard.settings.*'),
            ]];
        }

        $publication = static::modules($user, ['berita', 'galeri', 'kelulusan', 'kalender']);
        if ($publication) {
            if ($user->canManage('berita') || $user->canManage('galeri')) {
                $publication[] = static::item('Kategori', 'bi-tags', 'dashboard.categories.index', [], 'dashboard.categories.*');
            }
            $groups[] = ['label' => 'Publikasi', 'items' => $publication];
        }

        $profile = static::modules($user, ['guru_staf', 'keunikan', 'testimoni', 'kampus', 'fasilitas']);
        if ($profile) {
            $groups[] = ['label' => 'Profil Sekolah', 'items' => $profile];
        }

        if ($user->canManage('pendaftaran') || $user->canManage('berita_pendaftaran')) {
            $items = [];
            if ($user->canManage('pendaftaran')) {
                $items[] = static::item('Data Pendaftar', 'bi-person-lines-fill', 'dashboard.applicants.index', [], 'dashboard.applicants.*', $newApplicants);
                $items[] = static::item('Jalur & Formulir', 'bi-ui-checks', 'dashboard.registration-types.index', [], 'dashboard.registration-types.*|dashboard.fields.*');
                $items[] = static::item('Koneksi WhatsApp', 'bi-whatsapp', 'dashboard.whatsapp.edit', [], 'dashboard.whatsapp.*');
            }
            if ($user->canManage('berita_pendaftaran')) {
                $items[] = static::item('Berita Pendaftaran', 'bi-megaphone', 'dashboard.news.index', ['kanal' => 'pendaftaran'], 'dashboard.news.*', 0, 'pendaftaran');
            }
            $groups[] = ['label' => 'Pendaftaran', 'items' => $items];
        }

        // Konten yang ditugaskan Master Admin ke role ini
        if (! $user->isMaster()) {
            $assigned = [];
            foreach ($user->assignedMenus() as $menu) {
                $assigned[] = [
                    'label'   => $menu->title,
                    'icon'    => $menu->icon ?: Modules::icon($menu->type),
                    'url'     => $menu->dashboardUrl(),
                    'active'  => request()->routeIs('dashboard.studio.*', 'dashboard.blocks.*') && (int) request()->route('menu')?->id === $menu->id,
                    'badge'   => 0,
                    'hint'    => Modules::label($menu->type),
                ];
            }
            // Data menu buatan admin yang ditugaskan (tersimpan di tabel menu_contents)
            if ($user->assignedMenus()->contains(fn ($menu) => (bool) $menu->is_custom)) {
                $assigned[] = static::item('Data Menu Baru', 'bi-database', 'dashboard.archive.index', [], 'dashboard.archive.*');
            }
            if ($assigned) {
                $groups[] = ['label' => 'Tugas dari Master', 'items' => $assigned];
            }
        }

        if ($user->isMaster()) {
            $groups[] = ['label' => 'Sistem', 'items' => [
                static::item('Akun Admin', 'bi-person-gear', 'dashboard.users.index', [], 'dashboard.users.*'),
                static::item('Log Aktivitas', 'bi-clock-history', 'dashboard.activity.index', [], 'dashboard.activity.*'),
            ]];
        }

        $groups[] = ['label' => 'Akun', 'items' => [
            static::item('Profil Saya', 'bi-person-circle', 'dashboard.profile.edit', [], 'dashboard.profile.*'),
        ]];

        return $groups;
    }

    /** Ubah daftar kunci modul menjadi item sidebar (hanya yang boleh dikelola). */
    protected static function modules(User $user, array $keys): array
    {
        $items = [];
        $patterns = [
            'berita' => 'dashboard.news.*', 'galeri' => 'dashboard.gallery.*', 'kelulusan' => 'dashboard.graduates.*',
            'kalender' => 'dashboard.calendar.*', 'guru_staf' => 'dashboard.staff.*', 'keunikan' => 'dashboard.uniqueness.*',
            'testimoni' => 'dashboard.testimonials.*', 'kampus' => 'dashboard.branches.*', 'fasilitas' => 'dashboard.facilities.*',
        ];

        foreach ($keys as $key) {
            if (! $user->canManage($key)) {
                continue;
            }
            $m = Modules::permissions()[$key];
            $items[] = static::item($m['label'], $m['icon'], $m['route'][0], $m['route'][1], $patterns[$key] ?? '', 0, $key === 'berita' ? 'umum' : null);
        }

        return $items;
    }

    protected static function item(string $label, string $icon, string $route, array $params, string $pattern, int $badge = 0, ?string $channel = null): array
    {
        $active = $pattern !== '' && request()->routeIs(...explode('|', $pattern));

        // Berita umum & berita pendaftaran memakai route yang sama, dibedakan oleh ?kanal=
        if ($active && $channel !== null) {
            $current = request('kanal', request()->route('news')?->channel ?? 'umum');
            $active = $current === $channel;
        }

        return [
            'label'  => $label,
            'icon'   => $icon,
            'url'    => route($route, $params),
            'active' => $active,
            'badge'  => $badge,
            'hint'   => null,
        ];
    }

    /** Warna & label tiap role (dipakai layout dashboard yang sama). */
    public static function theme(string $role): array
    {
        return [
            'master' => [
                'label' => 'Master Admin', 'panel' => 'Pusat Kendali Website', 'icon' => 'bi-shield-lock',
                'color' => setting('dash_color_master', '#7A2B25'),
            ],
            'publikasi' => [
                'label' => 'Admin Publikasi', 'panel' => 'Ruang Redaksi', 'icon' => 'bi-broadcast',
                'color' => setting('dash_color_publikasi', '#2F5D50'),
            ],
            'pendaftaran' => [
                'label' => 'Admin Pendaftaran', 'panel' => 'Meja Pendaftaran', 'icon' => 'bi-clipboard-check',
                'color' => setting('dash_color_pendaftaran', '#1F4E79'),
            ],
        ][$role] ?? ['label' => 'Admin', 'panel' => 'Dashboard', 'icon' => 'bi-person', 'color' => '#7A2B25'];
    }
}
