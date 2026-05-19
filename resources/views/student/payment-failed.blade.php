@extends('layouts.admin.app')
@section('title', 'Payment Failed')

@section('content')
<div class="app-content main-content">
    <div class="side-app">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-0 shadow-lg">
                        <div class="card-body text-center p-5">
                            <div class="mb-4">
                                <div class="error-icon">
                                    <i class="fas fa-times-circle" style="font-size: 80px; color: #dc3545;"></i>
                                </div>
                            </div>
                            
                            <h1 class="text-danger mb-3">Payment Failed</h1>
                            <p class="lead mb-4">We were unable to process your payment. Please try again.</p>
                            
                            @if(session('error'))
                            <div class="alert alert-danger text-start mb-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ session('error') }}
                            </div>
                            @endif
                            
                            <div class="alert alert-warning text-start mb-4">
                                <h5 class="mb-3"><i class="fas fa-lightbulb me-2"></i>Possible Reasons:</h5>
                                <ul class="mb-0">
                                    <li>Insufficient funds in your account</li>
                                    <li>Incorrect card details</li>
                                    <li>Card expiry date has passed</li>
                                    <li>Bank server is temporarily unavailable</li>
                                    <li>Daily transaction limit exceeded</li>
                                </ul>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-center mt-4">
                                <a href="{{ route('payment.method') }}" class="btn btn-primary btn-lg px-5">
                                    <i class="fas fa-redo me-2"></i>Try Again
                                </a>
                            </div>
                            
                            <div class="mt-5 pt-4 border-top">
                                <p class="text-muted small">If the problem persists, please contact our support team or try a different payment method.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection