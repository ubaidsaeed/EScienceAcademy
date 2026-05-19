<div>
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 my-2">
            
            @if(isset($paymentError) && $paymentError == 'Failed')
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h3 class="card-title mb-0">Payment Failed</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">Unfortunately, your payment could not be processed through Bank Alfalah.</p>
                        <p class="mb-2">Please try again later or contact our support team if the problem persists.</p>
                        <a href="{{ route('student.payment.method') }}" class="btn btn-primary mt-2">Go Back</a>
                    </div>
                </div>

            @elseif(isset($paymentError) && $paymentError == 'Initiated')
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title mb-0">Payment Initiated</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">Your payment has been initiated successfully. Please wait for confirmation.</p>
                        <a href="{{ route('student.payment.method') }}" class="btn btn-primary mt-2">Go Back</a>
                    </div>
                </div>

            @else
                <div class="card border-secondary">
                    <div class="card-header bg-secondary text-white">
                        <h3 class="card-title mb-0">Payment Status Unknown</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">We couldn't determine your payment status at this time.</p>
                        <p class="mb-2">Please refresh the page or contact support for assistance.</p>
                        <a href="{{ route('student.payment.method') }}" class="btn btn-primary mt-2">Go Back</a>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
