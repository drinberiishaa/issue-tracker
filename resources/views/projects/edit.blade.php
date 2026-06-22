@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
<div class="max-w-xl mx-auto bg-white rounded-xl shadow-sm border border-gray-200 p-8">
    <h1 class="text-2xl font-bold mb-6">Edit Project</h1>

    @include('partials.errors')

    <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-4">
        @csrf
        @method('PUT')
        @include('projects.partials.form', ['project' => $project])
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-brand-600 text-white px-4 py-2 rounded-md font-medium hover:bg-brand-700">
                Save Changes
            </button>
            <a href="{{ route('projects.show', $project) }}" class="text-sm text-gray-500 hover:text-gray-700">Cancel</a>
        </div>
    </form>

    <form method="POST" action="{{ route('projects.destroy', $project) }}"
          onsubmit="return confirm('Delete this project and all its issues? This cannot be undone.');"
          class="mt-6 pt-6 border-t border-gray-100">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-sm text-red-600 hover:text-red-700 font-medium">
            Delete this project
        </button>
    </form>
</div>
@endsection
