@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Create Election</h1>

    <form action="{{ route('elections.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Election Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required>
        </div>
        <div class="mb-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required>
        </div>
        <button type="submit" class="btn btn-primary">Create Election</button>
    </form>
</div>
@endsection
