@php
    $preview = (isset($preview) && $preview === true) ? "preview" : "";
@endphp
<div class="leaderboard-wrapper leaderboard-theme_{{ $theme }} {{ $preview }}"> <!-- Set leaderboard theme in class -->
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