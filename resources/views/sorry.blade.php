@extends('layouts.static.dark.no-header')

@section('content')
    <div class="container home-wrapper">
        <div class="row vertically-center wizard-after">
            <div class="col-12 col-lg-6 my-lg-5 mt-5">
                @include('dropins.components.logo-link')

                <h1 class="mb-4">We're so sorry!</h1>

                <p class="h4 mb-5">It doesn't look like your Twitch account is on the beta list yet.</p>

                <div class="optin">
                    <p>Sign up to join the beta waiting list and get updates on new BUFFS and other neat stuff we're doing.</p>
                    @include('dropins.components.forms.optin')
                </div>
            </div>
            <div class="col-12 col-lg-6 my-5 text-center d-none d-lg-block">
                <img class="mb-5 mage" src="{{ asset('images/brand/mage.svg') }}" loading="lazy">
            </div>
        </div>
    </div>
@endsection
