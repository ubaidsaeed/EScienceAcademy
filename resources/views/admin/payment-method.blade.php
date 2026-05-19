<div>
   
    @if($page_type == 'view')
     <div class="row">
        <div class="col-12 my-3 my-md-5">
          @if (isset($subscription['subscribe']) && $subscription['subscribe']->payment_status == 'pending' )

              <div class="alert alert-danger alert-message fade show" role="alert" id="error-alert">
                  {{'You have not paid yet.'}}
                 </div>
                 @elseif(isset($subscription['subscribe']) && $subscription['subscribe']->payment_status == 'paid')
                 <!--<div class="alert alert-success alert-message fade show" role="alert" id="error-alert">-->
                 <!-- {{'Your payment has been completed.'}}-->
                 <!--</div>-->
          @endif
             <style>
                    :root {
                        --primary-color: #4361ee;
                        --secondary-color: #3f37c9;
                        --accent-color: #4895ef;
                        --light-color: #f8f9fa;
                        --dark-color: #212529;
                        --success-color: #4cc9f0;
                        --border-radius: 12px;
                        --box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
                        --transition: all 0.3s ease;
                    }

                    body {
                        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
                        background-color: #f5f7ff;
                        color: var(--dark-color);
                        line-height: 1.6;
                        padding: 20px;
                    }

                    .modern-card {
                        background: white;
                        border-radius: var(--border-radius);
                        box-shadow: var(--box-shadow);
                        overflow: hidden;
                        width: 100%;
                        margin: 20px auto;
                        transition: var(--transition);
                    }

                    .modern-card:hover {
                        transform: translateY(-5px);
                        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
                    }

                    .card-header {
                        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
                        color: white;
                        padding: 20px;
                        text-align: center;
                        justify-content: space-evenly;
                    }

                    .card-header h2 {
                        margin: 0;
                        font-weight: 600;
                        font-size: 1.5rem;
                    }

                    .card-body {
                        padding: 30px;
                    }

                    .pricing-grid {
                        display: grid;
                        grid-template-columns: 1fr 2fr;
                        gap: 20px;
                    }

                    .pricing-details {
                        display: grid;
                        grid-template-rows: repeat(4, 1fr);
                        gap: 15px;
                    }

                    .detail-item {
                        background: var(--light-color);
                        border-radius: var(--border-radius);
                        padding: 20px;
                        text-align: center;
                        transition: var(--transition);
                    }

                    .detail-item:hover {
                        background: #e9ecef;
                        transform: scale(1.02);
                    }

                    .detail-item span {
                        display: block;
                        font-size: 0.9rem;
                        color: #6c757d;
                        margin-bottom: 5px;
                        font-weight: 500;
                    }

                    .detail-item h3 {
                        margin: 0;
                        font-size: 1.4rem;
                        color: var(--dark-color);
                        font-weight: 700;
                    }

                    .features-section {
                        background: var(--light-color);
                        border-radius: var(--border-radius);
                        padding: 25px;
                    }

                    .features-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                        gap: 15px;
                    }

                    .feature-item {
                        background: white;
                        border-radius: 8px;
                        padding: 15px;
                        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
                        display: flex;
                        align-items: center;
                        gap: 10px;
                    }

                    .feature-item i {
                        font-size: 1.2rem;
                        width: 24px;
                        height: 24px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    }

                    .fa-circle-check {
                        color: var(--success-color);
                    }

                    .fa-circle-xmark {
                        color: #e63946;
                    }

                    @media (max-width: 768px) {
                        .pricing-grid {
                            grid-template-columns: 1fr;
                        }

                        .pricing-details {
                            grid-template-rows: auto;
                            grid-template-columns: repeat(2, 1fr);
                        }

                        .features-grid {
                            grid-template-columns: 1fr;
                        }
                    }

                    @media (max-width: 480px) {
                        .pricing-details {
                            grid-template-columns: 1fr;
                        }

                        .card-body {
                            padding: 20px;
                        }
                    }
                    .highlight-note {
    width: 100%;
    background: #fff4c4;
    padding: 18px 20px;
    border-left: 6px solid #ffb400;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin: 18px 0;
    opacity: 0;
}
.fade-custom {
    animation: fadeFastSlow 5s ease-in-out infinite;
}

