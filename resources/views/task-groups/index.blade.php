@extends('layouts.master')

@section('styles')
    <!-- Data table css -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Task Groups</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Task Groups</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Task Groups</h3>
                <div class="card-options">
                    <a href="{{ route('task-groups.create') }}" class="btn btn-primary btn-sm">
                        <i class="fe fe-plus"></i> Add Task Group
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
                    <table id="task-groups-table" class="table table-bordered text-nowrap border-bottom">
                        <thead>
                            <tr>
                                <th class="border-bottom-0">Name</th>
                                <th class="border-bottom-0">Departments</th>
                                <th class="border-bottom-0">Status</th>
                                <th class="border-bottom-0">Tasks</th>
                                <th class="border-bottom-0">Created By</th>
                                <th class="border-bottom-0">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($taskGroups as $taskGroup)
                                <tr>
                                    <td>{{ $taskGroup->name }}</td>
                                    <td>{{ $taskGroup->departments->pluck('name')->join(', ') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $taskGroup->is_active ? 'success' : 'danger' }}">
                                            {{ $taskGroup->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $taskGroup->tasks->count() }}</span>
                                    </td>
                                    <td>{{ $taskGroup->creator->name }}</td>
                                    <td>
                                        <a href="{{ route('task-groups.show', $taskGroup) }}" class="btn btn-primary btn-sm">
                                            <i class="fe fe-eye"></i>
                                        </a>
                                        <a href="{{ route('task-groups.edit', $taskGroup) }}" class="btn btn-info btn-sm">
                                            <i class="fe fe-edit-2"></i>
                                        </a>
                                        <form action="{{ route('task-groups.destroy', $taskGroup) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this task group?')">
                                                <i class="fe fe-trash-2"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $taskGroups->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- DATA TABLE JS-->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/responsive.bootstrap5.min.js') }}"></script>

    <script>
        $(function() {
            $('#task-groups-table').DataTable({
                responsive: true,
                dom: 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>
@endsection 