@extends('layouts.app')

@section('content')
<div class="container dashboard-wrapper">
  <div class="row">
    <div class="col-12">
      <h2>Chatbot Admin...</h2>
    </div>
</div>
<div class="row">
      @foreach ($chatbots as $bot)
        <div class="d-flex flex-row align-items-center justify-content-between card p-3 m-1">
          <h3 class="card-title">{{ $bot->twitch_username }}</h3>
          <div class="alert {{ $bot->joined ? 'alert-success' : 'alert-danger' }} mx-3">{{ $bot->joined ? 'Joined' : 'Parted' }}</div>
        </div>
      @endforeach
  </div>
</div>
@endsection
