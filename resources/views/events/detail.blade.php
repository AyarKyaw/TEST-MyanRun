@extends('layouts.master')

@section('title', $title . ' - MYANRUN')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;700;800&display=swap" rel="stylesheet">
<style>
    .font-kanit { font-family: 'Kanit', sans-serif !important; }
    
    .hero-banner {
        height: 500px;
        background-image: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(0,0,0,0.7)), url('{{ asset($image) }}');
        background-size: cover; 
        background-position: center;
        background-repeat: no-repeat;
        position: relative;
    }
    
    .hero-overlay {
        position: absolute; 
        inset: 0;
        display: flex;
        align-items: flex-end;
        padding-bottom: 60px;
        color: white;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-top: -50px; /* Overlap effect */
        position: relative;
        z-index: 10;
        padding: 30px;
    }

    .video-container {
        position: relative;
        padding-bottom: 56.25%;
        height: 0;
        overflow: hidden;
        border-radius: 8px;
        background: #000;
    }
    .video-container iframe {
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        border: 0;
    }
    .gallery-img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 8px;
        transition: transform 0.3s ease;
    }

    .race-icon { color: #ef4444; width: 25px; text-align: center; margin-right: 10px; }
    
    .sticky-bottom-bar {
        position: fixed;
        bottom: 0; left: 0; right: 0;
        background: white;
        padding: 15px;
        box-shadow: 0 -5px 15px rgba(0,0,0,0.1);
        z-index: 1000;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .past-event-grayscale { filter: grayscale(100%); }

    .back-btn {
        position: absolute;
        top: 20px; left: 20px;
        z-index: 100;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        color: white !important;
        width: 45px; height: 45px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        border: 1px solid rgba(255, 255, 255, 0.3);
    }
</style>

<div class="font-kanit mb-5 pb-5" style="background-color: #f8fafc;">
    <div class="hero-banner {{ $status == 'past' ? 'past-event-grayscale' : '' }}">
        <a href="{{ url()->previous() }}" class="back-btn">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div class="hero-overlay">
            <div class="container">
                <span class="badge badge-{{ $status == 'live' ? 'danger' : ($status == 'coming' ? 'success' : 'secondary') }} mb-3 px-3 py-2 text-uppercase">
                    {{ $status == 'live' ? 'Registration Open' : ($status == 'coming' ? 'Coming Soon' : 'Event Concluded') }}
                </span>
                <h1 class="display-4 font-weight-bold mb-2 text-white">{{ $title }}</h1>
                <p class="h4 font-weight-light"><i class="fas fa-map-marker-alt mr-2"></i> {{ $location }}</p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="info-card mb-4">
                    <h3 class="font-weight-bold mb-4">Event Details</h3>
                    <p class="text-secondary">
                        {{ $event->description ?? 'Experience the beauty of Myanmar through our curated running routes. Join us for an unforgettable experience through nature and local culture.' }}
                    </p>
                    
                    <hr class="my-4">
                    
                    <h5 class="font-weight-bold">Race Categories</h5>
                    <table class="table table-borderless mt-3">
                        <tr class="border-bottom">
                            <td><strong>Standard Entry</strong></td>
                            <td class="text-right text-primary font-weight-bold">
                                {{ number_format($event->price ?? 15000) }} MMK
                            </td>
                        </tr>
                        {{-- You can add more categories here or via a loop if you have a categories table --}}
                    </table>
                </div>

                <div class="info-card mb-4">
                    <h5 class="font-weight-bold mb-3">Event Highlight Video</h5>
                    <div class="video-container">
                        @if($event->video_url)
                            {{-- This works if you store the YouTube ID like 'K_FvDL_anrs' --}}
                            <iframe src="https://www.youtube.com/embed/{{ $event->video_url }}" 
                                    title="YouTube video player" frameborder="0" allowfullscreen></iframe>
                        @else
                            <div class="text-center p-5 bg-light">No video available for this event.</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="info-card" style="margin-top: 20px;">
                    <h5 class="font-weight-bold mb-4">Quick Info</h5>
                    <div class="mb-3 d-flex align-items-center">
                        <i class="fas fa-calendar-check race-icon"></i>
                        <span><strong>Date:</strong> {{ $date }}</span>
                    </div>
                    <div class="mb-3 d-flex align-items-center">
                        <i class="fas fa-clock race-icon"></i>
                        <span><strong>Time:</strong> {{ $event->time_range ?? '06:00 AM' }} Start</span>
                    </div>
                    <div class="mb-4 d-flex align-items-center">
                        <i class="fas fa-running race-icon"></i>
                        <span><strong>Type:</strong> {{ $status == 'past' ? 'Road Run' : 'City Run' }}</span>
                    </div>


                <div class="info-card mt-4" style="margin-top: 20px;">
                    <h5 class="font-weight-bold mb-3">What's Included</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Quality Event Shirt</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Personalized Bib</li>
                        <li class="mb-2"><i class="fas fa-check text-success mr-2"></i> Finisher Medal</li>
                        <li><i class="fas fa-check text-success mr-2"></i> Refreshments</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Mobile Sticky Bar --}}
<div class="sticky-bottom-bar d-lg-none">
    <div>
        <small class="text-muted d-block uppercase font-weight-bold" style="font-size: 10px;">Starting From</small>
        <span class="font-weight-bold h5 mb-0 text-primary">{{ number_format($event->price ?? 15000) }} MMK</span>
    </div>
    @if($status == 'live')
        <a href="{{ $event->reg_link ?? '#' }}" class="btn btn-danger font-weight-bold px-4 py-2 text-uppercase">Join Now</a>
    @elseif($status == 'coming')
        <span class="badge badge-success">Coming Soon</span>
    @else
        <a href="#" class="btn btn-secondary font-weight-bold px-4 py-2 text-uppercase">Results</a>
    @endif
</div>
@endsection