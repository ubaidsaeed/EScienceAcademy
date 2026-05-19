@extends('layouts.admin.app')
@section('title', 'Scholarship Requests')

@section('content')

    <div class="app-content main-content">
        <div class="side-app">
            <div class="page-header d-lg-flex d-block">
                <div class="page-leftheader">
                    <h4 class="page-title">Scholarship Request Details</h4>
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
                            <div class="row">
                                <div class="mb-3 col-lg-6">
                                    <label class="form-label">Full Name</label>
                                    <p class="form-control-static">{{ $scholarship->name }}</p>
                                </div>
                                <div class="mb-3 col-lg-6">
                                    <label class="form-label">Father Name</label>
                                    <p class="form-control-static">{{ $scholarship->father_name }}</p>
                                </div>
                                <div class="mb-3 col-lg-6">
                                    <label class="form-label">Contact No</label>
                                    <p class="form-control-static">{{ $scholarship->contact_no }}</p>
                                </div>
                                <div class="mb-3 col-lg-6">
                                    <label class="form-label">Email</label>
                                    <p class="form-control-static">{{ $scholarship->email }}</p>
                                </div>

                                <!-- File Downloads -->
                                <div class="mb-3 col-lg-3">
                                    <label class="form-label">Parents ID Card</label><br>
                                    @if ($scholarship->parents_id_card)
                                        <a href="{{ Storage::url($scholarship->parents_id_card) }}" target="_blank"
                                            class="btn btn-primary shadow-none">Download</a>
                                    @else
                                        <span class="text-muted">No file uploaded</span>
                                    @endif
                                </div>

                                <div class="mb-3 col-lg-3">
                                    <label class="form-label">Electricity Bills</label><br>
                                    @if ($scholarship->electricity_bills)
                                        <a href="{{ Storage::url($scholarship->electricity_bills) }}" target="_blank"
                                            class="btn btn-primary shadow-none">Download</a>
                                    @else
                                        <span class="text-muted">No file uploaded</span>
                                    @endif
                                </div>

                                <div class="mb-3 col-lg-3">
                                    <label class="form-label">Academic Transcripts</label><br>
                                    @if ($scholarship->academic_transcripts)
                                        <a href="{{ Storage::url($scholarship->academic_transcripts) }}" target="_blank"
                                            class="btn btn-primary shadow-none">Download</a>
                                    @else
                                        <span class="text-muted">No file uploaded</span>
                                    @endif
                                </div>

                                <div class="mb-3 col-lg-3">
                                    <label class="form-label">Parental Bank Certificate</label><br>
                                    @if ($scholarship->parental_bank_certificate)
                                        <a href="{{ Storage::url($scholarship->parental_bank_certificate) }}"
                                            target="_blank" class="btn btn-primary shadow-none">Download</a>
                                    @else
                                        <span class="text-muted">No file uploaded</span>
                                    @endif
                                </div>

                                <!-- Achievements -->
                                @if ($achievements->count() > 0)
                                    <div class="mb-3 col-lg-12">
                                        <label class="form-label">Achievements</label>
                                        <div class="row">
                                            @foreach ($achievements as $key => $achievement)
                                                <div class="col-lg-3 mb-2">
                                                    <a href="{{ Storage::url($achievement->achievement) }}" target="_blank"
                                                        class="btn btn-outline-primary w-100">
                                                        Achievement {{ $key + 1 }}
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-3 col-lg-12">
                                    <label class="form-label">Message</label>
                                    <div class="border p-3 rounded bg-light">
                                        {{ $scholarship->message ?? 'No message provided' }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('admin.scholarship.edit', $scholarship->id) }}" class="btn btn-primary">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                                {{-- <button class="btn btn-danger delete-btn" data-id="{{ $scholarship->id }}">
                                    <i class="fa fa-trash"></i> Delete
                                </button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('build/assets/admin/js/custom/message.js') }}"></script>
    
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
