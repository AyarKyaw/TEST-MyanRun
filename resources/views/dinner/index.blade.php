@extends('layouts.master')

@section('title', 'Dinner Tickets - MYANRUN')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .font-kanit { font-family: 'Kanit', sans-serif !important; }
    .thairun-shadow { box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); }
    
    .event-link { text-decoration: none !important; color: inherit !important; display: block; }
    .event-card { transition: transform 0.3s ease; }
    .event-card:hover { transform: translateY(-5px); }

    .status-ribbon {
        position: absolute;
        top: 12px;
        left: -4px;
        padding: 4px 12px;
        font-weight: 800;
        font-size: 11px;
        text-transform: uppercase;
        border-radius: 0 4px 4px 0;
        z-index: 10;
        box-shadow: 2px 2px 5px rgba(0,0,0,0.1);
    }
    
    .ribbon-gold { background: #f59e0b; color: #fff; }

    .event-section-title {
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.5rem;
        margin-bottom: 1.5rem;
        font-style: italic;
    }

    .event-card-img {
        height: 220px !important;
        object-fit: cover;
        width: 100%;
    }
</style>

<div class="page-title">
    <div class="themeflat-container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-heading">
                    <h1 class="title">Dinner Tickets</h1>
                </div>
                <div class="breadcrumbs">
                    <ul>
                        <li><a href="/">Homepage</a></li>
                        <li><i class="icon-Arrow---Right-2"></i></li>
                        <li><a>Dinner Tickets</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="font-kanit" style="background-color: #f8fafc; padding: 60px 0; min-height: 600px;">
    <div class="container">
        
        <div class="mb-5">
            <div class="event-section-title d-flex align-items-center">
                <span class="d-inline-block bg-warning rounded-pill mr-2" style="width: 8px; height: 32px;"></span>
                <h2 class="h3 font-weight-bold mb-0 text-dark">AVAILABLE DINNERS</h2>
            </div>
            
            <div class="row">
                @forelse($dinners as $dinner)
                    <div class="col-lg-4 col-md-6 mb-4">
                        {{-- Assuming you have a route to show specific dinner ticket details --}}
                        <a href="{{ route('dinner.tickets', $dinner->id) }}" class="event-link">
                            <div class="card border-0 rounded-lg overflow-hidden thairun-shadow position-relative h-100 event-card">
                                <div class="ribbon-gold status-ribbon">{{ $dinner->company }}</div>
                                
                                {{-- Use the stored image path --}}
                                <img src="{{ asset('storage/' . $dinner->image_path) }}" 
                                     class="card-img-top event-card-img" 
                                     alt="{{ $dinner->name }}">
                                     
                                <div class="card-body p-4">
                                    <span class="badge badge-light text-muted mb-2 uppercase" style="font-size: 10px;">
                                        {{ $dinner->date ? $dinner->date->format('d M Y') : 'Date TBA' }}
                                    </span>
                                    <h4 class="h5 font-weight-bold text-dark mb-4">{{ $dinner->name }}</h4>
                                    
                                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                        <span class="text-muted small">
                                            <i class="fas fa-map-marker-alt"></i> {{ $dinner->location ?? 'Location TBA' }}
                                        </span>
                                        <span class="btn btn-warning btn-sm font-weight-bold px-3 py-2 text-white">BOOK NOW</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-md-12">
                        <div class="p-5 text-center border rounded" style="border-style: dashed !important; border-color: #cbd5e1 !important;">
                            <p class="text-muted mb-0">No dinner events are currently available. Please check back later!</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@if(session('success'))
<div id="successModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[3rem] w-full max-w-sm p-10 shadow-2xl text-center transform transition-all animate-bounce-short">
        <div class="w-24 h-24 bg-[#C3E92D] rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-lime-200">
            <i class="fas fa-check text-4xl text-slate-900"></i>
        </div>
        <h2 class="text-2xl font-black text-slate-800 uppercase italic mb-2">Registration Sent!</h2>
        <p class="text-slate-500 text-sm font-semibold mb-8">{{ session('success') }}</p>
        
        <button onclick="document.getElementById('successModal').remove()" class="w-full py-4 bg-slate-900 text-white font-black rounded-2xl uppercase tracking-widest text-xs hover:bg-slate-800 transition-all">
            Awesome!
        </button>
    </div>
</div>

<style>
    @keyframes bounce-short {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .animate-bounce-short { animation: bounce-short 0.5s ease-in-out; }
</style>
@endif

@if(session('error'))
<div id="errorModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[3rem] w-full max-w-sm p-10 shadow-2xl text-center transform transition-all animate-shake">
        <div class="w-24 h-24 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-red-200">
            <i class="fas fa-times text-4xl text-white"></i>
        </div>
        <h2 class="text-2xl font-black text-slate-800 uppercase italic mb-2">Scan Failed!</h2>
        <p class="text-slate-500 text-sm font-semibold mb-8">{{ session('error') }}</p>
        
        <button onclick="document.getElementById('errorModal').remove()" class="w-full py-4 bg-red-600 text-white font-black rounded-2xl uppercase tracking-widest text-xs hover:bg-red-700 transition-all">
            Try Again
        </button>
    </div>
</div>

<style>
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }
    .animate-shake { animation: shake 0.3s ease-in-out; }
</style>
@endif
@endsection