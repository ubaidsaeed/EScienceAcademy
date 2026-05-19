@extends('layouts.admin.app')

@section('content')
<style>
    .invoice-container {
        padding: 1rem;
    }

    .invoice-container .invoice-header .invoice-logo {
        margin: 0.8rem 0 0 0;
        display: inline-block;
        font-size: 1.6rem;
        font-weight: 700;
        color: #2e323c;
    }

    .invoice-container .invoice-header .invoice-logo img {
        max-width: 130px;
    }

    .invoice-container .invoice-header address {
        font-size: 0.8rem;
        color: #9fa8b9;
        margin: 0;
    }

    .invoice-container .invoice-details {
        margin: 1rem 0 0 0;
        padding: 1rem;
        line-height: 180%;
        background: #f5f6fa;
    }

    .invoice-container .invoice-details .invoice-num {
        text-align: right;
        font-size: 0.8rem;
    }

    .invoice-container .invoice-body {
        padding: 1rem 0 0 0;
    }

    .invoice-container .invoice-footer {
        text-align: center;
        font-size: 0.7rem;
        margin: 5px 0 0 0;
    }

    .invoice-status {
        text-align: center;
        padding: 1rem;
        background: #ffffff;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        margin-bottom: 1rem;
    }

    .invoice-status h2.status {
        margin: 0 0 0.8rem 0;
    }

    .invoice-status h5.status-title {
        margin: 0 0 0.8rem 0;
        color: #9fa8b9;
        font-size: 14px;
    }

    .invoice-status p.status-type {
        margin: 0.5rem 0 0 0;
        padding: 0;
        line-height: 150%;
    }

    .invoice-status i {
        font-size: 1.5rem;
        margin: 0 0 1rem 0;
        display: inline-block;
        padding: 1rem;
        background: #f5f6fa;
        -webkit-border-radius: 50px;
        -moz-border-radius: 50px;
        border-radius: 50px;
    }

    .invoice-status .badge {
        text-transform: uppercase;
    }

    @media (max-width: 767px) {
        .invoice-container {
            padding: 1rem;
        }
    }

    .custom-table {
        border: 1px solid #e0e3ec;
    }

    .custom-table thead {
        background: #007ae1;
    }

    .custom-table thead th {
        border: 0;
        color: #ffffff;
    }

    .custom-table>tbody tr:hover {
        background: #fafafa;
    }

    .custom-table>tbody tr:nth-of-type(even) {
        background-color: #ffffff;
    }

    .custom-table>tbody td {
        border: 1px solid #e6e9f0;
    }

    .card {
        background: #ffffff;
        -webkit-border-radius: 5px;
        -moz-border-radius: 5px;
        border-radius: 5px;
        border: 0;
        margin-bottom: 1rem;
    }

    .text-success {
        color: #00bb42 !important;
    }

    .text-muted {
        color: #9fa8b9 !important;
    }

    .custom-actions-btns {
        margin: auto;
        display: flex;
        justify-content: flex-end;
    }

    .custom-actions-btns .btn {
        margin: .3rem 0 .3rem .3rem;
    }
    
    .progress {
        height: 20px;
        margin-bottom: 1rem;
    }
    
    .progress-bar {
        line-height: 20px;
    }
</style>
<div class="app-content main-content">
    <div class="side-app">
