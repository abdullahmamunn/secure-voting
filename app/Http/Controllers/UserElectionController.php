<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Position;
use App\Models\Vote;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserElectionController extends Controller
{
    public function index()
    {
        // $elections = Election::orderBy('start_date', 'desc')->get();
        // return view('elections.index', compact('elections'));

        $elections = Election::orderBy('start_date', 'desc')->get();
        return view('elections.index', compact('elections'));
    }

    public function show($electionId){


    $election = Election::findOrFail($electionId);

    // Ensure start_date and end_date are Carbon instances
    $election->start_date = Carbon::parse($election->start_date);
    $election->end_date = Carbon::parse($election->end_date);

    $positions = Position::where('election_id', $electionId)
    ->with(['candidates' => function ($query) use ($electionId) {
        $query->where('election_id', $electionId);
    }])
    ->get();

    // dd($positions);

    $hasVoted = Vote::where('election_id', $electionId)
                    ->where('voter_id', auth()->user()->id)
                    ->exists();

    $currentDate = Carbon::now();
    $isVotingActive = $currentDate->between($election->start_date, $election->end_date);

    // dd($election, $positions, $hasVoted, $isVotingActive);

    return view('elections.show', compact('election', 'positions', 'hasVoted', 'isVotingActive'));
}
}
