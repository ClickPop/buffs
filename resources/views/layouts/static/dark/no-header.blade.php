@include('dropins.core.head', ['bodyClass' => 'bg-dark'])

<main role="main">
    @include('dropins.components.login-cta')
    @yield('content')
</main>

@include('dropins.core.foot')
