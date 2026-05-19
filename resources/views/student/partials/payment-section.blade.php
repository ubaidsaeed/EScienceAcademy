<div id="">
    <div class="col-xl-12 col-lg-12 col-md-12 my-2"> 
        <div class="card">
            <div class="card-header border-bottom-0">
                <h3 class="card-title">
                    <i class="fas fa-credit-card me-2"></i>Complete Your Payment
                </h3>
            </div>
            <div class="card-body">
                <!-- Subscription summary will be dynamically inserted here -->
                
                <div class="card-pay">
                    <ul class="tabs-menu nav">
                        <li><a href="#tab21" data-bs-toggle="tab" class="active">
                            <i class="fa fa-university"></i> Bank Alfalah 
                        </a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab21">
                            <p class="text-muted mb-3">Secure payment via Bank Alfalah. Please select your preferred payment method:</p>
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-3">
                                    <button type="button" class="btn btn-primary w-100 py-3" onclick="showPaymentForm(1)" id="alfaWalletBtn">
                                        <i class="bi bi-wallet me-2"></i> Alfa Wallet
                                    </button>
                                </div>
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-3">
                                    <button type="button" class="btn btn-success w-100 py-3" onclick="showPaymentForm(3)" id="creditCardBtn">
                                        <i class="bi bi-credit-card me-2"></i> Credit/Debit Card
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mt-4 pt-3 border-top">
                                <div class="alert alert-light">
                                    <h6><i class="fas fa-shield-alt text-primary me-2"></i>Secure Payment Guarantee</h6>
                                    <p class="mb-0 small">Your payment information is encrypted and secure. We do not store your card details.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Forms (Initially Hidden) -->
    <div id="paymentForms" style="display: none;">
        <input id="Key1" name="Key1" type="hidden" value="erpN5hHuKSDDq86v">
        <input id="Key2" name="Key2" type="hidden" value="1643405915589698">
        
        <form id="HandshakeForm" class="d-none">
            <input id="HS_RequestHash" name="HS_RequestHash" type="hidden" value="">
            <input id="HS_IsRedirectionRequest" name="HS_IsRedirectionRequest" type="hidden" value="0">
            <input id="HS_ChannelId" name="HS_ChannelId" type="hidden" value="1001">
            <input id="HS_ReturnURL" name="HS_ReturnURL" type="hidden" value="">
            <input id="HS_MerchantId" name="HS_MerchantId" type="hidden" value="31080">
            <input id="HS_StoreId" name="HS_StoreId" type="hidden" value="043966">
            <input id="HS_MerchantHash" name="HS_MerchantHash" type="hidden" value="OUU362MB1upTFWq2pN1jb9GgNMAvBW/9XsbuHEqMHOdNTG64icXuLUniXXx60My0E3Ys2XgB2+w=">
            <input id="HS_MerchantUsername" name="HS_MerchantUsername" type="hidden" value="uporuv">
            <input id="HS_MerchantPassword" name="HS_MerchantPassword" type="hidden" value="X7zz/MXXC3xvFzk4yqF7CA==">
            <input id="HS_TransactionReferenceNumber" name="HS_TransactionReferenceNumber" type="hidden" value="">
        </form>
        
        <form action="https://payments.bankalfalah.com/SSO/SSO/SSO" id="PageRedirectionForm" method="post" novalidate="novalidate" class="d-none">
            <input id="AuthToken" name="AuthToken" type="hidden" value="">
            <input id="RequestHash" name="RequestHash" type="hidden" value="">
            <input id="ChannelId" name="ChannelId" type="hidden" value="1001">
            <input id="Currency" name="Currency" type="hidden" value="PKR">
            <input id="IsBIN" name="IsBIN" type="hidden" value="0">
            <input id="ReturnURL" name="ReturnURL" type="hidden" value="">
            <input id="MerchantId" name="MerchantId" type="hidden" value="31080">
            <input id="StoreId" name="StoreId" type="hidden" value="043966">
            <input id="MerchantHash" name="MerchantHash" type="hidden" value="OUU362MB1upTFWq2pN1jb9GgNMAvBW/9XsbuHEqMHOdNTG64icXuLUniXXx60My0E3Ys2XgB2+w=">
            <input id="MerchantUsername" name="MerchantUsername" type="hidden" value="uporuv">
            <input id="MerchantPassword" name="MerchantPassword" type="hidden" value="X7zz/MXXC3xvFzk4yqF7CA==">
            <input id="TransactionTypeId" name="TransactionTypeId" type="hidden" value="">
            <input id="TransactionReferenceNumber" name="TransactionReferenceNumber" type="hidden" value="">
            <input id="TransactionAmount" name="TransactionAmount" type="hidden" value="">
        </form>
    </div>

    <div id="paymentLoader" class="d-none">
        <div class="text-center py-5">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h5 class="mt-3">Processing Payment...</h5>
            <p class="text-muted">Please wait while we connect to the payment gateway.</p>
        </div>
    </div>

    <div class="alert alert-danger alert-message fade show d-none" role="alert" id="serror-alerts">
        <i class="fas fa-exclamation-circle me-2"></i>
        <span id="errorMessageText">Payment processing failed. Please try again.</span>
    </div>
