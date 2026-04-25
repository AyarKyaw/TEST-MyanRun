<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <base href="/">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="max-age=31536000, public">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Myanrun - Dashboard</title>

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/icon/Myan Run icon.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/@yaireo/tagify/dist/tagify.css') }}">
    
    <link href="{{ asset('assets/vendor/metismenu/dist/metisMenu.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-select/dist/css/bootstrap-select.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/vendor/chartist/css/chartist.min.css') }}">
    <link href="{{ asset('assets/css/switcher.css') }}" rel="stylesheet">
    <link class="main-plugins" href="{{ asset('assets/css/plugins.css') }}" rel="stylesheet">
    <link class="main-css" href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <style>
    /* 1. Hide the sidebar by default on smaller screens */
    .event-sidebar {
        display: none !important;
        transition: all 0.3s ease;
    }

    /* 2. Only show the sidebar when the screen is wide enough (e.g., 1400px+) */
    @media (min-width: 1400px) {
        .event-sidebar {
            display: block;
            right: 0;
            width: 300px; /* Adjust based on your template's width */
            position: fixed;
        }

        /* Adjust the main content margin ONLY when the sidebar is visible */
        .content-body.rightside-event {
            margin-right: 300px !important;
        }
    }

    /* 3. Handle the 'too small' state explicitly */
    @media (max-width: 1399px) {
        .content-body.rightside-event {
            margin-right: 0 !important;
        }
        .event-sidebar {
            right: -450px; /* Push it completely off-screen */
            display: none;
        }
    }
    [class^="DZ-bt-support-now DZ-theme-btn"] {
    display: none !important;
    visibility: hidden !important;
}
    [class^="DZ-bt-buy-now DZ-theme-btn"] {
    display: none !important;
    visibility: hidden !important;
}
</style>
    </head>
<body>

    <div id="preloader">
        <div class="sk-three-bounce">
            <div class="sk-child sk-bounce1"></div>
            <div class="sk-child sk-bounce2"></div>
            <div class="sk-child sk-bounce3"></div>
        </div>
    </div>
    

    <div id="main-wrapper">
        @include('dashboard.layouts.nav')
        @include('dashboard.layouts.header')
        @include('dashboard.layouts.sidebar')
        
        @yield('content')

    </div>

    <script src="{{ asset('assets/vendor/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/metismenu/dist/metisMenu.min.js') }}"></script>
    
    <script src="{{ asset('assets/vendor/@yaireo/tagify/dist/tagify.js') }}"></script>
    <script src="{{ asset('assets/vendor/chart-js/chart.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-datetimepicker/js/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/peity/jquery.peity.min.js') }}"></script>
    
    <script src="{{ asset('assets/vendor/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/vendor/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins-init/select2-init.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ asset('assets/js/dashboard/analytics.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard/dashboard-1.js') }}"></script>

    <script src="{{ asset('assets/vendor/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/js/translator.js') }}"></script>

    <script src="{{ asset('assets/js/deznav-init.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/demo.js') }}"></script>
    <script src="{{ asset('assets/js/styleSwitcher.js') }}"></script>

    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').css('background-image', 'url('+e.target.result +')').hide().fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imageUpload").on('change', function() { readURL(this); });
        $('.remove-img').on('click', function() {
            var noImg = "{{ asset('assets/images/no-img-avatar.avif') }}";
            $('.avatar-preview, #imagePreview').removeAttr('style');
            $('#imagePreview').css('background-image', 'url(' + noImg + ')');
        });
    </script>

    @stack('scripts')
</body>
</html>