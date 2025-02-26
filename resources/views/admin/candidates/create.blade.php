@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Create Candidate</h1>

    <form action="{{ route('candidates.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
    
        <!-- Candidate Name -->
        <div class="mb-3">
            <label for="name" class="form-label">Candidate Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            @if ($errors->has('name'))
                <div class="text-danger">{{ $errors->first('name') }}</div>
            @endif
        </div>
    
        <!-- Select Election -->
        <div class="mb-3">
            <label for="election_id" class="form-label">Select Election</label>
            <select class="form-control" id="election_id" name="election_id" required>
                <option value="">-- Select Election --</option>
                @foreach($elections as $election)
                    <option value="{{ $election->id }}" {{ old('election_id') == $election->id ? 'selected' : '' }}>
                        {{ $election->name }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('election_id'))
                <div class="text-danger">{{ $errors->first('election_id') }}</div>
            @endif
        </div>
    
        <!-- Select Position -->
        <div class="mb-3">
            <label for="position_id" class="form-label">Select Position</label>
            <select class="form-control" id="position_id" name="position_id" required>
                <option value="">-- Select Position --</option>
                <!-- Positions will be dynamically loaded here -->
            </select>
            @if ($errors->has('position_id'))
                <div class="text-danger">{{ $errors->first('position_id') }}</div>
            @endif
        </div>
    
        <!-- Personal Image -->
        <div class="mb-3">
            <label for="personal_image" class="form-label">Personal Image</label>
            <input type="file" class="form-control" id="personal_image" name="personal_image" required>
            @if ($errors->has('personal_image'))
                <div class="text-danger">{{ $errors->first('personal_image') }}</div>
            @endif
        </div>
    
        <!-- Nomination Image -->
        <div class="mb-3">
            <label for="nomination_image" class="form-label">Nomination Image</label>
            <input type="file" class="form-control" id="nomination_image" name="nomination_image" required>
            @if ($errors->has('nomination_image'))
                <div class="text-danger">{{ $errors->first('nomination_image') }}</div>
            @endif
        </div>
    
        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Create Candidate</button>
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


