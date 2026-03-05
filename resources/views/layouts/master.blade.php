<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'MYANRUN | HAPPY HUB FOR RUNNERS')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
/* ===============================
   COUNTDOWN
================================ */
.box-events-slide {
    position: relative; /* make this container the reference point */
    padding-top: 40px;  /* optional, to give space for logo */
}

.box-events-slide .logo-event {
    position: absolute;
    top: -130px;      /* moves logo above the top of the box */
    left: 50%;       /* center horizontally */
    transform: translateX(-50%); /* perfect horizontal centering */
    z-index: 10;     /* make sure it stays on top of other elements */
}

.box-events-slide .logo-event img {
    width: 200px;     /* adjust logo size as needed */
    height: auto;
}


.marathon-countdown {
    padding: 40px 0;
    text-align: center;
}
.logo-event img {
    max-width: 200px; /* adjust size */
    height: auto;
}
/* ===============================
   MODAL LAYOUT
================================ */
.modal-dialog {
    max-width: 500px !important;;
}

.marathon-modal {
    border-radius: 25px !important;
    overflow: hidden;
    border: 1px solid #e5e5e5;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
    padding: 0 !important;
}

.marathon-text h4 {
    font-family: "Libre Baskerville", serif;
    font-weight: 700;
    letter-spacing: 0.2px;
    text-align: center;
    color: #C3E92D;
    margin-bottom: 12px;
}

.primary-text {
    font-weight: 600;            
    font-size: 20px;
    color: #111;
    text-align: center;
}

.marathon-text p strong {
    font-weight: 400;
    text-align: justify;
}


.marathon-text,
.marathon-image {
    width: 100%;
}

/* Horizontal layout */
.marathon-body {
    display: flex;
    min-height: 360px;
    flex-direction: column;
}

/* Close button */
.marathon-close {
    position: absolute;
    top: 14px;
    right: 14px;
    z-index: 10;
}

/* LEFT: Text */
.marathon-text {
    padding: 32px;
    display: flex;

    flex-direction: column;
    justify-content: center;
    font-family: "Inter", sans-serif;
}

/* RIGHT: Image */
.marathon-image {
    width: 100%;
    background: #000;
}

.marathon-image img {
    width: 100%;
    height: 100%;
    display: block;
    object-fit: cover;
}

.video-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #fff;
    background: rgba(0,0,0,0.5);
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s;
}

.video-overlay:hover {
    background: rgba(0,0,0,0.7);
}

/* ===============================
   COUNTDOWN LINE
================================ */

.countdown-title {
    font-size: 60px;
    font-weight: 900;
    color: #ffffff;
    letter-spacing: 1px;
    margin-bottom: 10px;
}

.countdown-subtitle {
    font-size: 20px;
    color: #bbbbbb;
    margin-bottom: 40px;
}

.countdown-line {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 20px;
    font-size: 36px;
    font-weight: 500;
    color: #dddddd;
    background-color: #121212;
    padding: 50px 0;
}

.countdown-line strong {
    font-size: 64px;
    font-weight: 900;
    color: #ffffff;
}

.separator {
    font-size: 48px;
    color: #888888;
}

/* ===============================
   PRELOADER
================================ */
.preload-container {
    position: fixed;
    inset: 0;
    background: #000;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
}

.loader-gif {
    width: 180px;
    max-width: 60vw;
    height: auto;
}

/* ===============================
   MOBILE FIX (IMPORTANT)
================================ */
@media (max-width: 768px) {

    /* Modal stacks vertically */
    .marathon-body {
        flex-direction: column;
    }

    .marathon-text,
    .marathon-image {
        width: 100%;
    }

    .marathon-image {
        height: 100%;
    }

    .marathon-image img {
        width: 100%;
        height: 100%; /* 🔥 VERY IMPORTANT */
        object-fit: cover;
        display: block;
    }

    /* Countdown responsive */
    .countdown-title {
        font-size: 36px;
    }

    .countdown-subtitle {
        font-size: 16px;
        margin-bottom: 25px;
    }

    .countdown-line {
        font-size: 20px;
        gap: 12px;
    }

    .countdown-line strong {
        font-size: 36px;
    }

    .separator {
        font-size: 28px;
    }
}

.address {
    font-family: Arial, sans-serif;
}

.address p {
    margin-bottom: 5px;
    font-weight: bold;
}

.contact-numbers {
    display: flex;
    gap: 20px; /* spacing between numbers */
    align-items: center;
}

/* 1. Target the header and ensure it sits above content but below modals */
#header, 
.header, 
.header-sticky #header, {
    position: fixed; /* or sticky depending on your theme */
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000 !important; /* Above .item (usually 1-10) but below Modal (1050) */
}

/* 2. Ensure the Modal is always higher than the header */
.modal {
    z-index: 1060 !important; 
}

/* 3. Ensure the darkened background (backdrop) is also above the header */
.modal-backdrop {
    z-index: 1050 !important;
}

.contact-numbers span,
.contact-numbers a span {
    cursor: pointer;
    transition: color 0.3s;
}

.contact-numbers span:hover,
.contact-numbers a span:hover {
    color: #C3E92D; /* change to your desired color on hover */
}

.phone-link {
    color: inherit;          /* same color as text */
    text-decoration: none;   /* remove underline */
    font-weight: inherit;
}

.phone-link:hover,
.phone-link:focus,
.phone-link:active {
    color: #C3E92D;          /* change this to any color you want */
}

.contact-link {
    color: inherit;          /* same text color */
    text-decoration: none;   /* no underline */
    font-weight: inherit;
}

.contact-link:hover,
.contact-link:focus,
.contact-link:active {
    color: #C3E92D;          /* change to any color you want */
}

/* Hide mobile-specific menu items on Desktop */
@media (min-width: 992px) {
    .mobile-only-menu {
        display: none !important;
    }
}

/* Ensure the modal is always on top of the nav */
.modal {
    z-index: 10001 !important;
}
.modal-backdrop {
    z-index: 10000 !important;
}

/* Style the asterisk in the menu */
.menu li a span {
    font-weight: bold;
}
</style>
    <link rel="stylesheet" type="text/css" href="{{ asset('stylesheets/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('stylesheets/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('stylesheets/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('stylesheets/colors/color1.css') }}" id="colors">
    <link rel="stylesheet" type="text/css" href="{{ asset('stylesheets/swiper-bundle.min.css') }}">

    <link href="{{ asset('images/icon/Myan Run icon.png') }}" rel="apple-touch-icon-precomposed" sizes="48x48">
    <link href="{{ asset('images/icon/Myan Run icon.png') }}" rel="apple-touch-icon-precomposed">
    <link href="{{ asset('images/icon/Myan Run icon.png') }}" rel="shortcut icon">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/icon/Myan Run icon.png') }}">
    @stack('styles') {{-- For page-specific CSS --}}
</head>
<body class="header-sticky">
    
    {{-- Preloader --}}
    <div class="preload preload-container">
        <img src="{{ asset('images/running.gif') }}" alt="Loading" class="loader-gif">
        <h1 style="color: #7c6cb8;">MYANRUN.com</h1>
    </div>
    
    {{-- Navigation --}}
    @include('partials.header')
    @if(session('success'))
        <div style="background-color: #d4edda; color: #155724; padding: 15px; border: 1px solid #c3e6cb; border-radius: 5px;">
            {{ session('success') }}
        </div>
    @endif
    
    {{-- Content Area --}}
    <main>
        @yield('content')
    </main>
    @include('partials.footer')
    {{-- Scripts --}}
    <script>
        window.mapboxToken = "{{ env('MAPBOX_ACCESS_TOKEN') }}";
    </script>
    <script src="{{ asset('javascript/jquery.min.js') }}"></script>
    <script src="{{ asset('javascript/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('javascript/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('javascript/jquery.cookie.js') }}"></script>
    <script src="{{ asset('javascript/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('javascript/owl.carousel.js') }}"></script>
    <script src="{{ asset('javascript/wow.min.js') }}"></script>
    <script src="{{ asset('javascript/count-down.js') }}"></script>
    <script src="{{ asset('javascript/map.min.js') }}"></script>
    <script src="{{ asset('javascript/map.js') }}"></script>
    <script src="{{ asset('javascript/jquery-waypoints.js') }}"></script>
    <script src="{{ asset('javascript/jquery-countTo.js') }}"></script>
    <script src="{{ asset('javascript/main.js') }}"></script>

    @stack('scripts')
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
$(document).ready(function() {
    // 2. TELL AXIOS WHERE TO FIND THE CSRF TOKEN
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token;

    $(document).on('submit', '#tfre_custom-register-form', function(e) {
        e.preventDefault();
        e.stopImmediatePropagation();

        const $form = $(this);
        const $errorBox = $form.find('.error_message');
        const formData = new FormData(this);

        $errorBox.css('color', 'white').text('Processing...');

        axios.post('{{ route("register.submit") }}', formData)
            .then(function (response) {
                $errorBox.css('color', '#C3E92D').text(response.data.message);
                window.location.href = response.data.redirect;
            })
            .catch(function (error) {
                if (error.response && error.response.status === 419) {
                    $errorBox.css('color', 'orange').text('Session expired. Please refresh the page.');
                } else if (error.response && error.response.status === 422) {
                    $errorBox.css('color', 'red').html(Object.values(error.response.data.errors).flat().join('<br>'));
                } else {
                    $errorBox.css('color', 'red').text('An error occurred.');
                }
            });

        return false;
    });
});
</script>
@endpush
</body>
</html>