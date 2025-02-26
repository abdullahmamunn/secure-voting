<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElectionController extends Controller
{
    // Display list of elections
    public function index()
    {
        $elections = Election::all();
        return view('admin.elections.index', compact('elections'));
    }

    // Show form to create new election
    public function create()
    {
        return view('admin.elections.create');
    }

    // Store new election in database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Election::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => 'future',  // Default to future
        ]);

        return redirect()->route('elections.index')->with('success', 'Election created successfully');
    }

    public function edit($id){

        $election = Election::findOrFail($id);
        return view('admin.elections.edit', compact('election'));
    }

    public function update(Request $request, $id) {

        $request->validate([
            'name' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        $election = Election::findOrFail($id);
        $election->update($request->all());

        return redirect()->route('elections.index')->with('success', 'Election updated successfully.');
    }

    public function destroy($id) {
    $election = Election::findOrFail($id);
    $election->delete();

    return redirect()->route('elections.index')->with('success', 'Election deleted successfully.');
}




    public function getPositions($electionId){
        // Retrieve positions that belong to the selected election
        $positions = Position::where('election_id', $electionId)->get();

        // Return the positions as a JSON response
        return response()->json([
            'positions' => $positions
        ]);
    }

    public function calculateVotesOld($election_id) {
        $results = DB::table('candidates')
            ->leftJoin('votes', 'candidates.id', '=', 'votes.candidate_id')
            ->join('positions', 'candidates.position_id', '=', 'positions.id')
            ->select(
                'candidates.name as candidate_name', 
                'positions.name as position_name', 
                DB::raw('COUNT(votes.id) as total_votes')
            )
            ->where('candidates.election_id', $election_id) // Ensure to filter candidates based on the election
            ->groupBy('candidates.id', 'positions.name') // Group by candidate ID and position name
            ->orderBy('positions.name')
            ->orderBy('total_votes', 'DESC')
            ->get();
    
        return view('admin.elections.votes', compact('results'));
    }

    public function calculateVotesOld2($election_id) {
        // Step 1: Get total votes for each candidate based on election and position
        $totalVotes = DB::table('candidates')
            ->leftJoin('votes', 'candidates.id', '=', 'votes.candidate_id')
            ->join('positions', 'candidates.position_id', '=', 'positions.id')
            ->select(
                'candidates.id as candidate_id',
                'candidates.name as candidate_name', 
                'positions.name as position_name', 
                DB::raw('COUNT(votes.id) as total_votes')
            )
            ->where('candidates.election_id', $election_id) // Filter by election
            ->groupBy('candidates.id', 'positions.name') // Group by candidate and position
            ->orderBy('positions.name')
            ->orderBy('total_votes', 'DESC')
            ->get();
    
        // Step 2: Get winners by position, including those with 0 votes
        $winners = DB::table('positions')
            ->leftJoin('candidates', 'positions.id', '=', 'candidates.position_id')
            ->leftJoin('votes', 'candidates.id', '=', 'votes.candidate_id')
            ->select(
                'positions.name as position_name',
                'candidates.name as candidate_name',
                DB::raw('COUNT(votes.id) as total_votes')
            )
            ->where('candidates.election_id', $election_id) // Filter by election
            ->groupBy('positions.id', 'candidates.id') // Group by position and candidate
            ->orderBy('positions.name')
            ->get();
    
        // Step 3: Prepare the winner for each position
        $positionWinners = [];
    
        foreach ($winners as $winner) {
            if (!isset($positionWinners[$winner->position_name]) || $winner->total_votes > $positionWinners[$winner->position_name]['total_votes']) {
                $positionWinners[$winner->position_name] = [
                    'candidate_name' => $winner->candidate_name,
                    'total_votes' => $winner->total_votes,
                ];
            }
        }
    
        return view('admin.elections.votes', compact('totalVotes', 'positionWinners'));
    }

    public function calculateVotes($election_id) {
        // Step 1: Get total votes for each candidate based on election and position
        $totalVotes = DB::table('candidates')
            ->leftJoin('votes', 'candidates.id', '=', 'votes.candidate_id')
            ->join('positions', 'candidates.position_id', '=', 'positions.id')
            ->select(
                'candidates.id as candidate_id',
                'candidates.name as candidate_name', 
                'positions.name as position_name', 
                DB::raw('COUNT(votes.id) as total_votes')
            )
            ->where('candidates.election_id', $election_id) // Filter by election
            ->groupBy('candidates.id', 'positions.name') // Group by candidate and position
            ->orderBy('positions.name')
            ->orderBy('total_votes', 'DESC')
            ->get();
    
        // Step 2: Get winners by position, including those with 0 votes
        $winners = DB::table('positions')
            ->leftJoin('candidates', 'positions.id', '=', 'candidates.position_id')
            ->leftJoin('votes', 'candidates.id', '=', 'votes.candidate_id')
            ->select(
                'positions.name as position_name',
                'candidates.name as candidate_name',
                DB::raw('COUNT(votes.id) as total_votes')
            )
            ->where('candidates.election_id', $election_id) // Filter by election
            ->groupBy('positions.id', 'candidates.id') // Group by position and candidate
            ->orderBy('positions.name')
            ->get();
    
        // Step 3: Prepare the winner for each position
        $positionWinners = [];
        foreach ($winners as $winner) {
            if (!isset($positionWinners[$winner->position_name]) || $winner->total_votes > $positionWinners[$winner->position_name]['total_votes']) {
                $positionWinners[$winner->position_name] = [
                    'candidate_name' => $winner->candidate_name,
                    'total_votes' => $winner->total_votes,
                ];
            }
        }
    
        // Step 4: Define the desired order of positions
        $positionOrder =  DB::table('positions')
        ->where('election_id', $election_id)
        ->pluck('name') // Fetch position names
        ->toArray();
    
        // Step 5: Sort position winners based on the defined order
        $sortedPositionWinners = [];
        foreach ($positionOrder as $position) {
            if (isset($positionWinners[$position])) {
                $sortedPositionWinners[$position] = $positionWinners[$position];
            } else {
                $sortedPositionWinners[$position] = [
                    'candidate_name' => 'No candidates',
                    'total_votes' => 0,
                ];
            }
        }
    
        return view('admin.elections.votes', compact('totalVotes', 'sortedPositionWinners'));
    }
    
    
    
    

}
