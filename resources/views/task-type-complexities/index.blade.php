@extends('layouts.master')

@section('styles')
    <!-- Data table css -->
    <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap5.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/datatable/responsive.bootstrap5.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Task Type Complexities</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Task Type Complexities</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Task Type Complexities</h3>
                    <div class="card-options">
                        <a href="{{ route('task-type-complexities.create') }}" class="btn btn-primary btn-sm">
                            <i class="fe fe-plus"></i> Add Task Type Complexity
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
                        <table id="task-type-complexities-table" class="table table-bordered text-nowrap border-bottom">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0">Task Group</th>
                                    <th class="border-bottom-0">Task Type</th>
                                    <th class="border-bottom-0">Complexity Level</th>
                                    <th class="border-bottom-0">Description</th>
                                    <th class="border-bottom-0">Allocated Minutes</th>
                                    <th class="border-bottom-0">Status</th>
                                    <th class="border-bottom-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($complexities as $complexity)
                                    <tr>
                                        <td>
                                            @if($complexity->taskGroup)
                                                <a href="{{ route('task-groups.show', $complexity->taskGroup) }}">
                                                    {{ $complexity->taskGroup->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">No group assigned</span>
                                            @endif
                                        </td>
                                        <td>{{ $complexity->taskType->name }}</td>
                                        <td>{{ $complexity->complexityLevel->name }}</td>
                                        <td>{{ $complexity->description ?? 'No description' }}</td>
                                        <td>{{ $complexity->allocated_minutes }}</td>
                                        <td>
                                            @if($complexity->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('task-type-complexities.edit', $complexity) }}" class="btn btn-primary btn-sm">
                                                <i class="fe fe-edit-2"></i> Edit
                                            </a>
                                            <form action="{{ route('task-type-complexities.destroy', $complexity) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this task type complexity?')">
                                                    <i class="fe fe-trash-2"></i> Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $complexities->links() }}
                    </div>
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
            $('#task-type-complexities-table').DataTable({
                responsive: true,
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                },
                pageLength: 10,
                order: [[3, 'desc']],
            });
        });
    </script>
@endsection 