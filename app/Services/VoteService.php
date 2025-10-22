<?php

namespace App\Services;

use App\Models\User;
use App\Models\Vote;
use App\Models\Candidate;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VoteService
{
    public function vote(int $userId, int $candidateId)
    {
        $user = User::findOrFail($userId);

        if ($user->has_voted) {
            throw ValidationException::withMessages([
                'vote' => 'You have already voted.'
            ]);
        }

        DB::transaction(function () use ($user, $candidateId) {
            Vote::create([
                'user_id' => $user->id,
                'candidate_id' => $candidateId,
            ]);

            Candidate::where('id', $candidateId)->increment('total_votes');
            $user->update(['has_voted' => true]);
        });

        return [
            'message' => 'Vote submitted successfully.',
            'candidate_id' => $candidateId,
        ];
    }

    public function getAllVotes()
    {
        return Vote::with(['user', 'candidate'])->get();
    }

    public function getVotesByCandidate(int $candidateId)
    {
        return Vote::where('candidate_id', $candidateId)->with('user')->get();
    }

    public function hasUserVoted(int $userId)
    {
        $user = User::find($userId);
        return $user ? $user->has_voted : false;
    }
}