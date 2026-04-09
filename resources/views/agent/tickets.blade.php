@extends('dashboard.layouts.master')

@push('styles')
<style>
    .event-card-img { height: 180px; object-fit: cover; border-radius: 12px 12px 0 0; }
    .event-card { transition: all 0.3s ease; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-radius: 12px; background: #fff; }
    .event-card:hover { transform: translateY(-8px); box-shadow: 0 12px 25px rgba(0,0,0,0.1); }
    .section-header {
        display: flex; align-items: center; padding: 15px 25px; background: #fff;
        border-radius: 15px; margin-bottom: 25px; border-left: 5px solid #ef4444; 
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .section-header.past { border-left: 5px solid #94a3b8; background: #f8fafc; }
    .badge-date { background: #f1f5f9; color: #475569; font-weight: 700; font-size: 11px; }
    .stat-label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .bricks-divider { display: flex; gap: 10px; margin: 50px 0; justify-content: center; opacity: 0.3; }
    .brick { height: 8px; width: 40px; background: #cbd5e1; border-radius: 4px; }
    .past-container { background: rgba(241, 245, 249, 0.5); padding: 30px; border-radius: 20px; border: 2px dashed #e2e8f0; }
    
    /* New Style for Capacity Badge */
    .capacity-info {
        background: #f8fafc;
        border-radius: 8px;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
    }
</style>
@endpush

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-title mb-5">
            <h1 class="font-weight-bold text-dark">Event Management</h1>
            <p class="text-muted">Monitor registrations, verify KBZ payments, and manage race bibs.</p>
        </div>

        {{-- Section 1: ACTIVE EVENTS --}}
        <div class="section-header">
            <div>
                <h3 class="font-weight-bold mb-0" style="color: #b91c1c;"><i class="fas fa-running mr-2"></i> LIVE EVENTS</h3>
                <small class="text-muted text-uppercase font-weight-bold">Registration currently open</small>
            </div>
            <div class="ml-auto"><span class="badge badge-danger px-3 py-2">LIVE</span></div>
        </div>

        <div class="row">
            @forelse($nowEvents as $event)
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card event-card h-100">
                        <img src="{{ asset('storage/' . $event->image_path) }}" class="event-card-img" alt="event">
                        <div class="card-body">
                            <h5 class="font-weight-bold mb-1">{{ $event->name }}</h5>
                            <p class="text-muted small mb-3"><i class="far fa-calendar-alt"></i> {{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}</p>
                            
                            <div class="capacity-info mb-3">
                                <span class="stat-label text-muted d-block mb-1">Event Capacity</span>
                                @if($event->total_max_slots)
                                    <span class="font-weight-bold text-dark">
                                        <i class="fas fa-ticket-alt mr-1 text-danger"></i> {{ number_format($event->total_max_slots) }} Tickets
                                    </span>
                                @else
                                    <span class="font-weight-bold text-success">
                                        <i class="fas fa-infinity mr-1"></i> Unlimited
                                    </span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <a href="{{ route('agent.ticket.view', $event->id) }}" class="btn btn-dark btn-sm rounded-pill px-4">
                                    Manage Tickets
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No live events at the moment.</p>
                </div>
            @endforelse
        </div>

        <div class="bricks-divider">
            <div class="brick"></div><div class="brick" style="width: 80px;"></div><div class="brick"></div>
        </div>

        {{-- Section 2: PAST EVENTS --}}
        <div class="past-container">
            <div class="section-header past">
                <div><h3 class="font-weight-bold mb-0 text-secondary"><i class="fas fa-history mr-2"></i> PAST EVENTS</h3></div>
                <div class="ml-auto"><span class="badge badge-secondary px-3 py-2">ARCHIVED</span></div>
            </div>
            <div class="row">
                @forelse($pastEvents as $event)
                    <div class="col-xl-4 col-md-6 mb-4" style="filter: grayscale(0.6);">
                         <div class="card event-card h-100">
                            <img src="{{ asset('storage/' . $event->image_path) }}" class="event-card-img" alt="event">
                            <div class="card-body">
                                <h5 class="font-weight-bold text-muted">{{ $event->name }}</h5>
                                <div class="mb-2">
                                    <small class="text-muted">Total Capacity: {{ $event->total_max_slots ?? 'Unlimited' }}</small>
                                </div>
                                <p class="text-muted small mb-0">Event Completed</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5"><p class="text-muted italic">No past events found.</p></div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection