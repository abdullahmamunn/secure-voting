@extends('layouts.app')

@section('content')

<div class="container">
  <h1>Edit Position</h1>

  <form action="{{ route('positions.update', $position->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
          <label for="name" class="form-label">Position Name</label>
          <input type="text" class="form-control" id="name" name="name" value="{{ $position->name }}" required>
      </div>

      <div class="mb-3">
          <label for="election_id" class="form-label">Select Election</label>
          <select class="form-control" id="election_id" name="election_id" required>
              @foreach($elections as $election)
                  <option value="{{ $election->id }}" {{ $position->election_id == $election->id ? 'selected' : '' }}>
                      {{ $election->name }}
                  </option>
              @endforeach
          </select>
      </div>

      <button type="submit" class="btn btn-primary">Update Position</button>
  </form>
</div>


@endsection
