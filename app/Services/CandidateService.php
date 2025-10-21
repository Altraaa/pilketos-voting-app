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
        return Candidate::all();
    }

    public function createCandidate(array $data)
    {
        return Candidate::create($data);
    }

    public function findCandidate(int $id)
    {
        return Candidate::findOrFail($id);
    }

    public function updateCandidate(int $id, array $data)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->update($data);
        return $candidate;
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
        
        return $candidate;
    }
}