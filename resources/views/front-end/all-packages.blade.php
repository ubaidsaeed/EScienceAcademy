<div>
     @php
        $segment = request()->segment(1);
        @endphp 
        <div class="cs-breadcrumb-area background-common custom-breadcumb" style="background-image: url(https://theme-coderstation.com/kan/assets/images/banner/breadcrumb-bg.png);">
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
                    <div class="row justify-content-center g-4 gy-5 ">
                        
                        <!--card one -->
                                @foreach ($subscription as $index => $item)
                                    @php
                                    $cardClass = '';
                                    $cardClass .= match ($item->slug) {
                                        'basic-learning-pack' => 'bg-one',
                                        'comprehensive-learning-pack' => 'bg-two',
                                        default => 'bg-three',
                                    };
                                @endphp
                                <div class="col-lg-3 col-md-6 " style="margin-bottom:6% !important">
                    <div class="service__card__style__four wow fadeInUp" data-wow-delay=".25s">
                        <div class="card-inner {{$cardClass}} border-color-one">
                            <div>
                                <p class="tag {{$cardClass}} border-color-one">{{ $item->name }}</p>
                                 <p class="tag text-one border-color-one card-text">Rs. {{ $item->price }}/-
                                                        {{ $item->duration_type }}</p>
                            </div>
                            <p class="card-desc">
                            {!! $item->content !!}
                            </p>
                            <a href="{{ route('subscription.package', $item->slug) }}" class="tag {{$cardClass}} border-color-one" style="display: flex;">Subscribe</a>
                        </div>
                        <a href="{{ route('subscription.package', $item->slug) }}" class="service-btn rounded-button {{$cardClass}} border-color-one">
                            <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 17L17 1H7.8" stroke="white" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                @endforeach
                <!--card two -->
                <!--                <div class="col-lg-3 col-md-6">-->
                <!--    <div class="service__card__style__four wow fadeInUp" data-wow-delay=".25s">-->
                <!--        <div class="card-inner bg-two border-color-one">-->
                <!--            <div>-->
                <!--                <p class="tag bg-two border-color-one" style="display: flex;text-align: -webkit-center;">Comprehensive Learning Pack</p>-->
                <!--                 <p class="tag bg-two border-color-one">Rs. 4500 /- Monthly</p>-->
                <!--            </div>-->
                <!--            <p class="card-desc">-->
                <!--            <ul class="pricing-list-wrapper">-->
                <!--                <li>-->
                <!--                    <strong>Course</strong> 1-->
                <!--                </li>-->
                <!--                <li>-->
                <!--                    <strong>Access</strong> Partial Access-->
                <!--                </li>-->
                <!--                <li>-->
                <!--                    <strong>Study Material</strong> All Notes-->
                <!--                </li>-->
                <!--                <li>-->
                <!--                    <strong>Details</strong>User can access all notes-->
                <!--                    </span>-->
                <!--                </li>-->
                               
                <!--            </ul>-->
                <!--            </p>-->
                <!--            <a href="{{route('subscription.package', 'comprehensive-learning-pack')}}" class="tag bg-two border-color-one" style="display: flex;">Subscribe</a>-->
                <!--        </div>-->
                <!--        <a href="{{route('subscription.package', 'comprehensive-learning-pack')}}" class="service-btn rounded-button bg-two border-color-one">-->
                <!--            <svg width="19" height="18" viewBox="0 0 19 18" fill="none"-->
                <!--                xmlns="http://www.w3.org/2000/svg">-->
                <!--                <path d="M1 17L17 1H7.8" stroke="white" />-->
                <!--            </svg>-->
                <!--        </a>-->
                <!--    </div>-->
                <!--</div>-->
                
                <!--card thrid -->
                <!--                <div class="col-lg-3 col-md-6">-->
                <!--    <div class="service__card__style__four wow fadeInUp" data-wow-delay=".25s">-->
                <!--        <div class="card-inner bg-three border-color-one">-->
                <!--            <div>-->
                <!--                <p class="tag bg-three border-color-one">Mastermind Plan</p>-->
                <!--                 <p class="tag bg-three border-color-one">Rs. 6000 /- Monthly</p>-->
                <!--            </div>-->
                <!--            <p class="card-desc">-->
                <!--            <ul class="pricing-list-wrapper">-->
                <!--                <li>-->
                <!--                    <strong>Course</strong> 1-->
                <!--                </li>-->
                <!--                <li>-->
                <!--                    <strong>Access</strong> Premium Access-->
                <!--                </li>-->
                <!--                <li>-->
                <!--                    <strong>Study Material</strong> Notes, Video Lectures & MCQs-->
                <!--                </li>-->
                <!--                <li>-->
                <!--                    <strong>Details</strong>User can access all notes and video lectures-->
                <!--                    </span>-->
                <!--                </li>-->
                               
                <!--            </ul>-->
                <!--            </p>-->
                <!--            <a href="{{route('subscription.package', 'mastermind-plan')}}" class="tag bg-three border-color-one" style="display: flex;">Subscribe</a>-->
                <!--        </div>-->
                <!--        <a href="{{route('subscription.package', 'mastermind-plan')}}" class="service-btn rounded-button bg-three border-color-one">-->
                <!--            <svg width="19" height="18" viewBox="0 0 19 18" fill="none"-->
                <!--                xmlns="http://www.w3.org/2000/svg">-->
                <!--                <path d="M1 17L17 1H7.8" stroke="white" />-->
                <!--            </svg>-->
                <!--        </a>-->
                <!--    </div>-->
                <!--</div>-->
                
                    </div>
                </div>
            </div>
        </section>
        <!-- End Pricing Area -->
    
    </div>
    