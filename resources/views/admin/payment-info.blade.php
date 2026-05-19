<div>
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
</div>
