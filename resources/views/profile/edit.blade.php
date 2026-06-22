@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Profile</h1>
        <p class="text-sm text-gray-500 mt-1">Manage your account settings</p>
    </div>

    <div class="bg-white rounded-2xl shadow-card border border-gray-200 p-5 sm:p-8">
        <div class="flex items-center gap-4 mb-8 pb-6 border-b border-gray-100">
            <span class="flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-400 to-brand-600 text-white text-xl font-bold shadow-lg">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </span>
            <div>
                <p class="font-semibold text-gray-900 text-lg">{{ $user->name }}</p>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
        </div>

        @include('partials.errors')

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('PATCH')
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                       class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                       class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5 px-4">
            </div>
            <div class="pt-2">
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
