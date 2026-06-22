@if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3">
        <p class="text-sm font-medium text-red-800 mb-1">Please fix the following:</p>
        <ul class="text-sm text-red-700 list-disc list-inside space-y-0.5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
