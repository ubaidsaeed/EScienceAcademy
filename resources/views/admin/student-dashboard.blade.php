<div>
    <style>
        .avatar-xl {
            width: 5rem !important;
            height: 5rem !important;
            line-height: 4rem;
            font-size: 1.75rem;
        }

        .plan {
            font-size: x-large;
        }


        /*
         */
        * {
            box-sizing: border-box
        }

        body {
            font-family: Verdana, sans-serif;
            margin: 0
        }

        .mySlides {
            display: none
        }

        img {
            vertical-align: middle;
        }

        /* Slideshow container */
        .slideshow-container {
            max-width: 1000px;
            position: relative;
            margin: auto;
        }

        /* Next & previous buttons */
        .prev,
        .next {
            cursor: pointer;
            position: absolute;
            top: 50%;
            width: auto;
            padding: 16px;
            margin-top: -22px;
            color: white;
            font-weight: bold;
            font-size: 18px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
        }

        /* Position the "next button" to the right */
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        /* On hover, add a black background color with a little bit see-through */
        .prev:hover,
        .next:hover {
            background-color: rgba(0, 0, 0, 0.8);
        }

        /* Caption text */
        .text {
            color: #f2f2f2;
            font-size: 15px;
            padding: 8px 12px;
            position: absolute;
            bottom: 8px;
            width: 100%;
            text-align: center;
        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;
        }

        /* The dots/bullets/indicators */
        .dot {
            cursor: pointer;
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }

        .fade:not(.show) {
            opacity: 63;
        }

        .active,
        .dot:hover {
            background-color: #717171;
        }

        /* Fading animation */
        .fade {
            animation-name: fade;
            animation-duration: 1.5s;
        }

        @keyframes fade {
            from {
                opacity: .4
            }

            to {
                opacity: 1
            }
        }

        /* On smaller screens, decrease text size */
        @media only screen and (max-width: 300px) {

            .prev,
            .next,
            .text {
                font-size: 11px
            }
        }

        .numbertext {
            color: black;
            font-size: 18px;
            font-weight: bold;
            display: flex;
        }

        i.fa-solid.fa-book {
            font-size: 60px;
        }
    </style>

    <!-- {{-- user  --}} -->
    {{-- show error  --}}
    @if (isset($main_parent_name['cancelMessage']))
 <script>
   document.addEventListener('livewire:initialized', () => {
    Swal.fire({
        html :`You've been unsubscribed from our Portal. You will no longer receive an updates & offers. <br> If this was a mistake or you change your mind, you can resubscribe on click below.`,
        imageUrl: "https://escienceacademy.com/build/assets/frontend/images/escience-logo.svg",
        imageHeight: 100,
        confirmButtonColor: "#3A833A",
        confirmButtonText: "RESUBSCRIBE"
    }).then((result) => {
        if (result.isConfirmed) {
             @this.call('RESUBSCRIBE');
        }
    });
});
</script>
 @elseif (isset($main_parent_name['expirePackage']))
 <script>

                const plan_id = {!! json_encode($main_parent_name['plan_id']) !!};
                const level_id = {!! json_encode($main_parent_name['level_id']) !!};
            document.addEventListener('livewire:initialized', () => {
                Swal.fire({
                    title: 'Package Expired',
                    text: 'Your current package has expired. Please upgrade to continue using services.',
                    icon: 'warning',
                    confirmButtonText: 'Upgrade Now'
                }).then((result) => {
                    if (result.isConfirmed) {
                         const url = `/ReNewPackage/${plan_id}/${level_id}`;
                    window.location.href = url;
                    }
                });
            });
        </script>
        @elseif (isset($main_parent_name['errorMessage']))
        <div class="alert alert-danger alert-message fade show" style="width: 400px"  role="alert" id="error-alert">
            {{ $main_parent_name['errorMessage'] }}
        </div>
    @else
        <!-- subbscription plan -->
        <div class="row">
            <div class="col-md-12">
                <div class="card my-2" id="tabs-style3">
                    <div class="card-body">
                        <div class="panel panel-primary tabs-style-3">
                            <div class="tab-menu-heading">
                                <div class="tabs-menu ">
                                    <!-- Tabs -->
                                    <div class="col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-3">
                                                Active Plan
                                                <br>
                                                <h1 class="plan">{{ $main_parent_name['plan_name'] }} </h1>
                                            </div>
                                            <div class="col-lg-3">
                                                Board
                                                </br>
                                                <h1 class="plan">
                                                    @if ($main_parent_name['main_parent_name'] != 'Theory' && $main_parent_name['main_parent_name'] != 'MCQS')
                                                        {{ $main_parent_name['main_parent_name'] }}
                                                    @else
                                                        {{ $main_parent_name['main_first_parent_name'] }}
                                                    @endif
                                                </h1>
                                            </div>
                                            <div class="col-lg-3">
                                                Cost
                                                </br>
                                                <h1 class="plan">PKR.{{ $main_parent_name['price'] }} </h1>
                                            </div>
                                            <div class="col-lg-3">
                                                Renewal Date
                                                </br>
                                                <h1 class="plan">{{ $main_parent_name['renewal_date'] }}</h1>

                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="progress progress-md my-" style="margin-top: 40px;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" style="width: 86%">86%</div>
                                </div> --}}
                                    <div class="progress progress-md my-4" style="margin-top: 40px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info"
                                            style="width: {{ $completionPercentage }}%">
                                            {{ $completionPercentage }}%
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-8 my-4">

                                            <!--<label class="custom-switch">-->
                                            <!--    <input type="checkbox" name="custom-switch-checkbox"-->
                                            <!--        class="custom-switch-input">-->
                                            <!--    <span class="custom-switch-indicator"></span>-->
                                            <!--    <span class="custom-switch-description">Auto Renewal</span>-->
                                            <!--</label>-->
                                            <!--<div class="form-label"><span class="custom-switch-description">-->
                                            <!--    Your plan-->
                                            <!--        will-->
                                            <!--        automatically renew-->
                                                    <!--on: 02/17/2025. Payment Amount: PKR250-->
                                            <!--        </span>-->
                                            <!--</div>-->
                                        </div>
                                        <div class="col-lg-4 my-5">
                                            <div class="row">
                                                <div class="ml-12">
                                                    <button type="button" class="btn btn-outline-danger ml-7"  onclick="cancelPlan()">Cancel
                                                        plan
                                                    </button>
                                                     <a type="button" class="btn btn-success ml-1"
                                                        onclick="upgradePlan({ plan_id: {{ $main_parent_name['plan_id'] }}, level_id: {{ $main_parent_name['level_id'] }} })">
                                                        Upgrade plan
                                                    </a>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- subscription plan -->
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                <div class="card" id="media">

                    <div class="card-body">
                        <div class="media d-block d-sm-flex">
                            <img alt="" class="avatar avatar-xl brround me-3"
                                src="{{ asset('build/assets/admin/images/users/images.jpg') }}">
                            <div class="media-body pt-4 pt-sm-0">
                                <h5 class="mg-b-5 tx-inverse  tx-15 my-3">
                                    {{ Illuminate\Support\Facades\Auth::user()->name }}

                                </h5>
                                <ul _ngcontent-ng-c2954972171="" class="list-inline mb-0">
                                    <!--<li _ngcontent-ng-c2954972171="" class="list-inline-item me-3 mb-1 mb-sm-0"><span-->
                                    <!--        _ngcontent-ng-c2954972171="" class="h6">255</span><span-->
                                    <!--        _ngcontent-ng-c2954972171="" class="text-body fw-light ms-1">points</span>-->
                                    <!--</li>-->
                                    <!--<li _ngcontent-ng-c2954972171="" class="list-inline-item me-3 mb-1 mb-sm-0"><span-->
                                    <!--        _ngcontent-ng-c2954972171="" class="h6 ms-1">0</span><span-->
                                    <!--        _ngcontent-ng-c2954972171="" class="text-body fw-light ms-1">Completed-->
                                    <!--        courses</span></li>-->
                                    <li _ngcontent-ng-c2954972171="" class="list-inline-item me-3 mb-1 mb-sm-0"><span
                                            _ngcontent-ng-c2954972171=""
                                            class="h6 ms-1">{{ $main_parent_name['completed_lectures'] }}</span><span
                                            _ngcontent-ng-c2954972171="" class="text-body fw-light ms-1">Completed
                                            lessons</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
                
                <div class="col-md-6">
                        <div class="card">
                            <div class="card-header border-bottom-0">
                                {{-- <h3 class="card-title ">Defalut Accordion</h3> --}}
                            </div>
                            <div class="card-body">
                                <div aria-multiselectable="true" class="accordion" id="accordion" role="tablist">
                                    <div class="acc-card">
                                        <div class="acc-header" id="headingOne" role="tab">
                                            <h5 class="mb-0">
                                                <a aria-controls="collapseOne" aria-expanded="true"
                                                    data-bs-toggle="collapse" href="#collapseOne"
                                                    class="">Subscription Detail</a>
                                            </h5>
                                        </div>
                                        <div aria-labelledby="headingOne" class="collapse  " data-parent="#accordion"
                                            id="collapseOne" role="tabpanel" style="">
                                            <div class="acc-body">
                                                <div class="form-label">
                                                     <ul class="d-flex flex-wrap justify-content-center gap-3 p-0">
                                                        
                                                        <li class="login-social">
                                                            Board :{{ $subscription->board_name }}
                                                            <i class="fa-solid fa-circle-check"></i>

                                                        </li>
                                                        <li class="login-social">
                                                            Level : {{ $subscription->level_name}}
                                                            <i class="fa-solid fa-circle-check"></i>

                                                        </li>
                                                        <li class="login-social">
                                                         Subject : {{ $subscription->subject_name }}
                                                            <i class="fa-solid fa-circle-check"></i>

                                                        </li>
                                                    </ul>
                                                    <ul class="my-5">
                                                        <li class="login-social">

                                                         {!! $subscription->content !!}
                                                        </li>
                                                    </ul>
                                                   
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- accordion -->
                            </div>
                        </div>
                    </div>
                    </div>
            </div>  
            <div class="col-lg-12 mb-3 d-flex flex-column justify-content-center custom-top-tutorial">
                 <div class="card">
                    <div class="card-body">
                <h2 class="text-center">Quick Start</h2>
            <h4 class="text-center">A quick 40 second watch that’ll help you</h4>
                <iframe  style="border: 1px solid rgba(0, 0, 0, 0.1);" width="100%" height="500" src="https://on.driveway.app/guides/VA7mjOE/embed" allow="clipboard-write" allowfullscreen></iframe>
            </div>
            </div>
            </div>
             <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <canvas id="myChart" style="width:100%;" wire:ignore></canvas>
                    </div>
                    {{-- </div> --}}
                </div>
            </div>
        </div>

        <!-- end user  -->
        <!-- {{-- end day counter --}} -->
        <!-- my course section  -->
        
        <div class="accordion" id="headerAccordion" >
    @foreach ($folderHierarchy as $folder)
        <x-folder-accordion :folder="$folder" :openFolders="$openFolders" :level="0" />
    @endforeach