</div>

@push('script')
<script>
    // Payment functions
    // function showPaymentForm(paymentType) {
    //     if (!currentSubscriptionId) {
    //         alert('Please complete subscription selection first.');
    //         return;
    //     }
        
    //     const paymentMethod = paymentType === 1 ? 'Alfa Wallet' : 'Credit/Debit Card';
        
    //     if (confirm(`Proceed with ${paymentMethod} payment?\n\n`)) {
    //         // Show payment loader
    //         // document.getElementById('paymentLoader').classList.remove('d-none');
    //         document.getElementById('serror-alerts').classList.add('d-none');
            
    //         // Store current payment amount globally
    //         window.currentPaymentAmount = document.getElementById('TransactionAmount')?.value || 0;
            
    //         // Set transaction type
    //         if (document.getElementById('TransactionTypeId')) {
    //             document.getElementById('TransactionTypeId').value = paymentType;
    //         }
            
    //         // Show payment forms
    //         document.getElementById('paymentForms').style.display = 'block';
            
    //         // Trigger handshake after a short delay
    //         setTimeout(() => {
    //             submitHandshake();
    //         }, 1000);
    //     }
    // }
    function showPaymentForm(paymentType) {
    if (!currentSubscriptionId) {
        Swal.fire({
            icon: 'warning',
            title: 'Incomplete Selection',
            text: 'Please complete subscription selection first.',
            confirmButtonText: 'OK',
            confirmButtonColor: '#0072ff',
            background: '#ffffff',
            color: '#2c3e50'
        });
        return;
    }
    
    const paymentMethod = paymentType === 1 ? 'Alfa Wallet' : 'Credit/Debit Card';
    
    Swal.fire({
        title: 'Confirm Payment Method',
        html: `
            <div class="text-start ">
                <p>You have selected <strong>${paymentMethod}</strong> for payment.</p>
                <div class="mt-3 p-3 bg-light rounded d-none">
                    <p class="mb-1"><strong>Payment Details:</strong></p>
                    <p class="mb-1">Amount: <strong>PKR ${parseInt(window.currentPaymentAmount || 0).toLocaleString()}</strong></p>
                    <p class="mb-0">Subscription ID: <strong>#${currentSubscriptionId}</strong></p>
                </div>
                <p class="mt-3 small text-muted">You will be redirected to the secure payment gateway.</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Proceed to Payment',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#0072ff',
        cancelButtonColor: '#6c757d',
        background: '#ffffff',
        color: '#2c3e50',
        width: 500,
        customClass: {
            popup: 'swal2-payment-popup',
            confirmButton: 'swal2-payment-confirm',
            cancelButton: 'swal2-payment-cancel'
        },
        preConfirm: () => {
            return new Promise((resolve) => {
                // Show payment loader with SweetAlert
                Swal.fire({
                    title: 'Processing Payment',
                    html: `
                        <div class="text-center">
                            <div class="swal2-spinner mb-3 d-none" style="width: 50px; height: 50px; border-width: 3px;"></div>
                            <p class="mb-2">Connecting to secure payment gateway...</p>
                            <p class="small text-muted">Please do not close this window.</p>
                        </div>
                    `,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    background: '#ffffff',
                    color: '#2c3e50',
                    width: 450,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Hide any previous errors
                document.getElementById('serror-alerts').classList.add('d-none');
                
                // Store current payment amount globally
                window.currentPaymentAmount = document.getElementById('TransactionAmount')?.value || 0;
                
                // Set transaction type
                if (document.getElementById('TransactionTypeId')) {
                    document.getElementById('TransactionTypeId').value = paymentType;
                }
                
                // Show payment forms
                document.getElementById('paymentForms').style.display = 'block';
                                updatePaymentForms(currentSubscriptionId, window.currentPaymentAmount);

                // Trigger handshake after a short delay
                setTimeout(() => {
                    submitHandshake();
                    resolve();
                }, 1000);
            });
        }
    });
}

    function submitHandshake() {
        submitRequest("HandshakeForm");
        
        const myData = {
            HS_MerchantId: $("#HS_MerchantId").val(),
            HS_StoreId: $("#HS_StoreId").val(),
            HS_MerchantHash: $("#HS_MerchantHash").val(),
            HS_MerchantUsername: $("#HS_MerchantUsername").val(),
            HS_MerchantPassword: $("#HS_MerchantPassword").val(),
            HS_IsRedirectionRequest: $("#HS_IsRedirectionRequest").val(),
            HS_ReturnURL: $("#HS_ReturnURL").val(),
            HS_RequestHash: $("#HS_RequestHash").val(),
            HS_ChannelId: $("#HS_ChannelId").val(),
            HS_TransactionReferenceNumber: $("#HS_TransactionReferenceNumber").val(),
        };
        
        $.ajax({
            type: 'POST',
            url: 'https://payments.bankalfalah.com/HS/HS/HS',
            contentType: "application/x-www-form-urlencoded",
            data: myData,
            dataType: "json",
            success: function (r) {
                if (r != '') {
                    if (r.success == "true") {
                        $("#AuthToken").val(r.AuthToken);
                        $("#ReturnURL").val(r.ReturnURL);
                        submitRequest("PageRedirectionForm");
                        document.getElementById("PageRedirectionForm").submit();
                    } else {
                        // document.getElementById('paymentLoader').classList.add('d-none');
                        document.getElementById('serror-alerts').classList.remove('d-none');
                        document.getElementById('errorMessageText').textContent = 'Payment gateway connection failed. Please try again.';
                    }
                } else {
                    // document.getElementById('paymentLoader').classList.add('d-none');
                    document.getElementById('serror-alerts').classList.remove('d-none');
                    document.getElementById('errorMessageText').textContent = 'Payment gateway connection failed. Please try again.';
                }
            },
            error: function (error) {
                // document.getElementById('paymentLoader').classList.add('d-none');
                document.getElementById('serror-alerts').classList.remove('d-none');
                document.getElementById('errorMessageText').textContent = 'Network error. Please check your connection and try again.';
                console.error('Payment error:', error);
            }
        });
    }
    
    function submitRequest(formName) {
        var mapString = '', hashName = 'RequestHash';
        if (formName == "HandshakeForm") {
            hashName = 'HS_' + hashName;
        }
        
        $("#" + formName + " :input").each(function () {
            if ($(this).attr('id') != '') {
                mapString += $(this).attr('id') + '=' + $(this).val() + '&';
            }
        });
        
        if (typeof CryptoJS !== 'undefined') {
            $("#" + hashName).val(CryptoJS.AES.encrypt(
                CryptoJS.enc.Utf8.parse(mapString.substr(0, mapString.length - 1)),
                CryptoJS.enc.Utf8.parse($("#Key1").val()),
                {
                    keySize: 128 / 8,
                    iv: CryptoJS.enc.Utf8.parse($("#Key2").val()),
                    mode: CryptoJS.mode.CBC,
                    padding: CryptoJS.pad.Pkcs7
                }
            ));
        }
    }
    // Update payment forms with subscription details
// When updating payment forms
function updatePaymentForms(subscriptionId, totalAmount) {
    // Set values in Handshake form
    if (document.getElementById('HS_TransactionReferenceNumber')) {
        document.getElementById('HS_TransactionReferenceNumber').value = subscriptionId;
    }
    
    if (document.getElementById('HS_ReturnURL')) {
        // Use the route helper from Laravel (passed via data attribute)
        const baseUrl = document.getElementById('paymentSection').dataset.baseUrl || '';
        document.getElementById('HS_ReturnURL').value = `${baseUrl}/student/payment/response/${subscriptionId}`;
    }
    
    // Set values in PageRedirection form
    if (document.getElementById('TransactionReferenceNumber')) {
        document.getElementById('TransactionReferenceNumber').value = subscriptionId;
    }
    
    if (document.getElementById('TransactionAmount')) {
        document.getElementById('TransactionAmount').value = totalAmount;
    }
    
    if (document.getElementById('ReturnURL')) {
        const baseUrl = document.getElementById('paymentSection').dataset.baseUrl || '';
        document.getElementById('ReturnURL').value = `${baseUrl}/student/payment/response/${subscriptionId}`;
    }
}
    // Make functions globally available
    window.showPaymentForm = showPaymentForm;
    window.submitHandshake = submitHandshake;
    window.submitRequest = submitRequest;
</script>
@endpush