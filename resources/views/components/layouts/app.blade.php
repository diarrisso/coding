<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="flex flex-col min-h-screen bg-gray-100 dark:bg-zinc-800" x-data="{ mobileMenuOpen: false }">

    <header class="sticky top-0 left-0 right-0 bg-primary-base text-white p-4 shadow-md z-50">
        <div class="container mx-auto flex items-center justify-between">
            <button class="lg:hidden text-white dark:text-white" @click="mobileMenuOpen = !mobileMenuOpen" aria-label="Toggle mobile menu">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <a href="/" class="ms-2 me-5 flex items-center space-x-2 rtl:space-x-reverse lg:ms-0" wire:navigate>
                <div class="text-xl font-bold text-white dark:text-white">
                    <span>Mon App</span>
                </div>
            </a>
            <nav class="-mb-px max-lg:hidden">
                <ul class="flex justify-items-start space-x-6">
                    <li>
                        <flux:navbar.item
                            icon="home"
                            href="/"
                            :current="request()->is('/')"
                            class="flex items-center space-x-2 py-4 text-sm font-medium {{ request()->is('/') ? ' border-primary-500 text-primary-600 dark:text-primary-400' : 'text-zinc-700 dark:text-zinc-300 hover:text-primary-500 dark:hover:text-primary-400' }}"
                            wire:navigate
                        >
                            {{ __('Home') }}
                        </flux:navbar.item>
                    </li>
                    <li>
                        <flux:navbar.item
                            icon="folder"
                            href="/teasers"
                            :current="request()->is('teasers*')"
                            class="flex items-center space-x-2 py-4 text-sm font-medium {{ request()->is('teasers*') ? 'border-primary-500 text-primary-600 dark:text-primary-400' : 'text-zinc-700 dark:text-zinc-300 hover:text-primary-500 dark:hover:text-primary-400' }}"
                            wire:navigate
                        >
                            {{ __('Meine Teasers') }}
                        </flux:navbar.item>
                    </li>
                </ul>
            </nav>

            <div class="flex items-center space-x-4">

                @auth
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-sm font-medium text-white dark:text-white">
                            <div class="h-9 w-9 rounded-lg bg-primary-900  flex items-center justify-center text-sm md:text-2xl font-medium text-white">
                                {{ substr(auth()->user()->name, 0, 1) ?? 'U' }}
                            </div>
                        </button>


                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-zinc-900 ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <div class="px-4 py-2 text-sm">
                                    <div class="font-semibold text-gray-800 dark:text-white">{{ auth()->user()->name ?? 'U' }}</div>
                                    <div class="text-xs text-zinc-500 dark:text-zinc-400 truncate">{{ auth()->user()->email ?? '' }}</div>
                                </div>

                                <hr class="my-1 border-zinc-200 dark:border-zinc-700">

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="/login" class="text-white dark:text-white hover:text-green-200 text-sm font-medium">
                        Login
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <div
        x-show="mobileMenuOpen"
        @keydown.escape.window="mobileMenuOpen = false"
        class="lg:hidden fixed inset-y-0 left-0 z-50 w-64 bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700 transform transition-transform duration-300 shadow-lg"
        :class="{ '-translate-x-full': !mobileMenuOpen, 'translate-x-0': mobileMenuOpen }"
    >

        <div class="flex items-center justify-between p-4 border-b border-zinc-200 dark:border-zinc-700 bg-primary-base">
            <a href="/" class="flex items-center space-x-2" wire:navigate>
                <div class="text-xl font-bold text-white dark:text-white">Mon App</div>
            </a>
            <button @click="mobileMenuOpen = false" class="text-white dark:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>


        <flux:navlist variant="outline" class="p-4">
            <flux:navlist.group :heading="__('Navigation')" class="space-y-2">
                <flux:navlist.item
                    icon="home"
                    href="/"
                    :current="request()->is('/')"
                    class="flex items-center space-x-2 p-2 rounded-md {{ request()->is('/') ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-zinc-700 dark:text-zinc-300 hover:bg-primary-50 dark:hover:bg-primary-900/50' }}"
                    wire:navigate
                >
                    {{ __('Home') }}
                </flux:navlist.item>

                <flux:navlist.item
                    icon="folder"
                    href="/teasers"
                    :current="request()->is('teasers*')"
                    class="flex items-center space-x-2 p-2 rounded-md {{ request()->is('teasers*') ? 'bg-primary-100 dark:bg-primary-900 text-primary-700 dark:text-primary-300' : 'text-zinc-700 dark:text-zinc-300 hover:bg-primary-50 dark:hover:bg-primary-900/50' }}"
                    wire:navigate
                >
                    {{ __('Meine Teasers') }}
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

    </div>

    <main class="container mx-auto py-4 flex-grow">
        {{ $slot }}
    </main>

    @include('components.layouts.app.footer')

    @livewireScripts
    @livewireScriptConfig
    @fluxScripts
    @stack('scripts')
</body>
</html>
