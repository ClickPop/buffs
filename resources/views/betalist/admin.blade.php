@extends('layouts.app')

@section('content')
<div class="container-fluid dashboard-wrapper admin-betalist">
  <div class="row">
    <div class="col-12 mb-4">
      <div class="row">
        <div class="col-12 mb-4">
          <h3>BetaList</h3>

          <table id="betalist-table" class="table table-striped table-bordered" style="width:100%">
            <thead>
              <th>Email</th>
              <th>Twitch Username</th>
              <th>Status</th>
              <th>Actions</th>
            </thead>
            <tbody>
              @foreach ($betalist as $user)
              <tr data-twitch-id="{{ $user->id }}" class="betalist row_{{ $loop->index + 1 }}">
                <td>{{ $user->email }}</td>
                <td>{{ $user->username }}</td>
                <td>
                  @if ($user->current_status === 'approved')
                  <span class="badge badge-success">Approved</span>
                  @elseif ($user->current_status === 'denied')
                  <span class="badge badge-danger">Denied</span>
                  @else
                  <span class="badge badge-warning">Pending</span>
                  @endif
                </td>
                <td>
                  <button class="betalist_approve btn btn-success btn-sm my-1"
                    style="display: {{ $user->current_status !== 'approved' ? '' : 'none' }};" >Approve</button>
                  <button class="betalist_deny btn btn-danger btn-sm my-1"
                    style="display: {{ $user->current_status !== 'denied' ? '' : 'none' }};">Deny</button>
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