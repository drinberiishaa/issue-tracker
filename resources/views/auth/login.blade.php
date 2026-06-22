@extends('layouts.app')

@section('title', 'Log in')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8 mt-8">
    <h1 class="text-2xl font-bold mb-6">Log in</h1>

    @include('partials.errors')

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" name="password" type="password" required
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>
        <label class="flex items-center gap-2 text-sm text-gray-600">
            <input type="checkbox" name="remember" class="rounded border-gray-300 text-brand-600">
            Remember me
        </label>
        <button type="submit" class="w-full bg-brand-600 text-white py-2 rounded-md font-medium hover:bg-brand-700">
            Log in
        </button>
    </form>

    <p class="text-sm text-gray-500 mt-6">
        Don't have an account? <a href="{{ route('register') }}" class="text-brand-600 font-medium">Register</a>
    </p>

    <div class="mt-6 pt-6 border-t border-gray-100 text-xs text-gray-400 space-y-1">
        <p class="font-medium text-gray-500">Demo accounts (seeded):</p>
        <p>alice@example.com / password</p>
        <p>bob@example.com / password</p>
    </div>
</div>
@endsection
