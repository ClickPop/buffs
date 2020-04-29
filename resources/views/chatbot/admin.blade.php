@extends('layouts.app')

@section('content')
<div class="container-fluid dashboard-wrapper">
  <div class="row">
    <div class="col-12">
      <h2>Chatbot Admin...</h2>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <table id="chatbot-table" class="table table-striped table-bordered" style="width:100%">
        <thead>
          <th>Email</th>
          <th>Twitch Username</th>
          <th>Status</th>
          <th>Actions</th>
        </thead>
        <tbody>
          @foreach ($user_bots as $user_bot)
          <tr id={{ $user_bot->twitch_id }}>
            <td>{{ $user_bot->email }}</td>
            <td>{{ $user_bot->username }}</td>
            <td>{!! adminUserBotStatus($user_bot) !!}</td>
            <td>
              <div>
                @if (isset($user_bot->bot))
                <button class="admin_bot join btn btn-primary my-1"
                  {{ $user_bot->bot->joined ? 'disabled' : '' }}>Join</button>
                <button class="admin_bot part btn btn-danger my-1"
                  {{ $user_bot->bot->joined ? '' : 'disabled' }}>Part</button>
                @else
                <button class="admin_bot join btn btn-primary my-1" disabled>Join</button>
                <button class="admin_bot part btn btn-danger my-1" disabled>Part</button>
                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection