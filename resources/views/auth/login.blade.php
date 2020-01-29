@extends('layouts.static.dark.no-header')

@section('content')
<div class="container home-wrapper">
    <div class="row vertically-center wizard-after">
        <div class="col-12 col-lg-6 my-lg-5 mt-5">
            @include('dropins.components.logo-link')

            <h1 class="mb-4">Beta Access</h1>

            <p class="h4 mb-5">Are you one of the chosen few, selected to be a part of the next best thing?</p>

            <div class="login-box">
                <div class="social-login">
                    <a data-href="{{ route('oauth.login', ['provider' => 'twitch']) }}" data-remember-href="{{ route('oauth.login', ['provider' => 'twitch']) }}?remember=true" href="{{ route('oauth.login', ['provider' => 'twitch']) }}" class="btn btn-brand btn-round oauth-button">Login with Twitch</a>
                    <div class="mt-2 form-group form-check">
                        <label class="form-check-label">
                            <input type="checkbox" class="form-check-input remember-me">
                            Keep me logged in!
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 my-5 text-center d-none d-lg-block">
            <img class="mb-5 mage" src="{{ asset('images/brand/mage.svg') }}">
        </div>
    </div>
</div>
@endsection
