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
        $candidates = $this->candidateService->getAllCandidates();
        return response()->json($candidates);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
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
        return response()->json($candidate, 201);
    }

    public function show($id)
    {
        $candidate = $this->candidateService->findCandidate($id);
        return response()->json($candidate);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
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
        return response()->json($candidate);
    }

    public function destroy($id)
    {
        $result = $this->candidateService->deleteCandidate($id);
        return response()->json($result);
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
                'message' => 'Image uploaded successfully',
                'candidate' => $candidate
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }
}