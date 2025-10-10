<?php

namespace App\Http\Controllers;

use App\Services\CandidateService;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    protected $candidateService;

    public function __construct(CandidateService $candidateService)
    {
        $this->candidateService = $candidateService;
    }

    public function index()
    {
        $candidates = $this->candidateService->getAllCandidates();
        return response()->json($candidates);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'class' => 'required|string',
            'vision' => 'required|string',
            'mission' => 'required|string',
            'image' => 'nullable|string',
        ]);

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
            'name' => 'sometimes|string',
            'class' => 'sometimes|string',
            'vision' => 'sometimes|string',
            'mission' => 'sometimes|string',
            'image' => 'nullable|string',
        ]);

        $candidate = $this->candidateService->updateCandidate($id, $validated);
        return response()->json($candidate);
    }

    public function destroy($id)
    {
        $result = $this->candidateService->deleteCandidate($id);
        return response()->json($result);
    }
}