<div class="page-header d-lg-flex d-block">
    <div class="page-leftheader">
        <h4 class="page-title">Subscription Details</h4>
    </div>
    <div class="page-rightheader">
        @if (session('success'))
            <div class="alert alert-success alert-message fade show" role="alert" id="success-alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-message fade show" role="alert" id="error-alert">
                {{ session('error') }}
            </div>
        @endif
        <div class="btn-list">
            <a href="{{ route('admin.subscribe.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row gutters">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <div class="card">
                                <div class="card-body p-0">
                                    <div class="invoice-container">
                                        <div class="invoice-header">
                                            <div class="row gutters">
                                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                                                    <div class="invoice-logo">
                                                        eScience Academy
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row gutters">
                                                <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12 col-12">
                                                    <div class="invoice-details">
                                                        <address>
                                                            <strong>{{ $subscription->user_name }}</strong><br>
                                                            {{ $subscription->user_email }}<br>
                                                            Subscription ID: {{ $subscription->id }}
                                                        </address>
                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-12">
                                                    <div class="invoice-status">
                                                        <h5 class="status-title">Status</h5>
                                                        <span class="badge badge-{{ $subscription->status == 'active' ? 'success' : 'danger' }}">
                                                            {{ ucfirst($subscription->status) }}
                                                        </span>
                                                        <h5 class="status-title mt-3">Payment Status</h5>
                                                        <span class="badge badge-{{ $subscription->payment_status == 'paid' ? 'success' : ($subscription->payment_status == 'pending' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($subscription->payment_status) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Progress Bar -->
                                        <!--<div class="row gutters mt-3">-->
                                        <!--    <div class="col-12">-->
                                        <!--        <div class="card">-->
                                        <!--            <div class="card-body">-->
                                        <!--                <h6 class="card-title">Subscription Progress</h6>-->
                                        <!--                <div class="progress">-->
                                        <!--                    <div class="progress-bar bg-success" role="progressbar" -->
                                        <!--                         style="width: {{ $completionPercentage }}%" -->
                                        <!--                         aria-valuenow="{{ $completionPercentage }}" -->
                                        <!--                         aria-valuemin="0" -->
                                        <!--                         aria-valuemax="100">-->
                                        <!--                        {{ $completionPercentage }}%-->
                                        <!--                    </div>-->
                                        <!--                </div>-->
                                        <!--                <small class="text-muted">-->
                                                            <!--{{ $completedDuration ?? 0 }} of {{ $totalDuration ?? 0 }} days completed-->
                                        <!--                </small>-->
                                        <!--            </div>-->
                                        <!--        </div>-->
                                        <!--    </div>-->
                                        <!--</div>-->
                                        
                                        <div class="invoice-body">
                                            <form action="{{ route('admin.subscribe.update') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">
                                                <div class="row gutters">
                                                    <div class="col-lg-12 col-md-12 col-sm-12">
                                                        <div class="table-responsive">
                                                            <table class="table custom-table m-0">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Package Name</th>
                                                                        <th>Price</th>
                                                                        <th>Duration</th>
                                                                        <th>Start Date</th>
                                                                        <th>End Date</th>
                                                                        <th>Payment Status</th>
                                                                        <th>Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>
                                                                            <strong>{{ $plan->name }}</strong>
                                                                        </td>
                                                                        <td>PKR {{ number_format($plan->grand_total, 2) }}</td>
                                                                        <td>{{ $plan->duration }} {{ $plan->duration_type }}(s)</td>
                                                                        <td>{{ \Carbon\Carbon::parse($subscription->start_date)->format('M d, Y') }}</td>
                                                                        <td>{{ \Carbon\Carbon::parse($subscription->end_date)->format('M d, Y') }}</td>
                                                                        <td>
                                                                            <select class="form-control select2" name="payment_status" id="payment_status" style="width: 100%;">
                                                                                <option value="pending" {{ $subscription->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                                <option value="paid" {{ $subscription->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                                                                <option value="cancelled" {{ $subscription->payment_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                                            </select>
                                                                        </td>
                                                                        <td>
                                                                            <select class="form-control select2" name="status" id="status" style="width: 100%;">
                                                                                <option value="active" {{ $subscription->status == 'active' ? 'selected' : '' }}>Active</option>
                                                                                <option value="unactive" {{ $subscription->status == 'unactive' ? 'selected' : '' }}>Unactive</option>
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Additional Information -->
                                                
                                                <div class="row gutters mt-4">
                                                    <div class="col-12 text-end">
                                                        
                                                        <button type="submit" class="btn btn-primary">
                                                            <i class="fas fa-save"></i> Update Subscription
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
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
</div>
</div>
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

<script>
$(document).ready(function() {
    // // Initialize Select2
    // $('.select2').select2({
    //     width: '100%',
    //     theme: 'bootstrap'
    // });
    
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert-message').fadeOut('slow');
    }, 5000);
    
    // Form validation
    $('form').on('submit', function(e) {
        const paymentStatus = $('#payment_status').val();
        const status = $('#status').val();
        
        if (!paymentStatus || !status) {
            e.preventDefault();
            alert('Please select both Payment Status and Status');
            return false;
        }
    });
});
</script>
@endpush
@endsection