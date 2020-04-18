@extends('layouts.app')

@section('content')
<div class="container dashboard-wrapper">
    <div class="row">
        <div class="col-8">
            <h2>My Leaderboard</h2>
            <!-- if no leaderboard, show something else -->
            @empty($leaderboard)
            <h4>Looks like you currently don't have a leaderboard, click the button to create yours now!</h4>
            <a href="{{ route( 'leaderboards.quickStart' ) }}" class="btn btn-primary">Create Leaderboard</a>
            @endempty
            @isset($leaderboard)
                @include('dropins.components.leaderboard', ['preview' => true])
                @include('dropins.components.forms.leaderboard-settings', ['leaderboard' => $leaderboard])
            @endisset
        </div>
        <div class="col-8">
            <h2 class="my-3">My Chatbot</h2>
            @if (isset($chatbot))
            <div class="d-flex flex-column justify-content-center">
                @include('dropins.components.chatbot', ['chatbot' => $chatbot])
            </div>
            @else
                @empty($chatbot)
                    <h4>Looks like you don't have a chatbot setup. Click the button to set one up!</h4>
                    <a href="{{ route( 'chatbot.quickStart' ) }}" class="btn btn-primary">Create Chatbot</a>
                @endempty
            @endif
    </div>
</div>
@endsection
