@php
    $setting = \App\Models\Setting::first(); // ✅ Fetch only one record
    $headerRecord = $setting && $setting->header_menus ? json_decode($setting->header_menus, true) : [];
 $site_logo = $setting->site_logo ?? '';
 $favicon = $setting->favicon ?? '';
 $menus = \App\Models\Menu::with(['page', 'submenus.page', 'submenus.subChildMenus.page'])
    ->where('status', 1)
    ->orderBy('priority', 'asc')
    ->get();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', $lang ?? app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title> eScience Academy </title>
     
    <link rel="shortcut icon" href="{{asset('build/assets/frontend/images/escience-logo.svg')}}" type="image/x-icon">
    <link rel="stylesheet preload" href="{{asset('build/assets/frontend/css/plugins/fontawesome-5.css')}}" as="style">
    <link rel="stylesheet preload" href="{{asset('build/assets/frontend/css/vendor/bootstrap.min.css')}}" as="style">
    <link rel="stylesheet preload" href="{{asset('build/assets/frontend/css/vendor/animate.css')}}" as="style">
    <link rel="stylesheet preload" href="{{asset('build/assets/frontend/css/vendor/swiper.css')}}" as="style">
    <link rel="stylesheet preload" href="{{asset('build/assets/frontend/css/vendor/metismenu.css')}}" as="style">
    <link rel="stylesheet preload" href="{{asset('build/assets/frontend/css/vendor/fonts.css')}}" as="style">
    <link rel="stylesheet preload" href="{{asset('build/assets/frontend/css/vendor/magnific-popup.css')}}" as="style">
    <link rel="stylesheet preload" href="{{asset('build/assets/frontend/css/style.css')}}" as="style">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/multi-select/css/multi-select.css">

    <!-- footer break screen in mobile -->
    <style>
        /* .mobile-break {
            display: none;
        }

        @media only screen and (min-width: 1024px) {
            #bannerP.cs__banner__content__two__description {
                display: none;


            }
        }

        @media only screen and (max-width: 768px) {
            .mobile-break {
                display: inline;
            }

            .mobile-menu {
                display: block;

            }

            .cs-header-top-social-media {
                margin-left: 385px;
            }

        }

        @media only screen and (max-width:425px) {
            .cs-header-top-social-media {
                margin-left: 0px;
            }
        }

        @media only screen and (min-width:1024px) {
            .mobile-menu {
                display: block;
            }
        }

        @media only screen and (max-width: 1000px) {
            .pTag {
                display: none;
            }
        } */
    </style>
    @stack('style')
</head>

<body>

    <div class="bg-line">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
    @include('layouts.frontend.header')
    @yield('content')
    @include('layouts.frontend.footer')

    <script src="{{asset('build/assets/frontend/js/vendor/jquery.min.js')}}"></script>
    <script src="{{asset('build/assets/frontend/js/plugins/audio.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/vendor/bootstrap.min.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/vendor/swiper.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/vendor/counter-up.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/vendor/waypoint.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/vendor/wow.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/vendor/parallax.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/vendor/gsap.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/vendor/scrolltrigger.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/vendor/split-text.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/vendor/smooth-scroll.min.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/vendor/metisMenu.min.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/plugins/audio.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/plugins/magnific-popup.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/plugins/contact-form.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/plugins/resize-sensor.min.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/plugins/theia-sticky-sidebar.min.js')}}" defer></script>
    <script src="{{asset('build/assets/frontend/js/plugins/jquery.multi-select.js')}}" defer></script>
    
    <!-- main js file -->
    @stack('script')
    <script src="{{asset('build/assets/frontend/js/main.js')}}" defer></script>
    <script>
    $(document).ready(function() {
    $('.custome-select').select2();
    });
        function scrollToSection(sectionId) {
            document.getElementById(sectionId).scrollIntoView({
                behavior: 'smooth'
            });

            document.querySelector('.side-bar').classList.remove('show');
            document.querySelector('.bgshow').style.visibility = 'hidden';
        }
        document.getElementById('closeMenu').addEventListener('click', function() {
            document.querySelector('.side-bar').classList.remove('show');
        });
        window.addEventListener("scroll", function() {
            let slider = document.querySelector(".background-common");
            let menuBarRects = document.querySelectorAll(".cs-header-top-menu-bar svg rect");

            let sliderHeight = slider.offsetHeight;
            let scrollY = window.scrollY;

            if (scrollY > sliderHeight) {
                menuBarRects.forEach(rect => rect.style.fill = "black");
            } else {
                menuBarRects.forEach(rect => rect.style.fill = "white");
            }
        });
    </script>
</body>

</html>