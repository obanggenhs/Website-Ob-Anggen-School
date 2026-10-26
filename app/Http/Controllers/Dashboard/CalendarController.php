<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $events = CalendarEvent::query()
            ->when($request->filled('jenjang'), fn ($q) => $q->where('level', $request->jenjang))
            ->when($request->filled('kategori'), fn ($q) => $q->where('category', $request->kategori))
            ->when($request->filled('tahun'), fn ($q) => $q->where('academic_year', $request->tahun))
            ->when($request->query('waktu', 'mendatang') === 'mendatang', fn ($q) => $q->upcoming(), fn ($q) => $q->orderByDesc('start_date'))
            ->paginate(20)->withQueryString();

        $years = CalendarEvent::whereNotNull('academic_year')->distinct()->orderByDesc('academic_year')->pluck('academic_year');

        return view('dashboard.calendar.index', compact('events', 'years'));
    }

    public function create()
    {
        $month = (int) date('n');
        $year = (int) date('Y');
        $academic = $month >= 7 ? $year.'/'.($year + 1) : ($year - 1).'/'.$year;

        return view('dashboard.calendar.form', ['event' => new CalendarEvent([
            'start_date' => today(), 'category' => 'akademik', 'level' => 'semua', 'academic_year' => $academic, 'is_active' => true,
        ])]);
    }

    public function store(Request $request)
    {
        $event = CalendarEvent::create($this->validated($request));
        activity_log('menambah agenda', $event->title);

        return redirect()->route('dashboard.calendar.index')->with('success', 'Agenda ditambahkan ke kalender.');
    }

    public function edit(CalendarEvent $calendar)
    {
        return view('dashboard.calendar.form', ['event' => $calendar]);
    }

    public function update(Request $request, CalendarEvent $calendar)
    {
        $calendar->update($this->validated($request));
        activity_log('mengubah agenda', $calendar->title);

        return redirect()->route('dashboard.calendar.index')->with('success', 'Agenda diperbarui.');
    }

    public function destroy(CalendarEvent $calendar)
    {
        $calendar->delete();
        activity_log('menghapus agenda', $calendar->title);

        return back()->with('success', 'Agenda dihapus.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'title'         => ['required', 'string', 'max:150'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'start_date'    => ['required', 'date'],
            'end_date'      => ['nullable', 'date', 'after_or_equal:start_date'],
            'time_info'     => ['nullable', 'string', 'max:60'],
            'location'      => ['nullable', 'string', 'max:150'],
            'category'      => ['required', Rule::in(array_keys(CalendarEvent::CATEGORIES))],
            'level'         => ['required', Rule::in(array_keys(CalendarEvent::LEVELS))],
            'academic_year' => ['nullable', 'regex:/^\d{4}\/\d{4}$/'],
        ], ['academic_year.regex' => 'Format tahun ajaran: 2026/2027'], ['title' => 'nama agenda', 'start_date' => 'tanggal mulai', 'end_date' => 'tanggal selesai']);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
