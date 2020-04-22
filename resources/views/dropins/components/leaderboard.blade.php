@php
    $preview = (isset($preview) && $preview === true) ? "preview" : "";
    $route = Route::currentRouteName();
    $referralCounts = [];
    $wizards = [
      'Gandalf',
      'Merlin',
      'Dumbledore',
      'Hermione Granger',
      'Sabrina',
      'Prospero',
      'Saruman',
      'Voldemort',
      'Elminster',
      'Tim',
      'Harry Dresden',
      'Orwen',
      'Glinda',
      'Morgana Le Fay',
      'Kiki'
    ];
    shuffle($wizards);
    
    $wizard_num = 0;
    while(count($referralCounts) < 10) {
      $tempItem = (object)[
        "referrer" => $wizards[$wizard_num],
        "count" => 1
      ];
      array_push($referralCounts, $tempItem);
        $wizard_num++;
    }
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
      @if (is_array($referrals) && count($referrals) > 0)
      @for ($i = 0; $i < 10; $i++)
        <div class="leaderboard__row" style="display: {{ $i >= $leaderboard->length ? "none;" : "" }}">
          @if ($i < count($referrals))
            <div>{{ $referrals[$i]->referrer }}</div>
            <div>{{ $referrals[$i]->count }}</div>
          @elseif ($route === 'dashboard')
            <div>{{ $referralCounts[$i]->referrer }}</div>
            <div>{{ $referralCounts[$i]->count }}</div>
          @endif
        </div>
      @endfor
      @elseif(
      is_array($referrals) && 
      count($referrals) < 1 && 
      $route === 'dashboard'
      )
        @foreach($referralCounts as $referral)
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
  var route = '<?php echo $route; ?>';
  var wizards = '<?php echo json_encode($referralCounts); ?>';
</script>