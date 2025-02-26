<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Position;
use App\Models\Election;

class CandidateController extends Controller
{

    public function index()
    {
        $candidates = Candidate::with(['position', 'election'])->get();  // Fetch candidates with their positions and elections
        return view('admin.candidates.index', compact('candidates'));
    }
    // Show form to create new candidate
    public function create()
    {
        $elections = Election::all();  // Retrieve all elections
        $positions = Position::all();  // Retrieve all positions
        return view('admin.candidates.create', compact('elections', 'positions'));
    }

    // Store new candidate in database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'election_id' => 'required|exists:elections,id',
            'personal_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image
            'nomination_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image
        ]);

        $personalImagePath = $request->file('personal_image')->store('candidates/personal_images', 'public');
        $nominationImagePath = $request->file('nomination_image')->store('candidates/nomination_images', 'public');

        Candidate::create([
            'name' => $request->name,
            'position_id' => $request->position_id,
            'election_id' => $request->election_id,
            'personal_image' => $personalImagePath,
            'nomination_image' => $nominationImagePath,
        ]);

        return redirect()->route('candidates.index')->with('success', 'Candidate created successfully');
    }

    public function edit($id)
    {
        $candidate = Candidate::findOrFail($id);
        $positions = Position::all();  // Get positions for dropdown
        $elections = Election::all();  // Get elections for dropdown
        return view('admin.candidates.edit', compact('candidate', 'positions', 'elections'));
    }

    public function update(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'election_id' => 'required',
            'position_id' => 'required',
            'personal_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'nomination_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // dd($request->all(), $id);
    
        // Update candidate information
        $candidate->name = $request->name;
        $candidate->election_id = $request->election_id;
        $candidate->position_id = $request->position_id;
    
        // Handle personal image update
        if ($request->hasFile('personal_image')) {
            $personalImagePath = $request->file('personal_image')->store('personal_images', 'public');
            $candidate->personal_image = $personalImagePath;
        }
    
        // Handle nomination image update
        if ($request->hasFile('nomination_image')) {
            $nominationImagePath = $request->file('nomination_image')->store('nomination_images', 'public');
            $candidate->nomination_image = $nominationImagePath;
        }
    
        $candidate->save();
    
        return redirect()->route('candidates.index')->with('success', 'Candidate updated successfully!');
    }

    public function destroy($id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->delete();

        return redirect()->route('candidates.index')->with('success', 'Candidate deleted successfully.');
    }



}
