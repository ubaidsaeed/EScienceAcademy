@php
   $subjects = \App\Models\Subject::where('status', 'active')->get();
    if (auth()->check()) {
       $userRoleName = \Illuminate\Support\Facades\DB::table('users')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.id', auth()->user()->id)
            ->value('roles.name');
    }

@endphp
<style>
    .dropbtn {
        background-color: #04AA6D;
        color: white;
        padding: 16px;
        font-size: 16px;
        border: none;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        background-color: #f1f1f1;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .logout {
        text-align:left;
    }

    .dropdown-content a,
    button {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
    }

    .dropdown-content a:hover,
    button:hover {
        background-color: #ddd;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .dropdown:hover .dropbtn {
        background-color: #3e8e41;
    }
</style>

<!-- Start Header Area -->
<header class="cs-header-area">
    <!-- Start Home-1 Menu & Site Logo & Social Media -->
    <div class="cs-home-1-menu">
        <div class="cs-site-main-logo-menu-social">
            <div class="row align-items-center plr_md--30 plr--10 nav-lg-layout">
                <div class="col-xl-2 col-lg-2 col-md-5 col-sm-7 col-7 p-0">
                    <div class="cs-site-logo">
                        <a class="logo-light" href="{{ route('home') }}"><img
                                src="{{ asset('build/assets/frontend/images/escience-assets/escience-logo.svg') }}"
                                alt="cs"></a>
                    </div>
                </div>
                <div class="col-xl-10 col-lg-10 d-none d-lg-block">
                    <nav>
                        <div class="cs-home-1-menu custom-float-right">
                            <ul class="list-unstyled cs-desktop-menu">
                                <li class="menu-item">
                                    <a class="cs-dropdown-main-element @if (url()->current() == route('home')) active @endif" href="{{ route('home') }}">Home</a>
                                </li>
                               @foreach ($menus as $menu)
                               @if(!auth()->check() || strtolower($menu->name) !== 'register')
                                    <li class="menu-item {{ $menu->submenus->count() > 0 ? 'cs-has-dropdown' : '' }}">
                                        <a target="{{ $menu->target_window }}"
                                            href="{{ $menu->page ? route('frontend.page', $menu->page->slug) : $menu->link ?? 'javascript:void(0)' }}"
                                            class="cs-dropdown-main-element {{ url()->current() == ($menu->page ? route('frontend.page', $menu->page->slug) : '') ? 'active' : '' }}">
                                            {{ $menu->name }}
                                        </a>
                                        @if ($menu->submenus->count() > 0)
                                            <ul class="cs-submenu list-unstyled menu-pages custom-after">
                                                @foreach ($menu->submenus as $submenu)
                                                    <li class="nav-item">
                                                        <a target="{{ $submenu->target_window }}"
                                                            href="{{ $submenu->page ? url('/' . ($menu->slug ?? '') . '/' . $submenu->page->slug) : $submenu->link ?? 'javascript:void(0)' }}"
                                                            class="{{ $submenu->subChildMenus->count() > 0 ? 'cs-has-submenu' : '' }}">
                                                            <b>{{ $submenu->name }}</b>
                                                        </a>
                                                        @if ($submenu->subChildMenus->count() > 0)
                                                            <ul class="sub-cat">
                                                                @foreach ($submenu->subChildMenus as $childMenu)
                                                                    <li>
                                                                        <a target="{{ $childMenu->target_window }}"
                                                                            href="{{ $childMenu->page
                                                                                ? url('/' . ($menu->slug ?? '') . '/' . ($submenu->slug ?? '') . '/' . $childMenu->page->slug)
                                                                                : $childMenu->custom_link ?? 'javascript:void(0)' }}">
                                                                            {{ $childMenu->name }}
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                    @endif
                                @endforeach
                                <!-- <li class="menu-item">-->
                                <!--    <a class="cs-dropdown-main-element" target="_black" href="https://www.britishcouncil.pk/sites/default/files/exam_registration_process_guideline_mj2023_private_candidates.pdf">Registration</a>-->
                                <!--</li>-->
                                <li class=" menu-item dropdown">
                                    @if (auth()->check())
                                        <a class="cs-dropdown-main-element" href="#">
                                            {{ auth()->user()->name }}
                                        </a>
                                        <ul class="cs-submenu list-unstyled menu-pages custom-after dropdown-content"
                                            aria-labelledby="dropdownMenuButton1">
                                           
                                            
                                            <li class="">
                                              <!--<a href="  @if ($userRoleName == 'Student') {{ route('student.dashboard') }}-->
                                              <!--    @elseif($userRoleName == 'Admin')-->
                                              <!--        {{ route('admin.dashboard') }}-->
                                              <!--    @elseif($userRoleName == '')-->
                                              <!--        {{ route('payment.method') }} @endif"-->
                                              <!--      class="cs-has-submenu">-->
                                                 
                                              <!--      @if ($userRoleName == 'Student' || $userRoleName == 'Admin') Dashboard-->
                         
                                              <!--        @elseif($userRoleName == '')-->
                                              <!--            Dashboard @endif-->
                                              <!--  </a>-->
                                             <a href="@if ($userRoleName == 'Student') {{ route('student.dashboard') }}
                                                  @elseif($userRoleName == 'Admin')
                                                      {{ route('admin.dashboard') }}
                                                        @elseif($userRoleName == 'Manager')
                                                        {{ route('admin.dashboard') }}
                                                  @elseif($userRoleName == '')
                                                      {{ route('payment.method') }} @endif"
                                                    class="cs-has-submenu">
                                                 
                                                    @if ($userRoleName == 'Student' || $userRoleName == 'Admin') Dashboard
                                                    @elseif($userRoleName == 'Manager')
                                                    Dashboard
                                                      @elseif($userRoleName == '')

                                                          Dashboard @endif
                                                </a>
                                            </li>
                                             <li class="">
                                                <a href="{{ route('admin.profile') }}"
                                                    class="cs-has-submenu">Profile</a>
                                            </li>
                                            <li class="">
                                                <form id="logout-form" class="cs-has-submenu"
                                                    action="{{ route('logout') }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="cs-has-submenu logout">
                                                        Logout
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    @else
                                        <a class="cs-dropdown-main-element" href="{{ route('login') }}">
                                            Login
                                        </a>
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
                <div class="col-xl-1 col-lg-1 col-md-2 col-sm-5 col-5 p-0 custom-hide-lg">
                    <div class="cs-header-top-social-media">
                        
                        <div class="search-input-area">
                            <div class="container">
                                <div class="search-input-inner">
                                    <form action="#">
                                        <div class="input-div">
                                            <input id="searchInput1" class="search-input" type="text"
                                                placeholder="Search by keyword" required="">
                                        </div>
                                        <button type="submit" class="search-button">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                viewBox="0 0 22 22" fill="none">
                                                <path
                                                    d="M16.9584 15.7274L21.4911 20.2591L19.9936 21.7566L15.4619 17.2238C13.7758 18.5755 11.6785 19.3107 9.51738 19.3077C4.25968 19.3077 -0.00744629 15.0405 -0.00744629 9.78282C-0.00744629 4.52512 4.25968 0.257996 9.51738 0.257996C14.7751 0.257996 19.0422 4.52512 19.0422 9.78282C19.0453 11.9439 18.3101 14.0412 16.9584 15.7274ZM14.8354 14.9421C16.1785 13.5609 16.9286 11.7094 16.9256 9.78282C16.9256 5.68926 13.6099 2.37462 9.51738 2.37462C5.42382 2.37462 2.10918 5.68926 2.10918 9.78282C2.10918 13.8753 5.42382 17.191 9.51738 17.191C11.444 17.1941 13.2954 16.444 14.6767 15.1009L14.8354 14.9421Z"
                                                    fill="#F84E77"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="search-close-icon">
                                    <i class="fa-solid fa-xmark-large"></i>
                                </div>
                            </div>
                        </div>
                        <div class="cs-header-top-menu-bar menu-btn">
                            <a href="javascript:void(0)">
                                <svg width="29" height="29" viewBox="0 0 29 29" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="0.00012207" width="5" height="5" fill="white" />
                                    <rect x="0.00012207" y="12" width="5" height="5" fill="white" />
                                    <rect x="0.00012207" y="24" width="5" height="5" fill="white" />
                                    <rect x="12.0001" width="5" height="5" fill="white" />
                                    <rect x="12.0001" y="12" width="5" height="5" fill="white" />
                                    <rect x="12.0001" y="24" width="5" height="5" fill="white" />
                                    <rect x="24.0001" width="5" height="5" fill="white" />
                                    <rect x="24.0001" y="12" width="5" height="5" fill="white" />
                                    <rect x="24.0001" y="24" width="5" height="5" fill="white" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- End Home-1 Menu & Site Logo & Social Media -->
