<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\CalendarEvent;
use App\Models\Menu;
use App\Models\News;
use App\Models\RegistrationType;
use App\Models\Testimonial;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function index()
    {
        $home = Menu::where('type', 'beranda')->first();

        $blocks = $home ? $home->blocks()->where('is_active', true)->get() : collect();

        $branches = setting('home_show_branches') ? Branch::active()->get() : collect();

        $agenda = (setting('home_show_agenda') && Menu::moduleEnabled('kalender'))
            ? CalendarEvent::where('is_active', true)->upcoming()->take(6)->get()
            : collect();

        $news = (setting('home_show_news') && Menu::moduleEnabled('berita'))
            ? News::published()->with('category')->orderByDesc('is_featured')->latest('published_at')->take(3)->get()
            : collect();

        $testimonials = setting('home_show_testimonials')
            ? Testimonial::where('is_active', true)->where('show_on_home', true)->orderBy('position')->get()
            : collect();

        // Akses cepat dibentuk otomatis dari struktur menu yang disusun Master Admin
        $shortcuts = Menu::navTree()
            ->flatMap(fn ($m) => $m->isGroup() ? $m->children : collect([$m]))
            ->reject(fn ($m) => in_array($m->type, ['beranda', 'dropdown', 'tautan'], true))
            ->values();

        // Tombol "Daftar sekarang" di hero Beranda mengikuti menu Pendaftaran di navbar:
        // 1) menu Pendaftaran (grup atau submenu) harus tampil di navbar, dan
        // 2) minimal satu jalur pendaftaran sedang dibuka.
        $registrationMenuVisible = Menu::navTree()
            ->flatMap(fn ($m) => array_merge([$m], $m->children->all()))
            ->contains(fn ($m) => $m->isVisible() && (
                $m->type === 'pendaftaran'
                || Str::contains(Str::lower($m->slug.' '.$m->title), ['pendaftaran', 'ppdb'])
            ));

        $registrationOpen = $registrationMenuVisible
            && RegistrationType::where('is_open', true)->exists();

        return view('web.home', compact('home', 'blocks', 'branches', 'agenda', 'news', 'testimonials', 'shortcuts', 'registrationOpen'));
    }
}