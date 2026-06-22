@php $project = $project ?? null; @endphp

<div>
    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
    <input id="name" name="name" type="text" value="{{ old('name', $project?->name) }}" required
           class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
</div>

<div>
    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
    <textarea id="description" name="description" rows="4"
              class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">{{ old('description', $project?->description) }}</textarea>
</div>

<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start date</label>
        <input id="start_date" name="start_date" type="date"
               value="{{ old('start_date', $project?->start_date?->format('Y-m-d')) }}"
               class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label for="deadline" class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
        <input id="deadline" name="deadline" type="date"
               value="{{ old('deadline', $project?->deadline?->format('Y-m-d')) }}"
               class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500">
    </div>
</div>
