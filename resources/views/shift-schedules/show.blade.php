@extends('layouts.master')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Shift Schedule Details</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shift-schedules.index') }}">Shift Schedules</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shift Schedule Details</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Shift Schedule: {{ $shiftSchedule->name }}</h3>
                    <div class="card-options">
                        <a href="{{ route('shift-schedules.edit', $shiftSchedule) }}" class="btn btn-primary btn-sm me-2">
                            <i class="fe fe-edit"></i> Edit
                        </a>
                        <a href="{{ route('shift-schedules.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fe fe-list"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-3">Schedule Information</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th width="150">ID</th>
                                            <td>{{ $shiftSchedule->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Name</th>
                                            <td>{{ $shiftSchedule->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Time</th>
                                            <td>{{ $shiftSchedule->start_time->format('H:i') }} - {{ $shiftSchedule->end_time->format('H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Working Days</th>
                                            <td>{{ $shiftSchedule->formatted_days }}</td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $shiftSchedule->description ?? 'No description provided' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($shiftSchedule->is_active)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created</th>
                                            <td>{{ $shiftSchedule->created_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Last Updated</th>
                                            <td>{{ $shiftSchedule->updated_at->format('Y-m-d H:i:s') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="mb-3">Assigned Employees</h4>
                            <div class="table-responsive">
                                @if($users->count() > 0)
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Department</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($users as $user)
                                                <tr>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>{{ $user->department ? $user->department->name : '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <div class="alert alert-info">
                                        No employees are currently assigned to this shift schedule.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <form action="{{ route('shift-schedules.destroy', $shiftSchedule) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this shift schedule? This cannot be undone if users are assigned to it.')">
                                <i class="fe fe-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 