@extends('layouts.master')

@section('styles')
    <!-- DATERANGEPICKER CSS -->
    <link href="{{ asset('build/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.css') }}" rel="stylesheet"/>
    <!-- SELECT2 CSS -->
    <link href="{{ asset('build/assets/plugins/select2/select2.min.css') }}" rel="stylesheet"/>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Attendance</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Attendance</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Attendance Records</h3>
                    <div class="card-options">
                        <a href="{{ route('attendances.create') }}" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Add Attendance
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('attendances.index') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="start_date" class="form-label">Start Date</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $startDate }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="end_date" class="form-label">End Date</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $endDate }}">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="user_id" class="form-label">Employee</label>
                                    <select class="form-control select2" id="user_id" name="user_id">
                                        <option value="">All Employees</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ $userId == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-control" id="status" name="status">
                                        <option value="">All Status</option>
                                        <option value="present" {{ $status == 'present' ? 'selected' : '' }}>Present</option>
                                        <option value="absent" {{ $status == 'absent' ? 'selected' : '' }}>Absent</option>
                                        <option value="late" {{ $status == 'late' ? 'selected' : '' }}>Late</option>
                                        <option value="half_day" {{ $status == 'half_day' ? 'selected' : '' }}>Half Day</option>
                                        <option value="leave" {{ $status == 'leave' ? 'selected' : '' }}>Leave</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Filter</button>
                                <a href="{{ route('attendances.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table id="attendances-table" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th>Employee</th>
                                    <th>Date</th>
                                    <th>Time In</th>
                                    <th>Time Out</th>
                                    <th>Hours</th>
                                    <th>Status</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($attendances as $attendance)
                                    <tr>
                                        <td>{{ $attendance->id }}</td>
                                        <td>{{ $attendance->user->name }}</td>
                                        <td>{{ $attendance->date->format('Y-m-d') }}</td>
                                        <td>{{ $attendance->time_in ?? '-' }}</td>
                                        <td>{{ $attendance->time_out ?? '-' }}</td>
                                        <td>{{ $attendance->hours_worked }}</td>
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
                                        <td>
                                            <a href="{{ route('attendances.edit', $attendance) }}" class="btn btn-sm btn-primary">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <form action="{{ route('attendances.destroy', $attendance) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this record?')">
                                                    <i class="fe fe-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $attendances->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- SELECT2 JS -->
    <script src="{{ asset('build/assets/plugins/select2/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Select an option',
                width: '100%'
            });
        });
    </script>
@endsection 