@php
  $route = Route::currentRouteName();
  $wizardReferrals = [];
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
  while(count($wizardReferrals) < 10) {
    $tempItem = (object)[
      "referrer" => $wizards[$wizard_num],
      "count" => 1
    ];
    array_push($wizardReferrals, $tempItem);
      $wizard_num++;
  }

  if (isset($preview) && $preview === true) {
    $previewClass = "preview";
  } else { $preview = false; }
@endphp
<div class="leaderboard-wrapper theme-{{ $leaderboard->theme }} {{ $previewClass ?? '' }}"> <!-- Set leaderboard theme in class -->
  @isset($leaderboard)
  <div class="leaderboard">
    <div class="leaderboard__container">
      <div class="leaderboard__row">
        <div>Name</div>
        <div>Views</div>
      </div>
      <!-- LOOP -->
      @if (is_array($referrals))
      @for ($i = 0; $i < 10; $i++)
        <div class="leaderboard__row" style="{{ ($route === 'dashboard' && $i >= $leaderboard->length) || ($route !== 'dashboard' && ($i >= $leaderboard->length || $i >= count($referrals))) ? "display: none;" : "" }}">
          @if ($i < count($referrals))
            <div>{{ $referrals[$i]->referrer }}</div>
            <div>{{ $referrals[$i]->count }}</div>
          @elseif ($route === 'dashboard' && $preview)
            <div>{{ $wizardReferrals[$i]->referrer }}</div>
            <div>{{ $wizardReferrals[$i]->count }}</div>
          @else
            <div></div>
            <div></div>
          @endif
        </div>
      @endfor
      @endif
      <!-- END LOOP -->
    </div>
  </div>
  @endisset
</div>
<script type="text/javascript">
  var channel = '<?php echo $user->username; ?>';
  var route = '<?php echo $route; ?>';
  var wizards = '<?php echo json_encode($wizardReferrals); ?>';
</script>