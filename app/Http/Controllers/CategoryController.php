<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $activeOnly = $request->get('active_only', false);
            $categories = $this->categoryService->getAllCategories($activeOnly);
            
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data kategori: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function active(): JsonResponse
    {
        try {
            $categories = $this->categoryService->getActiveCategories();
            
            if ($categories->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada kategori aktif',
                    'data' => null
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data kategori aktif: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'slug' => 'nullable|string|max:255|unique:categories,slug',
                'description' => 'nullable|string',
                'order' => 'nullable|integer|min:0',
                'is_active' => 'boolean',
            ]);

            $category = $this->categoryService->createCategory($validated);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dibuat.',
                'data' => $category
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat kategori: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $category = $this->categoryService->findCategory($id);
            
            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail kategori: ' . $e->getMessage(),
                'data' => null
            ], 404);
        }
    }

    public function showBySlug($slug): JsonResponse
    {
        try {
            $category = $this->categoryService->findCategoryBySlug($slug);
            
            return response()->json([
                'success' => true,
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail kategori: ' . $e->getMessage(),
                'data' => null
            ], 404);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255|unique:categories,name,' . $id,
                'slug' => 'sometimes|string|max:255|unique:categories,slug,' . $id,
                'description' => 'nullable|string',
                'order' => 'sometimes|integer|min:0',
                'is_active' => 'sometimes|boolean',
            ]);

            $category = $this->categoryService->updateCategory($id, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diperbarui.',
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kategori: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $result = $this->categoryService->deleteCategory($id);

            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:categories,id',
            ]);

            $result = $this->categoryService->bulkDeleteCategories($validated['ids']);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateOrder(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'order' => 'required|array',
                'order.*.id' => 'required|integer|exists:categories,id',
                'order.*.order' => 'required|integer|min:0',
            ]);

            $result = $this->categoryService->updateCategoryOrder($validated['order']);

            return response()->json([
                'success' => true,
                'message' => $result['message']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui urutan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($id): JsonResponse
    {
        try {
            $category = $this->categoryService->toggleCategoryStatus($id);
            $status = $category->is_active ? 'diaktifkan' : 'dinonaktifkan';

            return response()->json([
                'success' => true,
                'message' => "Kategori berhasil {$status}.",
                'data' => $category
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    public function withCandidates(): JsonResponse
    {
        try {
            $categories = $this->categoryService->getCategoriesWithCandidates();
            
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data kategori dengan kandidat: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}