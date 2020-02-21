@extends('layouts.static.dark.no-header')

@section('content')
<div class="container">
    <div class="row align-items-center my-lg-4">
        <div class="col-12 col-lg-6 my-lg-5 mt-5 pr-lg-5">
            @include('dropins.components.logo-link')

            <h1 class="mb-4">Let the people who love your stream build your stream.</h1>

            <p class="h4 mb-5">We're building tools to help you find people who might like your content, and then get them to love it.</p>

            <div class="optin">
                <p>Sign up to get updates on new BUFFS and other neat stuff we're doing.</p>
                @include('dropins.components.forms.optin')
            </div>
        </div>
        <div class="col-12 col-lg-6 my-5 text-center d-none d-lg-block">
            <img class="img-fluid" src="{{ asset('images/brand/wizard.png') }}">
        </div>
    </div>
    <div class="row mb-4 mt-5">
        <div class="col">
            <h1 class="text-center">What we're conjuring.</h1>
        </div>
    </div>
    <div class="row align-items-center my-5">
        <div class="col-lg-5 offset-lg-1 text-center">
            <video class="img-fluid video" poster="" autoplay="true" playsinline="true" loop="true" muted="true">
                <source src="{{ asset('videos/leaderboard-sm.mp4')}}" type="video/mp4">
            </video>
        </div>
        <div class="col-lg-5 px-5">
            <h2 class="pt-3">The Referral Leaderboard</h2>
            <p>
                Encourage your viewers to share your show by using a leaderboard to award swag, select questions to answer, or just get your audience's competitive juices flowing.
            </p>
        </div>
    </div>
    <div class="row align-items-center my-5 pt-lg-3">
        <div class="col-lg-5 order-lg-2 text-center">
            <img src="{{ asset('images/brand/pet.gif')}}" class="img-fluid" />
        </div>
        <div class="col-lg-5 offset-lg-1 order-lg-1 px-5">
            <h2>Coming in the future...</h2>
            <p>
                A companion for your stream.
            </p>
        </div>
    </div>
</div>
@endsection
