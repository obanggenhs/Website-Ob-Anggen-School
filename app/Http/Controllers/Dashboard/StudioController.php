<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Models\Facility;
use App\Models\GalleryAlbum;
use App\Models\Graduate;
use App\Models\Menu;
use App\Models\News;
use App\Models\Registration;
use App\Models\RegistrationType;
use App\Models\Setting;
use App\Models\Staff;
use App\Models\Uniqueness;
use App\Support\Media;
use App\Support\Modules;
use App\Support\PageOptions;
use App\Support\SettingSchema;
use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Studio Struktur & Konten.
 * Kiri: pohon Menu Utama → Submenu. Kanan: ruang kerja menu terpilih
 * (Isi Konten · Pengaturan Menu · Akses Dashboard).
 */
class StudioController extends Controller
{
    public function index()
    {
        return view('dashboard.studio.index', [
            'tree'  => $this->tree(),
            'menu'  => null,
            'types' => Modules::types(),
        ]);
    }

    public function show(Request $request, Menu $menu)
    {
        $user = $request->user();
        abort_unless($user->canEditMenu($menu), 403, 'Menu ini belum ditugaskan untuk akun Anda.');

        $tab = $user->isMaster() && in_array($request->query('tab'), ['konten', 'pengaturan', 'akses'], true)
            ? $request->query('tab')
            : 'konten';

        $menu->load(['parent', 'children', 'blocks', 'editor']);

        return view('dashboard.studio.show', [
            'menu'       => $menu,
            'tab'        => $tab,
            'tree'       => $user->isMaster() ? $this->tree() : null,
            'types'      => Modules::types(),
            'summary'    => $this->summary($menu),
            'homeFields' => $menu->type === 'beranda' ? SettingSchema::home() : [],
            'roots'      => Menu::roots()->where('type', 'dropdown')->where('id', '!=', $menu->id)->get(),
        ]);
    }

