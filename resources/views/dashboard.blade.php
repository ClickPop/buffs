@extends('layouts.app')

@section('content')
<div class="container dashboard-wrapper">
    <div class="row">
        <div class="col-8">
            <div class="leaderboard-wrapper preview light"> <!-- Set leaderboard theme in class -->
                <h2>My Leaderboard</h2>
                <!-- if no leaderboard, show something else -->
                @empty($leaderboard)
                <p>Looks like you currently don't have a leaderboard, click the button to create yours now!</p>
                <a href="{{ route( 'leaderboard.quickCreate' ) }}" class="btn btn-default">Create Leaderboard</a>
                @endempty
                @isset($leaderboard)
                <div class="leaderboard">
                    <div class="leaderboard__container">
                        <div class="leaderboard__row">
                            <div>Name</div>
                            <div>Views</div>
                        </div>
                        <!-- LOOP -->
                        @if (is_array($referrals) && count($referrals))
                        @foreach($referrals as $referral)
                        <div class="leaderboard__row">
                            <div>{{ $referral->referrer }}</div>
                            <div>{{ $referral->count }}</div>
                        </div>
                        @endforeach
                        @endif
                        <!-- END LOOP -->
                    </div>
                </div>
                @endisset
                
            </div>
        </div>
    </div>
</div>
@endsection
