<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta http-equiv="Content-Security-Policy" content="script-src 'self' 'wasm-unsafe-eval' 'inline-speculation-rules' chrome-extension:;"> --}}

    <title> @yield('title') </title>
    <!-- Load jQuery first -->

    <link rel="shortcut icon" href="{{ asset('build/assets/frontend/images/escience-logo.svg') }}" type="image/x-icon">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>


    {{-- <script src="{{asset('build/assets/admin/dist/js/select2.min.js')}}"></script> --}}
    {{-- <script src="{{asset('build/assets/admin/dist/js/jquery.select2.js')}}"></script> --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css">

    <!--Favicon -->
    <!-- Bootstrap css -->
    <link href="{{ asset('build/assets/admin/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet" />
    <!--<link href="{{ asset('build/assets/admin/plugins/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" />-->
    <!--{{-- <link href="{{asset('build/assets/admin/plugins/bootstrap/css/bootstrap.rtl.css')}}" rel="stylesheet" /> --}}-->

    <!-- Style css -->
    <link href="{{ asset('build/assets/admin/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('build/assets/admin/css/plugins.css') }}" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

<!-- Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
    .toast-success {
        background-color: #28a745 !important;
    }
    .toast-error {
        background-color: #dc3545 !important;
    }
</style>
    <!-- Animate css -->
    <link href="{{ asset('build/assets/admin/css/animated.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('build/assets/admin/plugins/jQuerytransfer/icon_font/icon_font.css') }}">
    {{-- <link rel="stylesheet" href="{{asset('build/assets/admin/plugins/sweetalert/sweetalert.css')}}"> --}}
    <script src="{{ asset('build/assets/admin/tinymce/tinymce.min.js') }}"></script>
    {{-- <link href="{{ asset('build/assets/admin/plugins/time-picker/jquery.timepicker.css') }}" rel="stylesheet" /> --}}

    <!---Icons css-->
    <link href="{{ asset('build/assets/admin/css/icons.css') }}" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .badge-success {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
        }

        .fade {
            opacity: 0;
            transition: opacity 1.3s ease;
        }

        .show {
            opacity: 1;
        }

        .alert-message {
            position: fixed;
            z-index: 1000;
            width: max-content;
            height: max-content;
            right: 30px;
            top: 68px;
        }
    </style>
    <!-- Include DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
    @stack('style')



</head>

<body class="app sidebar-mini ltr">
    <div class="page" id="mainNavShow">
        <div class="page-main">
            @include('layouts.admin.header')
            @include('layouts.admin.sidebar')
            {{-- <div class="app-content main-content"> --}}
            <div class="side-app">
                @yield('content')
            </div>
            {{-- </div> --}}
        </div>

        @include('layouts.admin.footer')
    </div>
    <!-- Back to top -->
    <a href="#top" id="back-to-top"><span class="feather feather-chevrons-up"></span></a>
    <script src="{{ asset('build/assets/admin/plugins/jquery/jquery.min.js') }}"></script>

    <!-- INTERNAL Time Counter -->
    <script src="{{ asset('build/assets/admin/plugins/counters/counterup.min.js') }}"></script>
    <script src="{{ asset('build/assets/admin/plugins/counters/waypoints.min.js') }}"></script>
    <script src="{{ asset('build/assets/admin/plugins/counters/counter.js') }}"></script>
    <script src="{{ asset('build/assets/admin/plugins/circle-progress/circle-progress.min.js') }}"></script>

    <!--?jstree-->
    
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- INTERNAL Counters -->
    <script src="{{ asset('build/assets/admin/plugins/countdown/countdowntime.js') }}"></script>
    <script src="{{ asset('build/assets/admin/js/countdown.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Jquery js-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>

    <!--Moment js-->
    <script src="{{ asset('build/assets/admin/plugins/moment/moment.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('build/assets/admin/plugins/bootstrap/js/popper.min.js') }}"></script>
    <script src="{{ asset('build/assets/admin/plugins/bootstrap/js/bootstrap.min.js') }}"></script>


    <!--Othercharts js-->
    <script src="{{ asset('build/assets/admin/plugins/othercharts/jquery.sparkline.min.js') }}"></script>

    <!-- Circle-progress js-->
    <script src="{{ asset('build/assets/admin/plugins/circle-progress/circle-progress.min.js') }}"></script>

    <!--Sidemenu js-->
    <script src="{{ asset('build/assets/admin/plugins/sidemenu/sidemenu.js') }}"></script>

    <!-- P-scroll js-->
    <script src="{{ asset('build/assets/admin/plugins/p-scrollbar/p-scrollbar.js') }}"></script>
    <script src="{{ asset('build/assets/admin/plugins/p-scrollbar/p-scroll1.js') }}"></script>

    <!--Sidebar js-->
    <script src="{{ asset('build/assets/admin/plugins/sidebar/sidebar.js') }}"></script>

    <!-- Select2 js -->
    <script src="{{ asset('build/assets/admin/plugins/select2/select2.full.min.js') }}"></script>

    <!-- INTERNAL Timepicker js -->
    {{-- <script src="{{asset('build/assets/admin/plugins/time-picker/jquery.timepicker.js')}}"></script> --}}
    <script src="{{ asset('build/assets/admin/plugins/time-picker/toggles.min.js') }}"></script>

    <!-- INTERNAL Datepicker js -->
    <script src="{{ asset('build/assets/admin/plugins/date-picker/date-picker.js') }}"></script>
    <script src="{{ asset('build/assets/admin/plugins/date-picker/jquery-ui.js') }}"></script>
    <script src="{{ asset('build/assets/admin/plugins/input-mask/jquery.maskedinput.js') }}"></script>

    <!-- INTERNAL File-Uploads Js-->
    <script src="{{ asset('build/assets/admin/plugins/fancyuploder/jquery.ui.widget.js') }}"></script>
    <script src="{{ asset('build/assets/admin/plugins/fancyuploder/jquery.fileupload.js') }}"></script>
    <script src="{{ asset('build/assets/admin/plugins/fancyuploder/jquery.iframe-transport.js') }}"></script>
    <script src="{{ asset('build/assets/admin/plugins/fancyuploder/jquery.fancy-fileupload.js') }}"></script>
    <script src="{{ asset('build/assets/admin/plugins/fancyuploder/fancy-uploader.js') }}"></script>

    <!-- INTERNAL File uploads js -->
    <script src="{{ asset('build/assets/admin/plugins/fileupload/js/dropify.js') }}"></script>
    <script src="{{ asset('build/assets/admin/js/filupload.js') }}"></script>

    <!-- INTERNAL Sumoselect js-->
    <script src="{{ asset('build/assets/admin/plugins/sumoselect/jquery.sumoselect.js') }}"></script>

    <!-- INTERNAL intlTelInput js-->
    {{-- <script src="{{asset('build/assets/admin/plugins/intl-tel-input-master/intlTelInput.js')}}"></script>
		<script src="{{asset('build/assets/admin/plugins/intl-tel-input-master/country-select.js')}}"></script>
		<script src="{{asset('build/assets/admin/plugins/intl-tel-input-master/utils.js')}}"></script> --}}

    <!-- INTERNAL jquery transfer js-->
    {{-- <script src="{{asset('build/assets/admin/plugins/jQuerytransfer/jquery.transfer.js')}}"></script> --}}

    <!-- INTERNAL multi js-->
    {{-- <script src="{{asset('build/assets/admin/plugins/multi/multi.min.js')}}"></script> --}}

    <!-- INTERNAL Bootstrap-Datepicker js-->
    {{-- <script src="{{asset('build/assets/admin/plugins/bootstrap-datepicker/bootstrap-datepicker.js')}}"></script> --}}

    <!-- INTERNAL Form Advanced Element -->
    {{-- <script src="{{asset('build/assets/admin/js/formelementadvnced.js')}}"></script>
		<script src="{{asset('build/assets/admin/js/form-elements.js')}}"></script> --}}
    <script src="{{ asset('build/assets/admin/js/select2.js') }}"></script>

    <!-- INTERNAL Multiple select js -->
    {{-- <script src="{{asset('build/assets/admin/plugins/multipleselect/multiple-select.js')}}"></script>
		<script src="{{asset('build/assets/admin/plugins/multipleselect/multi-select.js')}}"></script> --}}

    <!--Sticky js -->
    <script src="{{ asset('build/assets/admin/js/sticky.js') }}"></script>


    <!-- Color Theme js -->
    <script src="{{ asset('build/assets/admin/js/themeColors.js') }}"></script>
    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js">
    </script>
    <!-- custom js -->
    <script src="{{ asset('build/assets/admin/js/custom.js') }}"></script>
    @stack('script')
</body>

</html>
