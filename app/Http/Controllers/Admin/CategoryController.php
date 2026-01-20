<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Http\Requests\Admin\CategoryRequest;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $categories = $this->categoryService->getAllCategories(5, $request->search);
        return view('admin.categories.index', compact('categories'));
    }

    public function export(Request $request)
    {
        $categories = $this->categoryService->exportCategories($request->search);
        return Excel::download(new \App\Exports\CategoriesExport($categories), 'data-kategori-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $this->categoryService->createCategory($request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function edit(Kategori $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, Kategori $category)
    {
        $this->categoryService->updateCategory($category, $request->validated());

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $category)
    {
        $result = $this->categoryService->deleteCategory($category);

        if ($result === true) {
            return redirect()->route('admin.categories.index')
                ->with('success', 'Kategori berhasil dihapus.');
        }

        return back()->with('error', $result);
    }
}
