@extends('layouts.master')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Edit Complexity Level</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('task-complexity.index') }}">Task Complexity</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Complexity Level</h3>
                    <div class="card-options">
                        <a href="{{ route('task-complexity.index') }}" class="btn btn-primary btn-sm">
                            <i class="fe fe-list"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('task-complexity.update', $complexityLevel) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label class="form-label" for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $complexityLevel->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label" for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $complexityLevel->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">Update Complexity Level</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($complexityLevel->taskTypeComplexities->isNotEmpty())
        <div class="row mt-4">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Associated Task Types</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Task Type</th>
                                        <th>Allocated Minutes</th>
                                        <th>Effective From</th>
                                        <th>Effective To</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($complexityLevel->taskTypeComplexities as $complexity)
                                        <tr>
                                            <td>{{ $complexity->taskType->name }}</td>
                                            <td>{{ $complexity->allocated_minutes }}</td>
                                            <td>{{ $complexity->effective_from->format('Y-m-d') }}</td>
                                            <td>{{ $complexity->effective_to ? $complexity->effective_to->format('Y-m-d') : 'Ongoing' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection 