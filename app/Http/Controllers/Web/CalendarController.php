<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use App\Models\Menu;
use Illuminate\Http\Request;

/**
 * Kalender akademik per jenjang (SD / SMP / SMA).
 * Kalender bulanan digambar oleh public/assets/js/calendar.js dari data JSON.
 */
class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $level = in_array($request->jenjang, ['sd', 'smp', 'sma'], true) ? $request->jenjang : null;

        // Tahun ajaran: dari ?tahun=, atau tahun ajaran yang sedang berjalan, atau yang terbaru
        $years = CalendarEvent::where('is_active', true)->whereNotNull('academic_year')
            ->distinct()->orderByDesc('academic_year')->pluck('academic_year');
        $current = today()->month >= 7
            ? today()->year.'/'.(today()->year + 1)
            : (today()->year - 1).'/'.today()->year;
        $year = match (true) {
            $request->filled('tahun') && $years->contains($request->tahun) => $request->tahun,
            $years->contains($current) => $current,
            default => $years->first() ?? $current,
        };
        $startYear = (int) substr($year, 0, 4);

        $events = CalendarEvent::where('is_active', true)
            ->forLevel($level)
            ->where(fn ($q) => $q->where('academic_year', $year)->orWhereNull('academic_year'))
            ->orderBy('start_date')
            ->get();

        // Bulan yang dibuka pertama kali
        $first = sprintf('%04d-07', $startYear);
        $last = sprintf('%04d-06', $startYear + 1);
        $month = match (true) {
            preg_match('/^\d{4}-\d{2}$/', (string) $request->bulan) === 1 => $request->bulan,
            today()->format('Y-m') >= $first && today()->format('Y-m') <= $last => today()->format('Y-m'),
            $events->isNotEmpty() => $events->first()->start_date->format('Y-m'),
            default => $first,
        };

        $next = $events->first(fn ($e) => $e->isOngoing() || $e->start_date->gte(today()));
        $menu = Menu::forModule('kalender', $level);

        $calendar = [
            'level'      => $level,
            'year'       => $year,
            'startYear'  => $startYear,
            'month'      => $month,
            'today'      => today()->format('Y-m-d'),
            'baseUrl'    => route('calendar.index'),
            'schoolName' => setting('site_name'),
            'categories' => CalendarEvent::CATEGORIES,
            'events'     => $events->map->toCalendarArray()->values(),
        ];

        return view('web.calendar.index', compact('events', 'level', 'years', 'year', 'next', 'menu', 'calendar'));
    }
}