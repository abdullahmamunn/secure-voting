<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Vote;
use Illuminate\Http\Request;

class UserVoteController extends Controller
{

    public function store(Request $request, $electionId){

        // dd($request->all(), $electionId);
    // Validate the incoming request
    // $request->validate([
    //     'candidates.*' => 'array',
    //     'candidates.*.*' => 'exists:candidates,id', // Ensure candidate IDs exist
    // ]);

    $request->validate([
        'candidates.*' => 'exists:candidates,id', // Ensure candidate IDs exist
    ]);




    foreach ($request->candidates as $positionId => $candidateId) {
        // Store each vote in your votes table
        Vote::create([
            'election_id' => $electionId,
            'position_id' => $positionId,
            'candidate_id' => $candidateId,
            'voter_id' => auth()->user()->id, // Assuming you're using Laravel's Auth
            'voted_at' => now(),
        ]);
    }

    return redirect()->back()->with('success', 'Your votes have been successfully submitted!');
}



    public function stores(Request $request, Election $election)
    {
        // Validate that the user hasn't already voted
        if (Vote::where('voter_id', auth()->id())->where('election_id', $election->id)->exists()) {
            return redirect()->back()->with('error', 'You have already voted in this election.');
        }

        // Record the vote
        Vote::create([
            'voter_id' => auth()->id(),
            'candidate_id' => $request->candidate_id,
            'election_id' => $election->id,
            'voted_at' => now(),
        ]);

        return redirect()->route('elections.index')->with('success', 'Your vote has been cast successfully.');
    }
}
