<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? config('app.name', 'Jaunt Jar') }}</title>

{{-- SEO Meta Tags --}}
<meta name="description" content="{{ $description ?? 'Organiza tus aventuras, guarda recuerdos y planifica futuros viajes con Jaunt Jar, tu compañero de viajes digital.' }}">
<meta name="keywords" content="viajes, diario de viajes, travel journal, planificador de viajes, destinos, aventuras, lugares visitados">
<meta name="author" content="{{ config('app.name', 'Jaunt Jar') }}">
<meta name="robots" content="index, follow">
<meta name="language" content="{{ str_replace('_', '-', app()->getLocale()) }}">
<link rel="canonical" href="{{ url()->current() }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $title ?? config('app.name', 'Jaunt Jar') }} - Tu diario de viajes personal">
<meta property="og:description" content="{{ $description ?? 'Organiza tus aventuras, guarda recuerdos y planifica futuros viajes con Jaunt Jar, tu compañero de viajes digital.' }}">
<meta property="og:image" content="{{ url('/og-image.webp') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}">
<meta property="og:site_name" content="{{ config('app.name', 'Jaunt Jar') }}">

{{-- Twitter --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="{{ $title ?? config('app.name', 'Jaunt Jar') }} - Tu diario de viajes personal">
<meta name="twitter:description" content="{{ $description ?? 'Organiza tus aventuras, guarda recuerdos y planifica futuros viajes con Jaunt Jar, tu compañero de viajes digital.' }}">
<meta name="twitter:image" content="{{ url('/og-image.webp') }}">

{{-- Theme Color --}}
<meta name="theme-color" content="#0ea5e9" media="(prefers-color-scheme: light)">
<meta name="theme-color" content="#18181b" media="(prefers-color-scheme: dark)">
<meta name="msapplication-TileColor" content="#0ea5e9">

{{-- Favicons con soporte dark/light mode del sistema --}}
<link rel="icon" href="/favicon-dark.webp" media="(prefers-color-scheme: light)">
<link rel="icon" href="/favicon-light.webp" media="(prefers-color-scheme: dark)">
<link rel="apple-touch-icon" href="/favicon-dark.webp">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
@stack('styles')