@keyframes fadeFastSlow {
    0%   { opacity: 0; }
    10%  { opacity: 1; }   /* Fast fade in */
    70%  { opacity: 1; }   /* Stay visible */
    100% { opacity: 0; }   /* Slow fade out */
}


                </style>
                  <div class="highlight-note fade-custom">
                        <div class="highlight-title">⚠️ Note</div>
                        <div class="highlight-text">
                            If you upgrade your package, the previous plan will be deactivated, and its fee will not be refunded. The new package will apply in full.
                        </div>
                    </div>
             <div class="modern-card">
                    <div class="card-header">
                        <h2>Package Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="pricing-grid">
                            <div class="pricing-details">
                                <div class="detail-item">
                                    <span>Plan</span>
                                    <h3> @isset($subscription['subscription_detial'])
                                            {{ ucfirst($subscription['subscription_detial']->name) }}
                                        @endisset
                                    </h3>
                                </div>
                                <div class="detail-item">
                                    <span>Board</span>
                                    <h3>
                                        @isset($subscription['subscription_detial'])
                                            {{ $subscription['subscription_detial']->board_name }}
                                        @endisset
                                    </h3>
                                </div>
                                <div class="detail-item">
                                    <span>Cost</span>
                                    <h3>PKR.@isset($subscription['subscription_detial'])
                                            {{ $subscription['subscription_detial']->price }}
                                        @endisset
                                    </h3>
                                </div>
                                <div class="detail-item">
                                    <span>Duration</span>
                                    <h3> @isset($subscription['subscription_detial']->duration)
                                            {{ $subscription['subscription_detial']->duration }}-{{ $subscription['subscription_detial']->duration_type }}
                                        @endisset
                                    </h3>
                                </div>
                            </div>
                            <div class="features-section">
                                <h3 style="margin-top: 0; margin-bottom: 20px; color: var(--primary-color);">Features
                                </h3>
                                <div class="features-grid">
                                    <div class="feature-item">
                                        @isset($subscription['subscription_detial']->online_notes)
                                            @if ($subscription['subscription_detial']->online_notes == 1)
                                                <i class="fa-solid fa-circle-check"></i>
                                            @else
                                                <i class="fa-solid fa-circle-xmark"></i>
                                            @endif
                                        @endisset
                                        <span>Online Notes</span>
                                    </div>
                                    <div class="feature-item">
                                        @isset($subscription['subscription_detial']->top_past_paper)
                                            @if ($subscription['subscription_detial']->top_past_paper == 1)
                                                <i class="fa-solid fa-circle-check"></i>
                                            @else
                                                <i class="fa-solid fa-circle-xmark"></i>
                                            @endif
                                        @endisset
                                        <span>Topical Past Papers</span>
                                    </div>
                                    <div class="feature-item">
                                        @isset($subscription['subscription_detial']->ws_aw_bg)
                                            @if ($subscription['subscription_detial']->ws_aw_bg == 1)
                                                <i class="fa-solid fa-circle-check"></i>
                                            @else
                                                <i class="fa-solid fa-circle-xmark"></i>
                                            @endif
                                        @endisset
                                        <span>Worksheets with Award Badges</span>
                                    </div>
                                    <div class="feature-item">
                                        @isset($subscription['subscription_detial']->recorded)
                                            @if ($subscription['subscription_detial']->recorded == 1)
                                                <i class="fa-solid fa-circle-check"></i>
                                            @else
                                                <i class="fa-solid fa-circle-xmark"></i>
                                            @endif
                                        @endisset
                                        <span>Recorded</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom Styles */
        .plan {
            font-size: 1.2rem;
            margin: 0;
        }

        .table thead tr th {
            font-size: x-large;
        }

        .login-social {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
            background: white;
            color: #212b36;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: border-color 0.3s ease;
        }

        .login-social:hover {
            border-color: #008060;
        }

        .fa-solid.fa-circle-check {
            color: green;
        }

        .fa-solid.fa-circle-xmark {
            color: red;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .plan {
                font-size: 1.2rem;
            }

            .login-social {
                font-size: 12px;
                padding: 8px 12px;
            }
        }

        @media (max-width: 576px) {
            .plan {
                font-size: 1rem;
            }

            .login-social {
                font-size: 10px;
                padding: 6px 10px;
            }
        }
    </style>
    <!-- Payment info start -->
    @if($subscription['subscribe']->payment_status != 'paid')
    <div class="col-xl-12 col-lg-12 col-md-12 my-2">
        <div class="card">
            <div class="card-header border-bottom-0">
                <h3 class="card-title">Payment Information</h3>
            </div>
            <div class="card-body">
                <div class="card-pay">
                    <ul class="tabs-menu nav">
                        <!--<li class=""><a href="#tab20" class="" data-bs-toggle="tab"><i class="fa fa-credit-card"></i> Credit Card</a></li>-->
                        <li><a href="#tab21" data-bs-toggle="tab" class="active"><i class="fa fa-paypal"></i> Alfalah </a></li>
                        <!--<li><a href="#tab22" data-bs-toggle="tab" class="active"><i class="fa fa-university"></i> Bank Transfer</a></li>-->
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane " id="tab20">
                            <div class="bg-danger-transparent-2 text-danger px-4 py-2 br-3 mb-4" role="alert">Please Enter Valid Details</div>
                            <div class="form-group">
                                <label class="form-label">Card Holder Name</label>
                                <input type="text" class="form-control" placeholder="First Name">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Card Number</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Search for...">
                                    <span class="input-group-append">
                                        <button class="btn btn-secondary box-shadow-0"><i class="fa fa-cc-visa"></i> &nbsp; <i class="fa fa-cc-amex"></i> &nbsp;
                                            <i class="fa fa-cc-mastercard"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group">
                                        <label class="form-label">Expiration</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" placeholder="MM" name="Month">
                                            <input type="number" class="form-control" placeholder="YY" name="Year">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="form-label">CVV <i class="fa fa-question-circle"></i></label>
                                        <input type="number" class="form-control" required="">
                                    </div>
                                </div>
                            </div>
                            <a href="javascript:void(0);" class="btn  btn-lg btn-primary">Confirm</a>
                        </div>
                        <div class="tab-pane active " id="tab21">
                            @php $id = 4; @endphp
                            <!--<form action="{{ route('payment.process.form',$id)}}" type="post">-->
                            <!-- @csrf-->
                            <p>Alfalah  is easiest way to pay online</p>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-2">
                            <button type="submit" class="btn btn-primary" wire:click="showForm({{$id}},1)"><i class="bi bi-bank" id="payment"></i></i> Alfa Wallet</button>
                            </div>
                            <div class="col-lg-2 col-md-2 col-2">
                            <button type="submit" class="btn btn-primary" wire:click="showForm({{$id}},3)"><i class="bi bi-bank" id="payment"></i> Credit/Debit Card</button>
                            </div>
                            </div>
                            <!--<p><button type="submit" class="btn btn-primary" wire:click="showForm({{$id}})"><i class="fa fa-paypal" id="payment"></i>Pay Now</button></p>-->
                            <!--<p class="mb-0"><strong>Note:</strong> Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>-->
                        <!--</form>-->
                        </div>
                        <div class="tab-pane " id="tab22">
                            <p>Bank account details</p>
                            <dl class="card-text">
                                <dt>BANK: </dt>
                                <dd> THE UNION BANK 0456</dd>
                            </dl>
                            <dl class="card-text">
                                <dt>Accaunt number: </dt>
                                <dd> 67542897653214</dd>
                            </dl>
                            <dl class="card-text">
                                <dt>IBAN: </dt>
                                <dd>543218769</dd>
                            </dl>
                            <p class="mb-0"><strong>Note:</strong> Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. </p>
                      <div  class="my-4">
                            <label class="form-label"> Attach Payment Slip</label>
                            <input type="file" class="form-control">
                      </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif($page_type == 'paymentFailed')
    <div class="col-xl-12 col-lg-12 col-md-12 my-2">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title mb-0">Payment Failed</h3>
            </div>
            <div class="card-body">
                <p class="mb-2">Unfortunately, your payment could not be processed through Bank Alfalah.</p>
                <p class="mb-2">Please try again later or contact our support team if the problem persists.</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-2">Return to Homepage</a>
            </div>
        </div>
    </div>
    @endif
    @elseif($page_type == 'successMessage')
    @if($message !='')
  <div class="alert alert-success alert-message fade show d-none" role="alert" id="serror-alerts">
                  {{$message}}
                 </div>
  @endif
  @elseif($page_type == 'paymentFailed')
    <div class="col-xl-12 col-lg-12 col-md-12 my-2">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title mb-0">Payment Failed</h3>
            </div>
            <div class="card-body">
                <p class="mb-2">Unfortunately, your payment could not be processed through Bank Alfalah.</p>
                <p class="mb-2">Please try again later or contact our support team if the problem persists.</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-2">Return to Homepage</a>
            </div>
        </div>
    </div>
