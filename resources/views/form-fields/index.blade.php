@extends('layouts.master')

@section('title', 'Form Fields Management')

@section('content')
    <div class="main-container container-fluid">
        <div class="page-header">
            <h1 class="page-title">Form Fields Management</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Form Fields</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Select Task Group</h3>
                    </div>
                    <div class="card-body">
                        <form method="GET" action="{{ route('form-fields.index') }}">
                            <div class="row mb-4">
                                <div class="col-lg-6">
                                    <select name="task_group_id" class="form-control" onchange="this.form.submit()">
                                        <option value="">Select Task Group</option>
                                        @foreach ($taskGroups as $group)
                                            <option value="{{ $group->id }}" {{ $selectedTaskGroup && $selectedTaskGroup->id == $group->id ? 'selected' : '' }}>
                                                {{ $group->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($selectedTaskGroup)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Form Fields for {{ $selectedTaskGroup->name }}</h3>
                            <div class="card-options">
                                <a href="{{ route('form-fields.create', ['task_group_id' => $selectedTaskGroup->id]) }}" class="btn btn-primary btn-sm">
                                    <i class="fe fe-plus"></i> Add New Field
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Label</th>
                                            <th>Type</th>
                                            <th>Required</th>
                                            <th>Display Order</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($formFields as $field)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $field->name }}</td>
                                                <td>{{ $field->label }}</td>
                                                <td>{{ $field->type }}</td>
                                                <td>
                                                    <span class="badge {{ $field->required ? 'bg-success' : 'bg-warning' }}">
                                                        {{ $field->required ? 'Yes' : 'No' }}
                                                    </span>
                                                </td>
                                                <td>{{ $field->display_order }}</td>
                                                <td>
                                                    <span class="badge {{ $field->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $field->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('form-fields.edit', $field->id) }}" class="btn btn-sm btn-info">
                                                        <i class="fe fe-edit"></i> Edit
                                                    </a>
                                                    
                                                    <form action="{{ route('form-fields.destroy', $field->id) }}" method="POST" class="d-inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                            <i class="fe fe-trash"></i> Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No form fields found for this task group</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
