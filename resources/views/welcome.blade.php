@extends('layouts.static.dark.no-nav')

@section('content')
<div class="container">
    <div class="row align-items-center">
        <div class="col-12 col-lg-6 my-5">
            <h1 class="mb-4">Let the people who love your stream build your stream.</h1>
            <p class="h4 mb-5">We're building tools to help you find people who might like your content, and then get them to love it.</p>
            
            <div class="optin">
                <p>Sign up to get updates on new BUFFS and other neat stuff we're doing.</p>
                @include('dropins.components.forms.optin')
            </div>
        </div>
        <div class="col-12 col-lg-6 my-5 text-center">
            @include('dropins.svg.mage')
        </div>

    </div>
</div>
@endsection
