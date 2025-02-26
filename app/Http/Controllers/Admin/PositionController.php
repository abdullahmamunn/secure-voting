<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\Election;

class PositionController extends Controller
{
        public function index()
        {
            $positions = Position::with('election')->get();  // Fetch positions with their elections
            return view('admin.positions.index', compact('positions'));
        }
        // Show form to create new position
        public function create()
        {
            $elections = Election::all();  // Retrieve all elections
            return view('admin.positions.create', compact('elections'));
        }
    
        // Store new position in database
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'election_id' => 'required|exists:elections,id',
            ]);
    
            Position::create([
                'name' => $request->name,
                'election_id' => $request->election_id,
            ]);
    
            return redirect()->route('positions.index')->with('success', 'Position created successfully');
        }

        public function edit($id)
        {
            $position = Position::findOrFail($id);
            $elections = Election::all();  // Get elections for dropdown
            return view('admin.positions.edit', compact('position', 'elections'));
        }

        public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required',
                'election_id' => 'required',
            ]);

            $position = Position::findOrFail($id);
            $position->update($request->all());

            return redirect()->route('positions.index')->with('success', 'Position updated successfully.');
        }

        public function destroy($id)
        {
            $position = Position::findOrFail($id);
            $position->delete();

            return redirect()->route('positions.index')->with('success', 'Position deleted successfully.');
        }



}
