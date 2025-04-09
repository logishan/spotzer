@extends('layouts.master')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Attendance Details</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Attendance</a></li>
                <li class="breadcrumb-item active" aria-current="page">Attendance Details</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Attendance Record #{{ $attendance->id }}</h3>
                    <div class="card-options">
                        <a href="{{ route('attendances.index') }}" class="btn btn-primary btn-sm">
                            <i class="fe fe-list"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="200">Employee</th>
                                    <td>{{ $attendance->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Date</th>
                                    <td>{{ $attendance->date->format('Y-m-d') }}</td>
                                </tr>
                                <tr>
                                    <th>Time In</th>
                                    <td>{{ $attendance->time_in ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Time Out</th>
                                    <td>{{ $attendance->time_out ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Hours Worked</th>
                                    <td>{{ $attendance->hours_worked }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($attendance->status == 'present')
                                            <span class="badge bg-success">Present</span>
                                        @elseif($attendance->status == 'absent')
                                            <span class="badge bg-danger">Absent</span>
                                        @elseif($attendance->status == 'late')
                                            <span class="badge bg-warning">Late</span>
                                        @elseif($attendance->status == 'half_day')
                                            <span class="badge bg-info">Half Day</span>
                                        @elseif($attendance->status == 'leave')
                                            <span class="badge bg-secondary">Leave</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($attendance->notes)
                                <tr>
                                    <th>Notes</th>
                                    <td>{{ $attendance->notes }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $attendance->created_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At</th>
                                    <td>{{ $attendance->updated_at->format('Y-m-d H:i:s') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-primary">
                            <i class="fe fe-edit"></i> Edit
                        </a>
                        <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                                <i class="fe fe-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 