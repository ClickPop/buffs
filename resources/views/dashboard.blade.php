@extends('layouts.app')

@section('content')
<div class="container dashboard-wrapper">
    <div class="row">
        <div class="col-8">
            <h2>My Leaderboard</h2>
            <!-- if no leaderboard, show something else -->
            @empty($leaderboard)
            <p>Looks like you currently don't have a leaderboard, click the button to create yours now!</p>
            <a href="{{ route( 'leaderboards.quickStart' ) }}" class="btn btn-primary">Create Leaderboard</a>
            @endempty
            @isset($leaderboard)
                @include('dropins.components.leaderboard', ['preview' => true])
                <div class="form-group">
                    <form method="POST">
                        @csrf
                        <div id="leaderboard-alert" class="alert alert-success text-center" style="display: none;"></div>
                        <div id='settings' class="my-4">
                            <select id="theme-selector" name="theme-selector" class="form-control">
                                <option value="light" {{ $leaderboard->theme === 'light' ? 'selected' : '' }}>Light</option>
                                <option value="dark" {{ $leaderboard->theme === 'dark' ? 'selected' : '' }}>Dark</option>
                            </select>
                            <div class="form-group">
                                <label for="leaderboard-length-slider">Number of rows on leaderboard: <span id="leaderboard-length">{{ $leaderboard->length }}</span></label>
                                <input class="custom-range" type="range" name="leaderboard-length-slider" id="leaderboard-length-slider" min="3" max="10" value={{ $leaderboard->length }} />
                            </div>
                            <button class="btn btn-primary form-control" id='settings-submit' type="submit" value="Submit">Submit</button>
                        </div>
                    </form>
                    <div id="embed-info" class="form-group">
                        <label for="embed-link">Leaderboard Embed Link</label>
                        <div class="d-flex flex-row align-items-center">
                            <input id="embed-link" name="embed-link" class="form-control" type="text" disabled value="{{ $_SERVER['SERVER_NAME'] }}/embed/leaderboard/{{ Auth::user()->username }}"/>
                            <button id="embed-copy" class="btn btn-secondary form-control">
                                @svg('icons/info')
                                COPY
                            </button>
                        </div>
                    </div>
                </div>
            @endisset
        </div>
    </div>
</div>
@endsection
