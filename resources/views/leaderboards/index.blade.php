@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-12 col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h1 class="h5 my-0">Leaderboards</h1>
            </div>
            @if(isset($leaderboards) && count($leaderboards))
            <div class="list-group list-group-flush">
                @foreach ($leaderboards as $leaderboard)
                <a href="#preview" data-leaderboard-id="{{ $leaderboard->id }}" class="preview-leaderboard list-group-item list-group-item-action">
                    <i class="{{ $leaderboard->stream->platform->icon_class }}"></i>
                    {{ $leaderboard->name }}
                </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
    @if(isset($leaderboards) && count($leaderboards))
    <div class="col-12 col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <h1 class="h5 my-0">Details & Settings</h1>
            </div>
            <div class="card-body">
                <form data-action="/leaderboard/#ID#/update" method="POST">
                    <div class="row">
                        @csrf
                        @method('PUT')
                        <div class="form-group col-12">
                            <label id="labelName" for="fieldName">Leaderboard Name</label>
                            <input type="text" class="form-control" id="fieldName" name="name">
                        </div>

                        @isset($themes)
                        <div class="form-group col-6">
                            <label id="labelTheme" for="fieldTheme">Theme</label>
                            <select id="fieldTheme" name="theme" class="form-control">
                                @foreach ($themes as $theme)
                                <option value="{{ $theme->class }}">{{ $theme->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endisset

                        <div class="col-12 align-items-center text-center">
                            <button class="btn btn-outline-primary" type="submit">Reset Referrals</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </div>



                </form>
            </div>
        </div>
    </div>

    @endif
</div>
@endsection