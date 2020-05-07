@extends('layouts.static.dark.error')

@section('content')
  <div class="my-0 container httpError">
    <div class="my-0 row align-items-center" style="height: 100vh;">
      <div class="my-4 col-12 text-center">
        @include('dropins.components.logo-link')
        <h3 class="httpError__code">@yield('code')</h3>
        <p class="h5 httpError__message">@yield('message')</p>
        <img src="{{asset('images/brand/wizard.png')}}" class="img-fluid httpError__image">
      </div>
    </div>
  </div>
@endsection