@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <h1 class="text-2xl font-bold mb-6">Profile</h1>

    @include('partials.errors')

    <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('PATCH')
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
        </div>
        <button type="submit" class="bg-brand-600 text-white px-4 py-2 rounded-md font-medium hover:bg-brand-700">
            Save
        </button>
    </form>
</div>
@endsection
