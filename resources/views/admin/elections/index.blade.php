@extends('layouts.app')

@section('content')
<div class="container">
    <h1>All Elections</h1>

    <a href="{{ route('elections.create') }}" class="btn btn-primary mb-3">Create New Election</a>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($elections as $election)
                <tr>
                    <td>{{ $election->id }}</td>
                    <td>{{ $election->name }}</td>
                    <td>{{ $election->start_date }}</td>
                    <td>{{ $election->end_date }}</td>
                    <td>{{ $election->status }}</td>
                    <td>
                        <!-- You can add edit/delete actions here -->
                        <a href="{{ route('elections.edit', $election->id) }}" class="btn btn-info btn-sm">Edit</a>
                        <a href="{{ route('elections.destroy', $election->id) }}" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
