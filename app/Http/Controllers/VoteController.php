<?php

namespace App\Http\Controllers;

use App\Services\VoteService;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    protected $voteService;

    public function __construct(VoteService $voteService)
    {
        $this->voteService = $voteService;
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'candidate_id' => 'required|exists:candidates,id',
            ]);

            $result = $this->voteService->vote($validated['user_id'], $validated['candidate_id']);
            return response()->json($result, 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function index()
    {
        $votes = $this->voteService->getAllVotes();
        return response()->json($votes);
    }

    public function showByCandidate($candidateId)
    {
        $votes = $this->voteService->getVotesByCandidate($candidateId);
        return response()->json($votes);
    }
}