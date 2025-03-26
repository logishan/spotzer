@extends('layouts.master')

@section('styles')
    <style>
        .attendance-sheet {
            overflow-x: auto;
        }
        .attendance-sheet table {
            min-width: 100%;
        }
        .attendance-sheet th, .attendance-sheet td {
            text-align: center;
            vertical-align: middle;
            padding: 8px;
            font-size: 0.9rem;
        }
        .day-header {
            width: 38px;
            min-width: 38px;
        }
        .present {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .absent {
            background-color: #f8d7da;
            color: #842029;
        }
        .late {
            background-color: #fff3cd;
            color: #664d03;
        }
        .half_day {
            background-color: #cff4fc;
            color: #055160;
        }
        .leave {
            background-color: #e2e3e5;
            color: #41464b;
        }
        .weekend {
            background-color: #f2f2f2;
        }
        .today {
            font-weight: bold;
            border: 2px solid #0d6efd;
        }
    </style>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Attendance Sheet</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('attendances.index') }}">Attendance</a></li>
                <li class="breadcrumb-item active" aria-current="page">Attendance Sheet</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Monthly Attendance Sheet - {{ \Carbon\Carbon::parse($month)->format('F Y') }}</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('attendances.sheet') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="month" class="form-label">Month</label>
                                    <input type="month" class="form-control" id="month" name="month" value="{{ $month }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="department_id" class="form-label">Department</label>
                                    <select class="form-control" id="department_id" name="department_id">
                                        <option value="">All Departments</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ $departmentId == $department->id ? 'selected' : '' }}>
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="attendance-sheet">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Shift</th>
                                    @for($day = 1; $day <= $daysInMonth; $day++)
                                        @php
                                            $currentDate = \Carbon\Carbon::parse($month . '-' . $day);
                                            $isWeekend = $currentDate->isWeekend();
                                            $isToday = $currentDate->isToday();
                                            $dayClass = $isWeekend ? 'weekend' : '';
                                            $dayClass .= $isToday ? ' today' : '';
                                        @endphp
                                        <th class="day-header {{ $dayClass }}">{{ $day }}<br><small>{{ $currentDate->format('D') }}</small></th>
                                    @endfor
                                    <th>Present</th>
                                    <th>Absent</th>
                                    <th>Late</th>
                                    <th>Half Day</th>
                                    <th>Leave</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    @php
                                        $presentCount = 0;
                                        $absentCount = 0;
                                        $lateCount = 0;
                                        $halfDayCount = 0;
                                        $leaveCount = 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->shiftSchedule ? $user->shiftSchedule->name : '-' }}</td>
                                        @for($day = 1; $day <= $daysInMonth; $day++)
                                            @php
                                                $date = $startDate->copy()->addDays($day - 1)->format('Y-m-d');
                                                $attendance = $user->attendances->where('date', $date)->first();
                                                $currentDate = \Carbon\Carbon::parse($date);
                                                $isWeekend = $currentDate->isWeekend();
                                                $isToday = $currentDate->isToday();
                                                $cellClass = $isWeekend ? 'weekend' : '';
                                                $cellClass .= $isToday ? ' today' : '';
                                                
                                                if ($attendance) {
                                                    $cellClass .= ' ' . $attendance->status;
                                                    
                                                    if ($attendance->status == 'present') $presentCount++;
                                                    elseif ($attendance->status == 'absent') $absentCount++;
                                                    elseif ($attendance->status == 'late') $lateCount++;
                                                    elseif ($attendance->status == 'half_day') $halfDayCount++;
                                                    elseif ($attendance->status == 'leave') $leaveCount++;
                                                }
                                            @endphp
                                            <td class="{{ $cellClass }}">
                                                @if($attendance)
                                                    @if($attendance->time_in)
                                                        <div>{{ \Carbon\Carbon::parse($attendance->time_in)->format('H:i') }}</div>
                                                    @endif
                                                    @if($attendance->time_out)
                                                        <div>{{ \Carbon\Carbon::parse($attendance->time_out)->format('H:i') }}</div>
                                                    @endif
                                                @endif
                                            </td>
                                        @endfor
                                        <td>{{ $presentCount }}</td>
                                        <td>{{ $absentCount }}</td>
                                        <td>{{ $lateCount }}</td>
                                        <td>{{ $halfDayCount }}</td>
                                        <td>{{ $leaveCount }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 