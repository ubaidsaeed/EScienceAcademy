@extends('layouts.admin.app')
@section('title', 'Thank You')

@section('content')
<div class="app-content main-content">
    <div class="side-app">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body text-center p-5">
                            <div class="mb-4">
                                <i class="fas fa-heart" style="font-size: 80px; color: #0072ff;"></i>
                            </div>
                            
                            <h1 class="text-primary mb-3">Thank You!</h1>
                            <p class="lead mb-4">Your subscription has been activated successfully.</p>
                            
                            @if($subscription)
                            <div class="alert alert-success text-start mb-4">
                                <h5 class="mb-3"><i class="fas fa-check-circle me-2"></i>Subscription Activated</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Plan:</strong> {{ $subscription->plan_name }}</p>
                                        <p><strong>Duration:</strong> {{ $subscription->duration_months }} months</p>
                                        <p><strong>Access Until:</strong> {{ \Carbon\Carbon::parse($subscription->end_date)->format('d M, Y') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                                        <p><strong>Board:</strong> {{ $subscription->board_name }}</p>
                                        <p><strong>Level:</strong> {{ $subscription->level_name }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-center mt-4">
                                <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-rocket me-2"></i>Start Learning
                                </a>
                                <a href="#" class="btn btn-outline-primary btn-lg px-5">
                                    <i class="fas fa-book-open me-2"></i>Browse Courses
                                </a>
                            </div>
                            
                            <div class="mt-5 pt-4 border-top">
                                <h5 class="mb-3">What's Next?</h5>
                                <div class="row text-start">
                                    <div class="col-md-4 mb-3">
                                        <div class="p-3 border rounded">
                                            <i class="fas fa-video text-primary mb-2"></i>
                                            <h6>Access Video Lectures</h6>
                                            <p class="small mb-0">Start watching comprehensive video lessons.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="p-3 border rounded">
                                            <i class="fas fa-file-pdf text-primary mb-2"></i>
                                            <h6>Download Notes</h6>
                                            <p class="small mb-0">Get access to detailed study materials.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="p-3 border rounded">
                                            <i class="fas fa-clipboard-check text-primary mb-2"></i>
                                            <h6>Take Practice Tests</h6>
                                            <p class="small mb-0">Test your knowledge with quizzes and exams.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection