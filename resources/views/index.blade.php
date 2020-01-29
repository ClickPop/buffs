@extends('layouts.static.dark.no-header')

@section('content')
<div class="container home-wrapper">
    <div class="row vertically-center wizard-after">
        <div class="col-12 col-lg-6 my-lg-5 mt-5">
            @include('dropins.components.logo-link')

            <h1 class="mb-4">Let the people who love your stream build your stream.</h1>

            <p class="h4 mb-5">We're building tools to help you find people who might like your content, and then get them to love it.</p>

            <div class="optin">
                <p>Sign up to get updates on new BUFFS and other neat stuff we're doing.</p>
                @include('dropins.components.forms.optin')
            </div>
        </div>
        <div class="col-12 col-lg-6 my-5 text-center d-none d-lg-block">
            <img class="mb-5 mage" src="{{ asset('images/brand/mage.svg') }}">
        </div>
    </div>
</div>
@endsection
