@php
use App\Models\Media;
use App\Models\Pages;
$pageId = Request::segment(2);

$page =Pages::where('slug',$pageId)->first();
    $mediaItems = Media::query()
        ->select('id','title', 'type', 'video', 'link', 'pdf', 'page_id', 'priority', 'status')
        ->where('page_id', $page->id)
        ->where('status', 'active')
        ->orderBy('priority', 'asc')
        ->get();

    @endphp

<section id="event" class="event-area default-padding" style="padding-bottom: 220px;">
    <div class="container">
        <div class="row">
            @if($pageId == 'gallery')
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="media-container-video">
                        <video controls preload="metadata" class="media-video"
                               aria-label="Video content {{ $index + 1 }}">
                            <source src="{{ asset('assets/WhatsApp Video 2025-09-30 at 12.21.27 AM.mp4') }}" type="video/mp4">
                            <p class="error-message">Your browser doesn't support HTML5 video.</p>
                        </video>
                         
                    </div>
                     <div class="info-title">
                        <h4>SBTE / STTB Chairman signing an MOU at Governor house sindh</h4>
                    </div>
            </div>
            @endif
            @forelse($mediaItems as $index => $media)
            
                {{-- Video --}}
                @if($media->type === 'video')
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="media-container-video">
                       
                        <video controls preload="metadata" class="media-video"
                               aria-label="Video content {{ $index + 1 }}">
                            <source src="{{ asset('storage/' . $media->video) }}" type="video/mp4">
                            <p class="error-message">Your browser doesn't support HTML5 video.</p>
                        </video>
                         
                    </div>
                  @if($media->title)
                         <div class="info-title">
                                    <h4>{{ $media->title }}</h4>
                                </div>
                                @endif
                </div>

                {{-- PDF --}}
                @elseif($media->type === 'pdf')
                <div class="col-lg-3 col-md-6 col-sm-12 mb-4">
                    <div class="media-container">
                        <div class="advisor-item">
                            <div class="info-box">
                                <a href="{{ asset($media->pdf) }}" target="_blank">
                                    <img src="{{asset('assets/pdf_109.webp')}}" alt="Thumb" class="img-fluid">
                                </a>
                                
                            </div>    
                        </div>
                    </div>
                      @if($media->title)
                         <div class="info-title">
                                    <h4>{{ $media->title }}</h4>
                                </div>
                                @endif
                </div>

                {{-- Link (YouTube/Vimeo etc.) --}}
                @elseif($media->type === 'link' && filter_var($media->link, FILTER_VALIDATE_URL))
                <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                    <div class="media-container-video">
                        <iframe src="{{ $media->link }}"
                                title="Media content {{ $index + 1 }}"
                                class="media-iframe"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                loading="lazy">
                        </iframe>
                    </div>
                    @if($media->title)
                         <div class="info-title">
                                    <h4>{{ $media->title }}</h4>
                                </div>
                                @endif
                </div>
                @endif

            @empty
                <div class="col-12">
                    <p class="text-center py-5">No active media items found.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<style>
/* Container keeps aspect ratio (16:9) */
.media-container-video {
    position: relative;
    width: 100%;
    padding-bottom: 114.25%; /* 16:9 aspect ratio */
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}
.media-container {
    position: relative;
    width: 100%;
    background: #000;
    border-radius: 8px;
    overflow: hidden;
}

/* Video and iframe scale responsively */
.media-video,
.media-iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
    object-fit: cover;
}

/* PDF thumbnails */
.media-container img {
    width: 100%;
    height: auto;
    display: block;
}

/* Error message */
.error-message {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: #721c24;
    background: #f8d7da;
    padding: 10px;
    width: 90%;
    border-radius: 5px;
    font-size: 14px;
}
.info-title {
    margin-top: 12px;
    text-align: center;
    background: #f8f9fa;      /* light background */
    padding: 10px 15px;
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: all 0.3s ease-in-out;
}

.info-title:hover {
    background: #e9ecef;      /* subtle hover */
    transform: translateY(-3px);
}

.info-title h4 {
    font-size: 1rem;
    font-weight: 600;
    margin: 0;
    color: #333;
    line-height: 1.4;
    word-break: break-word;   /* handles long titles */
}
</style>

{{-- Facebook SDK (include once at bottom of page) --}}
<div id="fb-root"></div>
<script async defer crossorigin="anonymous"
        src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v19.0"></script>
<script>

document.addEventListener('DOMContentLoaded', function() {
    // Video error handling
    const videos = document.querySelectorAll('.media-video');
    videos.forEach(video => {
        video.addEventListener('error', (e) => {
            console.error('Video loading error:', e);
            const errorMessage = video.nextElementSibling;
            if (errorMessage && errorMessage.classList.contains('error-message')) {
                errorMessage.style.display = 'block';
                // video.style.display = '';
            }
        });
    });

    // Iframe error handling
    const iframes = document.querySelectorAll('.media-iframe');
    iframes.forEach(iframe => {
        iframe.addEventListener('load', function() {
            try {
                if (!this.contentDocument || this.contentDocument.body.innerHTML === '') {
                    throw new Error('Empty iframe content');
                }
            } catch (e) {
                console.error('Iframe loading error:', e, 'URL:', this.src);
                // this.style.display = 'none';
                const errorMessage = this.nextElementSibling;
                if (errorMessage && errorMessage.classList.contains('error-message')) {
                    errorMessage.style.display = 'block';
                }
            }
        });
    });
});
</script>