<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Support\Upload;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        return view('dashboard.testimonials.index', ['items' => Testimonial::orderBy('position')->get()]);
    }

    public function create()
    {
        return view('dashboard.testimonials.form', ['item' => new Testimonial([
            'is_active' => true, 'show_on_home' => true, 'position' => (int) Testimonial::max('position') + 1,
        ])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['photo'] = Upload::store($request->file('photo'), 'testimonials');
        $item = Testimonial::create($data);
        activity_log('menambah testimoni', $item->name);

        return redirect()->route('dashboard.testimonials.index')->with('success', 'Testimoni ditambahkan.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('dashboard.testimonials.form', ['item' => $testimonial]);
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $data = $this->validated($request);
        $data['photo'] = Upload::replace($request->file('photo'), $testimonial->photo, 'testimonials');
        $testimonial->update($data);
        activity_log('mengubah testimoni', $testimonial->name);

        return redirect()->route('dashboard.testimonials.index')->with('success', 'Testimoni diperbarui.');
    }

    public function destroy(Testimonial $testimonial)
    {
        Upload::delete($testimonial->photo);
        $testimonial->delete();
        activity_log('menghapus testimoni', $testimonial->name);

        return back()->with('success', 'Testimoni dihapus.');
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:120'],
            'role'     => ['nullable', 'string', 'max:120'],
            'content'  => ['required', 'string', 'max:1500'],
            'position' => ['nullable', 'integer', 'min:0'],
            'photo'    => ['nullable', 'image'],
        ], [], ['name' => 'nama', 'content' => 'isi testimoni']);

        unset($data['photo']);
        $data['position'] = $data['position'] ?? 0;
        $data['show_on_home'] = $request->boolean('show_on_home');
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
