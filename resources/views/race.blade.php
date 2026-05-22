@extends('layouts.master')

@section('title', 'Race - MYANRUN')

@section('content')
<div class="page-title">
    <div class="themeflat-container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-heading">
                    <h1 class="title">our race</h1>
                </div>
                <div class="breadcrumbs">
                    <ul>
                        <li><a href="{{ url('/') }}">Homepage</a></li>
                        <li> <i class="icon-Arrow---Right-2"></i></li>
                        <li><a>Our Race</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="main-content blog-posts">
    <div class="themeflat-container">
        
        <!-- Generated Tab Buttons Dynamic Filter -->
        <div class="text-center mb-5">
            @foreach($races as $index => $race)
                <button class="tab-btn {{ $index === 0 ? 'active' : '' }}" onclick="showRace('race-{{ $race->slug }}')">
                    {{ $race->name }}
                </button>
            @endforeach
        </div>

        <div class="row">
            <div class="col-md-12 col-lg-9 col-xl-9 col-xxl-9 widget-blog-content">
                
                <!-- Display Tab Contents dynamically based on Active database statuses -->
                @foreach($races as $index => $race)
                    <div id="race-{{ $race->slug }}" class="tab-content {{ $index === 0 ? 'active' : '' }}">
                        <div class="post-wrap">
                            
                            @foreach($race->cards as $card)
                                <article class="entry format-standard wow fadeInUp animated">
                                    <div class="feature-post">
                                        <img src="{{ asset($card->image) }}" alt="{{ $card->title }}">
                                    </div>
                                    <div class="main-post">
                                        <div class="tag"><ul><li><a href="">MyanRun</a></li></ul></div>
                                        <h2 class="entry-title"><a href="">{{ $card->title }}</a></h2>
                                    </div>
                                </article>
                            @endforeach

                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
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
        background-color: #f32722;
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
    document.querySelectorAll('.tab-content').forEach(el => {
        el.classList.remove('active');
    });

    document.querySelectorAll('.tab-btn').forEach(el => {
        el.classList.remove('active');
    });

    document.getElementById(raceId).classList.add('active');
    event.currentTarget.classList.add('active');
}
</script>
@endpush