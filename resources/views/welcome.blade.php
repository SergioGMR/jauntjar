<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Tu diario de viajes personal</title>
    
    {{-- SEO Meta Tags --}}
    <meta name="description" content="Organiza tus aventuras, guarda recuerdos y planifica futuros viajes con Jaunt Jar, tu compañero de viajes digital.">
    <meta name="keywords" content="viajes, diario de viajes, travel journal, planificador de viajes, destinos, aventuras, lugares visitados">
    <meta name="author" content="{{ config('app.name', 'Jaunt Jar') }}">
    <meta name="robots" content="index, follow">
    <meta name="language" content="{{ str_replace('_', '-', app()->getLocale()) }}">
    <link rel="canonical" href="{{ url('/') }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="{{ config('app.name') }} - Tu diario de viajes personal">
    <meta property="og:description" content="Organiza tus aventuras, guarda recuerdos y planifica futuros viajes con Jaunt Jar, tu compañero de viajes digital.">
    <meta property="og:image" content="{{ url('/og-image.webp') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url('/') }}">
    <meta name="twitter:title" content="{{ config('app.name') }} - Tu diario de viajes personal">
    <meta name="twitter:description" content="Organiza tus aventuras, guarda recuerdos y planifica futuros viajes con Jaunt Jar, tu compañero de viajes digital.">
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
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="bg-gradient-to-br from-slate-50 via-white to-sky-50 dark:from-zinc-950 dark:via-zinc-900 dark:to-slate-900 min-h-screen antialiased">
    
    {{-- Navigation --}}
    <nav class="fixed top-0 left-0 right-0 z-50 bg-white/80 dark:bg-zinc-900/80 backdrop-blur-lg border-b border-slate-200/50 dark:border-zinc-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-3 group">
                    <img 
                        src="{{ Vite::asset('resources/images/logos/light-navbarlogo.webp') }}" 
                        alt="{{ config('app.name') }}" 
                        class="h-10 w-auto hidden dark:block"
                    >
                    <img 
                        src="{{ Vite::asset('resources/images/logos/dark-navbarlogo.webp') }}" 
                        alt="{{ config('app.name') }}" 
                        class="h-10 w-auto block dark:hidden"
                    >
                </a>
                
                {{-- Dark Mode Toggle & Auth Links --}}
                <div class="flex items-center gap-4">
                    {{-- Dark Mode Toggle --}}
                    <flux:radio.group x-data variant="segmented" x-model="$flux.appearance" class="hidden sm:flex">
                        <flux:radio value="light" icon="sun" />
                        <flux:radio value="dark" icon="moon" />
                        <flux:radio value="system" icon="computer-desktop" />
                    </flux:radio.group>
                    
                    {{-- Mobile dark mode toggle --}}
                    <div x-data class="sm:hidden">
                        <flux:button 
                            variant="ghost" 
                            size="sm" 
                            icon="moon" 
                            x-show="$flux.appearance === 'light'" 
                            @click="$flux.appearance = 'dark'"
                        />
                        <flux:button 
                            variant="ghost" 
                            size="sm" 
                            icon="sun" 
                            x-show="$flux.appearance === 'dark'" 
                            @click="$flux.appearance = 'light'"
                        />
                        <flux:button 
                            variant="ghost" 
                            size="sm" 
                            icon="computer-desktop" 
                            x-show="$flux.appearance === 'system'" 
                            @click="$flux.appearance = 'light'"
                        />
                    </div>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" 
                               class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-sky-500 to-indigo-600 rounded-xl shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 hover:scale-105 transition-all duration-200">
                                Dashboard
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-sky-500 to-indigo-600 rounded-xl shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 hover:scale-105 transition-all duration-200">
                                Iniciar Sesión
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="relative pt-32 pb-20 lg:pt-40 lg:pb-32 overflow-hidden">
        {{-- Background decoration --}}
        <div class="absolute inset-0 -z-10">
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-sky-400/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-400/20 rounded-full blur-3xl"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-gradient-to-r from-sky-200/30 to-indigo-200/30 dark:from-sky-900/20 dark:to-indigo-900/20 rounded-full blur-3xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300 text-sm font-medium mb-8">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                    </svg>
                    Tu compañero de viajes digital
                </div>
                
                {{-- Main heading --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-900 dark:text-white leading-tight mb-6">
                    Guarda tus <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-indigo-600">aventuras</span>, 
                    planifica tus <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-600">sueños</span>
                </h1>
                
                {{-- Subheading --}}
                <p class="text-lg sm:text-xl text-slate-600 dark:text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                    Organiza todos los lugares que has visitado, los destinos que quieres explorar y clasifica tus experiencias favoritas. Tu diario de viajes personal, siempre contigo.
                </p>
                
                {{-- CTA Buttons --}}
                @if (Route::has('login'))
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        <a href="{{ route('login') }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white bg-gradient-to-r from-sky-500 to-indigo-600 rounded-2xl shadow-xl shadow-sky-500/25 hover:shadow-sky-500/40 hover:scale-105 transition-all duration-300">
                            ¡Empieza ya!
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                        <a href="#features" 
                           class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-medium text-slate-700 dark:text-slate-300 bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 rounded-2xl hover:border-slate-300 dark:hover:border-zinc-600 hover:shadow-lg transition-all duration-300">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Descubrir más
                        </a>
                    </div>
                @endif
            </div>
            
            {{-- Hero Image/Mockup --}}
            <div class="mt-16 lg:mt-24 relative">
                <div class="relative mx-auto max-w-5xl">
                    {{-- Browser mockup --}}
                    <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-2xl shadow-slate-900/10 dark:shadow-black/30 border border-slate-200/50 dark:border-zinc-700/50 overflow-hidden">
                        {{-- Browser header --}}
                        <div class="flex items-center gap-2 px-4 py-3 bg-slate-50 dark:bg-zinc-900 border-b border-slate-200/50 dark:border-zinc-700/50">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <div class="flex-1 flex justify-center">
                                <div class="px-4 py-1.5 bg-white dark:bg-zinc-800 rounded-lg text-xs text-slate-500 dark:text-slate-400 flex items-center gap-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    {{ config('app.url') }}
                                </div>
                            </div>
                        </div>
                        {{-- Map preview --}}
                        <div class="aspect-[16/9] bg-gradient-to-br from-sky-100 via-slate-100 to-indigo-100 dark:from-zinc-800 dark:via-zinc-850 dark:to-slate-800 relative overflow-hidden">
                            {{-- Fake map with markers --}}
                            <div class="absolute inset-0 opacity-30 dark:opacity-20" style="background-image: url('data:image/svg+xml,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 100 100\"><defs><pattern id=\"grid\" width=\"10\" height=\"10\" patternUnits=\"userSpaceOnUse\"><path d=\"M 10 0 L 0 0 0 10\" fill=\"none\" stroke=\"%23cbd5e1\" stroke-width=\"0.5\"/></pattern></defs><rect width=\"100\" height=\"100\" fill=\"url(%23grid)\"/></svg></div>
                            
                            {{-- Decorative map elements --}}
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="grid grid-cols-3 gap-8 p-8">
                                    {{-- Visited marker --}}
                                    <div class="flex flex-col items-center gap-2 animate-bounce" style="animation-delay: 0s; animation-duration: 2s;">
                                        <div class="w-12 h-12 rounded-full bg-emerald-500 shadow-lg shadow-emerald-500/30 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400 bg-white/80 dark:bg-zinc-800/80 px-2 py-1 rounded-full">París</span>
                                    </div>
                                    {{-- Planned marker --}}
                                    <div class="flex flex-col items-center gap-2 animate-bounce" style="animation-delay: 0.3s; animation-duration: 2s;">
                                        <div class="w-12 h-12 rounded-full bg-amber-500 shadow-lg shadow-amber-500/30 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400 bg-white/80 dark:bg-zinc-800/80 px-2 py-1 rounded-full">Tokio</span>
                                    </div>
                                    {{-- Favorite marker --}}
                                    <div class="flex flex-col items-center gap-2 animate-bounce" style="animation-delay: 0.6s; animation-duration: 2s;">
                                        <div class="w-12 h-12 rounded-full bg-rose-500 shadow-lg shadow-rose-500/30 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                            </svg>
                                        </div>
                                        <span class="text-xs font-medium text-slate-600 dark:text-slate-400 bg-white/80 dark:bg-zinc-800/80 px-2 py-1 rounded-full">Barcelona</span>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Stats overlay --}}
                            <div class="absolute bottom-4 left-4 right-4 flex gap-3">
                                <div class="flex-1 bg-white/90 dark:bg-zinc-800/90 backdrop-blur-sm rounded-xl p-4 shadow-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-2xl font-bold text-slate-900 dark:text-white">24</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Visitados</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1 bg-white/90 dark:bg-zinc-800/90 backdrop-blur-sm rounded-xl p-4 shadow-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-2xl font-bold text-slate-900 dark:text-white">12</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Planeados</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1 bg-white/90 dark:bg-zinc-800/90 backdrop-blur-sm rounded-xl p-4 shadow-lg">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-rose-100 dark:bg-rose-900/30 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-2xl font-bold text-slate-900 dark:text-white">8</p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400">Favoritos</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Floating cards --}}
                    <div class="absolute -left-8 top-1/4 hidden lg:block">
                        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl p-4 w-48 transform -rotate-6 hover:rotate-0 transition-transform duration-300">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-sky-400 to-indigo-500 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945" />
                                    </svg>
                                </div>
                                <span class="text-sm font-medium text-slate-900 dark:text-white">Nuevo destino</span>
                            </div>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Roma añadida a tu lista de deseos ✨</p>
                        </div>
                    </div>
                    
                    <div class="absolute -right-8 top-1/3 hidden lg:block">
                        <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl p-4 w-52 transform rotate-6 hover:rotate-0 transition-transform duration-300">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl overflow-hidden bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center">
                                    <span class="text-lg">🗼</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-slate-900 dark:text-white">París, Francia</p>
                                    <div class="flex items-center gap-1">
                                        @for ($i = 0; $i < 5; $i++)
                                            <svg class="w-3 h-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="py-20 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section header --}}
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-4">
                    Todo lo que necesitas para tus viajes
                </h2>
                <p class="text-lg text-slate-600 dark:text-slate-400">
                    Herramientas simples pero poderosas para organizar todas tus aventuras
                </p>
            </div>
            
            {{-- Features grid --}}
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1: Visitados --}}
                <div class="group relative bg-white dark:bg-zinc-800/50 rounded-3xl p-8 shadow-sm hover:shadow-xl border border-slate-100 dark:border-zinc-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 shadow-lg shadow-emerald-500/25 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Lugares Visitados</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Registra todos los lugares que has explorado. Añade fotos, notas y recuerdos de cada destino que has conquistado.
                    </p>
                </div>
                
                {{-- Feature 2: Planeados --}}
                <div class="group relative bg-white dark:bg-zinc-800/50 rounded-3xl p-8 shadow-sm hover:shadow-xl border border-slate-100 dark:border-zinc-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 shadow-lg shadow-amber-500/25 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Viajes Planeados</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Crea tu lista de deseos viajera. Planifica futuros destinos y mantén vivo el sueño de tu próxima aventura.
                    </p>
                </div>
                
                {{-- Feature 3: Clasificados --}}
                <div class="group relative bg-white dark:bg-zinc-800/50 rounded-3xl p-8 shadow-sm hover:shadow-xl border border-slate-100 dark:border-zinc-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-400 to-pink-500 shadow-lg shadow-rose-500/25 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Clasificaciones</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Puntúa y clasifica tus experiencias. Crea tu propio ranking de destinos favoritos y comparte tus recomendaciones.
                    </p>
                </div>
                
                {{-- Feature 4: Mapa interactivo --}}
                <div class="group relative bg-white dark:bg-zinc-800/50 rounded-3xl p-8 shadow-sm hover:shadow-xl border border-slate-100 dark:border-zinc-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-sky-400 to-blue-500 shadow-lg shadow-sky-500/25 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Mapa Interactivo</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Visualiza todos tus viajes en un mapa mundial. Ve de un vistazo todos los lugares que has explorado y los que te faltan.
                    </p>
                </div>
                
                {{-- Feature 5: Estadísticas --}}
                <div class="group relative bg-white dark:bg-zinc-800/50 rounded-3xl p-8 shadow-sm hover:shadow-xl border border-slate-100 dark:border-zinc-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-violet-400 to-purple-500 shadow-lg shadow-violet-500/25 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Estadísticas</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Descubre patrones en tus viajes. Países visitados, continentes explorados y mucho más en estadísticas visuales.
                    </p>
                </div>
                
                {{-- Feature 6: Privacidad --}}
                <div class="group relative bg-white dark:bg-zinc-800/50 rounded-3xl p-8 shadow-sm hover:shadow-xl border border-slate-100 dark:border-zinc-700/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-slate-400 to-zinc-500 shadow-lg shadow-slate-500/25 flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 dark:text-white mb-3">Tu Privacidad</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Tus datos son solo tuyos. Tu diario de viajes es completamente privado y seguro, siempre bajo tu control.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 lg:py-32">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-500 via-indigo-500 to-purple-600 p-8 sm:p-12 lg:p-16">
                {{-- Background decoration --}}
                <div class="absolute inset-0 -z-10">
                    <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"></div>
                </div>
                
                <div class="relative text-center max-w-3xl mx-auto">
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white mb-6">
                        ¿Listo para empezar tu aventura?
                    </h2>
                    <p class="text-lg sm:text-xl text-white/80 mb-10">
                        Accede a Jaunt Jar y comienza a documentar todos los lugares increíbles que te esperan.
                    </p>
                    
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" 
                           class="inline-flex items-center px-8 py-4 text-lg font-semibold text-indigo-600 bg-white rounded-2xl shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300">
                            ¡Empieza ya!
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-12 border-t border-slate-200/50 dark:border-zinc-800/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <img 
                        src="{{ Vite::asset('resources/images/logos/light-navbarlogo.webp') }}" 
                        alt="{{ config('app.name') }}" 
                        class="h-8 w-auto hidden dark:block"
                    >
                    <img 
                        src="{{ Vite::asset('resources/images/logos/dark-navbarlogo.webp') }}" 
                        alt="{{ config('app.name') }}" 
                        class="h-8 w-auto block dark:hidden"
                    >
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    © {{ date('Y') }} {{ config('app.name') }}. Hecho con ❤️ para viajeros.
                </p>
            </div>
        </div>
    </footer>
    
    @fluxScripts
</body>
</html>
