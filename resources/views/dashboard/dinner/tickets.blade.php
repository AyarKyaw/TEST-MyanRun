@extends('dashboard.layouts.master')

@section('content')        
    <main class="content-body">
        <div class="container-fluid">
            
            <div class="page-title">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li><h1>Tickets: {{ $dinner->name }}</h1></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.dinner.manage', 'now') }}">Dinners</a></li>
                        <li class="breadcrumb-item active">Ticket Management</li>
                    </ol>
                </nav>
            </div>

            <div class="row mb-5 align-items-center">
                <div class="col-xl-12">
                    <div class="card m-0">
                        <div class="card-body py-3">
                            <div class="row align-items-center">
                                <div class="col-md-5 d-flex align-items-center">
                                    <div class="ms-1">
                                        <p class="mb-0 fs-14">Total Dinner Requests for this Event</p>
                                        <h3 class="mb-0 text-black fw-semibold fs-16">{{ $tickets->sum('quantity') }} Seats ({{ $tickets->count() }} Bookings)</h3>
                                    </div>
                                </div>
                                <div class="col-md-7 text-md-end">
                                    <a href="{{ route('admin.dinner.manage', 'now') }}" class="btn btn-light btn-xs px-4 me-2">Back to Dinners</a>
                                    <button class="btn btn-outline-primary btn-xs px-4">Export CSV</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Tickets for {{ $dinner->name }}</h4>
    
    <div class="btn-group">
        <a href="{{ route('admin.dinner.tickets.show', $dinner->id) }}" 
           class="btn btn-outline-secondary {{ !request('status') ? 'active' : '' }}">
            All
        </a>
        <a href="{{ route('admin.dinner.tickets.show', ['id' => $dinner->id, 'status' => 'pending']) }}" 
           class="btn btn-outline-warning {{ request('status') == 'pending' ? 'active' : '' }}">
            Pending
        </a>
        <a href="{{ route('admin.dinner.tickets.show', ['id' => $dinner->id, 'status' => 'confirmed']) }}" 
           class="btn btn-outline-success {{ request('status') == 'confirmed' ? 'active' : '' }}">
            Confirmed
        </a>
        <a href="{{ route('admin.dinner.tickets.show', ['id' => $dinner->id, 'status' => 'rejected']) }}" 
       class="btn btn-outline-danger {{ request('status') == 'rejected' ? 'active' : '' }}">
        Rejected
    </a>
    </div>
