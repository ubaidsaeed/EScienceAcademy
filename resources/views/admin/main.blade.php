
@include('layouts.admin.app')
  
<body class="app sidebar-mini">

    <!---Global-loader-->
    <div id="global-loader">
        <img src="{{ asset('assets/backend/images/svgs/loader.svg') }}" alt="loader">
    </div>

    <div class="page">
        <div class="page-main">
            @include('layouts.admin.sidebar')
            @include('layouts.admin.header')
            @yield('content')
        </div>
        @include('layouts.admin.footer')
    </div>

@include('layouts.admin.scripts')