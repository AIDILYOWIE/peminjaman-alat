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

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new \App\Imports\KategoriImport, $request->file('file'));
            return redirect()->route('admin.categories.index')
                ->with('success', 'Data kategori berhasil diimpor.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengimpor data: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $header = [['nama']];
        return Excel::download(new class($header) implements \Maatwebsite\Excel\Concerns\FromCollection {
            protected $data;
            public function __construct($data)
            {
                $this->data = collect($data);
            }
            public function collection()
            {
                return $this->data;
            }
        }, 'template-import-kategori.xlsx');
    }
}