</div>
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
                                        <th>Payment Slip</th>
                                        <th>Status</th>
                                        <th>Qty / Total Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets as $ticket)
                                    <tr>
                                        <td><span class="text-black font-w600">#{{ $ticket->ticket_no }}</span></td>
                                        
                                        <td>
                                            <div class="text-black fw-bold">
                                                {{-- Added null-safe checks for Name --}}
                                                {{ $ticket->registration?->first_name ?? 'SPONSOR' }} 
                                                {{ $ticket->registration?->last_name ?? 'GUEST' }}
                                            </div>
                                        </td>
                                        
                                        <td>
                                            {{-- Added null-safe checks for Email/Phone --}}
                                            <div class="text-primary font-w600 fs-13 mb-1">
                                                <i class="far fa-envelope me-1"></i> {{ $ticket->registration?->email ?? 'N/A' }}
                                            </div>
                                            <div class="text-muted fs-12 mb-1">
                                                <i class="fas fa-phone-alt me-1"></i> {{ $ticket->registration?->phone ?? 'N/A' }}
                                            </div>
                                            
                                            {{-- Safe Viber Logic --}}
                                            @php
                                                $viberNum = $ticket->registration?->viber ?? $ticket->registration?->phone;
                                            @endphp

                                            @if($viberNum)
                                                @php $cleanViber = str_replace(['+', ' ', '-'], '', $viberNum); @endphp
                                                <a href="viber://chat?number={{ $cleanViber }}" class="badge badge-sm light badge-info">
                                                    <i class="fab fa-viber me-1"></i> VIBER
                                                </a>
                                            @else
                                                <span class="badge badge-sm light badge-secondary">NO CONTACT</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($ticket->payment_slip)
                                                <div style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#viewSlip{{ $ticket->id }}">
                                                    <img src="{{ asset('uploads/payments/' . $ticket->payment_slip) }}" 
                                                         class="rounded shadow-sm" 
                                                         style="width: 45px; height: 45px; object-fit: cover; border: 1px solid #eee;">
                                                    <div class="text-primary mt-1" style="font-size: 10px; font-weight: 700;">VIEW FULL</div>
                                                </div>

                                                {{-- FULL DETAILS MODAL --}}
                                                <div class="modal fade" id="viewSlip{{ $ticket->id }}">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title text-black">Booking Details - #{{ $ticket->ticket_no }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body text-start">
                                                                <div class="row">
                                                                    {{-- Left Side: Guest & Ticket Info --}}
                                                                    <div class="col-md-5 border-end">
                                                                        <h6 class="text-primary fw-bold mb-3">Guest Information</h6>
                                                                        
                                                                        <div class="mb-3">
                                                                            <p class="mb-0 text-muted small uppercase">Full Name</p>
                                                                            <p class="text-black fw-bold">
                                                                                {{ $ticket->registration?->first_name ?? 'SPONSOR' }} {{ $ticket->registration?->last_name ?? 'GUEST' }}
                                                                            </p>
                                                                        </div>

                                                                        <div class="mb-3">
                                                                            <p class="mb-0 text-muted small uppercase">Contact Details</p>
                                                                            <p class="text-black mb-1"><i class="far fa-envelope me-2 text-primary"></i>{{ $ticket->registration?->email ?? 'N/A' }}</p>
                                                                            <p class="text-black mb-1"><i class="fas fa-phone-alt me-2 text-primary"></i>{{ $ticket->registration?->phone ?? 'N/A' }}</p>
                                                                            <p class="text-black">
                                                                                <i class="fab fa-viber me-2" style="color: #7360F2;"></i>
                                                                                <strong>{{ $ticket->registration?->viber ?? 'Not Provided' }}</strong>
                                                                            </p>
                                                                        </div>

                                                                        <hr>

                                                                        <h6 class="text-primary fw-bold mb-3">Ticket Summary</h6>
                                                                        <div class="d-flex justify-content-between mb-2 text-black">
                                                                            <span>Type:</span>
                                                                            <span class="badge badge-sm light badge-primary">{{ strtoupper($ticket->type) }}</span>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between mb-2 text-black">
                                                                            <span>Quantity:</span>
                                                                            <span class="fw-bold">x{{ $ticket->quantity }}</span>
                                                                        </div>
                                                                        <div class="d-flex justify-content-between text-black">
                                                                            <span>Total Price:</span>
                                                                            <span class="text-success fw-bold fs-18">{{ number_format($ticket->price) }} MMK</span>
                                                                        </div>
                                                                    </div>

                                                                    {{-- Right Side: Payment Proof --}}
                                                                    <div class="col-md-7 text-center bg-light p-3">
                                                                        <h6 class="text-start fw-bold mb-3 text-black">Payment Proof</h6>
                                                                        <a href="{{ asset('uploads/payments/' . $ticket->payment_slip) }}" target="_blank">
                                                                            <img src="{{ asset('uploads/payments/' . $ticket->payment_slip) }}" 
                                                                                 class="img-fluid rounded shadow" 
                                                                                 style="max-height: 420px; border: 4px solid white;">
                                                                        </a>
                                                                        <p class="mt-2 small text-muted font-italic">Click image to open original file</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                            <!-- <button type="button" class="btn btn-secondary light" data-bs-dismiss="modal">Close</button> -->
                                                            
                                                            @if($ticket->status == 'pending')
                                                                {{-- REJECT BUTTON & FORM --}}
                                                                <form action="{{ route('admin.dinner.reject', $ticket->id) }}" method="POST" 
                                                                    onsubmit="return confirm('Reject this payment? It will be moved to the rejected list.');" 
                                                                    class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-danger">Reject Payment</button>
                                                                </form>

                                                                {{-- APPROVE BUTTON & FORM --}}
                                                                <form action="{{ route('admin.dinner.approve', $ticket->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-primary">Approve Booking</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge light badge-dark text-muted">NO SLIP</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($ticket->status == 'pending')
                                                <span class="badge light badge-warning">Pending</span>
                                            @elseif($ticket->status == 'confirmed')
                                                <span class="badge light badge-success">Confirmed</span>
                                            @else
                                                <span class="badge light badge-danger">Rejected</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="fs-14 text-black font-w600">{{ number_format($ticket->price) }} MMK</div>
                                            <small class="text-muted">x{{ $ticket->quantity }} {{ $ticket->type }}</small>
                                        </td>

                                        <td>
    <div class="d-flex">
        @if($ticket->status == 'pending')
            <button type="button" class="btn btn-primary shadow btn-xs px-3 me-1" data-bs-toggle="modal" data-bs-target="#viewSlip{{ $ticket->id }}">
                REVIEW
            </button>
        @elseif($ticket->status == 'confirmed')
            <button class="btn btn-success light btn-xs px-3 me-1" data-bs-toggle="modal" data-bs-target="#viewSlip{{ $ticket->id }}">VERIFIED</button>
        @else
            {{-- Button for Rejected Status --}}
            <button type="button" class="btn btn-danger light btn-xs px-3 me-1" data-bs-toggle="modal" data-bs-target="#viewSlip{{ $ticket->id }}">
                REJECTED
            </button>
        @endif
        
        <button class="btn btn-danger shadow btn-xs btn-square"><i class="fa fa-trash"></i></button>
    </div>
</td>
                                    </tr>
                                    @empty
                                        <tr><td colspan="7" class="text-center p-5">No tickets registered for this event yet.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer border-0 pt-0">
    <div class="d-flex align-items-center justify-content-between flex-wrap">
        <div class="mb-2">
            <p class="mb-0 fs-13 text-muted">
                Showing {{ $tickets->firstItem() }} to {{ $tickets->lastItem() }} of {{ $tickets->total() }} entries
            </p>
        </div>
        <nav aria-label="Page navigation">
            <ul class="pagination pagination-primary pagination-circle mb-0">
                {{-- Previous Page Link --}}
                @if ($tickets->onFirstPage())
                    <li class="page-item disabled"><span class="page-link"><i class="la la-angle-left"></i></span></li>
                @else
                    <li class="page-item page-indicator"><a class="page-link" href="{{ $tickets->previousPageUrl() }}"><i class="la la-angle-left"></i></a></li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($tickets->render()->elements as $element)
                    @if (is_string($element))
                        <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $tickets->currentPage())
                                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($tickets->hasMorePages())
                    <li class="page-item page-indicator"><a class="page-link" href="{{ $tickets->nextPageUrl() }}"><i class="la la-angle-right"></i></a></li>
                @else
                    <li class="page-item disabled"><span class="page-link"><i class="la la-angle-right"></i></span></li>
                @endif
            </ul>
        </nav>
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection