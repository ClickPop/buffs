@extends('layouts.app')

@section('content')
<div class="container-fluid dashboard-wrapper">
    <div class="row">
        <div class="col-12 mb-4">
            <h2 class="mb-3">My Leaderboard</h2>
            <!-- if no leaderboard, show something else -->
            @empty($leaderboard)
            <p>Ready to get started growing your audience? Just click below to create your leaderboard.</p>
            <a href="{{ route( 'leaderboards.quickStart' ) }}" class="btn btn-primary">Create Leaderboard</a>
            @endempty
            @isset($leaderboard)
                @include('dropins.components.forms.leaderboard-settings', ['leaderboard' => $leaderboard])
            @endisset
        </div>
        <div class="col-12">
            <h2 class="my-3">Chatbot</h2>
            <p>
              The chatbot bot lets your viewers get their unique referral link by typing <strong>!buffs</strong>.
            </p>
            @if (isset($chatbot))
                @include('dropins.components.chatbot', ['chatbot' => $chatbot])
            @else
                @empty($chatbot)
                    <h4>Looks like you don't have a chatbot setup. Click the button to set one up!</h4>
                    <a href="{{ route( 'chatbot.quickStart' ) }}" class="btn btn-primary">Create Chatbot</a>
                @endempty
            @endif
        </div>
    </div>
</div>
@endsection
