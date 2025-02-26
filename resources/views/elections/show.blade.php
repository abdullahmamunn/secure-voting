@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $election->name }}</h1>
    <p>Election Status: <strong>{{ $election->status }}</strong></p>
    <p>Voting Period: {{ $election->start_date->format('d M Y') }} to {{ $election->end_date->format('d M Y') }}</p>

    @if ($hasVoted)
        <div class="alert alert-info">
            You have already voted in this election.
        </div>
        <h3>Candidate Information</h3>
        @foreach($positions as $position)
            <div class="card mb-3">
                <div class="card-header">
                    <h4>{{ $position->name }}</h4>
                </div>
                <div class="card-body">
                    @foreach($position->candidates as $candidate)
                        <p>
                            <img src="{{ asset('storage/' . $candidate->personal_image) }}" alt="{{ $candidate->name }}" style="width: 30px; height: 30px; margin-right: 10px;">
                            {{ $candidate->name }}
                            @if($candidate->nomination_image)
                                <img src="{{ asset('storage/' . $candidate->nomination_image) }}" alt="{{ $candidate->name }} Nomination" style="width: 30px; height: 30px; margin-left: 10px;">
                            @endif
                        </p>
                    @endforeach
                </div>
            </div>
        @endforeach
    @else
        @if ($isVotingActive)
            <form action="{{ route('votes.store', $election->id) }}" method="POST">
                @csrf

                @foreach($positions as $position)
                    <div class="card mb-3">
                        <div class="card-header">
                            <h4>{{ $position->name }}</h4>
                        </div>
                        <div class="card-body">
                            @foreach($position->candidates as $candidate)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="candidates[{{ $position->id }}]" value="{{ $candidate->id }}" id="candidate{{ $candidate->id }}">
                                    <label class="form-check-label" for="candidate{{ $candidate->id }}">
                                        <img src="{{ asset('storage/' . $candidate->personal_image) }}" alt="{{ $candidate->name }}" style="width: 30px; height: 30px; margin-right: 10px;">
                                        {{ $candidate->name }}
                                        @if($candidate->nomination_image)
                                            <img src="{{ asset('storage/' . $candidate->nomination_image) }}" alt="{{ $candidate->name }} Nomination" style="width: 30px; height: 30px; margin-left: 10px;">
                                        @endif
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <button type="submit" class="btn btn-success mt-2">Submit Votes</button>
            </form>
        @else
            <div class="alert alert-warning">
                Voting is not active at the moment. You can vote between {{ $election->start_date->format('d M Y') }} and {{ $election->end_date->format('d M Y') }}.
            </div>
            <h3>Candidate Information</h3>
            @foreach($positions as $position)
                <div class="card mb-3">
                    <div class="card-header">
                        <h4>{{ $position->name }}</h4>
                    </div>
                    <div class="card-body">
                        @foreach($position->candidates as $candidate)
                            <p>
                                <img src="{{ asset('storage/' . $candidate->personal_image) }}" alt="{{ $candidate->name }}" style="width: 30px; height: 30px; margin-right: 10px;">
                                {{ $candidate->name }}
                                @if($candidate->nomination_image)
                                    <img src="{{ asset('storage/' . $candidate->nomination_image) }}" alt="{{ $candidate->name }} Nomination" style="width: 30px; height: 30px; margin-left: 10px;">
                                @endif
                            </p>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    @endif

</div>
@endsection
