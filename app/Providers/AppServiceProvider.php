<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\News;
use App\Support\DashboardMenu;
use App\Support\MailSettings;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('id');
        MailSettings::apply();   // email memakai pengaturan dari dashboard bila sudah diisi
        Paginator::useBootstrapFive();
        Route::resourceVerbs(['create' => 'tambah', 'edit' => 'ubah']);

        // Menu Utama / Submenu yang dibuat admin lewat dashboard ditandai "buatan admin" (bukan bawaan),
        // lalu isinya dicatat di tabel menu_contents menurut nama menunya (lihat App\Support\MenuArchive)
        Menu::creating(function (Menu $menu) {
            if (! app()->runningInConsole() && auth()->check()) {
                $menu->is_custom = true;
                if (! $menu->created_by) {
                    $menu->created_by = auth()->id();
                }
            }
        });

        // Navbar & footer website
        View::composer(['layouts.web', 'web.partials.footer'], function ($view) {
            static $menus = null;
            $menus ??= rescue(fn () => Menu::navTree(), collect(), false);
            $view->with('navMenus', $menus);
        });

        // Sidebar halaman dalam website
        View::composer('web.partials.sidebar', function ($view) {
            $view->with('sidebarNews', rescue(fn () => News::published()->latest('published_at')->take(4)->get(), collect(), false));
        });

        // Layout dashboard (satu layout untuk tiga role)
        View::composer('layouts.dashboard', function ($view) {
            $user = auth()->user();
            $view->with('sidebarGroups', $user ? DashboardMenu::for($user) : []);
            $view->with('theme', DashboardMenu::theme($user?->role ?? 'master'));
        });
    }
}