@else

                                                                                                                                                                                                       
     <input id="Key1" name="Key1" type="hidden" value="erpN5hHuKSDDq86v">                                                                                                              
     <input id="Key2" name="Key2" type="hidden" value="1643405915589698">                                                                                                                
                                                                                                                                                                                                       
     <!--<h3>Handshake</h3>                                                                                                                                                                                -->
     <form id="HandshakeForm" wire:ignore class="d-none">                                                                                                      
         <input id="HS_RequestHash" name="HS_RequestHash" type="hidden" value="">                                                                                                                      
         <input id="HS_IsRedirectionRequest" name="HS_IsRedirectionRequest" type="hidden" value="0">                                                                                                   
         <input id="HS_ChannelId" name="HS_ChannelId" type="hidden" value="1001">                                                                                                                      
         <input id="HS_ReturnURL" name="HS_ReturnURL" type="hidden" value="{{$successUrl}}">                                                                     
         <input id="HS_MerchantId" name="HS_MerchantId" type="hidden" value="31080">                                                                                                                     
         <input id="HS_StoreId" name="HS_StoreId" type="hidden" value="043966">                                                                                                                     
         <input id="HS_MerchantHash" name="HS_MerchantHash" type="hidden" value="OUU362MB1upTFWq2pN1jb9GgNMAvBW/9XsbuHEqMHOdNTG64icXuLUniXXx60My0E3Ys2XgB2+w=">                            
         <input id="HS_MerchantUsername" name="HS_MerchantUsername" type="hidden" value="uporuv">                                                                                                      
         <input id="HS_MerchantPassword" name="HS_MerchantPassword" type="hidden" value="X7zz/MXXC3xvFzk4yqF7CA==">                                                                                    
         <input id="HS_TransactionReferenceNumber" name="HS_TransactionReferenceNumber" autocomplete="off" placeholder="Order ID"  value="{{$orderId}}">                                                                                     
         <button type="button" class="btn btn-custon-four btn-danger" id="handshake">Handshake</button>                                                                                                
     </form>                                                                                                                                                                                           
                                                                                                                                                                                                       
                                                                                                                                                                                                       
     <!--<h3>Page Redirection Request</h3>                                                                                                                                                                 -->
     <form action="https://payments.bankalfalah.com/SSO/SSO/SSO" id="PageRedirectionForm" method="post" novalidate="novalidate" class="d-none">                                                              
     	<input id="AuthToken" name="AuthToken" type="hidden" value="">                                                                                                                                
     	<input id="RequestHash" name="RequestHash" type="hidden" value="">                                                                                                                            
     	<input id="ChannelId" name="ChannelId" type="hidden" value="1001">                                                                                                                            
     	<input id="Currency" name="Currency" type="hidden" value="PKR">                                                                                                                               
         <input id="IsBIN" name="IsBIN" type="hidden" value="0">                                                                                     
     	<input id="ReturnURL" name="ReturnURL" type="hidden" value="https://escienceacademy.com/payment-{{$successUrl}}">                                                                            
         <input id="MerchantId" name="MerchantId" type="hidden" value="31080">                                                                                                                           
         <input id="StoreId" name="StoreId" type="hidden" value="043966">                                                                                                                     
     	<input id="MerchantHash" name="MerchantHash" type="hidden" value="OUU362MB1upTFWq2pN1jb9GgNMAvBW/9XsbuHEqMHOdNTG64icXuLUniXXx60My0E3Ys2XgB2+w=">                                  
     	<input id="MerchantUsername" name="MerchantUsername" type="hidden" value="uporuv">                                                                                                            
     	<input id="MerchantPassword" name="MerchantPassword" type="hidden" value="X7zz/MXXC3xvFzk4yqF7CA=="> 
     	@if($paymentType == 1)
     	<input id="TransactionTypeId" name="TransactionTypeId" type="hidden" value="{{$paymentType}}"> 
     	@else
     	<input id="TransactionTypeId" name="TransactionTypeId" type="hidden" value="{{$paymentType}}"> 
     	@endif
         <!--<select autocomplete="off" id="TransactionTypeId" name="TransactionTypeId">                                                                                                                   -->
         <!--    <option value="">Select Transaction Type</option>                                                                                                                                         -->
         <!--    <option value="1">Alfa Wallet</option>                                                                                                                                                    -->
             <!--<option value="2">Alfalah Bank Account</option>                                                                                                                                                -->
         <!--    <option value="3">Credit/Debit Card</option>                                                                                                                                              -->
         <!--</select>                                                                                                                                                                                     -->
     	<input autocomplete="off" id="TransactionReferenceNumber" name="TransactionReferenceNumber" placeholder="Order ID" type="text" value="{{$orderId}}">                                  
     	<input autocomplete="off"  id="TransactionAmount" name="TransactionAmount" placeholder="Transaction Amount" type="text" value="{{$transactionAmount}}">                                                             
     	<button type="submit" class="btn btn-custon-four btn-danger" id="run">RUN</button>                                                                                                            
     </form>
     @if($loader == true)
    <div id="loading-test-1" class="" style="display:flex; justify-content:center;margin-top:150px">
  <div class="loading-mdb">
    <div class="spinner-border loading-icon" role="status"></div>
    <span class="loading-text">Processing...</span>
  </div>
  </div>
  
  @endif
  
 <div class="alert alert-danger alert-message fade show d-none" role="alert" id="serror-alerts">
                  Unsuccessful.
                 </div>
              
    @endif
    @if ($paymentError == 'Failed')
     <div class="alert alert-danger alert-message fade show" role="alert" id="error-alert">
                  Payment not confirmed by bank.
       
    </div>
