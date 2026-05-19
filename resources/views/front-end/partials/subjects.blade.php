<!-- Start Subject Area -->
@php
    $subjects = \App\Models\Subject::query()
        ->select([
            'subjects.id',
            'subjects.name',
            'subjects.slug',
            'subjects.image_url',
            'subjects.status',
            'subjects.created_at',
            \DB::raw('GROUP_CONCAT(levels.name ORDER BY levels.name ASC) as level_names')
        ])
        ->leftJoin('subject_boards', 'subjects.id', '=', 'subject_boards.subject_id')
        ->leftJoin('levels', function($join) {
            $join->on('subject_boards.level_id', '=', 'levels.id')
                 ->where('levels.status', 'active');
        })
        ->where('subjects.status', 'active')
        ->groupBy('subjects.id', 'subjects.name', 'subjects.slug', 'subjects.image_url', 'subjects.status', 'subjects.created_at')
        ->orderBy('subjects.created_at', 'asc')
        ->get();
@endphp


<!-- Start About Area -->
<section class="cs__about__area area-4 mb-hides">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="about__content__wrapper-2">
                    <div class="section__title__area">
                        <p class="section__title__area__sub-title color-one wow fadeInUp" data-wow-delay=".25s">Welcome to
                            eScience Academy,</p>
                        <h2 class="section__title__area__title text-anim">Your Trusted online <br> learning platform
                        </h2>
                        <p class="section__title__area__description text-anim">trusted online learning platform for O & A
                            Level students around the world.</p>
                    </div>
                    <div class="button-area wow fadeInLeft" data-wow-delay=".35s">
                        <a href="javascript:void(0)" class="rounded-button bg-one d-none">
                            <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M1 17L17 1H7.8" stroke="#ffffff" />
                            </svg>
                        </a>
                        <a href="javascript:void(0)" class="cs-btn bg-one">All Services</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="about__image__wrapper-3 wow zoomIn" data-wow-delay=".35s">
                    <div class="image text-lg-end text-center">
                        <img src="{{ asset('build/assets/frontend/images/about/custom-icons-home.png') }}" width="603"
                            alt="">
                    </div>
                    <div class="shape-image floating-up-3">
                        <img src="{{ asset('build/assets/frontend/images/about/small-line.svg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="about__bottom__project__area cs-section-gapTop">
        <div class="row g-4 gy-5 justify-content-center">
            @foreach ($subjects as $subject)
                @php
                    $cardClass = '';
                    $cardClass .= match ($subject->slug) {
                        'physics' => 'bg-one',
                        'mathematics' => 'bg-two',
                        'chemistry' => 'bg-four',
                        default => 'bg-three',
                    };
                    
                    // Get levels text - fallback if no levels
                    $levelsText = $subject->level_names ?? 'O Level, AS Level, A2 Level';
                @endphp
                <div class="col-lg-3 col-md-6 col-sm-10">
                    <!-- Project Card -->
                    <div class="project-card-style-two wow fadeInUp" data-wow-delay="0.25s">
                        <div class="image-area">
                            <a href="{{ url('/subjects/' . ($subject->slug ?? '')) }}">
                                <img src="{{ asset('storage/app/public/subject/' . $subject->image_url) }}" alt="{{ $subject->name }}">
                            </a>
                        </div>
                        <div class="content-area">
                            <div class="content-inner {{ $cardClass }}">
                                <p class="sub-title">{{ $levelsText }}</p>
                                <h2 class="title"><a href="{{ url('/subjects/' . ($subject->slug ?? '')) }}">{{ $subject->name }}</a></h2>
                            </div>
                            <a href="{{ url('/subjects/' . ($subject->slug ?? '')) }}" class="project-btn rounded-button {{ $cardClass }} d-none">
                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 17L17 1H7.8" stroke="white" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    <!-- Project Card -->
                </div>
            @endforeach
        </div>
    </div>
</section>
<!-- End About Area -->