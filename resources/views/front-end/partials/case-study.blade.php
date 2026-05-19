@php
$caseStudy = Illuminate\Support\Facades\DB::table('case_study')
    ->join('case_study_board', 'case_study_board.case_id', '=', 'case_study.id')
    ->join('boards', 'boards.id', '=', 'case_study_board.board_id')
    ->where('case_study.status', 'active')
    ->select(
        'case_study.id',
        'case_study.image',
        'case_study.slug',
        'case_study.title',
        'case_study.content',
        'case_study.created_at',
        Illuminate\Support\Facades\DB::raw("GROUP_CONCAT(DISTINCT boards.name ORDER BY boards.name ASC SEPARATOR ', ') as board_names")
    )
    ->groupBy('case_study.id', 'case_study.image', 'case_study.slug', 'case_study.title', 'case_study.content', 'case_study.created_at')
    ->orderBy('case_study.created_at', 'desc')
    ->limit(3)
    ->get();

@endphp

<!-- Start Case Study Area -->
<div class="cs__case__study__area cs__case__study__area2 cs-section-gapTop" id="case_study">
    <div class="container">
        <div class="section__title__area style-two">
            <img src="{{ asset('build/assets/frontend/images/project/icon/01.svg') }}" alt="" class="title-shape floating-up-3">
            <p class="section__title__area__sub-title color-one wow fadeInUp" data-wow-delay=".35s">Case Studies & Case Studies</p>
            <h2 class="section__title__area__title text-anim">
                <div>We Provided</div>
                <div>Case study</div>
            </h2>
            <div class="button-area wow fadeInRight" data-wow-delay=".45s">
                <a href="{{ route('frontend.case-studies', ['slug' => null]) }}" class="rounded-button bg-one">
                    <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 17L17 1H7.8" stroke="#ffffff"></path>
                    </svg>
                </a>
                <a href="{{ route('frontend.case-studies', ['slug' => null]) }}" class="cs-btn bg-one">All Case Studies</a>
            </div>
        </div>

        <!-- Swiper Section -->
        <div class="section-inner wow fadeInUp" data-wow-delay=".35s">
            <div class="swiper projectSlider2 swiper-initialized swiper-horizontal swiper-backface-hidden">
                <div class="swiper-wrapper">
                    
                    @foreach ($caseStudy as $case)
                        <div class="swiper-slide">
                            <!-- Case Study Card -->
                            <div class="project-card-style-three">
                                <div class="image-area">
                                    <a href="#">
                                        <img src="{{ asset('storage/app/case_study/' . $case->image) }}" alt="{{ $case->title }}">
                                    </a>
                                </div>
                                <div class="content">
                                    <p class="tag color-one">{{ $case->board_names }}</p> <!-- Display Board Names -->
                                    <h3 class="title fadeIn">
                                        <a href="#">{{ $case->title }}</a>
                                    </h3>
                                </div>
                                <a href="{{ route('frontend.case-studies', $case->slug) }}" class="project-btn rounded-button bg-three">
                                    <svg width="19" height="18" viewBox="0 0 19 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 17L17 1H7.8" stroke="white"></path>
                                    </svg>
                                </a>
                            </div>
                            <!-- End Case Study Card -->
                        </div>
                    @endforeach
                    
                </div>
            </div>

            <!-- Swiper Pagination -->
            <div class="swiper-pagination3 swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal"></div>
        </div>
    </div>
</div>
