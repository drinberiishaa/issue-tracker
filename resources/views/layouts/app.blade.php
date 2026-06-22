<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Mini Issue Tracker')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#f0f4ff', 100: '#dbe4ff', 200: '#bac8ff',
                            300: '#91a7ff', 400: '#748ffc', 500: '#5c7cfa',
                            600: '#4c6ef5', 700: '#4263eb', 800: '#3b5bdb', 900: '#364fc7',
                        },
                        surface: {
                            50: '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    boxShadow: {
                        'soft': '0 1px 3px 0 rgb(0 0 0 / 0.04), 0 1px 2px -1px rgb(0 0 0 / 0.04)',
                        'card': '0 1px 3px 0 rgb(0 0 0 / 0.06), 0 2px 8px -2px rgb(0 0 0 / 0.06)',
                        'elevated': '0 4px 6px -1px rgb(0 0 0 / 0.07), 0 2px 4px -2px rgb(0 0 0 / 0.05)',
                    },
                },
            },
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-surface-50 min-h-screen text-gray-900 antialiased font-sans">

    @include('partials.nav')

    <main class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-8">
        @if (session('status'))
            <div class="mb-6 flex items-center gap-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 text-sm font-medium shadow-soft">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="border-t border-gray-200 mt-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-xs text-gray-400">
            Mini Issue Tracker &mdash; Built with Laravel
        </div>
    </footer>

    <script src="{{ asset('js/app.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
