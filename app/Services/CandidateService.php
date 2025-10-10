<?php

namespace App\Services;

use App\Models\Candidate;
use Illuminate\Support\Facades\Storage;

class CandidateService
{
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

        // Untuk Hapus Image (Soon Function)
        if ($candidate->image && Storage::exists($candidate->image)) {
            Storage::delete($candidate->image);
        }

        $candidate->delete();

        return ['message' => 'Candidate deleted successfully'];
    }
}