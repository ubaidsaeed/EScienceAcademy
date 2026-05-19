<!-- Start Assessment Board Area -->
@php
    $boards = \App\Models\Board::orderBy('created_at', 'asc')->where('status','active')->get();
@endphp

 <!-- Start Working Process Area -->
    <section class="cs__working__process__area cs-section-gap">
        <div class="container">
            <div class="section__title__area text-center">
                <p class="section__title__area__sub-title color-one text-center wow fadeInUp" data-wow-delay=".25s">Best Of Our Boards</p>
                <h2 class="section__title__area__title text-anim">Assessment Boards</h2>
            </div>
            <div class="section-inner">
                <div class="row justify-content-center g-4 gy-5">
                    <!-- Service Card -->
                     @foreach ($boards as $board)
                    @php
                        $cardClass = '';
                        $cardClass .= match ($board->slug) {
                            'caie' => 'bg-one',
                            'aqa-education' => 'bg-two',
                            default => 'bg-four',
                        };
                    @endphp
                    <div class="col-lg-3 col-md-6">
                        <div class="service__card__style__one wow fadeInUp" data-wow-delay=".25s">
                            <div class="card-inner custom-assesment-board {{ $cardClass }}">
                                <div class="service-icon">
                                    <img class="custom-image-layouts" src="{{$board->image_url}}" alt="">
                                </div>
                                <h3 class="service-title">{{$board->name}}</h3>
                                <p class="tag {{ $cardClass }}">Quality Education</p>
                                <p class="service-desc">{{$board->content}}</p>
                            </div>
                            <a href="{{ route('frontend.board', $board->slug) }}" class="service-btn rounded-button d-none {{ $cardClass }}">
                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 17L17 1H7.8" stroke="white" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                    <!-- Service Card -->
                
                </div>
            </div>
        </div>
    </section>
    <!-- End Working Process Area -->
