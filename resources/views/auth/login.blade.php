@extends('layouts.static.dark.no-header')

@section('content')
<div class="container home-wrapper">
    <div class="row vertically-center wizard-after">
        <div class="col-12 col-lg-6 my-lg-5 mt-5">
            <a href="/"><img class="mb-5 logo" src="{{ asset('images/brand/buffs_logo.svg') }}" width="135"></a>

            <h1 class="mb-4">Beta Access</h1>

            <p class="h4 mb-5">Are you one of the chosen few, selected to be a part of the next best thing?</p>

            <div class="login-box">
                <div class="social-login">
                    <a href="{{ route('oauth.login', ['provider' => 'twitch']) }}" class="btn btn-brand btn-round">Login with Twitch</a>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6 my-5 text-center d-none d-lg-block">
            <img class="mb-5 mage" src="{{ asset('images/brand/mage.svg') }}">
        </div>
    </div>
</div>
@endsection
