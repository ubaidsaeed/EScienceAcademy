<div>
    @php
        
        $subscription = App\Models\Subscriptionplans::where('status', 'active')->latest()->limit(3)->get();
        @endphp
        <div class="cs-breadcrumb-area background-common" style="background-image: url(https://theme-coderstation.com/kan/assets/images/banner/breadcrumb-bg.png);">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <!-- bread crumb inner wrapper -->
                        <div class="breadcrumb-inner text-center">
                            <h2 class="title">Package's</h2>
                            <div class="meta">
                                <span>Home / <a href="javascript:void(0)">Package's</a></span>
                            </div>
                        </div>
                        <!-- bread crumb inner wrapper end -->
                    </div>
                </div>
            </div>
            <div class="breadcrumb-shape-img">
                <img class="one floating-up-3" src="https://theme-coderstation.com/kan/assets/images/footer/dotted-03.svg" alt="">
                <img class="two" src="assets/images/footer/leaf.svg" alt="">
                <img class="three" src="https://theme-coderstation.com/kan/assets/images/banner/four-round.svg" alt="">
                <img class="four wow fadeIn" data-wow-delay=".55s" src="	https://theme-coderstation.com/kan/assets/images/footer/round.svg" alt="">
                <img class="five" src="https://theme-coderstation.com/kan/assets/images/footer/leaf-2.svg" alt="">
                <img class="six floating-up-3" src="assets/images/footer/dotted-04.svg" alt="">
                <img class="seven" src="https://theme-coderstation.com/kan/assets/images/footer/round2.svg" alt="">
            </div>
        </div>
        <!-- rts breadcrumba area end -->
        <!-- End Breadcrumb Area -->
    
        <!-- Start Pricing Area -->
        <section class="cs__service__area area-4 cs-section-gap" id="package">
            <div class="container">
                <div class="section__title__area text-center">
                    <p class="section__title__area__sub-title color-one text-center wow fadeInUp" data-wow-delay=".25s">Best Of
                        Our Packages</p>
                    <h2 class="section__title__area__title text-anim">Select the Approperiate Package</h2>
                </div>
                <div class="section-inner mt--60">
                    <div class="row justify-content-center g-4 gy-5">
                        <!-- Service Card -->
                        @foreach($subscription as $index => $item)
                        @php
                                            $cardClass = '';
                                            $cardClass .= match ($item->slug) {
                                                'gold' => 'bg-one',
                                                'silver' => 'bg-two',
                                                default => 'bg-three',
                                            };
                                        @endphp
                        <div class="col-lg-3 col-md-6">
                            <div class="service__card__style__four wow fadeInUp" data-wow-delay=".25s">
                                <div class="card-inner {{$cardClass}} border-color-one">
                                    <div>
                                        <p class="tag {{$cardClass}} border-color-one">{{$item->name}}</p>
                                         <p class="tag {{$cardClass}} border-color-one">Rs. {{$item->price}}/- {{$item->duration_type}}</p>
                                    </div>
                                    <p class="card-desc">
                                    <ul class="pricing-list-wrapper">
                                        @if($item->total_chapters > 0)
                                        <li>
                                            <strong>{{$item->total_chapters}}</strong> Chapters
                                        </li>
                                        @endif
                                         @if($item->online_notes == 1)
                                        <li>
                                            <strong>Online</strong> Notes
                                        </li>
                                        @endif
                                        @if($item->top_past_paper == 1)
                                        <li>
                                            <strong>Topical</strong> Past Papers
                                        </li>
                                        @endif
                                         @if($item->ws_aw_bg == 1)
                                        <li>
                                            <strong>Worksheets</strong>with Award Badges
                                            </span>
                                        </li>
                                        @endif
                                        @if($item->recorded == 1)
                                        <li>
                                            <strong>02</strong> Recorded / Live Sessions
                                        </li>
                                        @endif
                                    </ul>
                                    </p>
                                    <a href="javascript:void(0);" wire:click="subscribe('{{$item->slug}}')" class="tag {{$cardClass}} border-color-one" style="display: flex;">Subscribe</a>
                                </div>
                                <a href="javascript:void(0);" wire:click="subscribe('{{$item->slug}}')" class="service-btn rounded-button {{$cardClass}} border-color-one">
                                    <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 17L17 1H7.8" stroke="white" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        <!-- End Pricing Area -->
    
    </div>
    