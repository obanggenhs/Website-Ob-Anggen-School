<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $types = $this->types($request);

        $categories = Category::whereIn('type', $types)->withCount(['news', 'albums'])->orderBy('type')->orderBy('name')->get()->groupBy('type');

        return view('dashboard.categories.index', compact('categories', 'types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'type' => ['required', Rule::in($this->types($request))],
        ], [], ['name' => 'nama kategori']);

        $slug = Str::slug($data['name']);
        if (Category::where('type', $data['type'])->where('slug', $slug)->exists()) {
            return back()->with('error', 'Kategori "'.$data['name'].'" sudah ada.');
        }

        Category::create($data + ['slug' => $slug]);
        activity_log('menambah kategori', $data['name']);

        return back()->with('success', 'Kategori ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        abort_unless(in_array($category->type, $this->types($request), true), 403);

        $data = $request->validate(['name' => ['required', 'string', 'max:60']]);
        $category->update(['name' => $data['name'], 'slug' => Str::slug($data['name'])]);

        return back()->with('success', 'Kategori diperbarui.');
    }

    public function destroy(Request $request, Category $category)
    {
        abort_unless(in_array($category->type, $this->types($request), true), 403);

        $category->delete();
        activity_log('menghapus kategori', $category->name);

        return back()->with('success', 'Kategori dihapus. Berita/album di dalamnya menjadi tanpa kategori.');
    }

    protected function types(Request $request): array
    {
        $user = $request->user();

        return array_values(array_filter([
            ($user->canManage('berita') || $user->canManage('berita_pendaftaran')) ? 'news' : null,
            $user->canManage('galeri') ? 'gallery' : null,
        ]));
    }
}
