@php
    $preview = (isset($preview) && $preview === true) ? "preview" : "";
@endphp
<div class="leaderboard-wrapper theme-{{ $leaderboard->theme }} {{ $preview }}"> <!-- Set leaderboard theme in class -->
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
            <div class="leaderboard__row" style="display: {{ $loop->index >= $leaderboard->length ? "none;" : "" }}">
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
<script type="text/javascript">
    var channel = '<?php echo $user->username; ?>';
</script>