</header>
<!-- End Header Area -->


<!-- End Header Area -->

<!-- side bar for desktop -->
<div id="side-bar" class="side-bar header-one d-lg-none ">
    <div class="side-bar-top">
        <div class="thumbnail">
            <a href="{{ route('home') }}">
                <img src="{{ asset('build/assets/frontend/images/escience-assets/escience-logo.svg') }}"
                    alt="cs">
            </a>
        </div>
        <button class="close-icon-menu"><i class="far fa-times"></i></button>
    </div>
    <div class="inner d-none">
        <!-- inner menu area desktop start -->
        <div class="inner-main-wrapper-desk d-none d-lg-done">
            <div class="inner-content">
                <p class="desc">Arrived compass prepare an on as. Reasonable particular on my it in sympathize. Size
                    now easy eat hand how. Unwilling he departure elsewhere dejection at. Heart large seems may purse
                    means few blind.</p>
                <ul class="address">
                    <li>
                        <strong>ADDRESS</strong>
                        <p>adderss</p>
                    </li>
                    <li>
                        <strong>EMAIL</strong>
                        <p><a href="mail-to:support@example.com">support@example.com</a></p>
                    </li>
                    <li>
                        <strong>CONTACT</strong>
                        <p><a href="call-to:442073284499">+44-20-7328-4499</a></p>
                    </li>
                </ul>
                <div class="newsletter-form">
                    <div class="form-inner">
                        <div class="content">
                            <h3 class="title mb--20">Get Newsletter</h3>
                        </div>
                        <form action="#">
                            <div class="input-div">
                                <input type="email" placeholder="Your email..." required>
                            </div>
                            <button type="submit" class="subscribe-btn">Subscribe Now</button>
                        </form>
                    </div>
                </div>
                <div class="social-area">
                    <ul>
                        <li><a class="bg-one" href="#"><i class="fa-brands fa-facebook-f"></i></a></li>
                        <li><a class="bg-two" href="#"><i class="fa-brands fa-twitter"></i></a></li>
                        <li><a class="bg-three" href="#"><i class="fa-brands fa-instagram"></i></a></li>
                        <li><a class="bg-four" href="#"><i class="fa-brands fa-linkedin-in"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- mobile menu area start -->
    <!-- mobile menu area start -->