</div>
        <!-- end my course section  -->
        <!-- {{-- prog of student --}} -->


        <!-- {{-- end prog student --}}
        {{-- meeting  --}} -->
        
        <!-- {{-- end meeting --}} -->
         <div>
            <!-- Bootstrap Modal for Media Records   images -->
            <div class="modal fade " id="mediaModal" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true" style="display:{{$style}}">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mediaModalLabel">Media</h5>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                wire:click="closeMediaModal({{$refresh_id }})"><i class="bi bi-x "></i></button>

                        </div>
                        <div class="modal-body" id="slideRecord">
                            <!-- Render the dynamic HTML here -->
                            {{-- {!! $mediaHtml !!} --}}
                            <!-- Slideshow container -->
                            <h4>{{ $totalComplete + 1 }} / {{ $totalMediaCount }}</h4>

                            <div class="slideshow-container">
                                @foreach ($mediaHtml as $index => $item)
                                    @php
                                        $custom_properties = json_decode($item->custom_properties, true);
                                        $media_type = $custom_properties['media_type'] ?? null;
                                        $vimeo_id = $custom_properties['vimeo_id'] ?? null;
                                        $vimeo_access_token = config('services.vimeo.access_token');
                                        
                                        // Build Vimeo embed URL with access token for private videos
                                        if ($vimeo_id) {
                                            $vimeo_embed_url = 'https://player.vimeo.com/video/' . $vimeo_id;
                                            $embed_params = 'autoplay=0&title=0&byline=0&portrait=0';
                                            if ($vimeo_access_token) {
                                                $embed_params .= '&access_token=' . urlencode($vimeo_access_token);
                                            }
                                            $vimeo_embed_url .= '?' . $embed_params;
                                        } else {
                                            $vimeo_embed_url = null;
                                        }
                                    @endphp

                                    <div class="mySlides fade"
                                        @if ($index == 0) style="display: block;" @endif>
                                        <div class="row">
                                            <div class="col-lg-2"></div>
                                            <div class="col-lg-8">
                                                {{-- {{ $item->full_path }} --}}
                                            </div>
                                        </div>

                                        @if ($media_type === 'vimeo' && !empty($vimeo_embed_url))
                                           
                                            <div class="video-container"
                                                style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden;">
                                                <iframe
                                                    src="{{ $vimeo_embed_url }}"
                                                    frameborder="0" allow="autoplay; fullscreen; picture-in-picture"
                                                    allowfullscreen
                                                    style="position:absolute; top:0; left:0; width:100%; height:100%;">
                                                </iframe>
                                            </div>
                                        @else
                                            @php
                                                $extension = strtolower(pathinfo($item->path, PATHINFO_EXTENSION));
                                            @endphp

                                            @if ($extension === 'mp4')
                                                <video width="100%" controls>
                                                    <source src="{{ $item->path }}" type="video/mp4">
                                                </video>
                                            @else
                                                <img src="{{ $item->path }}" alt="{{ $item->name }}"
                                                    style="width:100%">
                                            @endif
                                        @endif

                                        <input type="hidden" class="currentSlide" value="{{ $item->id }}"
                                            data-id="{{ $item->model_id }}">
                                    </div>
                                @endforeach

                                <a class="prev" onclick="prevSlides(1)">❮</a>
                                <a class="next" onclick="plusSlides(1)">❯</a>
                            </div>
                            <meta name="csrf-token" content="{{ csrf_token() }}">
                        </div>
                        <div class="modal-footer">
                            <!-- Trigger button to simulate calling the method -->
                            {{-- <button wire:click="completeCourse(1)" class="btn btn-primary">Complete Course</button> --}}
                            <!--<button type="button" class="btn btn-secondary" data-bs-dismiss="modal"-->
                            <!--    onclick="window.location.reload();">Close</button>-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</div>
