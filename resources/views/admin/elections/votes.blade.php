@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Election Results</h1>

    <h3>Total Votes</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Candidate Name</th>
                <th>Position</th>
                <th>Total Votes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($totalVotes as $vote)
                <tr>
                    <td>{{ $vote->candidate_name }}</td>
                    <td>{{ $vote->position_name }}</td>
                    <td>{{ $vote->total_votes }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Winners by Position</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Position</th>
                <th>Winner</th>
                <th>Total Votes</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sortedPositionWinners as $position => $winner)
                <tr>
                    <td>{{ $position }}</td>
                    <td>{{ $winner['candidate_name'] }}</td>
                    <td>{{ $winner['total_votes'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