<div class="mobile-menu d-block d-lg-none">
    <nav class="nav-main mainmenu-nav mt--30">
        <ul class="mainmenu" id="mobile-menu-active">
            <!-- Home Menu -->
            <li class="menu-item">
                <a class="mobile-menu-link @if (url()->current() == route('home')) active @endif" href="{{ route('home') }}">Home</a>
            </li>

            <!-- Dynamic Menus -->
             @foreach ($menus as $menu)
                @if(!auth()->check() || strtolower($menu->name) !== 'register')
                    <li class="{{ $menu->submenus->count() > 0 ? 'has-droupdown' : 'menu-item' }}">
                        @if($menu->submenus->count() > 0)
                            <!-- Menu with submenus -->
                            <a class="main" href="javascript:void(0)">
                                {{ $menu->name }}
                            </a>
                            <ul class="cs-submenu list-unstyled menu-pages">
                                @foreach ($menu->submenus as $submenu)
                                    <li class="nav-item">
                                        @if($submenu->subChildMenus->count() > 0)
                                            <!-- Submenu with child menus -->
                                            <a class="cs-has-submenu" href="javascript:void(0)">
                                                <b>{{ $submenu->name }}</b>
                                            </a>
                                            <ul class="sub-cat">
                                                @foreach ($submenu->subChildMenus as $childMenu)
                                                    <li>
                                                        <a target="{{ $childMenu->target_window }}"
                                                           href="{{ $childMenu->page
                                                               ? route('frontend.page', ['slug' => $menu->slug, 'submenuSlug' => $submenu->slug, 'childSlug' => $childMenu->page->slug])
                                                               : ($childMenu->custom_link ?? 'javascript:void(0)') }}">
                                                            {{ $childMenu->name }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <!-- Submenu without child menus -->
                                            <a target="{{ $submenu->target_window }}"
                                               href="{{ $submenu->page
                                                   ? route('frontend.page', ['slug' => $menu->slug, 'submenuSlug' => $submenu->slug])
                                                   : ($submenu->custom_link ?? 'javascript:void(0)') }}">
                                                <b>{{ $submenu->name }}</b>
                                            </a>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <!-- Menu without submenus -->
                            <a class="mobile-menu-link @if ($menu->page && url()->current() == route('frontend.page', $menu->slug)) active @endif"
                               target="{{ $menu->target_window }}"
                               href="{{ $menu->page
                                   ? route('frontend.page', $menu->slug)
                                   : ($menu->custom_link ?? 'javascript:void(0)') }}">
                                {{ $menu->name }}
                            </a>
                        @endif
                    </li>
                @endif
            @endforeach

            <!-- Registration Menu -->
            <!--<li class="menu-item">-->
            <!--    <a class="mobile-menu-link" target="_blank"-->
            <!--       href="https://www.britishcouncil.pk/sites/default/files/exam_registration_process_guideline_mj2023_private_candidates.pdf">-->
            <!--        Registration-->
            <!--    </a>-->
            <!--</li>-->

            <!-- Login Menu -->
           <li class="has-droupdown ">
                    @if (auth()->check())
                        <a class="main" href="#">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="cs-submenu list-unstyled menu-pages">
                            
                            <li class="">
                                <a class="cs-has-submenu"
                                    href="@if ($userRoleName == 'Student') {{ route('student.dashboard') }}
                                                  @elseif($userRoleName == 'Admin')
                                                      {{ route('admin.dashboard') }}
                                                  @else
                                                      {{ route('payment.method') }} @endif"
                                    class="cs-has-submenu">

                                    @if ($userRoleName == 'Student' || $userRoleName == 'Admin')
                                        Dashboard
                                    @else
                                        Payment Method
                                    @endif
                                </a>
                            </li>
                            <li class="cs-has-submenu">
                                <a href="{{ route('admin.profile') }}" class="cs-has-submenu">Profile</a>
                            </li>
                            <li class="">
                                <form id="logout-form" class="cs-has-submenu" action="{{ route('logout') }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="cs-has-submenu logout"
                                        style="color: var(--color-heading);padding: 12px 4px !important;">
                                        Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    @else
                        <a class="menu-item" href="{{ route('login') }}">
                            Login
                        </a>
                    @endif
                </li>
        </ul>
    </nav>
