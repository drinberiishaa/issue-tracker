@extends('layouts.app')

@section('title', 'Log in')

@section('content')
<div class="max-w-md mx-auto mt-4 sm:mt-8">
    <div class="text-center mb-8">
        <span class="flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-lg mx-auto mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </span>
        <h1 class="text-2xl font-bold text-gray-900">Welcome back</h1>
        <p class="text-sm text-gray-500 mt-1">Sign in to your account to continue</p>
    </div>

    <div class="bg-white rounded-2xl shadow-card border border-gray-200 p-5 sm:p-8">
        @include('partials.errors')

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com"
                           class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 pl-10 pr-4">
                </div>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <input id="password" name="password" type="password" required placeholder="Enter your password"
                           class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 pl-10 pr-4">
                </div>
            </div>
            <label class="flex items-center gap-2.5 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded-md border-gray-300 text-brand-600 shadow-sm">
                Remember me
            </label>
            <button type="submit" class="btn-primary w-full justify-center py-2.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                Log in
            </button>
        </form>

        <p class="text-sm text-gray-500 mt-6 text-center">
            Don't have an account? <a href="{{ route('register') }}" class="text-brand-600 font-semibold hover:text-brand-700">Register</a>
        </p>

        <div class="mt-6 pt-6 border-t border-gray-100 rounded-xl bg-gray-50 -mx-2 px-4 py-3">
            <p class="text-xs font-semibold text-gray-500 mb-1">Demo account</p>
            <p class="text-xs text-gray-400">drin@gmail.com / password: drinberisha</p>
        </div>
    </div>
</div>
@endsection
