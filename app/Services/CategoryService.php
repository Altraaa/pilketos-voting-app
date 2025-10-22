<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryService
{
    public function getAllCategories(bool $activeOnly = false): Collection
    {
        $query = Category::withCount('candidates');
        
        if ($activeOnly) {
            $query->active();
        }
        
        return $query->ordered()->get();
    }

    public function getActiveCategories(): Collection
    {
        return Category::active()
            ->withCount('candidates')
            ->ordered()
            ->get();
    }

    public function findCategory(int $id): Category
    {
        return Category::with('candidates')->findOrFail($id);
    }

    public function findCategoryBySlug(string $slug): Category
    {
        return Category::with('candidates')->where('slug', $slug)->firstOrFail();
    }

    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    public function updateCategory(int $id, array $data): Category
    {
        $category = Category::findOrFail($id);
        $category->update($data);
        
        return $category->fresh();
    }

    public function deleteCategory(int $id): array
    {
        $category = Category::findOrFail($id);
        
        // Cek apakah kategori memiliki kandidat
        if ($category->candidates()->count() > 0) {
            throw new \Exception('Tidak dapat menghapus kategori yang memiliki kandidat.');
        }
        
        $category->delete();
        
        return ['message' => 'Kategori berhasil dihapus.'];
    }

    public function bulkDeleteCategories(array $ids): array
    {
        $categories = Category::whereIn('id', $ids)->get();
        $deletedCount = 0;
        
        foreach ($categories as $category) {
            if ($category->candidates()->count() === 0) {
                $category->delete();
                $deletedCount++;
            }
        }
        
        return [
            'message' => "{$deletedCount} kategori berhasil dihapus.",
            'deleted_count' => $deletedCount,
            'total_requested' => count($ids)
        ];
    }

    public function updateCategoryOrder(array $orderData): array
    {
        foreach ($orderData as $item) {
            Category::where('id', $item['id'])->update(['order' => $item['order']]);
        }
        
        return ['message' => 'Urutan kategori berhasil diperbarui.'];
    }

    public function toggleCategoryStatus(int $id): Category
    {
        $category = Category::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);
        
        return $category->fresh();
    }

    public function getCategoriesWithCandidates(): Collection
    {
        return Category::active()
            ->with(['candidates' => function($query) {
                $query->orderBy('name');
            }])
            ->ordered()
            ->get();
    }
}