@extends('layouts.master')

@section('styles')
    <!-- SELECT2 CSS -->
    <link href="{{ asset('build/assets/plugins/select2/select2.min.css') }}" rel="stylesheet"/>
@endsection

@section('content')
    <div class="page-header">
        <h1 class="page-title">Edit Shift Schedule</h1>
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('shift-schedules.index') }}">Shift Schedules</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Shift Schedule</li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Shift Schedule: {{ $shiftSchedule->name }}</h3>
                    <div class="card-options">
                        <a href="{{ route('shift-schedules.index') }}" class="btn btn-primary btn-sm">
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

                    <form action="{{ route('shift-schedules.update', $shiftSchedule) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $shiftSchedule->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('start_time') is-invalid @enderror" id="start_time" name="start_time" value="{{ old('start_time', $shiftSchedule->start_time->format('H:i')) }}" required>
                                    @error('start_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                    <input type="time" class="form-control @error('end_time') is-invalid @enderror" id="end_time" name="end_time" value="{{ old('end_time', $shiftSchedule->end_time->format('H:i')) }}" required>
                                    @error('end_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="days_of_week" class="form-label">Working Days</label>
                            <select class="form-control select2 @error('days_of_week') is-invalid @enderror" id="days_of_week" name="days_of_week[]" multiple>
                                <option value="0" {{ (old('days_of_week', $shiftSchedule->days_of_week) && in_array('0', old('days_of_week', $shiftSchedule->days_of_week))) ? 'selected' : '' }}>Sunday</option>
                                <option value="1" {{ (old('days_of_week', $shiftSchedule->days_of_week) && in_array('1', old('days_of_week', $shiftSchedule->days_of_week))) ? 'selected' : '' }}>Monday</option>
                                <option value="2" {{ (old('days_of_week', $shiftSchedule->days_of_week) && in_array('2', old('days_of_week', $shiftSchedule->days_of_week))) ? 'selected' : '' }}>Tuesday</option>
                                <option value="3" {{ (old('days_of_week', $shiftSchedule->days_of_week) && in_array('3', old('days_of_week', $shiftSchedule->days_of_week))) ? 'selected' : '' }}>Wednesday</option>
                                <option value="4" {{ (old('days_of_week', $shiftSchedule->days_of_week) && in_array('4', old('days_of_week', $shiftSchedule->days_of_week))) ? 'selected' : '' }}>Thursday</option>
                                <option value="5" {{ (old('days_of_week', $shiftSchedule->days_of_week) && in_array('5', old('days_of_week', $shiftSchedule->days_of_week))) ? 'selected' : '' }}>Friday</option>
                                <option value="6" {{ (old('days_of_week', $shiftSchedule->days_of_week) && in_array('6', old('days_of_week', $shiftSchedule->days_of_week))) ? 'selected' : '' }}>Saturday</option>
                            </select>
                            @error('days_of_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description', $shiftSchedule->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ old('is_active', $shiftSchedule->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>

                        <div class="form-footer">
                            <button type="submit" class="btn btn-primary">Update Shift Schedule</button>
                        </div>
                    </form>
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
                placeholder: 'Select days',
                width: '100%'
            });
        });
    </script>
@endsection 