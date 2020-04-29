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
                  <td>
                    @if (isset($user_bot->bot))
                      <button class="admin_bot join btn btn-primary my-1"
                        {{ $user_bot->bot->joined ? 'disabled' : '' }}>Join</button>
                      <button class="admin_bot part btn btn-danger my-1"
                        {{ $user_bot->bot->joined ? '' : 'disabled' }}>Part</button>
                    @else
                      <button class="admin_bot join btn btn-primary my-1" disabled>Join</button>
                      <button class="admin_bot part btn btn-danger my-1" disabled>Part</button>
                    @endif
                  </td>
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
                  <td>
                    @if (isset($unknown_bot->joined))
                      <button class="admin_bot join btn btn-primary my-1"
                        {{ $unknown_bot->joined ? 'disabled' : '' }}>Join</button>
                      <button class="admin_bot part btn btn-danger my-1"
                        {{ $unknown_bot->joined ? '' : 'disabled' }}>Part</button>
                    @else
                      <button class="admin_bot join btn btn-primary my-1" disabled>Join</button>
                      <button class="admin_bot part btn btn-danger my-1" disabled>Part</button>
                    @endif
                  </td>
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