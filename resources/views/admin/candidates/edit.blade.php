@extends('layouts.app')

@section('content')

<div class="container">
  <h1>Edit Candidate</h1>

  <form action="{{ route('candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="name" class="form-label">Candidate Name</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $candidate->name }}" required>
    </div>

    <div class="mb-3">
        <label for="election_id" class="form-label">Select Election</label>
        <select class="form-control" id="election_id" name="election_id" required>
            <option value="">-- Select Election --</option>
            @foreach($elections as $election)
                <option value="{{ $election->id }}" {{ $candidate->election_id == $election->id ? 'selected' : '' }}>{{ $election->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="position_id" class="form-label">Select Position</label>
        <select class="form-control" id="position_id" name="position_id" required>
            <option value="">-- Select Position --</option>
            @foreach($positions as $position)
                <option value="{{ $position->id }}" {{ $candidate->position_id == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="personal_image" class="form-label">Personal Image</label>
        @if($candidate->personal_image)
            <img src="{{ asset('storage/' . $candidate->personal_image) }}" alt="Personal Image" style="width: 100px;">
        @endif
        <input type="file" class="form-control" id="personal_image" name="personal_image">
    </div>

    <div class="mb-3">
        <label for="nomination_image" class="form-label">Nomination Image</label>
        @if($candidate->nomination_image)
            <img src="{{ asset('storage/' . $candidate->nomination_image) }}" alt="Nomination Image" style="width: 100px;">
        @endif
        <input type="file" class="form-control" id="nomination_image" name="nomination_image">
    </div>

    <button type="submit" class="btn btn-success">Update Candidate</button>
</form>
</div>

<script>
    document.getElementById('election_id').addEventListener('change', function() {
        let electionId = this.value;
        let positionDropdown = document.getElementById('position_id');

        // Clear the position dropdown
        positionDropdown.innerHTML = '<option value="">-- Select Position --</option>';

        if (electionId) {
            // Make an AJAX request to get positions for the selected election
            fetch(`/admin/elections/${electionId}/positions`)
                .then(response => response.json())
                .then(data => {
                    data.positions.forEach(position => {
                        let option = document.createElement('option');
                        option.value = position.id;
                        option.textContent = position.name;
                        positionDropdown.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching positions:', error));
        }
    });
</script>
@endsection
