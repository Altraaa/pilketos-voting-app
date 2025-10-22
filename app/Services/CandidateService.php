<?php

namespace App\Services;

use App\Models\Candidate;
use Illuminate\Support\Facades\Storage;

class CandidateService
{
    protected $uploadImageService;

    public function __construct(UploadImageService $uploadImageService)
    {
        $this->uploadImageService = $uploadImageService;
    }

    public function getAllCandidates()
    {
        return Candidate::with('category')->get();
    }

    public function getCandidatesByCategory(int $categoryId)
    {
        return Candidate::with('category')
            ->where('category_id', $categoryId)
            ->get();
    }

    public function getCandidatesWithActiveCategories()
    {
        return Candidate::with(['category' => function($query) {
            $query->where('is_active', true);
        }])->get();
    }

    public function createCandidate(array $data)
    {
        return Candidate::create($data);
    }

    public function findCandidate(int $id)
    {
        return Candidate::with('category')->findOrFail($id);
    }

    public function updateCandidate(int $id, array $data)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->update($data);
        return $candidate->load('category');
    }

    public function deleteCandidate(int $id)
    {
        $candidate = Candidate::findOrFail($id);

        if ($candidate->image && Storage::disk('public')->exists($candidate->image)) {
            Storage::disk('public')->delete($candidate->image);
        }

        $candidate->delete();

        return ['message' => 'Candidate deleted successfully'];
    }

    public function uploadCandidateImage(int $candidateId, $imageFile)
    {
        $candidate = $this->findCandidate($candidateId);
        
        if ($candidate->image) {
            $this->uploadImageService->delete($candidate->image);
        }

        $imagePath = $this->uploadImageService->upload($imageFile);
        
        $candidate->update(['image' => $imagePath]);
        
        return $candidate->load('category');
    }

    public function updateCandidateVotes(int $candidateId, int $voteCount = 1)
    {
        $candidate = Candidate::findOrFail($candidateId);
        $candidate->increment('total_votes', $voteCount);
        
        return $candidate->load('category');
    }
}