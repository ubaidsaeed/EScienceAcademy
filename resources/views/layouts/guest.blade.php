<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

 <title> eScience Academy </title>
     <link rel="shortcut icon" href="{{asset('build/assets/frontend/images/escience-logo.svg')}}" type="image/x-icon">

 <!-- Bootstrap css -->
    <link href="{{asset('build/assets/admin/plugins/bootstrap/css/bootstrap.css')}}" rel="stylesheet" />
    <link href="{{asset('build/assets/admin/plugins/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" />
    <link href="{{asset('build/assets/admin/plugins/bootstrap/css/bootstrap.rtl.css')}}" rel="stylesheet" />
{{--
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    --}}
    <!-- Style css -->
    <link href="{{asset('build/assets/admin/css/style.css')}}" rel="stylesheet" />
    <link href="{{asset('build/assets/admin/css/plugins.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Animate css -->
    <link href="{{asset('build/assets/admin/css/animated.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('build/assets/admin/plugins/jQuerytransfer/icon_font/icon_font.css')}}">
    {{--
    <link rel="stylesheet" href="{{asset('build/assets/admin/plugins/sweetalert/sweetalert.css')}}"> --}}
    <script src="{{asset('build/assets/admin/tinymce/tinymce.min.js')}}"></script>

    <!---Icons css-->
    <link href="{{asset('build/assets/admin/css/icons.css')}}" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="login-img">

    <div class="page" style="min-height:400px !important;">
        <div class="page-single">
            <div class="container">


                {{-- <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    --}}
                    {{ $slot }}
                    {{-- </div> --}}
            </div>
        </div>
    </div>
    <!-- Jquery js-->
    <script src="{{asset('build/assets/admin/plugins/jquery/jquery.min.js')}}"></script>

    <!-- Bootstrap js-->
    <script src="{{asset('build/assets/admin/plugins/bootstrap/js/popper.min.js')}}"></script>
    <script src="{{asset('build/assets/admin/plugins/bootstrap/js/bootstrap.min.js')}}"></script>

    <!-- Select2 js -->
    <script src="{{asset('build/assets/admin/plugins/select2/select2.full.min.js')}}"></script>

    <!-- P-scroll js-->
    <script src="{{asset('build/assets/admin/plugins/p-scrollbar/p-scrollbar.js')}}"></script>

    <!--Sticky js -->
    <script src="{{asset('build/assets/admin/js/sticky.js')}}"></script>


    <!-- Color Theme js -->
    <script src="{{asset('build/assets/admin/js/themeColors.js')}}"></script>

    <!-- custom js -->
    <script src="{{asset('build/assets/admin/js/custom.js')}}"></script>

</body>

</html>