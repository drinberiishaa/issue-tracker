@extends('layouts.app')

@section('title', 'New Project')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <h1 class="text-2xl font-bold mb-6">New Project</h1>

    @include('partials.errors')

    <form method="POST" action="{{ route('projects.store') }}" class="space-y-4">
        @csrf
        @include('projects.partials.form')
        <button type="submit" class="bg-brand-600 text-white px-4 py-2 rounded-md font-medium hover:bg-brand-700">
            Create Project
        </button>
    </form>
</div>
@endsection
