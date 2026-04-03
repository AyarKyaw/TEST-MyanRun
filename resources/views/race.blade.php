@extends('layouts.master')

@section('title', 'Race - MYANRUN')

@section('content')
<div class="page-title">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-heading">
                        <h1 class="title">our race</h1>
                    </div><div class="breadcrumbs">
                        <ul>
                            <li><a href="{{ url('/') }}">Homepage</a></li>
                            <li> <i class="icon-Arrow---Right-2"></i></li>
                            <li><a>Our Race</a></li>
                        </ul>
                    </div></div></div></div></div><section class="main-content blog-posts">
        <div class="themeflat-container">
            
            <div class="text-center mb-5">
                <button class="tab-btn active" onclick="showRace('cherry')">
                    Cherry Trail Run 2026
                </button>
                <button class="tab-btn" onclick="showRace('monsoon')">
                    Alaingni Monsoon Duathlon 2026
                </button>
            </div>

            <div class="row">
                <div class="col-md-12 col-lg-9 col-xl-9 col-xxl-9 widget-blog-content">
                    
                    <div id="cherry" class="tab-content active">
                        <div class="post-wrap">
                             <article class="entry format-standard wow fadeInUp animated">
                                <div class="feature-post">
                                    <img src="{{ asset('images/t(inches).jpg') }}" alt="image">
                                </div>
                                <div class="main-post">
                                    <div class="tag"><ul><li><a href="">MyanRun</a></li></ul></div>
                                    <h2 class="entry-title"><a href="">T-shirt Size Chart (Inches)</a></h2>
                                </div>
                            </article>

                             <article class="entry format-standard wow fadeInUp animated">
                                <div class="feature-post">
                                    <img src="{{ asset('images/t(cm).jpg') }}" alt="image">
                                </div>
                                <div class="main-post">
                                    <div class="tag"><ul><li><a href="">MyanRun</a></li></ul></div>
                                    <h2 class="entry-title"><a href="">T-shirt Size Chart (Cm)</a></h2>
                                </div>
                            </article>

                            <article class="entry format-standard wow fadeInUp animated">
                                <div class="feature-post">
                                    <img src="{{ asset('images/RF.jpg') }}" alt="image">
                                </div>
                                <div class="main-post">
                                    <div class="tag"><ul><li><a href="">MyanRun</a></li></ul></div>
                                    <h2 class="entry-title"><a href="">Registration Fees</a></h2>
                                </div>
                            </article>

                            <article class="entry format-standard wow fadeInUp animated">
                                <div class="feature-post">
                                    <img src="{{ asset('images/Entitlement.jpg') }}" alt="image">
                                </div>
                                <div class="main-post">
                                    <div class="tag"><ul><li><a href="">MyanRun</a></li></ul></div>
                                    <h2 class="entry-title"><a href="">Entitlement</a></h2>
                                </div>
                            </article>
                        </div>
                    </div>

                    <div id="monsoon" class="tab-content">
                        <div class="post-wrap">
                            <article class="entry format-standard wow fadeInUp animated">
                                <div class="feature-post">
                                    <img src="{{ asset('images/ADM/Register.jpg') }}" alt="image">
                                </div>
                                <div class="main-post">
                                    <div class="tag"><ul><li><a href="">MyanRun</a></li></ul></div>
                                    <h2 class="entry-title"><a href="">Registration Fee</a></h2>
                                </div>
                            </article>
                            <article class="entry format-standard wow fadeInUp animated">
                                <div class="feature-post">
                                    <img src="{{ asset('images/ADM/t_shirt(cm).jpg') }}" alt="image">
                                </div>
                                <div class="main-post">
                                    <div class="tag"><ul><li><a href="">MyanRun</a></li></ul></div>
                                    <h2 class="entry-title"><a href="">T-shirt Size Chart (Cm)</a></h2>
                                </div>
                            </article>
                            <article class="entry format-standard wow fadeInUp animated">
                                <div class="feature-post">
                                    <img src="{{ asset('images/ADM/t_shirt(inches).jpg') }}" alt="image">
                                </div>
                                <div class="main-post">
                                    <div class="tag"><ul><li><a href="">MyanRun</a></li></ul></div>
                                    <h2 class="entry-title"><a href="">T-shirt Size Chart (Inches)</a></h2>
                                </div>
                            </article>
                            <article class="entry format-standard wow fadeInUp animated">
                                <div class="feature-post">
                                    <img src="{{ asset('images/ADM/Entitlement.jpg') }}" alt="image">
                                </div>
                                <div class="main-post">
                                    <div class="tag"><ul><li><a href="">MyanRun</a></li></ul></div>
                                    <h2 class="entry-title"><a href="">Entitlement</a></h2>
                                </div>
                            </article>
                        </div>
                    </div>

                </div></div></div></section>
@endsection

@push('scripts')
<style>
    .tab-btn {
        padding: 10px 25px;
        margin: 5px;
        border: 2px solid #e2e2e2;
        background: transparent;
        cursor: pointer;
        transition: all 0.3s;
        font-weight: 600;
        text-transform: uppercase;
    }
    .tab-btn.active {
        background-color: #f32722; /* Matches your theme red */
        color: #fff;
        border-color: #f32722;
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
</style>

<script>
function showRace(raceId) {
    // Hide all contents
    document.querySelectorAll('.tab-content').forEach(el => {
        el.classList.remove('active');
    });

    // Remove active class from all buttons
    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('active');
    });

    // Show selected content
    document.getElementById(raceId).classList.add('active');
    
    // Add active class to clicked button
    event.currentTarget.classList.add('active');
}
</script>
@endpush