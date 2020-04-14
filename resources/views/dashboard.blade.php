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
                @include('dropins.components.leaderboard', ['preview' => false])
                <div id='theme' class="d-flex flex-row my-4">
                    <select id="theme-selector" class="form-control" value="Theme">
                        <option value="" selected disabled hidden>Theme</option>
                        <option value="light">Light</option>
                        <option value="dark">Dark</option>
                    </select>
                    <button class="btn btn-primary form-control" id='theme-submit' type="submit" value="Submit">Submit</button>
                </div>
            @endisset
        </div>
    </div>
</div>
@endsection
