@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Position</h1>

    <form action="{{ route('positions.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Position Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="election_id" class="form-label">Select Election</label>
            <select class="form-control" id="election_id" name="election_id" required>
                @foreach($elections as $election)
                    <option value="{{ $election->id }}">{{ $election->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Create Position</button>
    </form>
</div>
@endsection
