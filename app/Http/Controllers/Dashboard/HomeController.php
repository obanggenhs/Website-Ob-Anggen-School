<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\CalendarEvent;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\Graduate;
use App\Models\Menu;
use App\Models\News;
use App\Models\Registration;
use App\Models\RegistrationType;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Beranda dashboard: satu controller, tampilan berbeda untuk tiap role.
 */
class HomeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return match ($user->role) {
            'publikasi'   => $this->publication($user),
            'pendaftaran' => $this->registration($user),
            default       => $this->master(),
        };
    }

    protected function master()
    {
        $tree = Menu::roots()->with('children')->get();

        $stats = [
            ['label' => 'Menu & submenu', 'value' => Menu::count(), 'icon' => 'bi-diagram-3', 'url' => route('dashboard.studio.index')],
            ['label' => 'Berita terbit', 'value' => News::published()->count(), 'icon' => 'bi-newspaper', 'url' => route('dashboard.news.index')],
            ['label' => 'Album galeri', 'value' => GalleryAlbum::count(), 'icon' => 'bi-images', 'url' => route('dashboard.gallery.index')],
            ['label' => 'Pendaftar baru', 'value' => Registration::where('status', 'baru')->count(), 'icon' => 'bi-person-plus', 'url' => route('dashboard.applicants.index', ['status' => 'baru'])],
        ];

        $admins = User::where('role', '!=', 'master')->orderBy('role')->get();
        $activities = ActivityLog::with('user')->latest('created_at')->take(8)->get();
        $applicants = Registration::with('type')->latest()->take(5)->get();
        $hidden = Menu::where('is_active', false)->count();
        $drafts = News::where('status', 'draft')->count();

        return view('dashboard.home.master', compact('tree', 'stats', 'admins', 'activities', 'applicants', 'hidden', 'drafts'));
    }

    protected function publication(User $user)
    {
        $stats = [
            ['label' => 'Berita terbit', 'value' => News::published()->where('channel', 'umum')->count(), 'icon' => 'bi-newspaper'],
            ['label' => 'Draf berita', 'value' => News::where('channel', 'umum')->where('status', 'draft')->count(), 'icon' => 'bi-pencil'],
            ['label' => 'Foto di galeri', 'value' => GalleryPhoto::count(), 'icon' => 'bi-images'],
            ['label' => 'Data lulusan', 'value' => Graduate::count(), 'icon' => 'bi-mortarboard'],
        ];

        $latestNews = News::with('category')->where('channel', 'umum')->latest()->take(6)->get();
        $albums = GalleryAlbum::withCount('photos')->with('photos')->latest()->take(4)->get();
        $agenda = CalendarEvent::where('is_active', true)->upcoming()->take(5)->get();
        $assigned = $user->assignedMenus();
        $popular = News::published()->orderByDesc('views')->take(5)->get();

        return view('dashboard.home.publikasi', compact('stats', 'latestNews', 'albums', 'agenda', 'assigned', 'popular'));
    }

    protected function registration(User $user)
    {
        $pipeline = collect(Registration::STATUSES)->map(fn ($s, $key) => $s + [
            'key' => $key, 'count' => Registration::where('status', $key)->count(),
        ]);

        $types = RegistrationType::withCount([
            'registrations',
            'registrations as new_count' => fn ($q) => $q->where('status', 'baru'),
            'registrations as accepted_count' => fn ($q) => $q->where('status', 'diterima'),
        ])->orderByDesc('is_open')->orderBy('position')->get();

        $latest = Registration::with('type')->latest()->take(8)->get();
        $schedules = Registration::with('type')->whereNotNull('schedule_at')->where('schedule_at', '>=', now()->startOfDay())
            ->orderBy('schedule_at')->take(5)->get();

        // Grafik pendaftar 14 hari terakhir
        $daily = collect(range(13, 0))->map(function ($ago) {
            $day = today()->subDays($ago);

            return ['label' => $day->format('d/m'), 'value' => Registration::whereDate('created_at', $day)->count()];
        });

        $assigned = $user->assignedMenus();

        return view('dashboard.home.pendaftaran', compact('pipeline', 'types', 'latest', 'schedules', 'daily', 'assigned'));
    }
}
