@extends('layouts.admin.app')
@section('title', 'View Query')

@section('content')
<div class="app-content main-content">
    <div class="side-app">
        <div class="page-header d-lg-flex d-block">
            <div class="page-leftheader">
                <h4 class="page-title">View Query</h4>
            </div>
            <div class="page-rightheader">
                <a href="{{ route('custom-queries.index') }}" class="btn btn-secondary">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <div class="row">
                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Name</label>
                                <div class="form-control bg-light shadow-none">
                                    {{ $query->name }}
                                </div>
                            </div>
                             
                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Email</label>
                                <div class="form-control bg-light shadow-none">
                                    {{ $query->email }}
                                </div>
                            </div>
                             
                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Board</label>
                                <div class="form-control bg-light shadow-none">
                                    {{ $query->board ?: 'Not specified' }}
                                </div>
                            </div>
                             
                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Level</label>
                                <div class="form-control bg-light shadow-none">
                                    {{ $query->level ?: 'Not specified' }}
                                </div>
                            </div>
                             
                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Subject</label>
                                <div class="form-control bg-light shadow-none">
                                    {{ $query->subject ?: 'Not specified' }}
                                </div>
                            </div>
                             
                            <div class="mb-3 col-lg-6">
                                <label class="form-label">Date Submitted</label>
                                <div class="form-control bg-light shadow-none">
                                    {{ $query->created_at->format('d-m-Y H:i:s') }}
                                </div>
                            </div>
                            
                            <div class="mb-3 col-lg-12">
                                <label class="form-label">Message</label>
                                <div class="form-control bg-light shadow-none" style="height: auto; min-height: 200px;">
                                    {!! nl2br(e($query->message)) !!}
                                </div>
                            </div>
                        </div>
                            
                        <div class="modal-footer">
                            <a href="{{ route('custom-queries.index') }}" class="btn btn-secondary">Close</a>
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
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
</script>
@endpush