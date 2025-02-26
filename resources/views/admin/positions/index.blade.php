@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Positions</h1>

    <a href="{{ route('positions.create') }}" class="btn btn-primary mb-3">Create New Position</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Position Name</th>
                <th>Election</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($positions as $position)
                <tr>
                    <td>{{ $position->id }}</td>
                    <td>{{ $position->name }}</td>
                    <td>{{ $position->election->name }}</td>
                    <td>
                        <!-- You can add edit/delete actions here -->
                        <a href="{{ route('positions.edit', $position->id) }}" class="btn btn-info btn-sm">Edit</a>
                        <a href="#" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