@push('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script>
   
   function cancelPlan() {
        Swal.fire({
            title: 'Are you sure?', 
            text: "Do you want to cancel your current plan?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                    @this.call('cancel');
                
            }
        });
    }
      function upgradePlan(data) {
            Swal.fire({
                title: 'Proceed to upgrade?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Upgrade',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                      const url = `/ReNewPackage/${data.plan_id}/${data.level_id}`;
            window.location.href = url;
                }
            });
        }
   Livewire.on('closeMediaModal', () => {
        const mediaModal = document.getElementById('mediaModal'); 
        const videos = mediaModal.querySelectorAll('video');
        videos.forEach(video => {
            video.pause();
            video.currentTime = 0;
        });

        const modalInstance = bootstrap.Modal.getInstance(mediaModal);
        if (modalInstance) {
            modalInstance.hide(); // Properly hides the modal
        }
    });
    document.addEventListener('livewire:initialized', () => {
         
    Livewire.on('folderHierarchyUpdated', (event) => {
        event.forEach(folder => {

            const countElement = document.querySelector(`#count_${folder.media_id}`);
            const progress = document.querySelector(`#progress_${folder.media_id}`);

            const restart = document.querySelector(`#restart_${folder.media_id}`);
            const continueBtn = document.querySelector(`#continue_${folder.media_id}`);
            const start = document.querySelector(`#start_${folder.media_id}`);
            const complete = document.querySelector(`#complete_${folder.media_id}`);
            const exam = document.querySelector(`#exam_${folder.media_id}`);

            if (folder.completedCount > 0 && folder.completedCount < folder.totalMediaCount) {
                start?.classList.add('d-none');
                restart?.classList.remove('d-none');
                continueBtn?.classList.remove('d-none');
                complete?.classList.add('d-none');
            } else if (folder.completedCount === 0) {
                exam?.classList.add('d-none');
                 complete?.classList.add('d-none');
                restart?.classList.add('d-none');
                continueBtn?.classList.add('d-none');
                start?.classList.remove('d-none');
            } else if (folder.completedCount === folder.totalMediaCount) {
                exam?.classList.remove('d-none');
                complete?.classList.remove('d-none');
                start?.classList.add('d-none');
                restart?.classList.remove('d-none');
                continueBtn?.classList.add('d-none');
            }
            if (countElement  && progress ) {
                const percent = parseFloat(folder.progressPercentage).toFixed(1);
                progress.style.width = percent + '%';
                progress.innerHTML = percent + '%';
                countElement.innerHTML = folder.completedCount;
            } else {
                console.warn(`Missing DOM elements for media_id: ${folder.media_id}`);
            }
        });
    });

    Livewire.on('alertError', (message) => {
        alert(message);
    });
});


        const labels = {!! json_encode($progress->pluck('date')) !!};
        const data = {!! json_encode($progress->pluck('total')) !!};

        new Chart("myChart", {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    label: "Daily Completed Topics",
                    data: data,
                    fill: false,
                    borderColor: "blue",
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        // loading
        function showLoading(button) {
            const spinner = button.querySelector('.spinner-border');
            const buttonText = button.querySelector('span:last-child');

            // Show spinner and disable the button
            button.setAttribute('disabled', true);
            spinner.classList.remove('d-none');
            buttonText.textContent = 'Loading...';

            // Listen for Livewire events to re-enable the button
            Livewire.on('loadingComplete', () => {
                button.removeAttribute('disabled');
                spinner.classList.add('d-none');
                buttonText.textContent = 'Complete';
            });
        }
        document.addEventListener('livewire:init', () => {
            let slideIndex = 1;

            function showSlides(n) {
                const slides = document.querySelectorAll(".mySlides");
                const dots = document.querySelectorAll(".dot");

                if (slides.length === 0) {
                    console.error("No slides found in the DOM.");
                    return;
                }

                // Handle index overflow
                if (n > slides.length) slideIndex = 1;
                if (n < 1) slideIndex = slides.length;

                // Hide all slides
                slides.forEach(slide => {
                    slide.style.display = "none";
                });

                // Deactivate all dots
                dots.forEach(dot => {
                    dot.classList.remove("active");
                });

                // Show the current slide
                slides[slideIndex - 1].style.display = "block";

                // Activate the corresponding dot
                if (dots.length > 0) {
                    dots[slideIndex - 1].classList.add("active");
                }
            }
                window.prevSlides = function(n) {
                const currentSlide = document.querySelector('.mySlides[style*="block"] .currentSlide');
                if (currentSlide) {
                    const currentSlideValue = currentSlide.value;
                    const currentSlideId = currentSlide.getAttribute('data-id');

                    // Get CSRF token
                    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfTokenElement ? csrfTokenElement.content : '';

                    if (!csrfToken) {
                        console.error('CSRF token is missing');
                        return;
                    }

                    $.ajax({
                        url: '/student/previous-slide',
                        type: 'POST',
                        data: {
                            file_id: currentSlideValue, // Send current slide ID
                            model_id: currentSlideId,
                            _token: csrfToken,
                        },
                        success: function(response) {
                            if (response.mediaHtml.length > 0) {
                                let slideRecord =
                                    `<h4>${response.totalComplete} / ${response.totalMediaCount}</h4>`;
                                slideRecord += '<div class="slideshow-container">';

                                $.each(response.mediaHtml, function(index, value) {
                                    // Parse custom properties safely
                                    let customProperties = {};
                                    try {
                                        customProperties = JSON.parse(value
                                            .custom_properties || '{}');
                                    } catch (e) {
                                        console.warn('Invalid custom_properties JSON', e);
                                    }

                                    const mediaType = customProperties.media_type || '';
                                    const vimeoId = customProperties.vimeo_id || null;
                                    const vimeoEmbedUrl = vimeoId ?
                                        `https://player.vimeo.com/video/${vimeoId}` : null;
                                    const extension = value.path.split('.').pop()
                                        .toLowerCase();

                                    slideRecord += `
                            <div class="mySlides fade" ${index === 0 ? 'style="display: block;"' : ''}>
                                <div class="row">
                                    <div class="col-lg-2"></div>
                                    <div class="col-lg-8">${value.full_path || ''}</div>
                                </div>`;

                                    // Check media type and render accordingly
                                    if (mediaType === 'vimeo' && vimeoEmbedUrl) {
                                        slideRecord += `
                                <div class="video-container" style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden;">
                                    <iframe 
                                        src="${vimeoEmbedUrl}?autoplay=0&title=0&byline=0&portrait=0" 
                                        frameborder="0" 
                                        allow="autoplay; fullscreen; picture-in-picture" 
                                        allowfullscreen
                                        style="position:absolute; top:0; left:0; width:100%; height:100%;">
                                    </iframe>
                                </div>`;
                                    } else if (extension === 'mp4') {
                                        slideRecord += `
                                <video width="100%" controls>
                                    <source src="${value.path}" type="video/mp4">
                                </video>`;
                                    } else {
                                        slideRecord +=
                                            `<img src="${value.path}" alt="${value.name}" style="width:100%">`;
                                    }

                                    slideRecord += `
                            <input type="hidden" class="currentSlide" value="${value.id}" data-id="${value.model_id}">
                            </div>`;
                                });

                                slideRecord += `
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <a class="prev" onclick="prevSlides(1)">❮</a>
                        <a class="next" onclick="plusSlides(1)">❯</a>
                    </div>`;

                                $('#slideRecord').html(slideRecord);
                            } else {
                                $('.next').hide();
                            }
                        },
                        error: function(xhr) {
                            console.error('Error fetching previous slide:', xhr.responseText);
                        },
                    });
                }
            };
              window.plusSlides = function(n) {
                showSlides(slideIndex += n);

                // Update current slide via AJAX
                const currentSlide = document.querySelector('.mySlides[style*="block"] .currentSlide');
                if (currentSlide) {
                    const currentSlideValue = currentSlide.value;
                    const currentSlideId = currentSlide.getAttribute('data-id');

                    // Safely get CSRF token
                    const csrfTokenElement = document.querySelector('meta[name="csrf-token"]');
                    const csrfToken = csrfTokenElement ? csrfTokenElement.content : '';

                    if (!csrfToken) {
                        console.error('CSRF token is missing');
                        return;
                    }

                    $.ajax({
                        url: '/student/completed-slide',
                        type: 'POST',
                        data: {
                            file_id: currentSlideValue,
                            model_id: currentSlideId,
                            _token: csrfToken,
                        },
                        success: function(response) {
                            Livewire.dispatch('refreshTable');
                            if (response.mediaHtml.length > 0) {
                                let slideRecord =
                                   `<h4>${response.totalComplete + 2} / ${response.totalMediaCount}</h4>`;
                                slideRecord += '<div class="slideshow-container">';

                                $.each(response.mediaHtml, function(index, value) {
                                    // Parse custom properties safely
                                    let customProperties = {};
                                    try {
                                        customProperties = JSON.parse(value
                                            .custom_properties || '{}');
                                    } catch (e) {
                                        console.warn('Invalid custom_properties JSON', e);
                                    }

                                    const mediaType = customProperties.media_type || '';
                                    const vimeoId = customProperties.vimeo_id || null;
                                    const vimeoEmbedUrl = vimeoId ?
                                        `https://player.vimeo.com/video/${vimeoId}` : null;
                                    const extension = value.path.split('.').pop()
                                        .toLowerCase();

                                    slideRecord += `
                            <div class="mySlides fade" ${index === 0 ? 'style="display: block;"' : ''}>
                                <div class="row">
                                    <div class="col-lg-2"></div>
                                    <div class="col-lg-8">${value.full_path || ''}</div>
                                </div>`;

                                    // Check media type and render accordingly
                                    if (mediaType === 'vimeo' && vimeoEmbedUrl) {
                                        slideRecord += `
                                <div class="video-container" style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden;">
                                    <iframe 
                                        src="${vimeoEmbedUrl}?autoplay=0&title=0&byline=0&portrait=0" 
                                        frameborder="0" 
                                        allow="autoplay; fullscreen; picture-in-picture" 
                                        allowfullscreen
                                        style="position:absolute; top:0; left:0; width:100%; height:100%;">
                                    </iframe>
                                </div>`;
                                    } else if (extension === 'mp4') {
                                        slideRecord += `
                                <video width="100%" controls>
                                    <source src="${value.path}" type="video/mp4">
                                </video>`;
                                    } else {
                                        slideRecord +=
                                            `<img src="${value.path}" alt="${value.name}" style="width:100%">`;
                                    }

                                    slideRecord += `
                            <input type="hidden" class="currentSlide" value="${value.id}" data-id="${value.model_id}">
                            </div>`;
                                });

                                slideRecord += `
                        <meta name="csrf-token" content="{{ csrf_token() }}">
                        <a class="prev" onclick="prevSlides(1)">❮</a>
                        <a class="next" onclick="plusSlides(1)">❯</a>
                    </div>`;

                                $('#slideRecord').html(slideRecord);
                            } else {
                                $('.next').hide();
                            }
                        },
                        error: function(xhr) {
                            console.error('Error updating slide:', xhr.responseText);
                        },
                    });
                }
            };

            window.currentSlide = function(n) {
                showSlides(slideIndex = n);
            };

            showSlides(slideIndex);

            // Show modal on Livewire event
            Livewire.on('showModal', () => {
                const myModal = new bootstrap.Modal(document.getElementById('mediaModal'));
                myModal.show();
            });
        });
        // then page load 
        document.addEventListener('alertSuccess', function(event) {
            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "success",
                title: event.detail

            });
            // window.location.reload();
        });
        // block right click
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault(); // Prevents the right-click menu from appearing
        });
        document.addEventListener('keydown', function(e) {
            if (e.keyCode === 123) { // F12
                e.preventDefault(); // Prevent F12
            }
            if (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) { // Ctrl+Shift+I or Ctrl+Shift+J
                e.preventDefault(); // Prevent opening DevTools
            }
            if (e.ctrlKey && e.keyCode === 85) { // Ctrl+U
                e.preventDefault(); // Prevent viewing page source
            }
            // Block Ctrl + C (Copy)
            if (e.ctrlKey && (e.key === 'c' || e.key === 'C')) {
                e.preventDefault();
            }
            // Block Ctrl + S (Save)
            if (e.ctrlKey && (e.key === 's' || e.key === 'S')) {
                e.preventDefault();
            }
        }); 
// When a collapse is shown
document.addEventListener('shown.bs.collapse', (e) => {
  e.target.classList.add('visible');
});

// When a collapse is hidden
document.addEventListener('hidden.bs.collapse', (e) => {
  e.target.classList.remove('visible');
});

// Initial fixup in case some are already shown on load
document.querySelectorAll('.collapse.show').forEach(el => el.classList.add('visible'));

    </script>
    @if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: @json(session('success')),
            confirmButtonColor: '#3A833A',
        });
    </script>
@endif
@endpush

@endif