</div>
<!-- mobile menu area end -->
    <!--<div class="mobile-menu d-block d-lg-none">-->
    <!--    <nav class="nav-main mainmenu-nav mt--30">-->
    <!--        <ul class="mainmenu" id="mobile-menu-active">-->
    <!--            @foreach ($menus as $menu)-->
    <!--                <li-->
    <!--                    class=" {{ $menu->submenus->count() > 0 || $menu->name === 'Subjects' ?'has-droupdown' : 'menu-item' }}">-->
    <!--                    <a class="{{ $menu->submenus->count() > 0 || $menu->name === 'Subjects' ?'main' : ' mobile-menu-link' }}"-->
    <!--                        href="{{ $menu->name === 'Subjects' ? 'javascript:void(0)' : ($menu->page ? route('frontend.page', $menu->page->slug) : ($menu->custom_link ?? '#')) }}"-->
    <!--                        target="{{ $menu->target_window }}">{{ $menu->name }}</a>-->
    <!--                @if ($menu->name == 'Subjects')-->
    <!--                   <ul class="cs-submenu list-unstyled menu-pages">-->
    <!--                                        <li class="nav-item"><a href="service.html"><b>Physics</b></a>-->
    <!--                                    <ul class="sub-cat">-->
    <!--                                        <li><a href="{{ route('subject-detail') }}">O Level</a></li>-->
    <!--                                        <li><a href="#">AS Level</a></li>-->
    <!--                                        <li><a href="#">A2 Level</a></li>-->
    <!--                                    </ul>-->
    <!--                                    </li>-->
    <!--                                        <li class="nav-item"><a href="service-details.html"><b>Mathematics</b></a>-->
    <!--                                    <ul class="sub-cat">-->
    <!--                                        <li><a href="#">O Level</a></li>-->
    <!--                                        <li><a href="#">AS Level</a></li>-->
    <!--                                        <li><a href="#">A2 Level</a></li>-->
    <!--                                    </ul></li>-->
    <!--                                        <li class="nav-item"><a href="service-details-2.html"><b>Chemistry</b></a>-->
    <!--                                    <ul class="sub-cat">-->
    <!--                                        <li><a href="#">O Level</a></li>-->
    <!--                                        <li><a href="#">AS Level</a></li>-->
    <!--                                        <li><a href="#">A2 Level</a></li>-->
    <!--                                    </ul></li>-->
    <!--                                        <li class="nav-item"><a href="service-details-3.html"><b>Biologoy</b></a>-->
    <!--                                    <ul class="sub-cat">-->
    <!--                                        <li><a href="#">O Level</a></li>-->
    <!--                                        <li><a href="#">AS Level</a></li>-->
    <!--                                        <li><a href="#">A2 Level</a></li>-->
    <!--                                    </ul></li>-->
    <!--                                    </ul>-->
                        <!--<ul class="submenu">-->
                        <!--    @foreach ($subjects as $subject)-->
                        <!--        <li><a class=" mobile-menu-link" href="javascrip:void(0)">-->
                        <!--                {{ $subject->name }}</a></li>-->
                        <!--    @endforeach-->
                        <!--</ul>-->
    <!--                @endif-->
    <!--                @if ($menu->submenus->count() > 0)-->
    <!--                    <ul class="submenu">-->
    <!--                        @foreach ($menu->submenus as $submenu)-->
    <!--                            <li><a class=" mobile-menu-link " target="{{ $submenu->target_window }}"-->
    <!--                                    href="{{ $submenu->page ? route('frontend.page', $submenu->page->slug) : $submenu->custom_link ?? '#' }}">-->
    <!--                                    {{ $submenu->name }}</a></li>-->
    <!--                        @endforeach-->
    <!--                    </ul>-->
    <!--                @endif-->

    <!--                </li>-->
    <!--            @endforeach-->
    <!--                <li class="menu-item">-->
    <!--                 <a class="cs-dropdown-main-element" href="{{ route('login') }}">Login</a>-->
    <!--                </li>-->
    <!--        </ul>-->
    <!--    </nav>-->
    <!--</div>-->
    <!-- mobile menu area end -->
</div>
<!-- side bar for desktop -->



<!-- side bar for desktop -->
{{-- <div id="side-bar" class="side-bar header-one">
    <div class="side-bar-top">
        <div class="thumbnail" style="height:50px">
            <a href="index.html">
                <img src="{{ asset('build/assets/frontend/images/logo/logo-2.svg') }}" alt="cs"
                    style="height:70px">
            </a>
        </div>
        <button class="close-icon-menu"><i class="far fa-times"></i></button>
    </div>
    <!-- mobile menu area start -->

    <div class="mobile-menu d-block d-lg-none">
        <nav class="nav-main mainmenu-nav mt--30">
            <ul class="mainmenu metismenu" id="mobile-menu-active">
                @foreach ($menus as $menu)
                    <li class="menu-item"><a class="main mobile-menu-link"
                            href="{{ $menu->page ? route('frontend.page', $menu->page->slug) : $menu->custom_link ?? '#' }}"
                            target="{{ $menu->target_window }}">{{ $menu->name }}</a></li>
                    <li
                        class="menu-item {{ $menu->submenus->count() > 0 || $menu->name === 'Subjects' ? 'cs-has-dropdown' : '' }}">
                        <a href="{{ $menu->page ? route('frontend.page', $menu->page->slug) : $menu->custom_link ?? '#' }}"
                            class="main" target="{{ $menu->target_window }}">
                            {{ $menu->name }}
                        </a>
                        @if ($menu->name == 'Subjects')
                            <ul class="cs-submenu list-unstyled menu-pages mb-version-gap">
                                @foreach ($subjects as $subject)
                                    <li class="nav-item">
                                        <a href="javascript:void(0)">
                                            {{ $subject->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                        @if ($menu->submenus->count() > 0)
                            <ul class="submenu">
                                @foreach ($menu->submenus as $submenu)
                                    <li>
                                        <a class="mobile-menu-link" target="{{ $submenu->target_window }}"
                                            href="{{ $submenu->page ? route('frontend.page', $submenu->page->slug) : $submenu->custom_link ?? '#' }}">
                                            {{ $submenu->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>
</div> --}}
<!-- mobile menu area end -->
</div>
<!-- side bar for desktop -->
