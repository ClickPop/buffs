@extends('layouts.app')

@section('content')
<div class="container dashboard-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h3>Leaderboards</h3></div>
                <table class="table table-responsive-md table-hover table-sm">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">User Email</th>
                            <th scope="col">Platform</th>
                            <th scope="col">Channel Name</th>
                            <th scope="col">Leaderboard Name</th>
                            <th scope="col">Referrals</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($leaderboards as $leaderboard)
                        <tr>
                            <th scope="col">{{ $leaderboard->id }}</th>
                            <td>{{ $leaderboard->stream->user->email }}</td>
                            <td>{{ $leaderboard->stream->platform->description }}</td>
                            <td>{{ $leaderboard->stream->channel_name }}</td>
                            <td>{{ $leaderboard->name }}</td>
                            <td>{{ $leaderboard->referrals->count() }}</td>
                        </tr>   
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection