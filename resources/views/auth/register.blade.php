@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8 mt-8">
    <h1 class="text-2xl font-bold mb-6">Create an account</h1>

    @include('partials.errors')

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>
        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input id="password" name="password" type="password" required
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>
        <button type="submit" class="w-full bg-brand-600 text-white py-2 rounded-md font-medium hover:bg-brand-700">
            Register
        </button>
    </form>

    <p class="text-sm text-gray-500 mt-6">
        Already have an account? <a href="{{ route('login') }}" class="text-brand-600 font-medium">Log in</a>
    </p>
</div>
@endsection
