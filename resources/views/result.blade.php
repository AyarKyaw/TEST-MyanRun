@extends('layouts.master')

@section('title', 'Results - MYANRUN')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.tailwindcss.com"></script>

<style>
    .font-kanit { font-family: 'Kanit', sans-serif !important; }
    .thairun-shadow { box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); }
    
    .event-link { text-decoration: none !important; color: inherit !important; display: block; }
    .event-card { transition: transform 0.3s ease; }
    .event-card:not(.is-full):hover { transform: translateY(-5px); }

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
    .ribbon-soldout { background: #64748b; color: #fff; }

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

    /* Modal Animation */
    @keyframes bounce-short {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.02); }
    }
    .animate-modal { animation: bounce-short 0.3s ease-in-out; }
    
    .info-btn:hover { background-color: #f8fafc !important; transform: scale(1.1); }
    .info-btn { transition: all 0.2s ease; }
    
    .grayscale { filter: grayscale(100%); opacity: 0.7; }
</style>

<div class="page-title">
    <div class="themeflat-container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-heading">
                    <h1 class="title">Our Results</h1>
                </div>
                <div class="breadcrumbs">
                    <ul>
                        <li><a href="/">Homepage</a></li>
                        <li><i class="icon-Arrow---Right-2"></i></li>
                        <li><a>Results</a></li>
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
                <h2 class="h3 font-weight-bold mb-0 text-dark uppercase">Result Now</h2>
            </div>
            
            <div class="row">
    <div class="col-lg-4 col-md-6 mb-4" style="width: 616px !important;">
        <div class="card border-0 rounded-lg overflow-hidden thairun-shadow position-relative h-100 event-card">
            <a href="https://rqs.racetigertiming.com/Result/Info/515580eb2755407296bbe091e71a7c52?dbt=2&ticks=639090935660744658" class="event-link">
                <img src="{{ asset('images/result.jpg') }}" class="card-img-top event-card-img" alt="Result Image" >
                
                <div class="card-body p-4">
                    <div class="d-flex justify-content-end align-items-center border-top pt-3">
                        <span class="btn btn-warning btn-sm font-weight-bold px-4 py-2 text-white shadow-sm">SEE NOW</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
        </div>
    </div>
</div>
@endsection