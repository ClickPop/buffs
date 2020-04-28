@extends('layouts.app')

@section('content')
<div class="container dashboard-wrapper">
  <div class="row">
    <div class="col-12">
      <h2>BetaList Admin...</h2>
    </div>
  </div>
  <div class="row">
    <table id="betalist-table" class="table table-striped table-bordered" style="width:100%">
      <thead>
        <th>Email</th>
        <th>Twitch Username</th>
        <th>Status</th>
        <th>Actions</th>
      </thead>
      <tbody>
        @foreach ($betalist as $user)
        <tr>
          <td>{{ $user->email }}</td>
          <td>username</td>
          <td>{{ $user->current_status }}</td>
          <td>
            @if ($user->current_status === 'pending')
            <button class="btn btn-success btn-sm my-1">Approve</button>
            <button class="btn btn-danger btn-sm my-1">Deny</button>
            @elseif($user->current_status === 'denied')
            <button class="btn btn-success btn-sm my-1">Approve</button>
            @else
            <button class="btn btn-danger btn-sm my-1">Deny</button>
            @endif
          </td>
        </tr>
  </div>
  @endforeach
  </tbody>
  </table>
</div>
</div>
@endsection