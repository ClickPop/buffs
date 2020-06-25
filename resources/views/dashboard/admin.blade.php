@extends('layouts.app') @section('content')
<div class="container dashboard-wrapper">
  <div class="row">
    <div class="col-12">
      <h2>Buffs Administration</h2>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-sm-6 col-12">
      <div class="card bg-primary mb-4">
        <div class="card-header">
          <h5 class="my-0">Chatbots</h5>
        </div>
        <div class="card-body">
          <p class="card-text">Total Chatbots: {{ $total }}</p>
          <p class="card-text">Joined Chatbots: {{ $joined }}</p>
          <p class="card-text">Parted Chatbots: {{ $parted }}</p>
        </div>
        <div class="card-footer text-right">
          <a href="{{ route('admin.chatbots') }}" class="btn btn-secondary">View More</a>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-12">
      <div class="card bg-success mb-4">
        <div class="card-header">
          <h5 class="my-0">BetaList</h5>
        </div>
        <div class="card-body">
          <p class="card-text">Approved Total {{ $approved }}</p>
          <p class="card-text">Pending Total {{ $pending }}</p>
          <p class="card-text">Denied Total {{ $denied }}</p>
          <p class="card-text">Beta List Total {{ $betalist }}</p>
        </div>
        <div class="card-footer text-right">
          <a href="{{ route('admin.betalist') }}" class="btn btn-secondary">View More</a>
        </div>
      </div>
    </div>
    <div class="col-sm-8 col-12">
      <div class="input-group">
        <div class="input-group-prepend">
          <span class="input-group-text">API_KEY</span>
        </div>
        <input class="form-control" id="api_key" type="text" disabled value={{
          $API_KEY
          }} />
        <div class="input-group-append">
          <button id="api_key_copy" class="btn btn-primary">COPY</button>
        </div>
      </div>
      <div id="api_key_copy_alert" class="alert alert-success text-center" style="display: none;">Key copied to
        clipboard</div>
    </div>
  </div>
</div>
@endsection