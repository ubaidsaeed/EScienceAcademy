@extends('layouts.admin.app')

@section('content')
    <div class="app-content main-content">
        <div class="side-app">
            <div class="container-fluid">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Edit Package: {{ $package->name }}</h1>
                    <div>
                        <a href="{{ route('admin.packages.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Packages
                        </a>
                        <a href="{{ route('admin.packages.subjects', $package->id) }}" class="btn btn-info">
                            <i class="fas fa-book"></i> Manage Subjects
                        </a>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Package Details</h6>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.packages.update', $package->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Package Name *</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', $package->name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="board_id">Board *</label>
                                        <select name="board_id" id="board_id" class="form-control" required>
                                            <option value="">Select Board</option>
                                            @foreach ($boards as $board)
                                                <option value="{{ $board->id }}"
                                                    {{ $package->board_id == $board->id ? 'selected' : '' }}>
                                                    {{ $board->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="level_id">Level *</label>
                                        <select name="level_id" id="level_id" class="form-control" required>
                                            <option value="">Select Level</option>
                                            @foreach ($levels as $level)
                                                <option value="{{ $level->id }}"
                                                    {{ $package->level_id == $level->id ? 'selected' : '' }}>
                                                    {{ $level->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price">Package Price (₹) *</label>
                                        <input type="number" class="form-control" id="price" name="price"
                                            value="{{ old('price', $package->price) }}" step="0.01" min="0"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="duration">Duration *</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" id="duration" name="duration"
                                                value="{{ old('duration', $package->duration) }}" min="1" required>
                                            <select name="duration_type" class="form-control" required>
                                                <option value="days"
                                                    {{ $package->duration_type == 'days' ? 'selected' : '' }}>Days</option>
                                                <option value="months"
                                                    {{ $package->duration_type == 'months' ? 'selected' : '' }}>Months
                                                </option>
                                                <option value="years"
                                                    {{ $package->duration_type == 'years' ? 'selected' : '' }}>Years
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="student_limit">Student Limit (0 for unlimited)</label>
                                        <input type="number" class="form-control" id="student_limit" name="student_limit"
                                            value="{{ old('student_limit', $package->student_limit) }}" min="0">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="total_chapters">Total Chapters</label>
                                        <input type="number" class="form-control" id="total_chapters" name="total_chapters"
                                            value="{{ old('total_chapters', $package->total_chapters) }}" min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Features Included</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="online_notes"
                                                name="online_notes" value="1"
                                                {{ $package->online_notes ? 'checked' : '' }}>
                                            <label class="form-check-label" for="online_notes">Online Notes</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="top_past_paper"
                                                name="top_past_paper" value="1"
                                                {{ $package->top_past_paper ? 'checked' : '' }}>
                                            <label class="form-check-label" for="top_past_paper">Top Past Papers</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="ws_aw_bg"
                                                name="ws_aw_bg" value="1" {{ $package->ws_aw_bg ? 'checked' : '' }}>
                                            <label class="form-check-label" for="ws_aw_bg">Worksheets & Answers</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="recorded"
                                                name="recorded" value="1" {{ $package->recorded ? 'checked' : '' }}>
                                            <label class="form-check-label" for="recorded">Recorded Lectures</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="content">Package Description</label>
                                <textarea class="form-control" id="content" name="content" rows="3">{{ old('content', $package->content) }}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="status" name="status"
                                        value="1" {{ $package->status ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">Active</label>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Update Package</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
