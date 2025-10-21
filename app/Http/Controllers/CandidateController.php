<?php

namespace App\Http\Controllers;

use App\Services\CandidateService;
use App\Services\UploadImageService;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    protected $candidateService;
    protected $uploadImageService;

    public function __construct(CandidateService $candidateService, UploadImageService $uploadImageService)
    {
        $this->candidateService = $candidateService;
        $this->uploadImageService = $uploadImageService;
    }

    public function index()
    {
        try {
            $candidates = $this->candidateService->getAllCandidates();
            return response()->json([
                'error' => false,
                'message' => 'Berhasil menampilkan semua kandidat.',
                'data' => $candidates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal memuat data kandidat: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'desc' => 'required|string',
                'class' => 'required|string|max:100',
                'vision' => 'required|string',
                'mission' => 'required|string',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $this->uploadImageService->upload($request->file('image'));
                $validated['image'] = $imagePath;
            }

            $candidate = $this->candidateService->createCandidate($validated);

            return response()->json([
                'error' => false,
                'message' => 'Kandidat berhasil ditambahkan.',
                'data' => $candidate
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal menambahkan kandidat: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $candidate = $this->candidateService->findCandidate($id);
            return response()->json([
                'error' => false,
                'message' => 'Detail kandidat berhasil diambil.',
                'data' => $candidate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal menampilkan data kandidat: ' . $e->getMessage(),
                'data' => null
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'desc' => 'sometimes|string',
                'class' => 'sometimes|string|max:100',
                'vision' => 'sometimes|string',
                'mission' => 'sometimes|string',
                'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $this->uploadImageService->upload($request->file('image'));
                $validated['image'] = $imagePath;

                $candidate = $this->candidateService->findCandidate($id);
                if ($candidate->image) {
                    $this->uploadImageService->delete($candidate->image);
                }
            }

            $candidate = $this->candidateService->updateCandidate($id, $validated);

            return response()->json([
                'error' => false,
                'message' => 'Data kandidat berhasil diperbarui.',
                'data' => $candidate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal memperbarui kandidat: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->candidateService->deleteCandidate($id);

            return response()->json([
                'error' => false,
                'message' => 'Kandidat berhasil dihapus.',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal menghapus kandidat: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function uploadImage(Request $request, $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        try {
            $imagePath = $this->uploadImageService->upload($request->file('image'));

            $candidate = $this->candidateService->findCandidate($id);
            if ($candidate->image) {
                $this->uploadImageService->delete($candidate->image);
            }

            $candidate = $this->candidateService->updateCandidate($id, ['image' => $imagePath]);

            return response()->json([
                'error' => false,
                'message' => 'Gambar kandidat berhasil diperbarui.',
                'data' => $candidate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Gagal mengunggah gambar: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }
}