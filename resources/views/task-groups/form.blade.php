@props(['taskGroup' => null, 'departments', 'users'])

<form action="{{ $taskGroup ? route('task-groups.update', $taskGroup) : route('task-groups.store') }}" method="POST" class="space-y-6">
    @csrf
    @if($taskGroup)
        @method('PUT')
    @endif

    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" value="{{ old('name', $taskGroup?->name) }}" required>
        @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('description', $taskGroup?->description) }}</textarea>
        @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="departments" class="block text-sm font-medium text-gray-700">Departments</label>
        <select name="departments[]" id="departments" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" multiple required>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ in_array($department->id, old('departments', $taskGroup?->departments->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
        @error('departments')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="users" class="block text-sm font-medium text-gray-700">Users</label>
        <select name="users[]" id="users" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" multiple required>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ in_array($user->id, old('users', $taskGroup?->users->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
            @endforeach
        </select>
        @error('users')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-start">
        <div class="flex items-center h-5">
            <input type="checkbox" name="is_active" id="is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="1" {{ old('is_active', $taskGroup?->is_active ?? true) ? 'checked' : '' }}>
        </div>
        <div class="ml-3 text-sm">
            <label for="is_active" class="font-medium text-gray-700">Active</label>
            <p class="text-gray-500">Inactive task groups won't be available for new tasks</p>
        </div>
    </div>

    <div class="flex justify-end space-x-3">
        <a href="{{ route('task-groups.index') }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Cancel</a>
        <button type="submit" class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
            {{ $taskGroup ? 'Update' : 'Create' }} Task Group
        </button>
    </div>
</form>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize multiple select fields with Select2
        $('#departments').select2({
            placeholder: 'Select departments',
            allowClear: true
        });
        
        $('#users').select2({
            placeholder: 'Select users',
            allowClear: true
        });
    });
</script>
@endpush 