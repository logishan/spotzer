@extends('layouts.master')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Shift Schedules</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Shift Schedules</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Shift Schedules</h3>
                    <div class="card-options">
                        <a href="{{ route('shift-schedules.create') }}" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Add Shift Schedule
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

                    <div class="table-responsive">
                        <table id="shift-schedules-table" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th width="5%">ID</th>
                                    <th>Name</th>
                                    <th>Time</th>
                                    <th>Days</th>
                                    <th>Status</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($schedules as $schedule)
                                    <tr>
                                        <td>{{ $schedule->id }}</td>
                                        <td>{{ $schedule->name }}</td>
                                        <td>{{ $schedule->start_time }} - {{ $schedule->end_time }}</td>
                                        <td>{{ $schedule->formatted_days }}</td>
                                        <td>
                                            @if($schedule->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('shift-schedules.show', $schedule) }}" class="btn btn-sm btn-info">
                                                <i class="fe fe-eye"></i>
                                            </a>
                                            <a href="{{ route('shift-schedules.edit', $schedule) }}" class="btn btn-sm btn-primary">
                                                <i class="fe fe-edit"></i>
                                            </a>
                                            <form action="{{ route('shift-schedules.destroy', $schedule) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this schedule?')">
                                                    <i class="fe fe-trash"></i>
                                                </button>
                                            </form>
                                        </td>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#shift-schedules-table').DataTable({
                responsive: true,
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                }
            });
        });
    </script>
@endsection 