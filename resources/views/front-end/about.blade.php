@extends('layouts.frontend.app')

@section('content')
    @if($page_type == 'about')
    <div>
        
    
    <div class="cs-breadcrumb-area background-common custom-breadcumb" style="background-image: url({{asset('build/assets/frontend/images/escience-assets/custom-breadcrumb.jpeg')}}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- bread crumb inner wrapper -->
                    <div class="breadcrumb-inner text-center">
                       <h2 class="title">
                       @php
                           $content = $pageRecord->title;
                           if(request()->segment(3)){
                               $content = str_replace(ucfirst(request()->segment(2)), ' ', $content);
                               }
                               
                       @endphp
                       @if(request()->segment(3) == 'custom-query')
                       Create Your Plan
                       @else
                       {{ ucfirst($content) }}
                       @endif
                   </h2>
                        <div class="meta">
                            <span><a href="/">Home </a> / <a href="javascript:void(0)">{{ucfirst($pageRecord->title)}}</a></span>
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
    @if(request()->segment(1) == 'scholarships')
          @include('front-end.partials.scholarships')
    @elseif(request()->segment(1) == 'about-us')
    <!-- Start About Area -->
    <section class="cs__about__area bg-none">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="about__image__wrapper wow fadeInLeft" data-wow-delay=".25s">
                        <div class="image stuff2">
                        <img data-depth="0.4" src="{{asset('build/assets/frontend/images/escience-assets/farhan.jpeg')}}" width="513" alt="">
                        </div>
                        <div class="floating-text-area floating-up-2">
                            <h5 class="text">Experience <br> <span>20+ Years</span></h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about__content__wrapper">
                        <div class="section__title__area">
                            <h2 class="section__title__area__title text-anim">Welcome to eScience Academy!</h2>
                            <p class="section__title__area__description text-anim">Your trusted online learning platform for O & A Level students around the world.</p>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End About Area -->

     @elseif(request()->segment(1) == 'contact-us' || request()->segment(3) == 'custom-query' )
     @include('front-end.partials.contact-us')
     @elseif(request()->segment(1) == 'career')
     @include('front-end.partials.career')
     @endif
     @if (request()->segment(1) == 'subjects')
                @php
                    // Get the submenu (e.g., Physics) by slug from segment 3
                    $submenu = \App\Models\SubMenu::where('slug', request()->segment(2))
                        ->where('status', 'active')
                        // ->whereHas('menu', fn($q) => $q->where('slug', 'subjects')->where('status', 'active'))
                        ->first();
                    // print_r($submenu->id);
                    $levelRecord = collect(); // Empty collection by default

                    if ($submenu) {
                        // Fetch child menus (e.g., O Level, AS Level, A2 Level) excluding the current childSlug
                        $levelRecord = \App\Models\SubChildMenu::where('menu_child_id', $submenu->id)
                            ->where('slug', '!=', request()->segment(4))
                            ->where('status', 'active')
                            ->orderBy('priority', 'asc')
                            ->get();
                        // print_r($levelRecord);
                    }

                @endphp
            @endif
    @foreach ($SectionRecord  as $page_chunk)
                @if ($page_chunk->content_type == 1)
                    
                 <section class="@if (request()->segment(1) == 'scholarships') cs-project-details-area @elseif(request()->segment(1) == 'subjects') cs-service-details-area @else cs__about__area area-3 cs-section-gapBottom mt--150 @endif" id="package">
                      <div class="area-inner">
                    <div class="container">
                        @if (request()->segment(1) == 'subjects')
                                    <div class="row g-45">
                                        <div class="col-xl-4 col-lg-5">
                                            <div class="blog-right-sidebar">
                                                <div class="blog-single-widget service-list">
                                                    <h4 class="heading-title">{{ $submenu->name }}</h4>
                                                    <ul class="service-item-list">
                                                        @foreach ($levelRecord as $level)
                                                            <li><a
                                                                    href="{{ $level->page ? route('frontend.page', ['slug' => 'subjects', 'submenuSlug' => request()->segment(2), 'childSlug' => $level->page->slug]) : $level->link ?? '#' }}">{{ $level->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>


                                            </div>
                                        </div>
                                        <div class="col-xl-8 col-lg-7 order-change">
                                @endif
                    {!! $page_chunk->page_data !!}
                    @if (request()->segment(1) == 'subjects')
                            </div>
                        </div>
                @endif
                    </div>
                    </div>
                    </section>
                @elseif($page_chunk->content_type == 2)
                    @include('front-end.partials.subjects')
                @elseif ($page_chunk->content_type == 3)
                @include('front-end.partials.package')
                @elseif ($page_chunk->content_type == 4)
                @include('front-end.partials.assessment-boards')
                @elseif ($page_chunk->content_type == 5)
                @include('front-end.partials.counselling')
                @elseif ($page_chunk->content_type == 6)
                @include('front-end.partials.case-study')
                @endif
        @endforeach
    <!-- End Pricing Area -->
    @else
    <div class="cs-breadcrumb-area background-common" style="background-image: url(https://theme-coderstation.com/kan/assets/images/banner/breadcrumb-bg.png);">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <!-- bread crumb inner wrapper -->
                    <div class="breadcrumb-inner text-center">
                        <h2 class="title"></h2>
                        <div class="meta">
                            <span><a href="/">Home </a> / <a href="javascript:void(0)">eoorr</a></span>
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

@endif
@endsection
