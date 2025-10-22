<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;

class VoteResultsController extends Controller
{
    public function index()
    {
        return view('pages.vote', [
            'title' => 'Hasil Vote',
        ]);
    }
    
    public function getResultsApi()
    {
        $candidates = Candidate::select('id', 'name', 'class', 'image', 'total_votes', 'desc')
            ->orderBy('total_votes', 'desc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $candidates,
            'total_votes' => $candidates->sum('total_votes')
        ]);
    }
}