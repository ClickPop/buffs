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

  if (isset($preview) && $preview === true) {
    $previewClass = "preview";
  } else { $preview = false; }
@endphp
<div class="leaderboard-wrapper theme-{{ $leaderboard->theme }} {{ $previewClass ?? '' }}"> <!-- Set leaderboard theme in class -->
  @isset($leaderboard)
  <div class="leaderboard" data-route="{{ $route }}" data-channel="{{$user->username}}" data-wizards='{!! json_encode($wizards) !!}'>
    <div class="leaderboard__container">
      <div class="leaderboard__row">
        <div>Name</div>
        <div>Views</div>
      </div>

      @if (is_array($referrals))
      @for ($i = 0; $i < 10; $i++)
        <div class="leaderboard__row" style="{{ ($i < $leaderboard->length && ($i < count($referrals) || ($i < count($wizards) && !count($referrals) && $preview))) ? "" : "display: none;" }}">
          @if ($i < count($referrals))
            <div>{{ $referrals[$i]->referrer }}</div>
            <div>{{ $referrals[$i]->count }}</div>
          @elseif ($route === 'dashboard' && $preview)
            <div>{{ $wizards[$i] }}</div>
            <div>0</div>
          @else
            <div></div>
            <div></div>
          @endif
        </div>
      @endfor
      @endif
    </div>
  </div>
  @endisset
</div>