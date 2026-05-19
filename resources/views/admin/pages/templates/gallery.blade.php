
    <style>
       
        h1 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 600;
            font-size: 2.5rem;
        }

        .description {
            color: #7f8c8d;
            max-width: 600px;
            margin: 0 auto;
            font-size: 1.1rem;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .gallery-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .gallery-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.15);
        }

        .gallery-item img {
            width: 100%;
            height: 220px;
            object-fit: cover;
            display: block;
            transition: transform 0.5s ease;
        }

        .gallery-item:hover img {
            transform: scale(1.05);
        }

        .gallery-item .caption {
            padding: 18px;
            text-align: center;
            font-weight: 500;
            color: #2c3e50;
        }

        /* Lightbox Styles */
        .lightbox {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.92);
            z-index: 9999999999;
            
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .lightbox.active {
            display: flex;
            opacity: 1;
        }

        .lightbox-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 95%;
            max-width: 1200px;
            height: 90vh;
        }

        .lightbox-main {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 80%;
            position: relative;
        }

        .lightbox-content {
            max-width: 85%;
            max-height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .lightbox-content img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .lightbox-caption {
            color: white;
            text-align: center;
            margin-top: 20px;
            font-size: 1.3rem;
            font-weight: 500;
            padding: 0 20px;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
            color: white;
            font-size: 2.2rem;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .lightbox-close:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(1.1);
        }

        .lightbox-nav {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
            padding: 0 30px;
        }

        .lightbox-prev,
        .lightbox-next {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            border: none;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 1.8rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
        }

        .lightbox-prev:hover,
        .lightbox-next:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: scale(1.1);
        }

        .thumbnail-container {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 25px;
            flex-wrap: wrap;
            max-width: 90%;
            padding: 15px;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            backdrop-filter: blur(5px);
        }

        .thumbnail {
            width: 80px;
            height: 60px;
            object-fit: cover;
            border-radius: 6px;
            cursor: pointer;
            opacity: 0.6;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .thumbnail.active,
        .thumbnail:hover {
            opacity: 1;
            border-color: #3498db;
            transform: scale(1.05);
        }

        .lightbox-counter {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.1);
            padding: 8px 15px;
            border-radius: 20px;
            backdrop-filter: blur(5px);
        }

        footer {
            text-align: center;
            margin-top: 40px;
            color: #7f8c8d;
            font-size: 0.95rem;
            padding: 20px;
            width: 100%;
        }

        @media (max-width: 768px) {
            .gallery {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 20px;
            }

            .lightbox-nav {
                padding: 0 15px;
            }

            .lightbox-prev,
            .lightbox-next {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .thumbnail {
                width: 70px;
                height: 50px;
            }

            h1 {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .gallery {
                grid-template-columns: 1fr;
            }

            .lightbox-content {
                max-width: 95%;
            }

            .thumbnail-container {
                max-width: 95%;
                gap: 8px;
            }

            .thumbnail {
                width: 60px;
                height: 45px;
            }
        }
    </style>
@php
    $pageId = Request::segment(2);

    // Fetch the page or throw a 404 if not found
    $page = \App\Models\Pages::where('slug', $pageId)
        ->where('status', 'active')
        ->firstOrFail();

    // Get active galleries with their images
    $galleries = \App\Models\Gallery::with(['images' => function ($q) {
        $q->orderBy('created_at', 'asc');
    }])
    ->where('status', 'active')
    ->where('page_id',$page->id)
    ->orderBy('created_at', 'asc')
    ->get();

    // Prepare galleries data for JavaScript
    $galleriesJs = $galleries->map(function ($g) {
        return [
            'id' => $g->id,
            'title' => $g->title ?? 'N/A',
            'thumbnail' => $g->thumbnail ?? 'default-thumbnail.jpg',
            'images' => $g->images->map(function ($img) use ($g) {
                return [
                    'src' => asset('images/gallery/images/' . ($img->image ?? 'default-image.jpg')),
                    'thumb' => asset('images/gallery/images/' . ($img->image ?? 'default-image.jpg')),
                    'caption' => $g->title ?? 'N/A',
                    'galleryId' => $g->id
                ];
            })->toArray()
        ];
    })->toArray();
@endphp

<div class="gallery">
    @isset($galleries)
        @if($galleries->isNotEmpty())
            @foreach ($galleries as $gallery)
                <div class="gallery-item" data-gallery-id="{{ $gallery->id }}" data-image-index="0">
                    <img src="{{ asset('images/gallery/thumbnail/' . ($gallery->thumbnail ?? 'default-thumbnail.jpg')) }}" 
                         alt="{{ $gallery->title ?? 'Image' }}">
                    <div class="caption">{{ $gallery->title ?? 'N/A' }}</div>
                </div>
            @endforeach
        @else
            <p>No galleries available.</p>
        @endif
    @endisset
</div>

<div class="lightbox">
    <div class="lightbox-container">
        <div class="lightbox-counter">
            <span id="current-index">1</span> / <span id="total-images"></span>
        </div>
        <button class="lightbox-close" aria-label="Close Lightbox">
            <i class="fas fa-times"></i>
        </button>
        <div class="lightbox-main">
            <div class="lightbox-nav">
                <button class="lightbox-prev" aria-label="Previous Image">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="lightbox-next" aria-label="Next Image">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div class="lightbox-content">
                <img src="" alt="" id="lightbox-image">
            </div>
        </div>
        <div class="lightbox-caption"></div>
        <div class="thumbnail-container">
            <!-- Thumbnails will be added dynamically -->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image data from PHP
        const galleries = @json($galleriesJs);
        let currentGalleryImages = [];
        let currentIndex = 0;

        // DOM elements
        const lightbox = document.querySelector('.lightbox');
        const lightboxImg = document.querySelector('#lightbox-image');
        const lightboxCaption = document.querySelector('.lightbox-caption');
        const lightboxClose = document.querySelector('.lightbox-close');
        const lightboxPrev = document.querySelector('.lightbox-prev');
        const lightboxNext = document.querySelector('.lightbox-next');
        const thumbnailContainer = document.querySelector('.thumbnail-container');
        const currentIndexEl = document.getElementById('current-index');
        const totalImagesEl = document.getElementById('total-images');
        const galleryItems = document.querySelectorAll('.gallery-item');

        // Open lightbox when gallery item is clicked
        galleryItems.forEach((item) => {
            item.addEventListener('click', () => {
                const galleryId = item.getAttribute('data-gallery-id');
                const imageIndex = parseInt(item.getAttribute('data-image-index')); // Always 0 for first image

                // Set current gallery images
                const gallery = galleries.find(g => g.id == galleryId);
                currentGalleryImages = gallery ? gallery.images : [];
                
                if (currentGalleryImages.length === 0) {
                    lightbox.style.display = 'none';
                    return;
                }

                // Update total images count
                totalImagesEl.textContent = currentGalleryImages.length;

                // Clear and populate thumbnails for the current gallery
                thumbnailContainer.innerHTML = '';
                currentGalleryImages.forEach((image, index) => {
                    const thumb = document.createElement('img');
                    thumb.src = image.thumb;
                    thumb.alt = image.caption || 'Thumbnail';
                    thumb.classList.add('thumbnail');
                    if (index === imageIndex) thumb.classList.add('active');

                    thumb.addEventListener('click', () => {
                        updateLightbox(index);
                    });

                    thumbnailContainer.appendChild(thumb);
                });

                // Open lightbox with the first image of the gallery
                updateLightbox(imageIndex);
                lightbox.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        // Close lightbox
        lightboxClose.addEventListener('click', closeLightbox);

        // Navigate to previous image
        lightboxPrev.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + currentGalleryImages.length) % currentGalleryImages.length;
            updateLightbox(currentIndex);
        });

        // Navigate to next image
        lightboxNext.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % currentGalleryImages.length;
            updateLightbox(currentIndex);
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('active')) return;

            if (e.key === 'Escape') {
                closeLightbox();
            } else if (e.key === 'ArrowLeft') {
                currentIndex = (currentIndex - 1 + currentGalleryImages.length) % currentGalleryImages.length;
                updateLightbox(currentIndex);
            } else if (e.key === 'ArrowRight') {
                currentIndex = (currentIndex + 1) % currentGalleryImages.length;
                updateLightbox(currentIndex);
            }
        });

        // Update lightbox content
        function updateLightbox(index) {
            currentIndex = index;
            lightboxImg.src = currentGalleryImages[index].src;
            lightboxImg.alt = currentGalleryImages[index].caption || 'Image';
            lightboxCaption.textContent = currentGalleryImages[index].caption || 'N/A';
            currentIndexEl.textContent = index + 1;

            // Update active thumbnail
            document.querySelectorAll('.thumbnail').forEach((thumb, i) => {
                thumb.classList.toggle('active', i === index);
            });

            // Add loading effect with error handling
            lightboxImg.style.opacity = '0';
            lightboxImg.onerror = () => {
                lightboxImg.src = @json(asset('images/gallery/images/default-image.jpg'));
                lightboxImg.style.opacity = '1';
            };
            lightboxImg.onload = () => {
                lightboxImg.style.transition = 'opacity 0.3s ease';
                lightboxImg.style.opacity = '1';
            };
        }

        // Close lightbox function
        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = 'auto';
            lightboxImg.style.opacity = '0';
            lightboxImg.src = '';
            currentGalleryImages = [];
            thumbnailContainer.innerHTML = '';
        }

        // Close lightbox when clicking on the background
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                closeLightbox();
            }
        });
    });
</script>
