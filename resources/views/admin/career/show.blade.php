@extends('layouts.admin.app')

@section('title', 'Career Application Details')

@section('content')
<div class="app-content main-content">
    <div class="side-app">
    <div class="page-header d-lg-flex d-block">
        <div class="page-leftheader">
            <h4 class="page-title">Career Application Details</h4>
        </div>
        <div class="page-rightheader">
            <div class="btn-list">
                <a href="{{ route('careers.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control shadow-none" 
                                   value="{{ $career->name }}" readonly>
                        </div>
                        
                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control shadow-none" 
                                   value="{{ $career->email }}" readonly>
                        </div>
                        
                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Position Applied For</label>
                            <input type="text" class="form-control shadow-none" 
                                   value="{{ $career->position }}" readonly>
                        </div>
                        
                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Contact Number</label>
                            <input type="text" class="form-control shadow-none" 
                                   value="{{ $career->contact }}" readonly>
                        </div>
                        
                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Resume</label>
                            <div class="d-flex align-items-center">
                                @if($career->resume)
                                    <a href="{{ route('careers.download', $career->id) }}" 
                                       class="btn btn-primary shadow-none me-2" target="_blank">
                                        <i class="fa fa-download"></i> Download Resume
                                    </a>
                                    <span class="text-muted">
                                        <small>{{ $career->resume }}</small>
                                    </span>
                                @else
                                    <span class="text-muted">No resume uploaded</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mb-3 col-lg-6">
                            <label class="form-label">Applied Date</label>
                            <input type="text" class="form-control shadow-none" 
                                   value="{{ $career->created_at->format('Y-m-d H:i:s') }}" readonly>
                        </div>
                        
                        <div class="mb-3 col-12">
                            <label class="form-label">Cover Letter</label>
                            <div class="card">
                                <div class="card-body bg-light">
                                    {!! nl2br(e($career->cover_letter)) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <a href="{{ route('careers.index') }}" class="btn btn-secondary">Close</a>
                        <a href="{{ route('careers.download', $career->id) }}" 
                           class="btn btn-primary" target="_blank">
                            <i class="fa fa-download"></i> Download Resume
                        </a>
                    </div>
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
        // Format cover letter text
        const coverLetter = document.getElementById('cover-letter-content');
        if (coverLetter) {
            coverLetter.innerHTML = coverLetter.textContent.replace(/\n/g, '<br>');
        }
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