<div>
    <div class="cs-breadcrumb-area background-common"
        style="background-image: url(https://theme-coderstation.com/kan/assets/images/banner/breadcrumb-bg.png);">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- bread crumb inner wrapper -->
                    <div class="breadcrumb-inner text-center">
                        <h2 class="title">
                            @isset($data['caseStudies']->title){{$data['caseStudies']->title}} @else Case Studies @endif
                        </h2>
                        <div class="meta">
                            <span>Home / <a href="javascript:void(0)">@isset($data['caseStudies']->title){{$data['caseStudies']->title}} @else Case Studies @endisset</a></span>
                        </div>
                    </div>
                    <!-- bread crumb inner wrapper end -->
                </div>
            </div>
        </div>
        <div class="breadcrumb-shape-img">
            <img class="one floating-up-3" src="https://theme-coderstation.com/kan/assets/images/footer/dotted-03.svg"
                alt="">
            <img class="two" src="assets/images/footer/leaf.svg" alt="">
            <img class="three" src="https://theme-coderstation.com/kan/assets/images/banner/four-round.svg"
                alt="">
            <img class="four wow fadeIn" data-wow-delay=".55s"
                src="	https://theme-coderstation.com/kan/assets/images/footer/round.svg" alt="">
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
            @if ($data['page_type'] == 'caseStudiesSingle')
                {!! $data['caseStudies']->content !!}
            @elseif($data['page_type'] == 'caseStudies')
                <div class="section__title__area text-center">
                    <p class="section__title__area__sub-title color-one text-center wow fadeInUp" data-wow-delay=".25s">
                        Best
                        Of
                        Our Packages</p>
                    <h2 class="section__title__area__title text-anim">We Provided
                        Case study</h2>
                </div>

                <div class="section-inner mt--60">
                    <div class="row justify-content-center g-4 gy-5">
                        <div class="swiper-wrapper">
                            <!-- Service Card -->
                            <div class="row">
                                @foreach ($data['caseStudies'] as $case)
                                    <div class="col-lg-4 col-md-6 my-4">
                                        <div class="project-card-style-three">
                                            <div class="image-area">
                                                <a href="#">
                                                    <img src="{{ asset('storage/app/case_study/' . $case->image) }}"
                                                        alt="{{ $case->title }}">
                                                </a>
                                            </div>
                                            <div class="content">
                                                <p class="tag color-one">{{ $case->board_names }}</p>
                                                <h3 class="title animated fadeIn">
                                                    <a href="#">{{ $case->title }}</a>
                                                </h3>
                                            </div>
                                            <a href="{{ route('frontend.case-studies', $case->slug) }}"
                                                class="project-btn rounded-button bg-three">
                                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1 17L17 1H7.8" stroke="white"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>


                        {{-- </div> --}}
                    </div>
                </div>
            @endif
    </section>
    <!-- End Pricing Area -->

</div>