@endif
</div>


@push('script')
     <script>                                                                                                                                                                   
                document.addEventListener('error', function(event) {
    const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });
    Toast.fire({
      icon: "error",
      title: event.detail
    });
});                                                                                                                                                                          
          document.addEventListener('callHandShake', function(event) {
           setTimeout(function(){
        submitRequest("HandshakeForm");   
            var myData = {                                                                                                                                                                                     
                HS_MerchantId : $("#HS_MerchantId").val(),                                                                                                                                                     
                HS_StoreId : $("#HS_StoreId").val(),                                                                                                                                                     
                HS_MerchantHash : $("#HS_MerchantHash").val(),                                                                                                                                                 
                HS_MerchantUsername : $("#HS_MerchantUsername").val(),                                                                                                                                         
                HS_MerchantPassword : $("#HS_MerchantPassword").val(),                                                                                                                                         
                HS_IsRedirectionRequest : $("#HS_IsRedirectionRequest").val(),                                                                                                                                 
                HS_ReturnURL : $("#HS_ReturnURL").val(),                                                                                                                                                       
                HS_RequestHash : $("#HS_RequestHash").val(),                                                                                                                                                   
                HS_ChannelId: $("#HS_ChannelId").val(),                                                                                                                                                        
                HS_TransactionReferenceNumber: $("#HS_TransactionReferenceNumber").val(),                                                                                                                      
            }                                                                                                                                                                                                  
                                                                                                                                                                                                               
                                                                                                                                                                                                               
            $.ajax({                                                                                                                                                                                           
                type: 'POST',                                                                                                                                                                                  
                url: 'https://payments.bankalfalah.com/HS/HS/HS',                                                                                                                                            
                contentType: "application/x-www-form-urlencoded",                                                                                                                                                
                data: myData,                                                                                                                                                                  
                dataType: "json",                                                                                                                                                                              
                beforeSend: function () {                                                                                                                                                                      
                },                                                                                                                                                                                             
                success: function (r) { 
                    console.log(r)
                    if (r != '') {                                                                                                                                                                             
                        if (r.success == "true") {                                                                                                                                                             
                            $("#AuthToken").val(r.AuthToken);                                                                                                                                                  
                            $("#ReturnURL").val(r.ReturnURL);                                                                                                                                                  
                            // alert('Success: Handshake Successful');      
                             submitRequest("PageRedirectionForm");                                                                                                                                                                  
                      document.getElementById("PageRedirectionForm").submit(); 
                        }                                                                                                                                                                                      
                        else                                                                                                                                                                                      
                        {       
                             $('#loading-test-1').addClass('d-none');
                              $('#serror-alerts').removeClass('d-none');
                        // 	alert('Error: Handshake Unsuccessful');                                                                                                                                                                                       
                        }						                                                                                                                                                                                      
                    }                                                                                                                                                                                          
                    else                                                                                                                                                                                          
                    {       
                        $('#loading-test-1').addClass('d-none');
                         $('#serror-alerts').removeClass('d-none');
                    // 	alert('Error: Handshake Unsuccessful');                                                                                                                                                                                            
                    }					                                                                                                                                                                                          
                },                                                                                                                                                                                             
                error: function (error) {                                                                                                                                                                      
                    alert('Error: An error occurred');                                                                                                                                               
                },                                                                                                                                                                                             
                complete: function(data) {                                                                                                                                                                     
                    $("#handshake").removeAttr('disabled', 'disabled');                                                                                                                                        
                }                                                                                                                                                                                              
            });  
           }, 2000);
                                                                                                                                                                                                 
  
          });                                                                                                                                                                                                      
    $("#handshake").click(function () {                                                                                                                                                                       
 
        alert('avs');
        return false;
        $("#handshake").attr('disabled', 'disabled');                                                                                                                                                          
        submitRequest("HandshakeForm");                                                                                                                                                                        
        // if ($("#HS_IsRedirectionRequest").val() == "1") {                                                                                                                                                      
        //     document.getElementById("HandshakeForm").submit();                                                                                                                                                 
        // }                                                                                                                                                                                                      
        // else {                                                                                                                                                                                                 
        //     var myData = {                                                                                                                                                                                     
        //         HS_MerchantId : $("#HS_MerchantId").val(),                                                                                                                                                     
        //         HS_StoreId : $("#HS_StoreId").val(),                                                                                                                                                     
        //         HS_MerchantHash : $("#HS_MerchantHash").val(),                                                                                                                                                 
        //         HS_MerchantUsername : $("#HS_MerchantUsername").val(),                                                                                                                                         
        //         HS_MerchantPassword : $("#HS_MerchantPassword").val(),                                                                                                                                         
        //         HS_IsRedirectionRequest : $("#HS_IsRedirectionRequest").val(),                                                                                                                                 
        //         HS_ReturnURL : $("#HS_ReturnURL").val(),                                                                                                                                                       
        //         HS_RequestHash : $("#HS_RequestHash").val(),                                                                                                                                                   
        //         HS_ChannelId: $("#HS_ChannelId").val(),                                                                                                                                                        
        //         HS_TransactionReferenceNumber: $("#HS_TransactionReferenceNumber").val(),                                                                                                                      
        //     }                                                                                                                                                                                                  
                                                                                                                                                                                                               
                                                                                                                                                                                                               
        //     $.ajax({                                                                                                                                                                                           
        //         type: 'POST',                                                                                                                                                                                  
        //         url: 'https://sandbox.bankalfalah.com/HS/HS/HS',                                                                                                                                            
        //         contentType: "application/x-www-form-urlencoded",                                                                                                                                                
        //         data: myData,                                                                                                                                                                  
        //         dataType: "json",                                                                                                                                                                              
        //         beforeSend: function () {                                                                                                                                                                      
        //         },                                                                                                                                                                                             
        //         success: function (r) {                                                                                                                                                                        
        //             if (r != '') {                                                                                                                                                                             
        //                 if (r.success == "true") {                                                                                                                                                             
        //                     $("#AuthToken").val(r.AuthToken);                                                                                                                                                  
        //                     $("#ReturnURL").val(r.ReturnURL);                                                                                                                                                  
        //                     // alert('Success: Handshake Successful');      
        //                      submitRequest("PageRedirectionForm");                                                                                                                                                                  
        //               document.getElementById("PageRedirectionForm").submit(); 
        //                 }                                                                                                                                                                                      
        //                 else                                                                                                                                                                                      
        //                 {                                                                                                                                                                                      
        //                 	alert('Error: Handshake Unsuccessful');                                                                                                                                                                                       
        //                 }						                                                                                                                                                                                      
        //             }                                                                                                                                                                                          
        //             else                                                                                                                                                                                          
        //             {                                                                                                                                                                                          
        //             	alert('Error: Handshake Unsuccessful');                                                                                                                                                                                            
        //             }					                                                                                                                                                                                          
        //         },                                                                                                                                                                                             
        //         error: function (error) {                                                                                                                                                                      
        //             alert('Error: An error occurred');                                                                                                                                               
        //         },                                                                                                                                                                                             
        //         complete: function(data) {                                                                                                                                                                     
        //             $("#handshake").removeAttr('disabled', 'disabled');                                                                                                                                        
        //         }                                                                                                                                                                                              
        //     });                                                                                                                                                                                                
        // }                                                                                                                                                                                                      
                                                                                                                                                                                                               
   
   
        }); 
   function submitRequest(formName) {                                                                                                                                                                             
                                                                                                                                                                                                             
  var mapString = '', hashName = 'RequestHash';                                                                                                                                                              
  if (formName == "HandshakeForm") {                                                                                                                                                                         
      hashName = 'HS_' + hashName;                                                                                                                                                                           
  }                                                                                                                                                                                                          
                                                                                                                                                                                                             
  $("#" + formName+" :input").each(function () {                                                                                                                                                             
      if ($(this).attr('id') != '') {                                                                                                                                                                        
          mapString += $(this).attr('id') + '=' + $(this).val() + '&';                                                                                                                                       
      }                                                                                                                                                                                                      
  });                                                                                                                                                                                                        
                                                                                                                                                                                                             
  $("#" + hashName).val(CryptoJS.AES.encrypt(CryptoJS.enc.Utf8.parse(mapString.substr(0, mapString.length - 1)), CryptoJS.enc.Utf8.parse($("#Key1").val()),                                                  
      {                                                                                                                                                                                                      
          keySize: 128 / 8,                                                                                                                                                                                  
          iv: CryptoJS.enc.Utf8.parse($("#Key2").val()),                                                                                                                                                     
          mode: CryptoJS.mode.CBC,                                                                                                                                                                           
          padding: CryptoJS.pad.Pkcs7                                                                                                                                                                        
      }));                                                                                                                                                                                                   
   }                                                                                                                                                                                                                
                                                                                                                                                                                                               
     </script>                                                                                                                                                                                         
@endpush