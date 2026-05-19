@extends('layouts.frontend.app')

@section('content')
<div>
    <style>
        .custom-bg {
            background-repeat: no-repeat !important;
            background: fixed;
            background-position: top;
            background-size: 100%;
        }
    </style>
    @if (!isset($data['subscription']))
        @php
            $settings = \App\Models\Setting::first(); // ✅ Fetch only one record
            $site_description = $settings->site_description ?? '';
            $site_title = $settings->site_title ?? '';
            $words = explode(' ', str_replace("\n", ' ', $site_title)); // Split by space and replace new lines

        @endphp

            <!-- Start Banner Area -->
        <section class="cs__banner__one">
            <div class="cs__banner__one__bg-shape">
                <div class="bg-overlay"></div>

            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-10">
                        <div class="cs__banner__content">
                            <h2 class="cs__banner__content__title quote">{{ $words[0] ?? '' }} {{ $words[1] ?? '' }} <br>
                                {{ $words[2] ?? '' }}
                                {{ $words[3] ?? '' }}</h2>
                            <p class="cs__banner__content__description text-anim custom-font-size">
                                {!! $site_description !!}
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 order-change sm-hide">
                        <div class="cs__banner__one__hero stuff wow fadeIn" data-wow-delay=".55s">
                            <div class="flip-container">
                                <div class="flip-inner">
                                    <div class="flip-front">
                                        <img src="{{ asset('build/assets/frontend/images/escience-assets/fav-icon.svg') }}"
                                             width="400" alt="">
                                    </div>
                                    <div class="flip-back">
                                        <img src="{{ asset('build/assets/frontend/images/escience-assets/fav-icon.svg') }}"
                                             width="400" alt="">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="cs__banner__one__round-shape">
            </div>
        </section>
        <!-- End Banner Area -->

        @isset($pageSections)
            @foreach ($pageSections as $index => $section)
                @if ($section->content_type == 1)
                    {{-- <div class="feature-custom-class"> --}}
                    {!! $section->page_data !!}
                    {{-- </div> --}}
                @elseif($section->content_type == 3)
                    {{-- @include('front-end.templates.team')
 --}}

                    <section class="cs__pricing__area area-2 cs-section-gap mt--50 mb-hide custom-price-padding">
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-12">
                                      @php
                                            // Group subscriptions by level_name for dynamic tabs
                                            $levels = $subscription->groupBy('level_name')->keys();
                                        @endphp
                                    <div class="section__title__area text-left">
                                        <p class="section__title__area__sub-title custom-styling-card text-center wow fadeInUp"
                                           data-wow-delay=".25s">Price Table</p>
                                        <h2 class="section__title__area__title text-anim text-center">Lets Get Started</h2>

                                    </div>
                                </div>
                                <div class="col-xl-12 mb-hide">
                                    <div class="pricing-tabs custom-pricing-tabs text-center mb-5">
                                      
                                        @foreach ($levels as $index => $level)
                                            <button class="tab-pricing-btn {{ $index == 0 ? 'active' : '' }}" data-tab="{{ Str::slug($level) }}">{{ $level }}</button>
                                        @endforeach
                                    </div>
                                    @foreach ($levels as $index => $level)
                                        <div class="pricing-tab-content {{ $index == 0 ? 'active' : '' }}" id="{{ Str::slug($level) }}">
                                       <div
                                                class="swiper-pagination{{ $index }} swiper-pagination-clickable custom-center swiper-pagination-bullets swiper-pagination-horizontal">
                                           
                                            </div>
                                            <div class="pricing-slider-inner" data-wow-delay=".35s">
                                                <div class="swiper pricingSlider pricingSlider-Desktop">
                                                    <div class="swiper-wrapper">
                                                        @php
                                                            $count = 1;
                                                             $levelSubscriptions = $subscription->where('level_name', $level);
                                                              $packageCount = $levelSubscriptions->count();
                                                              $cardLayoutClass = $packageCount == 1 ? 'single-package' : 'multiple-packages';

                                                        @endphp
                                                        @foreach ($levelSubscriptions as $index => $item)
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
                                                            <div class="pricing-card-style-two" @if($cardLayoutClass == 'single-package') style="display: flex;justify-content: center;" @endif>
                                                                <div class="card-inner custom-height-package" @if($cardLayoutClass == 'single-package') style="width:25%" @endif>

                                                                    <div class="heading-space">
                                                                        <h4 cass="title text-center">{{ $item->name }}</h4>
                                                                    </div>
                                                                    <div class="content-space my-5">
                                                                       
                                                                             {!! $item->content !!}
                                                                        <ul class="pricing-list-wrapper">
                                                                            <!--<li><strong>Study Material:</strong></li>-->
                                                                            @if(isset($planFeatures[$item->id]) && $planFeatures[$item->id]->isNotEmpty())
                                                                                @foreach($planFeatures[$item->id] as $feature)
                                                                                    <li>
                                                                                        <i class="{{ $feature->icon ?? 'fa fa-check' }}"></i>
                                                                                        {{ $feature->name }}
                                                                                    </li>
                                                                                @endforeach
                                                                            @else
                                                                            @endif
                                                                        </ul>
                                                                    </div>
                                                                    <div class="price-area">
                                                                        <h4 class="price {{ $cardClass }}">
                                                                            @if($item->slug == 'custom-plan') Make Your Own Plan  @else <span
                                                                                class="currency">Starting from RS.</span><span
                                                                                class="amount">{{ $item->price }}/</span><br> <p style="color:white">(Per Month)</p>
                                                                            @endif
                                                                        </h4>
                                                                        <a class="subscribe-btn {{ $cardClass }}"
                                                                           href="@if($item->slug == 'custom-plan') {{ route('frontend.page', 'contact-us/' . $item->level_slug . '/custom-query') }} @else {{ route('subscription.package', $item->slug.'/'.$item->level_slug) }} @endif" >
                                                                            <span>@if($item->slug == 'custom-plan') Contact Us  @else Subscribe Now @endif</span></a>
                                                                    </div>

                                                                </div>
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
                                                        @php
                                                            if ($count != 1) {
                                                                echo '</div>';
                                                            }
                                                            
                                                        @endphp
                                                        <div class="swiper-slide">
                                                            <div class="pricing-card-style-two">
                                                                <table class="comparison-table">
                                                                    <tr>
                                                                        @foreach ($levelSubscriptions as $item)
                                                                            <th>{{ $item->name }}</th>
                                                                        @endforeach
                                                                    </tr>

                                                                    @foreach ($featureRecords as $feature)
                                                                            <tr>
                                                                                @foreach ($levelSubscriptions as $item)
                                                                                    @php
                                                                                        $planFeatureIds = collect(
                                                                                            $planFeatures[$item->id] ??
                                                                                                [],
                                                                                        )
                                                                                            ->pluck('id')
                                                                                            ->toArray();
                                                                                        $hasFeature = in_array(
                                                                                            $feature->id,
                                                                                            $planFeatureIds,
                                                                                        );
                                                                                    @endphp
                                                                                    <td>
                                                                                        @if ($hasFeature)
                                                                                            <i
                                                                                                class="fa-solid fa-check text-success"></i>
                                                                                        @else
                                                                                            <i
                                                                                                class="fa-solid fa-xmark text-danger"></i>
                                                                                        @endif
                                                                                        {{ $feature->name }}
                                                                                    </td>
                                                                                @endforeach
                                                                            </tr>
                                                                        @endforeach
                                                                </table>
                                                            </div>
                                                        </div>

                                                    </div>
                                        
                                                </div>
                                        
                                            </div>
                              
                                        </div>
                                    @endforeach
                                </div>
                    </section>

                    {{-- mobile --}}
                    <!-- Mobile Pricing Section -->
                    <section class="cs__pricing__area area-2 cs-section-gap mt--50 hide-none">
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-9">
                                    <div class="section_title_area text-left">
                                        <p class="section_titlearea_sub-title text-center wow  text-white" data-wow-delay=".25s">Price Table</p>
                                        <h2 class="section_titlearea_title text-anim text-white">Let's Get Started</h2>
                                        <div class="swiper-pagination-area">
                                            <div
                                                class="swiper-pagination10 swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal">
                                            </div>
                                        </div>
                                        <!-- Pricing Tabs -->
                                        <div class="pricing-tabs text-center mb-5">
                                            @php
                                                $levels = $subscription->groupBy('level_name')->keys();
                                            @endphp
                                            @foreach ($levels as $index => $level)
                                                <button class="tab-pricing-btn custom-tab-mb-btn {{ $index == 0 ? 'active' : '' }}" data-tab="m{{ Str::slug($level) }}">{{ $level }}</button>
                                            @endforeach
                                        </div>
                                    </div>
                                    @foreach ($levels as $index => $level)
                                        <div class="pricing-tab-content content-tab-mb-btn {{ $index == 0 ? 'active' : '' }}"  id="m{{ Str::slug($level) }}">
                                            <div class="pricing-slider-inner " data-wow-delay=".35s">
                                                <div class="swiper pricingSlider pricingSlider-Mobile">
                                                    
                                                    <div class="swiper-wrapper">
                                                        @php
                                                            $levelSubscriptions = $subscription->where('level_name', $level);
                                                            $features = [
                                                                'online_notes' => 'Topical Notes',
                                                                'top_past_paper' => 'Topical Unsolved Past Papers',
                                                                'ws_aw_bg' => 'Difficult Topical Question Lectures',
                                                                'recorded' => 'Recorded Lectures',
                                                            ];
                                                        @endphp
                                                        @foreach ($levelSubscriptions as $index => $item)
                                                            @php
                                                                $cardClass = '';
                                                                $cardClass .= match ($item->slug) {
                                                                    'basic-learning-pack' => 'bg-one',
                                                                    'comprehensive-learning-pack' => 'bg-two',
                                                                    'custom-plan' => 'bg-four',
                                                                    default => 'bg-three',
                                                                };
                                                            @endphp
                                                            <div class="swiper-slide">
                                                                <!-- Pricing Card -->
                                                                <div class="pricing-card-style-two">
                                                                    <div class="card-inner main-custom-center">
                                                                        <h2 class="title">{{ $item->name }}</h2>
                                                                        {!! $item->content !!}
                                                                         @if(isset($planFeatures[$item->id]) && $planFeatures[$item->id]->isNotEmpty())
                                                                                <ul class="feature-list-mobile">
                                                                                    @foreach($planFeatures[$item->id] as $feature)
                                                                                        <li>
                                                                                            <i class="{{ $feature->icon ?? 'fa fa-check' }}"></i>
                                                                                            {{ $feature->name }}
                                                                                        </li>
                                                                                    @endforeach
                                                                                </ul>
                                                                            @endif
                                                                        <div class="price-area">
                                                                            <!--<h4 class="price {{ $cardClass }}">-->
                                                                            <!--    @if($item->slug == 'custom-plan')-->
                                                                            <!--        Make Your Own Plan-->
                                                                            <!--    @else-->
                                                                            <!--        <span class="currency">RS.</span><span class="amount">{{ $item->price }}</span> /{{ $item->duration_type }}-->
                                                                            <!--    @endif-->
                                                                            <!--</h4>-->
                                                                             <h4 class="price {{ $cardClass }}">
                                                                            @if($item->slug == 'custom-plan') Make Your Own Plan  @else <span
                                                                                class="currency">Starting from RS.</span><span
                                                                                class="amount">{{ $item->price }}/</span><br> <p style="color:white">(Per Month)</p>
                                                                            @endif
                                                                        </h4>
                                                                        <a class="subscribe-btn {{ $cardClass }}"
                                                                           href="@if($item->slug == 'custom-plan') {{ route('frontend.page', 'contact-us/' . $item->level_slug . '/custom-query') }} @else {{ route('subscription.package', $item->slug.'/'.$item->level_slug) }} @endif" >
                                                                            <span>@if($item->slug == 'custom-plan') Contact Us  @else Subscribe Now @endif</span></a>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <a href="@if($item->slug == 'custom-plan') {{ route('frontend.page', 'contact-us/' . $item->level_slug . '/custom-query') }} @else {{ route('subscription.package', $item->slug.'/'.$item->level_slug) }} @endif"
                                                                       class="pricing-btn rounded-button d-none {{ $cardClass }}">
                                                                        <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M1 17L17 1H7.8" stroke="white" />
                                                                        </svg>
                                                                    </a>
                                                                </div>
                                                                <!-- End Pricing Card -->
                                                            </div>
                                                        @endforeach
                                                        <div class="swiper-slide">
                                                            <!-- Comparison Table -->
                                                            <div class="pricing-card-style-two">
                                                                <div class="responsive-table">
                                                                    <table class="comparison-table">
                                                                        <tr>
                                                                            @foreach ($levelSubscriptions as $item)
                                                                                <th>{{ $item->name }}</th>
                                                                            @endforeach
                                                                        </tr>
                                                                         @foreach ($featureRecords as $feature)
                                                                                <tr>
                                                                                    @foreach ($levelSubscriptions as $item)
                                                                                        @php
                                                                                            $planFeatureIds = collect(
                                                                                                $planFeatures[
                                                                                                    $item->id
                                                                                                ] ?? [],
                                                                                            )
                                                                                                ->pluck('id')
                                                                                                ->toArray();
                                                                                            $hasFeature = in_array(
                                                                                                $feature->id,
                                                                                                $planFeatureIds,
                                                                                            );
                                                                                        @endphp
                                                                                        <td>
                                                                                            @if ($hasFeature)
                                                                                                <i
                                                                                                    class="fa-solid fa-check text-success"></i>
                                                                                            @else
                                                                                                <i
                                                                                                    class="fa-solid fa-xmark text-danger"></i>
                                                                                            @endif
                                                                                            {{ $feature->name }}
                                                                                        </td>
                                                                                    @endforeach
                                                                                </tr>
                                                                            @endforeach
                                                                    </table>
                                                                </div>
                                                            </div>
                                                            <!-- End Comparison Table -->
                                                        </div>
                                                    </div>
                                                   <div class="swiper-pagination custom-swiper-pagination swiper-pagination-clickable custom-center swiper-pagination-bullets swiper-pagination-horizontal"></div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>
                    <!-- End Mobile Pricing Section -->

                @elseif($section->content_type == 2)
                    @include('front-end.partials.subjects')
                @elseif($section->content_type == 4)
            
                    @include('front-end.partials.assessment-boards')
                @elseif($section->content_type == 5)
                    @include('front-end.partials.counselling')
                @elseif($section->content_type == 6)
                    @include('front-end.partials.case-study')
                @elseif($section->content_type == 7)
                    @include('front-end.partials.achievements')
                @elseif($section->content_type == 8)
                    @include('front-end.partials.faqs')
                @endif
            @endforeach
        @endisset
        <!-- End Subject Area -->
    @else
       
    @endif
</div>
@endsection

@push('script')
    <script src="{{ asset('build/assets/admin/js/custom/message.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush