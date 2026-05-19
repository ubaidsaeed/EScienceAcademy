<div>
    <style>
        .swiper-slide .pricing-card-style-two {
            width: 100%;
        }

        .pricing-card-style-two {
            position: relative;
            z-index: 1;
        }

        .pricing-card-style-two .custom-height-package {
           min-height: 550px;
        }

        .pricing-card-style-two .card-inner {
            padding: 50px 30px;
            background: #fff;
            text-align: left;
            border-radius: 30px;
        }
        .heading-space {
        height: 10%;
         }
         .bg-one{
             background-color:#8E2DE2;
         }
         .bg-two{
             background-color:#DE6262;
         }
         .bg-three{
             background-color:#02AAB0;
         }
         .bg-four{
             background-color:#FF512F;
         }
         

        .custom-height-package h4 {
            text-align: center !important;
        }

        .content-space {
            height: 50%;
        }

        .pricing-card-style-two .card-inner .price-area .price {
            max-width: max-content;
            padding: 20px 30px;
            border-radius: 100px;
            font-size: 16px;
            color: #fff !important;
            margin-bottom: 25px;
        }

        .custom-height-package h4 {
            text-align: center !important;
        }

        .subscribe-btn {
            position: relative;
            /*left: 40px;*/
            padding: 10px;
            color: #fff;
            border-radius: 10px;
        }
        .subscribe-btn:hover{
            cursor:pointer;
            color:#fff;
        }
        .price-area{
            display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
        }
        
        .pricing-slider-inner .swiper-slide {
    transform: translateY(0);
    transition: all 0.4s;
}
.custom-pricing-slider {
    display: flex;
    width: 100% !important;
    gap: 20px;
}
.pricing-card-style-two .card-inner .pricing-list-wrapper li {
    margin: 0;
    margin-bottom: 10px;
    font-weight: 400;
    color: var(--color-heading-1);
}
.package-note {
    font-size: 12px;
    color: #856404;
    background-color: #fff3cd;
    border: 1px solid #ffeaa7;
    padding: 8px 12px;
    border-radius: 5px;
    margin: 10px 0;
    text-align: center;
    line-height: 1.4;
}
    </style>
    <link rel="stylesheet preload" href="{{ asset('build/assets/frontend/css/vendor/swiper.css') }}" as="style">
    <!--<link rel="stylesheet preload" href="{{ asset('build/assets/frontend/css/style.css') }}" as="style">-->
    <div class="page-header d-lg-flex d-block">
                @php
                                                        $count = 1;
                                                    @endphp
                                                    @foreach ($subscription as $index => $item)
                                                        @php
                                                            $cardClass = '';
                                                            $cardClass .= match ($item->slug) {
                                                                'basic-learning-pack' => 'bg-one',
                                                                'comprehensive-learning-pack' => 'bg-two',
                                                                'custom-plan' => 'bg-four',
                                                                default => 'bg-three',
                                                            };
                                                            if ($count == 1) {
                                                                echo ' <div class="swiper-slide v1 custom-pricing-slider">';
                                                            }
                                                        @endphp

                                                        <!-- Pricing Card -->
                                                       <div class="pricing-card-style-two " >
                                                            <div class="card-inner custom-height-package" @if($item->id ==$existedPackage ) style="border: 2px solid red;" @endif>
                                                                <div class="heading-space">
                                                                    <h4 cass="title text-center">{{ $item->name }}</h4>
                                                                </div>
                                                                <div class="content-space">
                                                                    <p class="time"> {!! $item->content !!}</p>
                                                                </div>
                                                                <div class="price-area">
                                                                    <h4 class="price  {{ $cardClass }}"><span
                                                                            class="currency">RS.</span><span
                                                                            class="amount">{{ $item->price }}</span>
                                                                        /{{ $item->duration_type }}</h4>
                                                                        @if($item->id != $existedPackage)
                                                                        <!--<div class="package-note">-->
                                                                        <!--    <i class="fa fa-info-circle"></i> If you change this package, the amount you paid for current plan will not be refundable. You will also need to pay the full amount of the new package.-->
                                                                        <!--</div>-->
                                                                        @elseif($item->id == $existedPackage && $isPackageExpired)
                                                                        <div class="package-note" style="background-color: #f8d7da; border-color: #f5c6cb; color: #721c24;">
                                                                            <i class="fa fa-exclamation-triangle"></i> Your package has expired. Please renew to continue using services.
                                                                        </div>
                                                                        @elseif($item->id == $existedPackage && $isExpiringSoon)
                                                                        <div class="package-note" style="background-color: #fff3cd; border-color: #ffeaa7; color: #856404;">
                                                                            <i class="fa fa-clock-o"></i> Your package is expiring soon (within 2 days). You can renew it now.
                                                                        </div>
                                                                        @endif
                                                                        <a class="subscribe-btn {{ $cardClass }}"
                                                                     href="@if($item->id == $existedPackage && !$canRenewCurrentPackage) javascript:void(0) @else {{ route('subscription.package', $item->slug.'/'.$level_slug) }} @endif" ><span>@if($item->id == $existedPackage && !$canRenewCurrentPackage) Active Package @elseif($item->id == $existedPackage && $canRenewCurrentPackage) Renew Package @else Subscribe
                                                                        Now @endif</span></a>
                                                                </div>

                                                            </div>



                                                            <!-- <a href="{{ route('subscription.package', $item->slug) }}"
                                                                            class="pricing-btn rounded-button {{ $cardClass }}">
                                                                            <svg width="19" height="18" viewBox="0 0 19 18"
                                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                                <path d="M1 17L17 1H7.8" stroke="white" />
                                                                            </svg>
                                                                        </a> -->
                                                        </div>
                                                        <!-- Pricing Card -->

                                                        @php
                                                            $count += 1;
                                                            if ($count == 5) {
                                                                $count = 1;

                                                                echo '</div>';
                                                            }
                                                        @endphp
                                                    @endforeach
        <!-- Pricing Card -->
    </div>


</div>
