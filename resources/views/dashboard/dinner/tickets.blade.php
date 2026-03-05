@extends('dashboard.layouts.master')

@section('content')        
    <main class="content-body">
        <div class="container-fluid">
            
            <div class="page-title">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        {{-- Dynamic Heading based on the dinner being viewed --}}
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
                                        <h3 class="mb-0 text-black fw-semibold fs-16">{{ $tickets->count() }} Tickets</h3>
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
                                        <th>Type/Price</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tickets as $ticket)
                                    <tr>
                                        <td><span class="text-black font-w600">#{{ $ticket->ticket_no }}</span></td>
                                        
                                        <td>
                                            {{ $ticket->registration->first_name }} 
                                            {{ $ticket->registration->middle_name ? $ticket->registration->middle_name . ' ' : '' }} 
                                            {{ $ticket->registration->last_name }}
                                        </td>
                                        
                                        <td>
                                            <div class="text-primary font-w500">{{ $ticket->registration->email }}</div>
                                            <small>{{ $ticket->registration->phone }}</small>
                                        </td>

                                        <td>
                                            @if($ticket->payment_slip)
                                                <div style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#slipModal{{ $ticket->id }}">
                                                    <img src="{{ asset('uploads/payments/' . $ticket->payment_slip) }}" 
                                                         alt="Slip" 
                                                         class="rounded" 
                                                         style="width: 45px; height: 45px; object-fit: cover; border: 1px solid #eee;">
                                                    <div class="text-primary mt-1" style="font-size: 10px; font-weight: 700;">VIEW FULL</div>
                                                </div>
                                            @else
                                                <span class="badge light badge-dark text-muted" style="font-size: 10px;">NO SLIP</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($ticket->status == 'pending')
                                                <span class="badge light badge-warning">Pending</span>
                                            @else
                                                <span class="badge light badge-success">Confirmed</span>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="fs-14 text-black font-w600">{{ number_format($ticket->price) }} MMK</div>
                                            <small class="text-muted">{{ strtoupper($ticket->type) }}</small>
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($ticket->status == 'pending')
                                                    <form action="{{ route('admin.dinner.approve', $ticket->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-primary shadow btn-xs px-3 me-1">APPROVE</button>
                                                    </form>
                                                @else
                                                    <button type="button" class="btn btn-success light btn-xs px-3 me-1 disabled">ACTIVE</button>
                                                @endif
                                                
                                                <button type="button" class="btn btn-danger shadow btn-xs btn-square"><i class="fa fa-trash"></i></button>
                                            </div>

                                            {{-- Modal for Slip --}}
                                            @if($ticket->payment_slip)
                                            <div class="modal fade" id="slipModal{{ $ticket->id }}">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Payment Proof: #{{ $ticket->ticket_no }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body text-center bg-light">
                                                            <img src="{{ asset('uploads/payments/' . $ticket->payment_slip) }}" class="img-fluid rounded shadow" alt="Payment Slip">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger light btn-sm" data-bs-dismiss="modal">Close</button>
                                                            @if($ticket->status == 'pending')
                                                                <form action="{{ route('admin.dinner.approve', $ticket->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-primary btn-sm">APPROVE NOW</button>
                                                                </form>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No tickets found for this dinner.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection