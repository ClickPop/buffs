<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <title>{{ config('app.name', 'Laravel') }}</title>
  
  <!-- DNS Prefetch -->
  <link rel="dns-prefetch" href="//fonts.gstatic.com">
  <link rel="dns-prefetch" href="//www.googletagmanager.com">

  
  <!-- Google Tag Manager -->
  @if (app()->environment() === 'production')
  <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    '//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
  })(window,document,'script','dataLayer','GTM-P4N363N');</script>
  @else
    <script>
      var gtmConsoleStyle = [
        'color: rgba(131,93,161,1)',
        'font-size: 16px',
        'font-weight: bold'
      ];
      console.info("%c🧙 Google Tag Manager not loaded in non-production environments.", gtmConsoleStyle.join(';'));
    </script>
  @endif
  <!-- End Google Tag Manager -->
  
  <!-- Favicon & App Icons -->
  <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('/favicon/apple-icon-57x57.png') }}">
  <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('/favicon/apple-icon-60x60.png') }}">
  <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('/favicon/apple-icon-72x72.png') }}">
  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('/favicon/apple-icon-76x76.png') }}">
  <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('/favicon/apple-icon-114x114.png') }}">
  <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('/favicon/apple-icon-120x120.png') }}">
  <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('/favicon/apple-icon-144x144.png') }}">
  <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('/favicon/apple-icon-152x152.png') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/favicon/apple-icon-180x180.png') }}">
  <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('/favicon/android-icon-192x192.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('/favicon/favicon-96x96.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('images/favicon/site.webmanifest') }}">
  <meta name="msapplication-TileColor" content="#3d1e5e">
  <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
  <meta name="theme-color" content="#3d1e5e">
  
  <!-- Open Graph / Twitter Info -->
  <meta property="og:title" content="BUFFS">
  <meta property="og:description" content="Let the people who love your channel grow your channel.">
  <meta property="og:url" content="https://buffs.app">
  <meta property="og:image" content="https://buffs.app/images/brand/og.png">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:site" content="@BUFFSapp">
  <meta name="twitter:creator" content="@clickpop">
  
  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="{{$bodyClass ?? ''}}">
  @if (app()->environment() === 'production')
  <!-- Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P4N363N"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
  <!-- End Google Tag Manager (noscript) -->
  @endif
    