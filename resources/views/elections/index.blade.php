@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Available Elections</h1>

    @foreach($elections as $election)
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">{{ $election->name }}</h5>
                <p class="card-text">
                    Start Date: {{ \Carbon\Carbon::parse($election->start_date)->format('d M Y') }} <br>
                    End Date: {{ \Carbon\Carbon::parse($election->end_date)->format('d M Y') }} <br>
                    Status: <strong>{{ $election->status }}</strong>
                </p>
                <a href="{{ route('user-elections.show', $election->id) }}" class="btn btn-primary">View Election</a>

                @if($election->status == 'Election is Finished')
                    <a href="{{ route('admin.elections.votes', $election->id) }}" class="btn btn-primary">View Result</a>
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection
