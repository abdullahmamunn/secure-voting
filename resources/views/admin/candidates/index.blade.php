@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Candidates</h1>

    <a href="{{ route('candidates.create') }}" class="btn btn-primary mb-3">Create New Candidate</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Candidate Name</th>
                <th>Position</th>
                <th>Election</th>
                <th>Personal Image</th>
                <th>Nomination Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($candidates as $candidate)
                <tr>
                    <td>{{ $candidate->id }}</td>
                    <td>{{ $candidate->name }}</td>
                    <td>{{ $candidate->position->name }}</td>
                    <td>{{ $candidate->election->name }}</td>

                    <!-- Display personal image -->
                    <td>
                        @if($candidate->personal_image)
                            <img src="{{ asset('storage/' . $candidate->personal_image) }}" alt="{{ $candidate->name }}" width="100">
                        @else
                            <p>No Image</p>
                        @endif
                    </td>

                    <!-- Display nomination image -->
                    <td>
                        @if($candidate->nomination_image)
                            <img src="{{ asset('storage/' . $candidate->nomination_image) }}" alt="{{ $candidate->name }}" width="100">
                        @else
                            <p>No Image</p>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('candidates.edit', $candidate->id) }}" class="btn btn-info btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
