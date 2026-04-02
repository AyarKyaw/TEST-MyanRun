@extends('layouts.master')

@section('title', 'Events - MYANRUN')

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
    
    .ribbon-red { background: #ef4444; color: #fff; }
    .ribbon-green { background: #22c55e; color: #fff; }
    .ribbon-grey { background: #64748b; color: #fff; }

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
    /* Custom Scrollbar for Modal Content */
.modal-content-scroll::-webkit-scrollbar { width: 6px; }
.modal-content-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }

.animate-in { animation: modalIn 0.3s ease-out forwards; }
@keyframes modalIn {
    from { opacity: 0; transform: translateY(20px) scale(0.95); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}
</style>

<div class="page-title">
    <div class="themeflat-container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-heading">
                    <h1 class="title">Our Events</h1>
                </div>
                <div class="breadcrumbs">
                    <ul>
                        <li><a href="/">Homepage</a></li>
                        <li><i class="icon-Arrow---Right-2"></i></li>
                        <li><a>Our Events</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="font-kanit" style="background-color: #f8fafc; padding: 60px 0;">
    <div class="container">
        
        <div class="mb-5">
            <div class="event-section-title d-flex align-items-center">
                <span class="d-inline-block bg-danger rounded-pill mr-2" style="width: 8px; height: 32px;"></span>
                <h2 class="h3 font-weight-bold mb-0 text-dark">NOW RUN</h2>
            </div>
            <div class="row">
                @forelse($nowEvents as $event)
<div class="col-lg-4 col-md-6 mb-4">
    @php
        $eventName = trim($event->name);
        $isRegistered = auth()->check() && isset($userTickets) && in_array($eventName, array_map('trim', $userTickets));
    @endphp

    <div class="card border-0 rounded-lg overflow-hidden thairun-shadow position-relative h-100 event-card">
        @if($isRegistered)
            <div class="ribbon-grey status-ribbon" style="background: #6366f1;">REGISTERED</div>
        @else
            <div class="ribbon-red status-ribbon">LIVE EVENT</div>
        @endif

        <button type="button" 
                onclick="openDescModal({{ json_encode($event->name) }}, {{ json_encode($event->description ?? 'No description available.') }})" 
                class="position-absolute border-0 rounded-circle d-flex align-items-center justify-content-center bg-white text-dark shadow-sm hover:scale-110 transition-all" 
                style="top: 12px; right: 12px; width: 35px; height: 35px; z-index: 20; cursor: pointer;">
            <i class="fas fa-info-circle text-primary"></i>
        </button>

        <img src="{{ asset('storage/' . $event->image_path) }}" class="card-img-top event-card-img">
        
        <div class="card-body p-4 d-flex flex-column justify-content-between">
            <h4 class="h5 font-weight-bold text-dark mb-4">{{ $event->name }}</h4>
            
            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                <span class="text-muted small"><i class="far fa-calendar-alt"></i> {{ $event->date->format('M d') }}</span>

                @if($isRegistered)
                    <button class="btn btn-secondary btn-sm font-weight-bold px-3 py-2 text-white" disabled style="cursor: not-allowed; opacity: 0.8;">
                        <i class="fas fa-check-circle mr-1"></i> SECURED
                    </button>
                @elseif($event->is_full)
                    <button class="btn btn-secondary btn-sm font-weight-bold px-3 py-2 text-white" disabled style="cursor: not-allowed; opacity: 0.8;">
                        <i class="fas fa-check-circle mr-1"></i> SECURED
                    </button>
                @else
                    <a href="/ticket?event={{ urlencode($event->name) }}" class="btn btn-danger btn-sm font-weight-bold px-3 py-2 text-white">
                        ENTER NOW
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@empty
    <div class="col-12"><p>No live events.</p></div>
@endforelse
            </div>
        </div>

        <div class="mb-5">
            <div class="event-section-title d-flex align-items-center">
                <span class="d-inline-block bg-success rounded-pill mr-2" style="width: 8px; height: 32px;"></span>
                <h2 class="h3 font-weight-bold mb-0 text-dark">COMING RUN</h2>
            </div>
            <div class="row">
                @forelse($comingEvents as $event)
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="{{ route('events.show', $event->id) }}" class="event-link">
                        <div class="card border-0 rounded-lg overflow-hidden thairun-shadow position-relative h-100 event-card">
                            <div class="ribbon-green status-ribbon">REG OPEN</div>
                            <img src="{{ asset('storage/' . $event->image_path) }}" class="card-img-top event-card-img">
                            <div class="card-body p-4">
                                <span class="badge badge-light text-muted mb-2 uppercase" style="font-size: 10px;">{{ $event->company }}</span>
                                <h4 class="h5 font-weight-bold text-dark mb-4">{{ $event->name }}</h4>
                                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                    <span class="text-muted small"><i class="far fa-calendar-alt"></i> {{ $event->date->format('M d') }}</span>
                                    <span class="btn btn-success btn-sm font-weight-bold px-3 py-2 text-white" style="background-color: #22c55e; border:none;">VIEW INFO</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12"><p class="text-muted">Stay tuned for upcoming races!</p></div>
                @endforelse
            </div>
        </div>

        <div>
            <div class="event-section-title d-flex align-items-center">
                <span class="d-inline-block bg-secondary rounded-pill mr-2" style="width: 8px; height: 32px;"></span>
                <h2 class="h3 font-weight-bold mb-0 text-muted">PAST RUN</h2>
            </div>
            <div class="row">
                @forelse($pastEvents as $event)
                <div class="col-lg-4 col-md-6 mb-4">
                    <a href="{{ route('events.show', $event->id) }}" class="event-link">
                        <div class="card border-0 rounded-lg overflow-hidden thairun-shadow position-relative h-100 event-card opacity-75">
                            <div class="ribbon-grey status-ribbon">COMPLETED</div>
                            <img src="{{ asset('storage/' . $event->image_path) }}" class="card-img-top event-card-img">
                            <div class="card-body p-4">
                                <span class="badge badge-light text-muted mb-2 uppercase" style="font-size: 10px;">{{ $event->company }}</span>
                                <h4 class="h5 font-weight-bold text-dark mb-4">{{ $event->name }}</h4>
                                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                    <span class="text-muted small"><i class="fas fa-check-circle"></i> {{ $event->date->format('M d') }}</span>
                                    <span class="btn btn-secondary btn-sm font-weight-bold px-3 py-2 text-white">VIEW INFO</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12"><p class="text-muted">No past records found.</p></div>
                @endforelse
            </div>
        </div>

    </div>
</div>
<div id="descModal" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9999] flex items-center justify-center p-6" onclick="if(event.target === this) closeDescModal()">
    <div class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl transform transition-all animate-modal relative overflow-hidden">
        
        <button onclick="closeDescModal()" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center rounded-full bg-slate-100 text-slate-400 hover:text-slate-600 hover:bg-slate-200 transition-all z-50">
            <i class="fas fa-times"></i>
        </button>

        <div class="p-10">
            <h3 id="descModalTitle" class="text-xl font-black text-slate-800 uppercase italic mb-6 border-b-4 border-yellow-400 d-inline-block pb-1">Event Details</h3>
            
            <div id="descModalContent" class="text-slate-600 text-lg leading-relaxed max-h-[60vh] overflow-y-auto pr-4 custom-scrollbar font-medium">
                </div>
        </div>

        <div class="px-10 pb-8">
            <button onclick="closeDescModal()" class="w-full py-4 bg-slate-900 text-white font-bold rounded-xl uppercase tracking-widest text-xs hover:bg-slate-800 transition-all">
                Got it
            </button>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>
<script>
    function openDescModal(title, description) {
        document.getElementById('descModalTitle').innerText = title;
        document.getElementById('descModalContent').innerText = description;
        
        const modal = document.getElementById('descModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDescModal() {
        const modal = document.getElementById('descModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection