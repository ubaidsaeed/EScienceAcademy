<!--app-sidebar-->
<style>
    .header-brand-img.dark-logo {
        max-width: 45% !important;
        display: flex;
        justify-self: center;

    }

    /* For screens ≥ 992px when sidenav is toggled */
    @media (min-width: 992px) {
        .sidenav-toggled .header-brand-img.dark-logo {
            max-width: 45% !important;
            display: none;
            justify-self: center;

        }
    }
    /* Active menu item styles */
.side-menu__item.active {
    background: rgba(255, 255, 255, 0.1) !important;
    /* border-right: 3px solid #fff !important; */
}

.slide-item.active {
    background: rgba(255, 255, 255, 0.1) !important;
    color: #fff !important;
}

/* Open/expanded menu styles */
.slide.is-expanded .side-menu__item {
    background: rgba(255, 255, 255, 0.05);
}

.slide-menu.open {
    display: block !important;
}

/* Keep parent expanded when child is active */
.slide.is-expanded .slide-menu {
    display: block;
}

/* Icon styling for active items */
.side-menu__item.active .sidemenu_icon {
    color: #fff !important;
}

/* Arrow rotation for expanded items */
.slide.is-expanded .angle {
    transform: rotate(90deg);
    transition: transform 0.3s ease;
}
</style>
<div class="sticky" style="position:unset !important">
    <aside class="app-sidebar ">
        <div class="app-sidebar__logo">
            <a class="header-brand text-white" href="/">
                <!--<img src="{{ asset('build/assets/admin/images/brand/logo.png') }}" class="header-brand-img desktop-lgo"-->
                <!--    alt="Dayonelogo">-->
                <img src="{{ asset('build/assets/admin/images/brand/escience-logo (1).svg') }}"
                    class="header-brand-img dark-logo" alt="Dayonelogo">
                <!--<img src="{{ asset('build/assets/admin/images/brand/favicon.png') }}" class="header-brand-img mobile-logo"-->
                <!--    alt="Dayonelogo">-->
                <!--<img src="{{ asset('build/assets/admin/images/brand/favicon1.png') }}"-->
                <!--    class="header-brand-img darkmobile-logo" alt="Dayonelogo">-->
                <!--eScienceAcademy-->
            </a>
        </div>
        <div class="app-sidebar3">
            <div class="main-menu">
                <div class="app-sidebar__user">
                    <div class="dropdown user-pro-body text-center">
                        <div class="user-pic" style="display: inline-block !important;">
                            <img src="@if (auth()->user()->avatar != '') {{ asset('storage/app/public/' . auth()->user()->avatar) }} @else{{ asset('build/assets/admin/images/userEmptyImage.jpg') }} @endif"
                                alt="user-img" class="avatar-xxl rounded-circle mb-1">
                        </div>

                        <div class="user-info">
                            <h5 class=" mb-2">{{ Illuminate\Support\Facades\Auth::user()->name }}</h5>
                            <span
                                class="text-muted app-sidebar__user-name text-sm">{{ Illuminate\Support\Facades\Auth::user()->email }}</span>
                        </div>
                    </div>
                </div>
                <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                        width="24" height="24" viewBox="0 0 24 24">
                        <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                    </svg></div>

                <ul class="side-menu">
                    @can('view dashboard')
                        <li class="slide {{ request()->routeIs('admin.dashboard') ? 'is-expanded' : '' }}">
                            <a class="side-menu__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="feather feather-home  sidemenu_icon"></i>
                                <span class="side-menu__label">Dashboard</span>
                            </a>
                        </li>
                    @endcan

                    {{-- check user permission --}}
                    @php
                        use Illuminate\Support\Facades\Auth;
                        use Illuminate\Support\Facades\DB;

                        $userScription = DB::table('subscriptions')
                            ->join('users', 'subscriptions.user_id', '=', 'users.id')
                            ->where([['users.id', Auth::user()->id], ['subscriptions.status', 'active']])
                            // ->latest('subscriptions.created_at')
                            ->select('subscriptions.*')
                            ->first();

                    @endphp
                    @if ($userScription != null && $userScription->status == 'active')
                        <li class="side-menu-label1"><a href="javascript:void(0);">Student</a></li>
                        @can('view student dashboard')
                            <li class="slide {{ request()->routeIs('student.dashboard') ? 'is-expanded' : '' }}">
                                <a class="side-menu__item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}" href="{{ route('student.dashboard') }}">
                                    <i class="feather feather-home  sidemenu_icon"></i>
                                    <span class="side-menu__label">Dashboard</span>
                                </a>
                            </li>
                        @endcan

                        @can('view payment')
                            {{-- <li class="slide">
                                    <a class="side-menu__item" href="{{ route('student.payment.info') }}">
                                        <i class="feather feather-home  sidemenu_icon"></i>
                                        <span class="side-menu__label">Payment Info</span>
                                    </a>
                                </li> --}}
                        @endcan
                    @endif
                    @can('view file manage')
                        <li class="slide {{ request()->routeIs('filemanager.index') ? 'is-expanded' : '' }}">
                            <a class="side-menu__item {{ request()->routeIs('filemanager.index') ? 'active' : '' }}" href="{{ route('filemanager.index') }}">
                                <i class="feather feather-home  sidemenu_icon"></i>
                                <span class="side-menu__label">File Manager</span>
                            </a>
                        </li>
                    @endcan
                    {{-- check user permission --}}
                    @php
                        $board = Illuminate\Support\Facades\Gate::allows('view boards');
                        $level = Illuminate\Support\Facades\Gate::allows('view levels');
                        $subject = Illuminate\Support\Facades\Gate::allows('view subjects');
                    @endphp
                    @if ($board || $level || $subject)
                        <li class="slide {{ request()->routeIs('boards.*') || request()->routeIs('levels.*') || request()->routeIs('subjects.*') ? 'is-expanded' : '' }}">
                            <a class="side-menu__item {{ request()->routeIs('boards.*') || request()->routeIs('levels.*') || request()->routeIs('subjects.*') ? 'active' : '' }}" data-bs-toggle="slide" href="javascript:void(0);">
                                <i class="feather feather-shopping-cart sidemenu_icon"></i>
                                <span class="side-menu__label">Academic</span><i
                                    class="angle fa fa-angle-right"></i></a>
                            <ul class="slide-menu {{ request()->routeIs('boards.*') || request()->routeIs('levels.*') || request()->routeIs('subjects.*') ? 'open' : '' }}">
                                <li class="side-menu-label1"><a href="javascript:void(0);">Academic</a></li>
                                @can('view boards')
                                    <li><a href="{{ route('boards.index') }}" class="slide-item {{ request()->routeIs('boards.*') ? 'active' : '' }}">Boards</a></li>
                                @endcan
                                @can('view levels')
                                    <li><a href="{{ route('levels.index') }}" class="slide-item {{ request()->routeIs('levels.*') ? 'active' : '' }}">Levels</a></li>
                                @endcan
                                @can('view subjects')
                                    <li><a href="{{ route('subjects.index') }}" class="slide-item {{ request()->routeIs('subjects.*') ? 'active' : '' }}">Subjects</a></li>
                                @endcan
                        </li>
                </ul>
                </li>
                @endif
                @can('view boards')
                    {{-- <li class="slide">
                    <a class="side-menu__item" href="{{ route('admin.tabs') }}">
                        <i class="feather feather-home  sidemenu_icon"></i>
                        <span class="side-menu__label">Tabs</span>
                    </a>
                </li> --}}
                @endcan
                @can('view notification')
                    {{-- <li class="slide">
                    <a class="side-menu__item" href="{{ route('admin.contactus') }}">
                        <i class="feather feather-headphones sidemenu_icon"></i>
                        <span class="side-menu__label">Contact Us</span>
                    </a>
                </li> --}}
                @endcan
                @can('view custom query')
                    <li class="slide {{ request()->routeIs('custom-queries.index') ? 'is-expanded' : '' }}">
                    <a class="side-menu__item {{ request()->routeIs('custom-queries.index') ? 'active' : '' }}" href="{{ route('custom-queries.index') }}">
                        <i class="feather feather-headphones sidemenu_icon"></i>
                        <span class="side-menu__label">Custom Query</span>
                    </a>
                </li>
                @endcan
                @php
                    $packages = Illuminate\Support\Facades\Gate::allows('view packages');
                    $subscription = Illuminate\Support\Facades\Gate::allows('view subscription');
                    $feature = Illuminate\Support\Facades\Gate::allows('view feature');
                @endphp
                @if ($packages || $subscription || $feature)
                    <li class="slide {{ request()->routeIs('features.*') || request()->routeIs('levels.*') || request()->routeIs('admin.subscribe.*') ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ request()->routeIs('features.*') || request()->routeIs('admin.packages.*') || request()->routeIs('admin.subscribe.*') ? 'active' : '' }}" data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="feather feather-shopping-cart sidemenu_icon"></i>
                            <span class="side-menu__label">Packages</span><i class="angle fa fa-angle-right"></i></a>
                        <ul class="slide-menu {{ request()->routeIs('features.*') || request()->routeIs('admin.packages.*') || request()->routeIs('admin.subscribe.*') ? 'open' : '' }}">
                            <li class="side-menu-label1"><a href="javascript:void(0);">Packages</a></li>
                            @can('view feature')
                            <li><a href="{{ url('/features') }}" class="slide-item {{ request()->routeIs('features.*') ? 'active' : '' }}">Feature</a></li>
                            @endcan
                            @can('view packages')
                            <li><a href="{{route('admin.packages.index')}}" class="slide-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">Packages</a></li>
                            @endcan
                            @can('view subscription')
                             <li><a href="{{route('admin.subscribe.index')}}" class="slide-item {{ request()->routeIs('admin.subscribe.*') ? 'active' : '' }}">Subscriptions</a></li>
                            @endcan
                        </ul>
                    </li>
                    @endif
                    @php
                    $pages = Illuminate\Support\Facades\Gate::allows('view page');
                    $header_menu = Illuminate\Support\Facades\Gate::allows('view header menu');
                    $settings = Illuminate\Support\Facades\Gate::allows('view settings');
                    $case_studies = Illuminate\Support\Facades\Gate::allows('view case studies');
                    $careers = Illuminate\Support\Facades\Gate::allows('view careers');
                    $scholarship = Illuminate\Support\Facades\Gate::allows('view scholarship');
                @endphp
                @if ($pages || $header_menu || $feature || $settings || $case_studies || $careers || $scholarship)
                    <li class="slide {{ request()->routeIs('pages.*') || request()->routeIs('menus.*') || request()->routeIs('admin.settings.*') || request()->routeIs('case-studies.*') || request()->routeIs('careers.*') || request()->routeIs('admin.scholarship.*') ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ request()->routeIs('pages.*') || request()->routeIs('menus.*') || request()->routeIs('admin.settings.*') || request()->routeIs('case-studies.*') || request()->routeIs('careers.*') || request()->routeIs('admin.scholarship.*') ? 'active' : '' }}" data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="feather feather-shopping-cart sidemenu_icon"></i>
                            <span class="side-menu__label">CMS</span><i class="angle fa fa-angle-right"></i></a>
                        <ul class="slide-menu {{ request()->routeIs('pages.*') || request()->routeIs('menus.*') || request()->routeIs('settings.*') || request()->routeIs('case-studies.*') || request()->routeIs('careers.*') || request()->routeIs('admin.scholarship.*') ? 'open' : '' }}">
                            <li class="side-menu-label1"><a href="javascript:void(0);">CMS</a></li>
                            @can('view page')
                            <li><a href="{{ route('pages.list') }}" class="slide-item {{ request()->routeIs('pages.*') ? 'active' : '' }}">Pages</a></li>
                            @endcan
                            @can('view header menu')
                            <li><a href="{{ route('menus.index') }}" class="slide-item {{ request()->routeIs('menus.*') ? 'active' : '' }}">Header Menu</a></li>
                            @endcan
                            @can('view settings')
                            <li><a href="{{ route('admin.settings.index') }}" class="slide-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Settings</a></li>
                            @endcan
                            @can('view case studies')
                            <li><a href="{{ route('case-studies.index') }}" class="slide-item {{ request()->routeIs('case-studies.*') ? 'active' : '' }}">Case Studies</a></li>
                            @endcan
                            @can('view careers')
                            <li><a href="{{ route('careers.index') }}" class="slide-item {{ request()->routeIs('careers.*') ? 'active' : '' }}">Careers</a></li>
                            @endcan
                            @can('view scholarship')
                            <li><a href="{{ route('admin.scholarship.index') }}" class="slide-item {{ request()->routeIs('admin.scholarship.*') ? 'active' : '' }}">Scholarship</a>
                            @endcan
                            </li>

                        </ul>

                    </li>
                @endif
                @php
                    $users = Illuminate\Support\Facades\Gate::allows('view users');
                    $permissions = Illuminate\Support\Facades\Gate::allows('view permissions');
                    $roles = Illuminate\Support\Facades\Gate::allows('view roles');

                @endphp
                @if ($roles || $users || $permissions)
                    <li class="slide {{ request()->routeIs('users.permissions.*') || request()->routeIs('users.roles.*') || request()->routeIs('user.*') ? 'is-expanded' : '' }}">
                        <a class="side-menu__item {{ request()->routeIs('users.permissions.*') || request()->routeIs('users.roles.*') || request()->routeIs('user.*') ? 'active' : '' }}" data-bs-toggle="slide" href="javascript:void(0);">
                            <i class="feather feather-airplay sidemenu_icon"></i>
                            <span class="side-menu__label">User Management</span><i
                                class="angle fa fa-angle-right"></i>
                        </a>
                        <ul class="slide-menu {{ request()->routeIs('users.permissions.*') || request()->routeIs('users.roles.*') || request()->routeIs('user.*') ? 'open' : '' }}">
                            <li class="side-menu-label1"><a href="javascript:void(0);">User Management</a></li>
                            @can('view permissions')
                                <li><a href="{{ Route('users.permissions.index') }}" class="slide-item {{ request()->routeIs('users.permissions.*') ? 'active' : '' }}">Permissions</a></li>
                            @endcan
                            @can('view roles')
                                <li><a href="{{ Route('users.roles.index') }}" class="slide-item {{ request()->routeIs('users.roles.*') ? 'active' : '' }}">Roles</a></li>
                            @endcan
                            @can('view users')
                                <li><a href="{{ Route('user.list') }}" class="slide-item {{ request()->routeIs('user.*') ? 'active' : '' }}">Users</a></li>
                            @endcan
                        </ul>
                    </li>
                @endif
                @can('view subjects')
                @endcan
                </ul>
                <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                        width="24" height="24" viewBox="0 0 24 24">
                        <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                    </svg></div>
            </div>
        </div>
    </aside>
</div>
<!--app-sidebar closed-->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to activate current menu
    function activateCurrentMenu() {
        const currentPath = window.location.pathname;
        
        // Check all direct links
        document.querySelectorAll('.side-menu__item[href]').forEach(item => {
            const href = item.getAttribute('href');
            if (href && href !== 'javascript:void(0)' && href !== '#') {
                if (currentPath === href || 
                    (href !== '/' && currentPath.startsWith(href)) ||
                    (href.includes('?') && currentPath === href.split('?')[0])) {
                    
                    item.classList.add('active');
                    
                    // Expand parent if exists
                    const parentSlide = item.closest('.slide');
                    if (parentSlide) {
                        parentSlide.classList.add('is-expanded');
                        const slideMenu = parentSlide.querySelector('.slide-menu');
                        if (slideMenu) {
                            slideMenu.classList.add('open');
                        }
                    }
                }
            }
        });
        
        // Check nested menu items
        document.querySelectorAll('.slide-item').forEach(item => {
            const href = item.getAttribute('href');
            if (href && currentPath === href) {
                item.classList.add('active');
                
                // Expand all parent menus
                let parent = item.closest('.slide-menu');
                while (parent) {
                    parent.classList.add('open');
                    const grandParent = parent.closest('.slide');
                    if (grandParent) {
                        grandParent.classList.add('is-expanded');
                        parent = grandParent.querySelector('.slide-menu');
                    } else {
                        parent = null;
                    }
                }
            }
        });
        
        // If no direct match found, try partial match
        if (!document.querySelector('.side-menu__item.active') && 
            !document.querySelector('.slide-item.active')) {
            document.querySelectorAll('.side-menu__item[href]').forEach(item => {
                const href = item.getAttribute('href');
                if (href && href !== '/' && currentPath.includes(href)) {
                    item.classList.add('active');
                    const parentSlide = item.closest('.slide');
                    if (parentSlide) {
                        parentSlide.classList.add('is-expanded');
                    }
                }
            });
        }
    }
    
    // Initialize menu activation
    activateCurrentMenu();
    
    // Handle menu toggle clicks
    document.querySelectorAll('.side-menu__item[data-bs-toggle="slide"]').forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parentSlide = this.closest('.slide');
            const slideMenu = this.nextElementSibling;
            
            // Close other open menus
            document.querySelectorAll('.slide').forEach(slide => {
                if (slide !== parentSlide) {
                    slide.classList.remove('is-expanded');
                    const otherMenu = slide.querySelector('.slide-menu');
                    if (otherMenu) {
                        otherMenu.classList.remove('open');
                    }
                }
            });
            
            // Toggle current menu
            parentSlide.classList.toggle('is-expanded');
            if (slideMenu && slideMenu.classList.contains('slide-menu')) {
                slideMenu.classList.toggle('open');
            }
        });
    });
});
</script>