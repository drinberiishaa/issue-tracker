@extends('layouts.app')

@section('title', 'New Project')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-400 hover:text-brand-600 transition-colors font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to projects
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-card border border-gray-200 p-5 sm:p-8">
        <div class="flex items-center gap-3 mb-6">
            <span class="flex items-center justify-center w-10 h-10 rounded-xl bg-brand-50 text-brand-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </span>
            <h1 class="text-xl font-bold text-gray-900">New Project</h1>
        </div>

        @include('partials.errors')

        <form method="POST" action="{{ route('projects.store') }}" class="space-y-5">
            @csrf
            @include('projects.partials.form')
            <div class="pt-2">
                <button type="submit" class="btn-primary w-full justify-center py-2.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Create Project
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
