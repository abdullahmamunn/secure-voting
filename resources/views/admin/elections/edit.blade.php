@extends('layouts.app')

@section('content')

  <div class="container">
    <h1>Edit Election</h1>

    <form action="{{ route('elections.update', $election->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Election Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $election->name }}" required>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $election->start_date }}" required>
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $election->end_date }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Election</button>
    </form>
  </div>

@endsection

