@extends('dashboard.layouts.master')

@push('styles')
<style>
    /* Card Styles */
    .dinner-card-img { height: 180px; object-fit: cover; border-radius: 12px 12px 0 0; }
    .dinner-card { transition: all 0.3s ease; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-radius: 12px; background: #fff; }
    .dinner-card:hover { transform: translateY(-8px); box-shadow: 0 12px 25px rgba(0,0,0,0.1); }
    
    /* Section Headers */
    .section-header {
        display: flex;
        align-items: center;
        padding: 15px 25px;
        background: #fff;
        border-radius: 15px;
        margin-bottom: 25px;
        border-left: 5px solid #22c55e; /* Success Green */
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
    }
    .section-header.past {
        border-left: 5px solid #94a3b8; /* Slate Gray */
        background: #f8fafc;
    }
    
    .badge-date { background: #f1f5f9; color: #475569; font-weight: 700; font-size: 11px; }
    .stat-label { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }

    /* The "Bricks" Visual Separator */
    .bricks-divider {
        display: flex;
        gap: 10px;
        margin: 50px 0;
        justify-content: center;
        opacity: 0.3;
    }
    .brick { height: 8px; width: 40px; background: #cbd5e1; border-radius: 4px; }

    /* Past Section Background */
    .past-container {
        background: rgba(241, 245, 249, 0.5);
        padding: 30px;
        border-radius: 20px;
        border: 2px dashed #e2e8f0;
    }
</style>
@endpush

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-title mb-5">
            <h1 class="font-weight-bold text-dark">Ticket Management</h1>
            <p class="text-muted">Monitor registrations, verify payments, and manage attendees.</p>
        </div>

        {{-- Section 1: ACTIVE DINNERS --}}
        <div class="section-header">
            <div>
                <h3 class="font-weight-bold mb-0" style="color: #166534;">
                    <i class="fas fa-bolt mr-2"></i> ACTIVE DINNERS
                </h3>
                <small class="text-muted text-uppercase font-weight-bold">Currently open for registrations</small>
            </div>
            <div class="ml-auto">
                <span class="badge badge-success px-3 py-2">LIVE</span>
            </div>
        </div>

        <div class="row">
            @forelse($dinners->get(1, []) as $dinner)
                @include('dashboard.dinner.partials.ticket_card', ['dinner' => $dinner])
            @empty
                <div class="col-12 text-center py-5">
                    <img src="{{ asset('images/empty.svg') }}" style="width: 100px; opacity: 0.5;">
                    <p class="text-muted mt-3">No active dinners at the moment.</p>
                </div>
            @endforelse
        </div>

        {{-- Visual Bricks Divider --}}
        <div class="bricks-divider">
            <div class="brick"></div>
            <div class="brick" style="width: 80px;"></div>
            <div class="brick"></div>
        </div>

        {{-- Section 2: PAST DINNERS --}}
        <div class="past-container">
            <div class="section-header past">
                <div>
                    <h3 class="font-weight-bold mb-0 text-secondary">
                        <i class="fas fa-history mr-2"></i> PAST DINNERS
                    </h3>
                    <small class="text-muted text-uppercase font-weight-bold">Completed events and archives</small>
                </div>
                <div class="ml-auto">
                    <span class="badge badge-secondary px-3 py-2">ARCHIVED</span>
                </div>
            </div>

            <div class="row">
                @forelse($dinners->get(0, []) as $dinner)
                    <div class="col-xl-4 col-md-6 mb-4" style="filter: grayscale(0.4);">
                        @include('dashboard.dinner.partials.ticket_card', ['dinner' => $dinner])
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted italic">No past events in the archive.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection