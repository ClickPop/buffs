@extends('layouts.app')

@section('content')
<div class="container dashboard-wrapper">
    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="my-0">My Streams</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            Twitch
                        </li>
                        <ul>
                            <li>
                            <a href="/twitch/leaderboard/{{ Auth::user()->username }}/settings">Leaderboard Settings</a>
                            </li>
                            <li>
                            <a href="/twitch/leaderboard/{{ Auth::user()->username }}">Leaderboard</a>
                            </li>
                        </ul>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="my-0">Bufflets</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action" 
                        href="{{route('leaderboards.index')}}">
                        Leaderboards
                    </a>
                    <div class="list-group-item">
                        Stream Pets
                    </div>
                </div>
            </div>
        </div>

        <div class="col-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="my-0">Administration</h5>
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action" 
                        href="{{route('leaderboards.admin')}}">
                        Leaderboards
                    </a>
                    <a class="list-group-item list-group-item-action" 
                        href="">
                        Leaderboard Referrals
                    </a>
                    <div class="list-group-item">
                        Stream Pets
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
