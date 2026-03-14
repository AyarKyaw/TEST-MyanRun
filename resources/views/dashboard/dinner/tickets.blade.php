@extends('dashboard.layouts.master')

@section('content')        
<main class="content-body">
    <div class="container-fluid">
        
        {{-- Header & Breadcrumbs --}}
        <div class="page-title">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li><h1>Tickets: {{ $dinner->name }}</h1></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.dinner.manage', 'now') }}">Dinners</a></li>
                    <li class="breadcrumb-item active">Ticket Management</li>
                </ol>
            </nav>
        </div>

        {{-- Refined Summary Card --}}
    <div class="row mb-4">
        <div class="col-xl-12">
            {{-- Added h-100 to ensure the card fills the row height --}}
            <div class="card m-0 shadow-sm border-0 h-100" style="border-radius: 12px;">
                {{-- Increased py-4 for more vertical "breathing room" --}}
                <div class="card-body py-4 px-4">
                    <div class="row align-items-center h-100">
                        
                        {{-- Metric 1: Revenue --}}
                        <div class="col-md-3 border-end">
                            <div class="d-flex flex-column justify-content-center">
                                <span class="text-muted fw-medium mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em;">Confirmed Revenue</span>
                                <h3 class="mb-0 fw-bold text-success" style="font-size: 24px;"> {{-- Bumped font size slightly --}}
                                    {{ number_format($tickets->where('status', 'confirmed')->sum('price')) }} 
                                    <span class="fs-12 fw-normal text-muted">MMK</span>
                                </h3>
                            </div>
                        </div>

                        {{-- Metric 2: Public Seats --}}
                        <div class="col-md-2 border-end text-center">
                            <div class="d-flex flex-column justify-content-center">
                                <span class="text-muted fw-medium mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em;">Public Seats</span>
                                <h3 class="mb-0 fw-bold text-dark" style="font-size: 24px;">
                                    {{ $dinner->public_seats_count ?? 0 }}<span class="text-muted fw-light" style="font-size: 14px;">/{{ $dinner->public_capacity ?? '∞' }}</span>
                                </h3>
                            </div>
                        </div>

                        {{-- Metric 3: Sponsor Seats --}}
                        <div class="col-md-2 border-end text-center">
                            <div class="d-flex flex-column justify-content-center">
                                <span class="text-muted fw-medium mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em;">Sponsor Seats</span>
                                <h3 class="mb-0 fw-bold text-dark" style="font-size: 24px;">
                                    {{ $dinner->sponsor_seats_count ?? 0 }}<span class="text-muted fw-light" style="font-size: 14px;">/{{ $dinner->sponsor_capacity ?? 0 }}</span>
                                </h3>
                            </div>
                        </div>

                        {{-- Metric 4: Total Bookings --}}
                        <div class="col-md-2 border-end text-center">
                            <div class="d-flex flex-column justify-content-center">
                                <span class="text-muted fw-medium mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em;">Total Bookings</span>
                                <h3 class="mb-0 fw-bold text-primary" style="font-size: 24px;">{{ $tickets->total() }}</h3>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="col-md-3">
                            <div class="d-flex justify-content-end gap-2 align-items-center">
                                <a href="{{ route('admin.dinner.manage', 'now') }}" class="btn btn-light btn-sm fw-bold px-3 py-2">
                                    <i class="fa fa-chevron-left me-1 fs-12"></i> Back
                                </a>
                                <button class="btn btn-primary btn-sm fw-bold px-3 py-2 shadow-sm">
                                    <i class="fa fa-download me-1 fs-12"></i> Export
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

        {{-- Filter Tabs --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0 fw-bold">Guest List</h4>
            <div class="btn-group shadow-sm bg-white rounded">
                <a href="{{ route('admin.dinner.tickets.show', $dinner->id) }}" 
                   class="btn btn-outline-secondary btn-sm {{ !request('status') ? 'active' : '' }}">All</a>
                <a href="{{ route('admin.dinner.tickets.show', ['id' => $dinner->id, 'status' => 'pending']) }}" 
                   class="btn btn-outline-warning btn-sm {{ request('status') == 'pending' ? 'active' : '' }}">Pending</a>
                <a href="{{ route('admin.dinner.tickets.show', ['id' => $dinner->id, 'status' => 'confirmed']) }}" 
                   class="btn btn-outline-success btn-sm {{ request('status') == 'confirmed' ? 'active' : '' }}">Confirmed</a>
                <a href="{{ route('admin.dinner.tickets.show', ['id' => $dinner->id, 'status' => 'rejected']) }}" 
                   class="btn btn-outline-danger btn-sm {{ request('status') == 'rejected' ? 'active' : '' }}">Rejected</a>
            </div>
            <div class="d-flex gap-3 align-items-center">
                {{-- Scanning Status Badge --}}
                @if($dinner->is_scanning_open)
                    <span class="badge badge-success pulse"><i class="fa fa-broadcast-tower me-1"></i> SCANNING LIVE</span>
                @else
                    <span class="badge badge-danger"><i class="fa fa-lock me-1"></i> SCANNING DISABLED</span>
                @endif

                {{-- Toggle Button --}}
                <form action="{{ route('admin.dinner.toggle-scan', $dinner->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn {{ $dinner->is_scanning_open ? 'btn-outline-danger' : 'btn-primary' }} btn-sm fw-bold">
                        {{ $dinner->is_scanning_open ? 'Disable Scanning' : 'Enable Scanning' }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Main Tickets Table --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover dataTablesCard" id="example5">
                            <thead>
                                <tr>
                                    <th>Ticket No</th>
                                    <th>Guest Name</th>
                                    <th>Contact Info</th>
                                    <th>Scan Status</th>
                                    <th>Payment Slip</th>
                                    <th>Status</th>
                                    <th>Qty / Total Price</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tickets as $ticket)
                                <tr>
                                    <td>
                                    @if($ticket->type === 'Sponsored' && $ticket->ticket_no)
                                        {{-- Show the unique ticket_no for Sponsors --}}
                                        <div class="mb-1">
                                            <span class="badge badge-xs light badge-dark" style="letter-spacing: 0.5px;">
                                                <i class="fa fa-ticket-alt me-1 text-primary"></i> {{ $ticket->ticket_no }}
                                            </span>
                                        </div>
                                    @else
                                        {{-- Fallback for Public users or older logic --}}
                                        @php
                                            $generatedCodes = \App\Models\SponsorCode::where('dinner_ticket_id', $ticket->id)->get();
                                        @endphp

                                        @forelse($generatedCodes as $codeRecord)
                                            <div class="mb-1">
                                                <span class="badge badge-xs light badge-dark" style="letter-spacing: 0.5px;">
                                                    <i class="fa fa-ticket-alt me-1 text-primary"></i> {{ $codeRecord->code }}
                                                </span>
                                            </div>
                                        @empty
                                            <span class="text-muted fs-11">Pending Generation</span>
                                        @endforelse
                                    @endif
                                </td>
                                    <td>
                                        <div class="text-black fw-bold">
                                            {{ $ticket->registration?->first_name ?? 'SPONSOR' }} 
                                            {{ $ticket->registration?->last_name ?? 'GUEST' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-primary font-w600 fs-13 mb-1"><i class="far fa-envelope me-1 text-muted"></i> {{ $ticket->registration?->email ?? 'N/A' }}</div>
                                        @php
                                            $viberNum = $ticket->registration?->viber ?? $ticket->registration?->phone;
                                            $cleanViber = str_replace(['+', ' ', '-'], '', $viberNum);
                                        @endphp
                                        @if($viberNum)
                                            <a href="viber://chat?number={{ $cleanViber }}" class="badge badge-sm light badge-info fw-bold">
                                                <i class="fab fa-viber me-1"></i> VIBER
                                            </a>
                                        @else
                                            <span class="badge badge-sm light badge-secondary">NO CONTACT</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->scanned_at)
                                            <span class="badge bg-success text-white" style="font-size: 11px;">
                                                ✅ {{ $ticket->scanned_at->format('h:i A') }}
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted" style="font-size: 11px;">Not Scanned</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ticket->payment_slip)
                                            <div style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#viewSlip{{ $ticket->id }}">
                                                <img src="{{ asset('uploads/payments/' . $ticket->payment_slip) }}" class="rounded shadow-sm border" style="width: 40px; height: 40px; object-fit: cover;">
                                                <div class="text-primary mt-1 fw-bold fs-10 text-uppercase">View Proof</div>
                                            </div>

                                            {{-- MODAL --}}
                                            <div class="modal fade" id="viewSlip{{ $ticket->id }}">
                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title text-black fw-bold">Booking Details - #{{ $ticket->ticket_no }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-start">
                                                            <div class="row">
                                                                <div class="col-md-5 border-end">
                                                                    <h6 class="text-primary fw-bold mb-3">Guest Information</h6>
                                                                    <p class="mb-1 text-muted small">FULL NAME</p>
                                                                    <p class="text-black fw-bold">{{ $ticket->registration?->first_name ?? 'SPONSOR' }} {{ $ticket->registration?->last_name ?? 'GUEST' }}</p>
                                                                    <hr>
                                                                    <h6 class="text-primary fw-bold mb-3">Ticket Summary</h6>
                                                                    <div class="d-flex justify-content-between mb-2">
                                                                        <span>Quantity:</span> <span class="fw-bold">x{{ $ticket->quantity }}</span>
                                                                    </div>
                                                                    <div class="d-flex justify-content-between">
                                                                        <span>Total:</span> <span class="text-success fw-bold fs-18">{{ number_format($ticket->price) }} MMK</span>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-7 text-center bg-light p-3">
                                                                    <img src="{{ asset('uploads/payments/' . $ticket->payment_slip) }}" class="img-fluid rounded shadow" style="max-height: 420px;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            @if($ticket->status == 'pending')
                                                                <form action="{{ route('admin.dinner.reject', $ticket->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger btn-sm px-4 rounded-pill">Reject</button>
                                                                </form>
                                                                <form action="{{ route('admin.dinner.approve', $ticket->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-primary btn-sm px-4 rounded-pill">Approve</button>
                                                                </form>
                                                            @endif
                                                            <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="badge light badge-dark text-muted">NO SLIP</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge light {{ $ticket->status == 'confirmed' ? 'badge-success' : ($ticket->status == 'pending' ? 'badge-warning' : 'badge-danger') }}">
                                            {{ ucfirst($ticket->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fs-14 text-black font-w600">{{ number_format($ticket->price) }} MMK</div>
                                        <small class="text-muted">x{{ $ticket->quantity }} {{ $ticket->type }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <button type="button" class="btn btn-primary shadow btn-xs px-3 me-1" data-bs-toggle="modal" data-bs-target="#viewSlip{{ $ticket->id }}">
                                                {{ $ticket->status == 'pending' ? 'REVIEW' : 'DETAILS' }}
                                            </button>
                                            <button class="btn btn-danger shadow btn-xs btn-square"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center p-5">No tickets found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="card-footer border-0 pt-0">
                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                            <p class="mb-0 fs-13 text-muted">Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} entries</p>
                            {{ $tickets->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@if(session('download_urls'))
    <script>
        (function() {
            // Unique key for this specific set of downloads
            const downloadKey = "downloaded_{{ md5(json_encode(session('download_urls'))) }}";
            
            // If we've already done this in this browser tab, stop.
            if (sessionStorage.getItem(downloadKey)) return;

            const urls = @json(session('download_urls'));
            
            urls.forEach((url, index) => {
                setTimeout(() => {
                    const link = document.createElement('a');
                    link.href = url;
                    link.download = url.split('/').pop();
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }, index * 500);
            });

            // Mark as finished so "Back" button doesn't trigger it again
            sessionStorage.setItem(downloadKey, 'true');
        })();
    </script>
    @php session()->forget('download_urls'); @endphp
@endif
@endsection