<div class="my-2">
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
    {{-- show error  --}}
    @if (isset($main_parent_name['errorMessage']))

        <div class="alert alert-danger alert-message fade show"  style="width:400px" role="alert" id="error-alert">
            {{ $main_parent_name['errorMessage'] }}
        </div>
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
    @else
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-12 ">
                <div class="card">
                    <div class="card-body">
                        <svg class="card-custom-icon text-success icon-dropshadow-success" x="1008" y="1248"
                            viewBox="0 0 24 24" height="100%" width="100%" preserveAspectRatio="xMidYMid meet"
                            focusable="false">
                            <path
                                d="M2.5 13.5A.5.5 0 0 1 3 13h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5M13.991 3l.024.001a1.5 1.5 0 0 1 .538.143.76.76 0 0 1 .302.254c.067.1.145.277.145.602v5.991l-.001.024a1.5 1.5 0 0 1-.143.538.76.76 0 0 1-.254.302c-.1.067-.277.145-.602.145H2.009l-.024-.001a1.5 1.5 0 0 1-.538-.143.76.76 0 0 1-.302-.254C1.078 10.502 1 10.325 1 10V4.009l.001-.024a1.5 1.5 0 0 1 .143-.538.76.76 0 0 1 .254-.302C1.498 3.078 1.675 3 2 3zM14 2H2C0 2 0 4 0 4v6c0 2 2 2 2 2h12c2 0 2-2 2-2V4c0-2-2-2-2-2" />

                        </svg>
                        <p class=" mb-1 ">Total Chapters</p>
                        <h2 class="mb-1 font-weight-bold">{{ $main_parent_name['total_courses'] }}</h2>
                        <!-- <span class="mb-1 text-muted"><span class="text-danger"><i class="fa fa-caret-down  me-1"></i> 43.2</span> than last month</span> -->
                        <div class="progress progress-sm mt-3 bg-success-transparent">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                style="width: {{ $main_parent_name['completion_percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body">
                        <svg class="card-custom-icon text-primary icon-dropshadow-primary" x="1008" y="1248"
                            viewBox="0 0 24 24" height="100%" width="100%" preserveAspectRatio="xMidYMid meet"
                            focusable="false">
                            <path
                                d="M10.854 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 8.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                            <path
                                d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1" />
                        </svg>
                        <p class=" mb-1 ">Complete lessons</p>
                        <h2 class="mb-1 font-weight-bold">{{ $main_parent_name['completed_lectures'] }}</h2>
                        <!-- <span class="mb-1 text-muted"><span class="text-success"><i class="fa fa-caret-up  me-1"></i> 19.8</span> than last month</span> -->
                        <div class="progress progress-sm mt-3 bg-primary-transparent">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary"
                                style="width: {{ $main_parent_name['completion_percentage'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-12">
                <div class="card">
                    <div class="card-body mb-5">
                        <svg class="card-custom-icon text-danger icon-dropshadow-danger" x="1008" y="1248"
                            viewBox="0 0 24 24" height="100%" width="100%" preserveAspectRatio="xMidYMid meet"
                            focusable="false">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.-->
                                <path
                                    d="M4.1 38.2C1.4 34.2 0 29.4 0 24.6C0 11 11 0 24.6 0L133.9 0c11.2 0 21.7 5.9 27.4 15.5l68.5 114.1c-48.2 6.1-91.3 28.6-123.4 61.9L4.1 38.2zm503.7 0L405.6 191.5c-32.1-33.3-75.2-55.8-123.4-61.9L350.7 15.5C356.5 5.9 366.9 0 378.1 0L487.4 0C501 0 512 11 512 24.6c0 4.8-1.4 9.6-4.1 13.6zM80 336a176 176 0 1 1 352 0A176 176 0 1 1 80 336zm184.4-94.9c-3.4-7-13.3-7-16.8 0l-22.4 45.4c-1.4 2.8-4 4.7-7 5.1L168 298.9c-7.7 1.1-10.7 10.5-5.2 16l36.3 35.4c2.2 2.2 3.2 5.2 2.7 8.3l-8.6 49.9c-1.3 7.6 6.7 13.5 13.6 9.9l44.8-23.6c2.7-1.4 6-1.4 8.7 0l44.8 23.6c6.9 3.6 14.9-2.2 13.6-9.9l-8.6-49.9c-.5-3 .5-6.1 2.7-8.3l36.3-35.4c5.6-5.4 2.5-14.8-5.2-16l-50.1-7.3c-3-.4-5.7-2.4-7-5.1l-22.4-45.4z" />
                            </svg>
                        </svg>
                        <p class=" mb-1 ">Your Current Badge</p>
                        <h2 class="mb-1 font-weight-bold"> </h2>
                        <div class="progresss progress-smd mt-3 bg-danger-transparents">
                            <!-- <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" style="width: 40%"></div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- my course section  -->
       
        <div class="accordion" id="headerAccordion" >
    @foreach ($folderHierarchy as $folder)
        <x-folder-accordion :folder="$folder" :openFolders="$openFolders" :level="0" />
    @endforeach
