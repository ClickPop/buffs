@extends('layouts.app')

@section('content')
  <div class="container-fluid dashboard-wrapper">
    <div class="row">
      <div class="col-12 mb-4">
        <div class="row">
          <div class="col-12 mb-4">
            <h3>Users' Chatbots</h3>

            <table class="assigned-chatbots-table table table-striped table-bordered" style="width:100%">
              <thead>
                <th>Email</th>
                <th>Twitch Username</th>
                <th>Status</th>
                <th>Actions</th>
              </thead>
              <tbody>
              @foreach ($user_bots->assigned as $user_bot)
                <tr>
                  <td>{{ $user_bot->email }}</td>
                  <td>{{ $user_bot->username }}</td>
                  <td>{!! adminUserBotStatus($user_bot) !!}</td>
                  <td>[...]</td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>

          <div class="col-12">
            <h3>Unassigned Chatbots</h3>

            <table class="unassigned-chatbots-table table table-striped table-bordered" style="width:100%">
              <thead>
                <th>Twitch User ID</th>
                <th>Last Know Twitch Username</th>
                <th>Status</th>
                <th>Actions</th>
              </thead>
              <tbody>
              @foreach ($user_bots->unassigned as $unknown_bot)
                <tr>
                  <td>{{ $unknown_bot->twitch_userId }}</td>
                  <td>{{ $unknown_bot->twitch_username }}</td>
                  <td>{!! adminUserBotStatus($unknown_bot, false) !!}</td>
                  <td>[...]</td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection