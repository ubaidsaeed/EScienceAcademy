@extends('layouts.admin.app')
@section('title', 'Scholarship Requests')

@section('content')
    <div class="app-content main-content">
        <div class="side-app">
            <div class="page-header d-lg-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">
                        @if ($page_type == 'create')
                            Create Scholarship Request
                        @else
                            Edit Scholarship Request
                        @endif
                    </h4>
                </div>
                <div class="page-rightheader">
                    <a href="{{ route('admin.scholarship.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form
                                action="{{ $page_type == 'create' ? route('admin.scholarship.store') : route('admin.scholarship.update', $scholarship->id) }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                @if ($page_type == 'edit')
                                    @method('PUT')
                                    <input type="hidden" name="id" value="{{ $scholarship->id }}">
                                @endif

                                <div class="row">
                                    <div class="mb-3 col-lg-6">
                                        <label for="name" class="form-label">Full Name *</label>
                                        <input type="text"
                                            class="form-control shadow-none @error('name') is-invalid @enderror"
                                            id="name" name="name"
                                            value="{{ old('name', $scholarship->name ?? '') }}"
                                            {{ $page_type == 'show' ? 'readonly' : '' }}>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-lg-6">
                                        <label for="father_name" class="form-label">Father Name *</label>
                                        <input type="text"
                                            class="form-control shadow-none @error('father_name') is-invalid @enderror"
                                            id="father_name" name="father_name"
                                            value="{{ old('father_name', $scholarship->father_name ?? '') }}"
                                            {{ $page_type == 'show' ? 'readonly' : '' }}>
                                        @error('father_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-lg-6">
                                        <label for="contact_no" class="form-label">Contact No *</label>
                                        <input type="text"
                                            class="form-control shadow-none @error('contact_no') is-invalid @enderror"
                                            id="contact_no" name="contact_no"
                                            value="{{ old('contact_no', $scholarship->contact_no ?? '') }}"
                                            {{ $page_type == 'show' ? 'readonly' : '' }}>
                                        @error('contact_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3 col-lg-6">
                                        <label for="email" class="form-label">Email *</label>
                                        <input type="email"
                                            class="form-control shadow-none @error('email') is-invalid @enderror"
                                            id="email" name="email"
                                            value="{{ old('email', $scholarship->email ?? '') }}"
                                            {{ $page_type == 'show' ? 'readonly' : '' }}>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- File Uploads -->
                                    <div class="mb-3 col-lg-3">
                                        <label for="parents_id_card" class="form-label">
                                            Parents ID Card *
                                            @if ($page_type == 'edit' && $scholarship->parents_id_card)
                                                <br><small class="text-success">File uploaded</small>
                                            @endif
                                        </label>
                                        @if ($page_type == 'show' && $scholarship->parents_id_card)
                                            <a href="{{ Storage::url($scholarship->parents_id_card) }}" target="_blank"
                                                class="btn btn-primary shadow-none">Download</a>
                                        @else
                                            <input type="file"
                                                class="form-control @error('parents_id_card') is-invalid @enderror"
                                                id="parents_id_card" name="parents_id_card">
                                            @error('parents_id_card')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="mb-3 col-lg-3">
                                        <label for="electricity_bills" class="form-label">
                                            Electricity Bills *
                                            @if ($page_type == 'edit' && $scholarship->electricity_bills)
                                                <br><small class="text-success">File uploaded</small>
                                            @endif
                                        </label>
                                        @if ($page_type == 'show' && $scholarship->electricity_bills)
                                            <a href="{{ Storage::url($scholarship->electricity_bills) }}" target="_blank"
                                                class="btn btn-primary shadow-none">Download</a>
                                        @else
                                            <input type="file"
                                                class="form-control @error('electricity_bills') is-invalid @enderror"
                                                id="electricity_bills" name="electricity_bills">
                                            @error('electricity_bills')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="mb-3 col-lg-3">
                                        <label for="academic_transcripts" class="form-label">
                                            Academic Transcripts *
                                            @if ($page_type == 'edit' && $scholarship->academic_transcripts)
                                                <br><small class="text-success">File uploaded</small>
                                            @endif
                                        </label>
                                        @if ($page_type == 'show' && $scholarship->academic_transcripts)
                                            <a href="{{ Storage::url($scholarship->academic_transcripts) }}"
                                                target="_blank" class="btn btn-primary shadow-none">Download</a>
                                        @else
                                            <input type="file"
                                                class="form-control @error('academic_transcripts') is-invalid @enderror"
                                                id="academic_transcripts" name="academic_transcripts">
                                            @error('academic_transcripts')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <div class="mb-3 col-lg-3">
                                        <label for="parental_bank_certificate" class="form-label">
                                            Parental Bank Certificate *
                                            @if ($page_type == 'edit' && $scholarship->parental_bank_certificate)
                                                <br><small class="text-success">File uploaded</small>
                                            @endif
                                        </label>
                                        @if ($page_type == 'show' && $scholarship->parental_bank_certificate)
                                            <a href="{{ Storage::url($scholarship->parental_bank_certificate) }}"
                                                target="_blank" class="btn btn-primary shadow-none">Download</a>
                                        @else
                                            <input type="file"
                                                class="form-control @error('parental_bank_certificate') is-invalid @enderror"
                                                id="parental_bank_certificate" name="parental_bank_certificate">
                                            @error('parental_bank_certificate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        @endif
                                    </div>

                                    <!-- Achievements -->
                                    <div class="mb-3 col-lg-12">
                                        <label class="form-label">Achievements</label>
                                        @if ($page_type == 'show')
                                            @foreach ($achievements as $key => $achievement)
                                                <div class="mb-2">
                                                    <a href="{{ Storage::url($achievement->achievement) }}"
                                                        target="_blank" class="btn btn-outline-primary">
                                                        Download Achievement {{ $key + 1 }}
                                                    </a>
                                                </div>
                                            @endforeach
                                        @else
                                            <div id="achievements-container">
                                                <div class="input-group mb-2">
                                                    <input type="file" name="achievements[]" class="form-control">
                                                    <button type="button" class="btn btn-success add-achievement">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <small class="text-muted">Add multiple achievement documents</small>
                                        @endif
                                    </div>

                                    <div class="mb-3 col-lg-12">
                                        <label for="message" class="form-label">Message</label>
                                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4"
                                            {{ $page_type == 'show' ? 'readonly' : '' }}>{{ old('message', $scholarship->message ?? '') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                @if ($page_type != 'show')
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary shadow-none">
                                            @if ($page_type == 'create')
                                                <i class="fa fa-save"></i> Save
                                            @else
                                                <i class="fa fa-sync"></i> Update
                                            @endif
                                        </button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
                    // Add more achievement fields
                    $(document).on('click', '.add-achievement', function() {
                        const container = $('#achievements-container');
                        const count = container.children().length + 1;

                        const html = `
        <div class="input-group mb-2">
            <input type="file" name="achievements[]" class="form-control">
            <button type="button" class="btn btn-danger remove-achievement">
                <i class="fa fa-minus"></i>
            </button>
        </div>`;

                        container.append(html);
                    });

                    // Remove achievement field
                    $(document).on('click', '.remove-achievement', function() {
                        $(this).closest('.input-group').remove();
                    });

                    // Delete confirmation
                });
    </script>
    
 @if (session()->has('success'))
        <script>
            setTimeout(() => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    },
                });
                Toast.fire({
                    icon: "success",
                    title: '{{ session()->get('success') }}',
                });
            }, 3000);
        </script>
    @endif
     @if (session()->has('error'))
        <script>
            setTimeout(() => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: "top-end",
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    },
                });
                Toast.fire({
                    icon: "error",
                    title: '{{ session()->get('error') }}',
                });
            }, 3000);
        </script>
    @endif
@endpush