</div>
        <!-- end my course section  -->
        <!-- Bootstrap Modal for Media Records   images -->
       <div>
            <!-- Bootstrap Modal for Media Records   images -->
            <div class="modal fade " id="mediaModal" data-bs-backdrop="static" data-bs-keyboard="false"
                tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="mediaModalLabel">Media</h5>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                wire:click="closeMediaModal({{$refresh_id }})"><i class="bi bi-x"></i></button>

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
                                        $vimeo_url = $custom_properties['vimeo_url'] ?? null;
                                        $vimeo_access_token = config('services.vimeo.access_token');
                                        
                                        // Extract hash from Vimeo URL (for unlisted/private videos)
                                        $vimeo_hash = null;
                                        if ($vimeo_url && preg_match('/vimeo\.com\/\d+\/([\w]+)/', $vimeo_url, $matches)) {
                                            $vimeo_hash = $matches[1];
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

                                        @if ($media_type === 'vimeo' && !empty($vimeo_id))
                                            
                                            <div class="video-container"
                                                style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden;">
                                                <div id="vimeo-player-{{ $item->id }}-{{ $index }}" 
                                                     data-vimeo-id="{{ $vimeo_id }}"
                                                     data-vimeo-hash="{{ $vimeo_hash ?? '' }}"
                                                     data-vimeo-token="{{ $vimeo_access_token ?? '' }}"
                                                     style="position:absolute; top:0; left:0; width:100%; height:100%;">
                                                </div>
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


@push('script')
    <!-- Vimeo Player.js Library -->
    <script src="https://player.vimeo.com/api/player.js"></script>
    <!-- Livewire Event Listener for Modal -->
    <script>
    
    // Initialize Vimeo Player with access token for private videos
    function initializeVimeoPlayer(playerId, vimeoId, accessToken, vimeoHash) {
        const playerElement = document.getElementById(playerId);
        if (!playerElement) return;
        
        // Build iframe URL
        let iframeUrl = `https://player.vimeo.com/video/${vimeoId}?autoplay=0&title=0&byline=0&portrait=0`;
        
        // Add hash if available (required for unlisted/private videos)
        if (vimeoHash) {
            iframeUrl += `&h=${encodeURIComponent(vimeoHash)}`;
        }
        
        // Add access token if available (required for private videos)
        if (accessToken) {
            iframeUrl += `&access_token=${encodeURIComponent(accessToken)}`;
        }
        
        // Create iframe
        const iframe = document.createElement('iframe');
        iframe.src = iframeUrl;
        iframe.frameBorder = '0';
        iframe.allow = 'autoplay; fullscreen; picture-in-picture';
        iframe.allowFullscreen = true;
        iframe.style.cssText = 'position:absolute; top:0; left:0; width:100%; height:100%;';
        
        // Clear and add iframe
        playerElement.innerHTML = '';
        playerElement.appendChild(iframe);
    }
    
    // Initialize all Vimeo players on page load
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-vimeo-id]').forEach(function(element) {
            const vimeoId = element.getAttribute('data-vimeo-id');
            const accessToken = element.getAttribute('data-vimeo-token');
            const vimeoHash = element.getAttribute('data-vimeo-hash');
            if (vimeoId) {
                initializeVimeoPlayer(element.id, vimeoId, accessToken, vimeoHash);
            }
        });
    });
    
var completed_lectures = {{ $main_parent_name['completed_lectures'] }};
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
        
 function icon(completed_lectures) {
    var iconElement = document.querySelector('.card-custom-icon.text-danger');

    if (iconElement) {
        if (completed_lectures == 0) {
            iconElement.style.fill = '#f7284a';  // Red
        } 
        if (completed_lectures == 5) {
            iconElement.style.fill = '#e3b113';  // Yellow
        } 
        if (completed_lectures == 10) {
            iconElement.style.fill = '#467fcf';  // Blue
        }
        else{
            iconElement.style.fill = '#fe7f00';  // gold
        }
    }
};
   
    });
     window.onload = icon();