    /* =====================================================================
       Struktur menu (khusus Master Admin)
       ===================================================================== */

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'              => ['required', 'string', 'max:80'],
            'type'               => ['required', Rule::in(array_keys(Modules::types()))],
            'parent_id'          => ['nullable', 'exists:menus,id'],
            'param'              => ['nullable', 'string', 'max:60'],
            'url'                => ['nullable', 'required_if:type,tautan', 'string', 'max:255'],
            'access_publikasi'   => ['nullable', 'boolean'],
            'access_pendaftaran' => ['nullable', 'boolean'],
        ], [], ['title' => 'nama menu', 'type' => 'jenis isi', 'url' => 'alamat tautan']);

        $type = Modules::type($data['type']);
        $parent = isset($data['parent_id']) ? Menu::find($data['parent_id']) : null;

        if ($parent && ! empty($type['root_only'])) {
            return back()->withInput()->with('error', $type['label'].' hanya bisa dibuat sebagai Menu Utama.');
        }
        if ($parent && $parent->type !== 'dropdown') {
            return back()->withInput()->with('error', 'Submenu hanya bisa ditaruh di dalam Menu Utama bertipe Grup Dropdown.');
        }
        if (! empty($type['single']) && Menu::where('type', $data['type'])->exists()) {
            return back()->withInput()->with('error', $type['label'].' sudah ada. Jenis ini hanya boleh satu.');
        }

        $menu = Menu::create([
            'title'              => $data['title'],
            'type'               => $data['type'],
            'parent_id'          => $parent?->id,
            'param'              => $data['param'] ?? null,
            'url'                => $data['url'] ?? null,
            'position'           => (int) Menu::where('parent_id', $parent?->id)->max('position') + 1,
            'is_active'          => true,
            'access_publikasi'   => $request->boolean('access_publikasi'),
            'access_pendaftaran' => $request->boolean('access_pendaftaran'),
            'updated_by'         => $request->user()->id,
        ]);

        activity_log('membuat menu', $menu->title.' ('.$menu->type_label.')');

        return redirect()->route('dashboard.studio.show', $menu)
            ->with('success', 'Menu "'.$menu->title.'" dibuat. Sekarang isi kontennya di tab Isi Konten.');
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'title'        => ['required', 'string', 'max:80'],
            'slug'         => ['required', 'alpha_dash', 'max:90', Rule::unique('menus', 'slug')->ignore($menu->id)],
            'type'         => ['required', Rule::in(array_keys(Modules::types()))],
            'parent_id'    => ['nullable', 'exists:menus,id'],
            'param'        => ['nullable', 'string', 'max:60'],
            'url'          => ['nullable', 'required_if:type,tautan', 'string', 'max:255'],
            'icon'         => ['nullable', 'string', 'max:60'],
            'is_active'    => ['nullable', 'boolean'],
            'is_highlight' => ['nullable', 'boolean'],
            'open_new_tab' => ['nullable', 'boolean'],
            'show_sidebar' => ['nullable', 'boolean'],
        ], [], ['title' => 'nama menu', 'slug' => 'alamat (slug)']);

        $type = Modules::type($data['type']);
        $parentId = $data['parent_id'] ?? null;

        if ($parentId == $menu->id) {
            return back()->with('error', 'Menu tidak bisa menjadi submenu dari dirinya sendiri.');
        }
        if ($parentId && (! empty($type['root_only']) || $menu->children()->exists())) {
            return back()->with('error', 'Menu ini harus tetap menjadi Menu Utama.');
        }
        if ($parentId && Menu::find($parentId)?->type !== 'dropdown') {
            return back()->with('error', 'Submenu hanya bisa ditaruh di dalam Grup Dropdown.');
        }
        if ($data['type'] !== 'dropdown' && $menu->children()->exists()) {
            return back()->with('error', 'Menu ini masih punya submenu. Pindahkan submenu terlebih dulu sebelum mengubah jenisnya.');
        }
        if (! empty($type['single']) && Menu::where('type', $data['type'])->where('id', '!=', $menu->id)->exists()) {
            return back()->with('error', $type['label'].' sudah ada di menu lain.');
        }

        if ((int) $menu->parent_id !== (int) $parentId) {
            $data['position'] = (int) Menu::where('parent_id', $parentId)->max('position') + 1;
        }

        $menu->update($data + [
            'parent_id'    => $parentId,
            'is_active'    => $request->boolean('is_active'),
            'is_highlight' => $request->boolean('is_highlight'),
            'open_new_tab' => $request->boolean('open_new_tab'),
            'show_sidebar' => $request->boolean('show_sidebar'),
            'updated_by'   => $request->user()->id,
        ]);

        activity_log('mengubah pengaturan menu', $menu->title);

        return redirect()->route('dashboard.studio.show', [$menu, 'tab' => 'pengaturan'])->with('success', 'Pengaturan menu disimpan.');
    }

    public function access(Request $request, Menu $menu)
    {
        $values = [
            'access_publikasi'   => $request->boolean('access_publikasi'),
            'access_pendaftaran' => $request->boolean('access_pendaftaran'),
        ];

        $menu->update($values + ['updated_by' => $request->user()->id]);

        if ($menu->isGroup() && $request->boolean('apply_children')) {
            $menu->children()->update($values);
        }

        activity_log('mengatur akses dashboard', $menu->title.' → '.(implode(', ', $menu->roleBadges()) ?: 'hanya master'));

        return redirect()->route('dashboard.studio.show', [$menu, 'tab' => 'akses'])->with('success', 'Akses dashboard diperbarui.');
    }

    public function toggle(Request $request, Menu $menu)
    {
        $menu->update(['is_active' => ! $menu->is_active, 'updated_by' => $request->user()->id]);
        activity_log($menu->is_active ? 'menampilkan menu' : 'menyembunyikan menu', $menu->title);

        if ($request->expectsJson()) {
            return response()->json(['active' => $menu->is_active]);
        }

        return back()->with('success', 'Menu "'.$menu->title.'" sekarang '.($menu->is_active ? 'tampil' : 'disembunyikan').' di website.');
    }

    /** Simpan urutan hasil seret (drag & drop) pohon menu. */
    public function reorder(Request $request)
    {
        $request->validate([
            'items'             => ['required', 'array'],
            'items.*.id'        => ['required', 'integer', 'exists:menus,id'],
            'items.*.parent_id' => ['nullable', 'integer', 'exists:menus,id'],
        ]);

        $menus = Menu::whereIn('id', collect($request->items)->pluck('id'))->get()->keyBy('id');
        $positions = [];

        foreach ($request->items as $item) {
            $menu = $menus[$item['id']];
            $parentId = $item['parent_id'] ?? null;

            // Aturan yang sama dengan form: submenu hanya di dalam Grup Dropdown
            if ($parentId) {
                $parent = Menu::find($parentId);
                if (! $parent || $parent->type !== 'dropdown' || ! empty(Modules::type($menu->type)['root_only'])) {
                    return response()->json(['message' => '"'.$menu->title.'" tidak bisa dipindah ke sana.'], 422);
                }
            }

            $key = $parentId ?? 0;
            $positions[$key] = ($positions[$key] ?? 0) + 1;
            $menu->update(['parent_id' => $parentId, 'position' => $positions[$key]]);
        }

        activity_log('menyusun ulang struktur menu');

        return response()->json(['message' => 'Urutan menu disimpan.']);
    }

    /** Geser satu tingkat ke atas/bawah (alternatif tanpa seret). */
    public function move(Request $request, Menu $menu, string $direction)
    {
        $siblings = Menu::where('parent_id', $menu->parent_id)->orderBy('position')->get()->values();
        $index = $siblings->search(fn ($m) => $m->id === $menu->id);
        $swap = $siblings->get($direction === 'naik' ? $index - 1 : $index + 1);

        if ($swap) {
            [$a, $b] = [$menu->position, $swap->position];
            $menu->update(['position' => $b === $a ? $a + ($direction === 'naik' ? -1 : 1) : $b]);
            $swap->update(['position' => $a]);
        }

        return back();
    }

    public function destroy(Request $request, Menu $menu)
    {
        if ($menu->type === 'beranda') {
            return back()->with('error', 'Beranda tidak bisa dihapus, hanya bisa disembunyikan dari navbar.');
        }

        $title = $menu->title;
        $removed = $menu->children()->count();

        foreach ($menu->children()->with('blocks')->get()->push($menu->load('blocks')) as $item) {
            foreach ($item->headerFiles() as $file) {
                Upload::delete($file);
            }
            foreach ($item->blocks as $block) {
                foreach ($block->files() as $file) {
                    Upload::delete($file);
                }
                foreach ($block->cards as $card) {
                    foreach ($card->files() as $file) {
                        Upload::delete($file);
                    }
                }
            }
        }

        $menu->delete();
        activity_log('menghapus menu', $title.($removed ? " beserta {$removed} submenu" : ''));

        return redirect()->route('dashboard.studio.index')->with('success', 'Menu "'.$title.'" dihapus.');
    }

    /* =====================================================================
       Isi konten (Master + role yang ditugaskan)
       ===================================================================== */

    /**
     * Header halaman & teks pembuka, termasuk latar header:
     * foto, video lokal, link YouTube, atau slide foto.
     * Dipakai semua menu yang punya halaman, juga hero Beranda.
     */
    public function content(Request $request, Menu $menu)
    {
        abort_unless($request->user()->canEditMenu($menu), 403);

        $data = $request->validate([
            'subtitle'        => ['nullable', 'string', 'max:255'],
            'intro'           => ['nullable', 'string'],
            'banner_type'     => ['required', Rule::in(array_keys(Menu::BANNER_TYPES))],
            // File tanpa batas ukuran & jumlah (file besar dikirim bertahap dari dashboard)
            'banner'          => ['nullable', 'image'],
            'remove_banner'   => ['nullable', 'boolean'],
            'banner_video'    => ['nullable', 'file', 'mimes:'.Media::VIDEO_MIMES],
            'remove_video'    => ['nullable', 'boolean'],
            'banner_youtube'  => ['nullable', 'required_if:banner_type,youtube', 'url', 'max:255'],
            'slides'          => ['nullable', 'array'],
            'slides.*'        => ['image'],
            'remove_slides'   => ['nullable', 'array'],
            'remove_slides.*' => ['integer'],
            // Lapisan warna di atas latar: ikut (pengaturan semua halaman) / tipis / sedang / pekat
            'banner_overlay'  => ['nullable', Rule::in(array_merge(['ikut'], array_keys(Menu::OVERLAYS)))],
            'overlay_all'     => ['nullable', 'boolean'],
        ], [
            'banner_youtube.required_if' => 'Isi link YouTube untuk latar header jenis YouTube.',
            'banner_video.mimes'         => 'Video harus berformat MP4, WEBM, OGG, atau MOV agar bisa diputar di browser.',
        ], [
            'subtitle' => 'sub judul', 'banner' => 'foto latar', 'banner_video' => 'video latar',
            'banner_youtube' => 'link YouTube', 'slides' => 'slide foto', 'slides.*' => 'slide foto',
        ]);

        if ($data['banner_type'] === 'youtube' && ! youtube_id($data['banner_youtube'] ?? null)) {
            return back()->withInput()->withErrors(['banner_youtube' => 'Link YouTube tidak dikenali. Contoh: https://www.youtube.com/watch?v=xxxxxxxxxxx']);
        }

        // Foto (juga dipakai sebagai gambar sampul/poster saat video belum termuat)
        if ($request->boolean('remove_banner')) {
            Upload::delete($menu->banner);
            $menu->banner = null;
        }
        $menu->banner = Upload::replace($request->file('banner'), $menu->banner, 'banners');

        // Video lokal
        if ($request->boolean('remove_video')) {
            Upload::delete($menu->banner_video);
            $menu->banner_video = null;
        }
        $menu->banner_video = Upload::replace($request->file('banner_video'), $menu->banner_video, 'banners/video');

        // Slide foto: hapus yang dicentang, lalu tambahkan yang baru diunggah
        $slides = collect($menu->banner_slides ?? []);
        foreach ((array) $request->input('remove_slides', []) as $index) {
            Upload::delete($slides->get((int) $index));
            $slides->forget((int) $index);
        }
        foreach ((array) $request->file('slides', []) as $file) {
            if ($path = Upload::store($file, 'banners/slides')) {
                $slides->push($path);
            }
        }
        $slides = $slides->values();

        // Jenis yang dipilih harus punya isi
        $missing = match ($data['banner_type']) {
            'video'  => ! $menu->banner_video ? 'Unggah file video terlebih dahulu untuk latar header jenis Video lokal.' : null,
            'slider' => $slides->count() < 2 ? 'Slide foto membutuhkan minimal 2 gambar.' : null,
            default  => null,
        };
        if ($missing) {
            $menu->banner_slides = $slides->all();
            $menu->save();   // file yang sudah terunggah tetap disimpan

            return back()->withInput()->with('error', $missing);
        }

        // Lapisan warna: untuk halaman ini saja, atau (khusus Master Admin) untuk semua halaman sekaligus
        $overlay = $data['banner_overlay'] ?? 'ikut';
        $overlayAll = $request->boolean('overlay_all') && $overlay !== 'ikut' && $request->user()->isMaster();
        if ($overlayAll) {
            Setting::putMany(['page_header_overlay' => $overlay]);
            Menu::query()->whereNotNull('banner_overlay')->update(['banner_overlay' => null]);
        }

        $menu->fill([
            'subtitle'       => $menu->type === 'beranda' ? $menu->subtitle : ($data['subtitle'] ?? null),
            'intro'          => $menu->type === 'beranda' ? $menu->intro : clean_html($data['intro'] ?? ''),
            'banner_type'    => $data['banner_type'],
            'banner_youtube' => $data['banner_youtube'] ?? $menu->banner_youtube,
            'banner_slides'  => $slides->all(),
            'banner_overlay' => $overlayAll || $overlay === 'ikut' ? null : $overlay,
            'updated_by'     => $request->user()->id,
        ])->save();

        activity_log('mengubah header halaman', $menu->title.' (latar: '.Menu::BANNER_TYPES[$menu->banner_type]['label']
            .', lapisan: '.Menu::OVERLAYS[$menu->headerOverlay()]['label'].($overlayAll ? ' untuk semua halaman' : '').')');

        $label = $menu->type === 'beranda' ? 'Latar hero Beranda' : 'Header & teks pembuka "'.$menu->title.'"';
        $note = $overlayAll ? ' Lapisan warna "'.Menu::OVERLAYS[$overlay]['label'].'" kini dipakai di semua halaman.' : '';

        return back()->with('success', $label.' disimpan.'.$note);
    }

    /**
     * Fitur bantuan pengunjung untuk halaman ini:
     * pencarian, filter kategori, filter tahun, urutkan, tampilan, jumlah hasil, bagikan, jumlah per halaman.
     */
    public function options(Request $request, Menu $menu)
    {
        abort_unless($request->user()->canEditMenu($menu), 403);

        $type = $menu->type === 'beranda' ? 'halaman' : $menu->type;
        abort_unless(PageOptions::supports($type), 404);

        $request->validate(['per_page' => ['nullable', Rule::in(PageOptions::PER_PAGE)]]);

        $options = [];
        foreach (PageOptions::SUPPORT[$type] as $key) {
            $options[$key] = $key === 'per_page'
                ? (int) ($request->input('per_page') ?: PageOptions::DEFAULTS[$type]['per_page'])
                : ($request->boolean('opt_'.$key) ? 1 : 0);
        }

        // Pengaturan lain di page_options (mis. letak isi tambahan) tetap dipertahankan
        $menu->update([
            'page_options' => array_merge((array) $menu->page_options, $options),
            'updated_by'   => $request->user()->id,
        ]);

        $on = collect($options)->filter(fn ($v, $k) => $k !== 'per_page' && $v)->keys()
            ->map(fn ($k) => PageOptions::label($type, $k))->implode(', ');
        activity_log('mengatur fitur halaman', $menu->title.($on ? ': '.$on : ''));

        return redirect()->to(route('dashboard.studio.show', $menu).'#fitur-halaman')
            ->with('success', 'Fitur bantuan pengunjung untuk "'.$menu->title.'" disimpan.');
    }

    /** Letak isi tambahan (blok) di halaman modul: di atas atau di bawah daftar. */
    public function blocksPlace(Request $request, Menu $menu)
    {
        abort_unless($request->user()->canEditMenu($menu), 403);
        abort_unless($menu->supportsBlocks() && ! $menu->isPage(), 404);

        $place = $request->validate(['blocks_at' => ['required', Rule::in(['atas', 'bawah'])]])['blocks_at'];

        $menu->update([
            'page_options' => array_merge((array) $menu->page_options, ['blocks_at' => $place]),
            'updated_by'   => $request->user()->id,
        ]);
        activity_log('mengatur letak isi tambahan', $menu->title.': '.$place);

        return redirect()->to(route('dashboard.studio.show', $menu).'#isi-tambahan')
            ->with('success', 'Isi tambahan "'.$menu->title.'" sekarang tampil di '.($place === 'atas' ? 'atas' : 'bawah').' daftar.');
    }

    /** Isi Beranda: hero, sambutan, statistik, video, dan section yang tampil. */
    public function home(Request $request, Menu $menu)
    {
        abort_unless($menu->type === 'beranda' && $request->user()->canEditMenu($menu), 403);

        $fields = SettingSchema::home();
        $rules = [];
        foreach ($fields as $key => $field) {
            $rules[$key] = match ($field['type']) {
                'image'  => ['nullable', 'image'],
                'number' => ['nullable', 'integer', 'min:0', 'max:100000'],
                'url'    => ['nullable', 'url', 'max:255'],
                'toggle' => ['nullable', 'boolean'],
                default  => ['nullable', 'string', 'max:5000'],
            };
        }
        $data = $request->validate($rules);

        $values = [];
        foreach ($fields as $key => $field) {
            $values[$key] = match ($field['type']) {
                'image'  => $request->hasFile($key) ? Upload::replace($request->file($key), setting($key), 'home') : setting($key),
                'toggle' => $request->boolean($key) ? 1 : 0,
                'editor' => clean_html($data[$key] ?? ''),
                default  => $data[$key] ?? '',
            };
        }

        Setting::putMany($values);
        $menu->touch();
        activity_log('mengubah isi beranda');

        return back()->with('success', 'Isi Beranda disimpan.');
    }

    /* =====================================================================
       Bantuan
       ===================================================================== */

    protected function tree()
    {
        return Menu::roots()
            ->with(['children' => fn ($q) => $q->withCount('blocks')])
            ->withCount('blocks')
            ->get();
    }

    /** Ringkasan data modul untuk tab Isi Konten (menu bertipe modul). */
    protected function summary(Menu $menu): ?array
    {
        $p = $menu->param;
        $user = auth()->user();

        $summary = match ($menu->type) {
            'berita' => [
                'items' => News::query()
                    ->when($p === 'pendaftaran', fn ($q) => $q->where('channel', 'pendaftaran'))
                    ->when(Str::startsWith((string) $p, 'kategori:'), fn ($q) => $q->whereHas('category', fn ($c) => $c->where('slug', Str::after($p, 'kategori:'))))
                    ->latest()->take(5)->get()
                    ->map(fn ($n) => ['title' => $n->title, 'meta' => ($n->status === 'published' ? 'Terbit' : 'Draf').' · '.tgl($n->published_at ?? $n->created_at, 'd M Y'), 'url' => route('dashboard.news.edit', $n)]),
                'count' => News::when($p === 'pendaftaran', fn ($q) => $q->where('channel', 'pendaftaran'))->count(),
                'noun' => 'berita',
            ],
            'galeri' => [
                'items' => GalleryAlbum::withCount('photos')->latest()->take(5)->get()
                    ->map(fn ($a) => ['title' => $a->title, 'meta' => $a->photos_count.' foto', 'url' => route('dashboard.gallery.edit', $a)]),
                'count' => GalleryAlbum::count(), 'noun' => 'album',
            ],
            'kelulusan' => [
                'items' => Graduate::when($p, fn ($q) => $q->where('level', $p))->latest()->take(5)->get()
                    ->map(fn ($g) => ['title' => $g->name, 'meta' => $g->level_label.' · '.$g->graduation_year, 'url' => route('dashboard.graduates.edit', $g)]),
                'count' => Graduate::when($p, fn ($q) => $q->where('level', $p))->count(), 'noun' => 'lulusan',
            ],
            'kalender' => [
                'items' => CalendarEvent::forLevel($p)->upcoming()->take(5)->get()
                    ->map(fn ($e) => ['title' => $e->title, 'meta' => $e->date_label, 'url' => route('dashboard.calendar.edit', $e)]),
                'count' => CalendarEvent::forLevel($p)->count(), 'noun' => 'agenda',
            ],
            'guru_staf' => [
                'items' => Staff::orderBy('position')->take(5)->get()
                    ->map(fn ($s) => ['title' => $s->name, 'meta' => $s->position_title ?: $s->category_label, 'url' => route('dashboard.staff.edit', $s)]),
                'count' => Staff::count(), 'noun' => 'orang',
            ],
            'keunikan' => [
                'items' => Uniqueness::orderBy('position')->take(5)->get()
                    ->map(fn ($u) => ['title' => $u->title, 'meta' => $u->is_active ? 'Tampil' : 'Disembunyikan', 'url' => route('dashboard.uniqueness.edit', $u)]),
                'count' => Uniqueness::count(), 'noun' => 'program',
            ],
            'fasilitas' => [
                'items' => Facility::with('branch')->where('type', $p ?: 'gedung')->orderBy('position')->take(5)->get()
                    ->map(fn ($f) => ['title' => $f->name, 'meta' => $f->branch?->name, 'url' => route('dashboard.facilities.edit', $f)]),
                'count' => Facility::where('type', $p ?: 'gedung')->count(), 'noun' => 'fasilitas',
            ],
            'pendaftaran' => [
                'items' => RegistrationType::withCount('registrations')->when($p, fn ($q) => $q->where('audience', $p))->orderBy('position')->get()
                    ->map(fn ($t) => ['title' => $t->name, 'meta' => $t->status_label.' · '.$t->registrations_count.' pendaftar', 'url' => route('dashboard.registration-types.edit', $t)]),
                'count' => RegistrationType::when($p, fn ($q) => $q->where('audience', $p))->count(), 'noun' => 'jalur',
            ],
            'cek_status' => [
                'items' => collect(Registration::STATUSES)->map(fn ($s, $key) => [
                    'title' => $s['label'], 'meta' => Registration::where('status', $key)->count().' pendaftar',
                    'url' => route('dashboard.applicants.index', ['status' => $key]),
                ])->values(),
                'count' => Registration::count(), 'noun' => 'pendaftar',
            ],
            default => null,
        };

        if (! $summary) {
            return null;
        }

        $permission = Modules::permissionForMenu($menu->type, $menu->param);
        $module = $permission ? Modules::permissions()[$permission] : null;

        return $summary + [
            'can_manage' => $permission && $user->canManage($permission),
            'manage_url' => $module ? route($module['route'][0], $module['route'][1]) : null,
            'module'     => $module['label'] ?? null,
        ];
    }
}