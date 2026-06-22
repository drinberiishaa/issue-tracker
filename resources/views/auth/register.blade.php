@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="max-w-md mx-auto mt-4 sm:mt-8">
    <div class="text-center mb-8">
        <span class="flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-500 to-brand-700 text-white shadow-lg mx-auto mb-4">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
        </span>
        <h1 class="text-2xl font-bold text-gray-900">Create an account</h1>
        <p class="text-sm text-gray-500 mt-1">Get started with Issue Tracker</p>
    </div>

    <div class="bg-white rounded-2xl shadow-card border border-gray-200 p-5 sm:p-8">
        @include('partials.errors')

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Name</label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus placeholder="Your full name"
                           class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 pl-10 pr-4">
                </div>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required placeholder="you@example.com"
                           class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 pl-10 pr-4">
                </div>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <input id="password" name="password" type="password" required placeholder="At least 8 characters"
                           class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 pl-10 pr-4">
                </div>
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Confirm password</label>
                <div class="relative">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Repeat your password"
                           class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 pl-10 pr-4">
                </div>
            </div>
            <button type="submit" class="btn-primary w-full justify-center py-2.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                Register
            </button>
        </form>

        <p class="text-sm text-gray-500 mt-6 text-center">
            Already have an account? <a href="{{ route('login') }}" class="text-brand-600 font-semibold hover:text-brand-700">Log in</a>
        </p>
    </div>
</div>
@endsection