// Get the value of completed_lectures from the backend
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
                complete?.classList.add('d-none');
                continueBtn?.classList.remove('d-none');
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
                                    const vimeoAccessToken = @json(config('services.vimeo.access_token'));
                                    
                                    // Build Vimeo embed URL with access token for private videos
                                    let vimeoEmbedUrl = null;
                                    if (vimeoId) {
                                        let embedParams = 'autoplay=0&title=0&byline=0&portrait=0';
                                        if (vimeoAccessToken) {
                                            embedParams += '&access_token=' + encodeURIComponent(vimeoAccessToken);
                                        }
                                        vimeoEmbedUrl = `https://player.vimeo.com/video/${vimeoId}?${embedParams}`;
                                    }
                                    
                                    const extension = value.path.split('.').pop()
                                        .toLowerCase();

                                    slideRecord += `
                            <div class="mySlides fade" ${index === 0 ? 'style="display: block;"' : ''}>
                                <div class="row">
                                    <div class="col-lg-2"></div>
                                    <div class="col-lg-8">${value.full_path || ''}</div>
                                </div>`;

                                    // Check media type and render accordingly
                                    if (mediaType === 'vimeo' && vimeoId) {
                                        // Extract hash from vimeo_url if available
                                        let vimeoHash = null;
                                        const vimeoUrl = customProperties.vimeo_url || '';
                                        const hashMatch = vimeoUrl.match(/vimeo\.com\/\d+\/([\w]+)/);
                                        if (hashMatch) {
                                            vimeoHash = hashMatch[1];
                                        }
                                        
                                        const playerId = `vimeo-player-${value.id}-${index}`;
                                        slideRecord += `
                                <div class="video-container" style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden;">
                                    <div id="${playerId}" 
                                         data-vimeo-id="${vimeoId}"
                                         data-vimeo-hash="${vimeoHash || ''}"
                                         data-vimeo-token="${vimeoAccessToken || ''}"
                                         style="position:absolute; top:0; left:0; width:100%; height:100%;">
                                    </div>
                                </div>`;
                                        
                                        // Initialize Vimeo player after slide is added
                                        setTimeout(() => {
                                            initializeVimeoPlayer(playerId, vimeoId, vimeoAccessToken, vimeoHash);
                                        }, 100);
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
                                    const vimeoAccessToken = @json(config('services.vimeo.access_token'));
                                    
                                    // Build Vimeo embed URL with access token for private videos
                                    let vimeoEmbedUrl = null;
                                    if (vimeoId) {
                                        let embedParams = 'autoplay=0&title=0&byline=0&portrait=0';
                                        if (vimeoAccessToken) {
                                            embedParams += '&access_token=' + encodeURIComponent(vimeoAccessToken);
                                        }
                                        vimeoEmbedUrl = `https://player.vimeo.com/video/${vimeoId}?${embedParams}`;
                                    }
                                    
                                    const extension = value.path.split('.').pop()
                                        .toLowerCase();

                                    slideRecord += `
                            <div class="mySlides fade" ${index === 0 ? 'style="display: block;"' : ''}>
                                <div class="row">
                                    <div class="col-lg-2"></div>
                                    <div class="col-lg-8">${value.full_path || ''}</div>
                                </div>`;

                                    // Check media type and render accordingly
                                    if (mediaType === 'vimeo' && vimeoId) {
                                        // Extract hash from vimeo_url if available
                                        let vimeoHash = null;
                                        const vimeoUrl = customProperties.vimeo_url || '';
                                        const hashMatch = vimeoUrl.match(/vimeo\.com\/\d+\/([\w]+)/);
                                        if (hashMatch) {
                                            vimeoHash = hashMatch[1];
                                        }
                                        
                                        const playerId = `vimeo-player-${value.id}-${index}`;
                                        slideRecord += `
                                <div class="video-container" style="position:relative; padding-bottom:56.25%; height:0; overflow:hidden;">
                                    <div id="${playerId}" 
                                         data-vimeo-id="${vimeoId}"
                                         data-vimeo-hash="${vimeoHash || ''}"
                                         data-vimeo-token="${vimeoAccessToken || ''}"
                                         style="position:absolute; top:0; left:0; width:100%; height:100%;">
                                    </div>
                                </div>`;
                                        
                                        // Initialize Vimeo player after slide is added
                                        setTimeout(() => {
                                            initializeVimeoPlayer(playerId, vimeoId, vimeoAccessToken, vimeoHash);
                                        }, 100);
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
    e.preventDefault();  // Prevents the right-click menu from appearing
});
document.addEventListener('keydown', function(e) {
if (e.keyCode === 123) {  // F12
e.preventDefault();    // Prevent F12
}
if (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74)) {  // Ctrl+Shift+I or Ctrl+Shift+J
e.preventDefault();    // Prevent opening DevTools
}
if (e.ctrlKey && e.keyCode === 85) {  // Ctrl+U
e.preventDefault();    // Prevent viewing page source
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
 
function icon(completed_lectures) {
    var iconElement = document.querySelector('.card-custom-icon.text-danger');

    if (iconElement) {
        if (completed_lectures == 0) {
            iconElement.style.fill = '#f7284a';  // Red
        } 
        if (completed_lectures == 5) {
            iconElement.style.fill = '#e3b113';  // Yellow
        } 
        if (completed_lectures == 10) {
            iconElement.style.fill = '#467fcf';  // Blue
        }
        else{
            iconElement.style.fill = '#fe7f00';  // gold
        }
    }
};

    </script>
@endpush
@endif