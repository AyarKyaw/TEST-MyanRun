@extends('dashboard.layouts.master')

@section('content')        
    <div class="event-sidebar dz-scroll" id="eventSidebar">
        <div class="card shadow-none rounded-0 bg-transparent h-auto mb-0">
            <div class="card-body text-center event-calender pb-2">
                <input type='text' class="form-control d-none" id='datetimepicker1'>
            </div>
        </div>
        
        <div class="card shadow-none rounded-0 bg-transparent h-auto">
            <div class="card-header border-0 pb-0">
                <h4 class="text-black">Upcoming Events</h4>
            </div>
            <div class="card-body">
                {{-- Example Event Item --}}
                <div class="d-flex mb-5 align-items-center event-list">
                    <div class="p-3 text-center rounded me-3 date-bx bgl-primary">
                        <h2 class="mb-0 text-black">3</h2>
                        <h5 class="mb-1 text-black">Wed</h5>
                    </div>
                    <div class="px-0">
                        <h6 class="mt-0 mb-3 fs-14"><a class="text-black" href="#">Live Concert Choir</a></h6>
                        <div class="progress mb-0" style="height:4px; width:100%;">
                            <div class="progress-bar bg-warning" style="width:85%;" role="progressbar"></div>
                        </div>
                    </div>
                </div>
                {{-- Add more event logic here --}}
            </div>
            <div class="card-footer justify-content-between border-0 d-flex fs-14">
                <span>5 events more</span>
                <a href="#" class="text-primary">View more <i class="las la-long-arrow-alt-right ms-2"></i></a>
            </div>
        </div>
    </div>

    <main class="content-body">
        <div class="container-fluid">
            
            <div class="page-title">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li><h1>Event Tickets</h1></li>
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">Event Tickets</li>
                    </ol>
                </nav>
            </div>

            <div class="row mb-5 align-items-center">
                <div class="col-xl-3 mb-4 mb-xl-0">
                    <a href="" class="btn btn-primary light btn-lg d-block rounded fs-18">+ New Customer</a>
                </div>

                <div class="col-xl-9">
                    <div class="card m-0">
                        <div class="card-body py-3">
                            <div class="row align-items-center">
                                <div class="col-md-5 d-flex align-items-center">
                                    <div class="ms-1">
                                        <p class="mb-0 fs-14">Total Customers</p>
                                        <h3 class="mb-0 text-black fw-semibold fs-16">{{ $customers->count() }} Person</h3>
                                    </div>
                                </div>
                                <div class="col-md-7 text-md-end">
                                    <button class="btn btn-outline-primary btn-xs px-4">Active</button>
                                    <button class="btn btn-danger btn-xs px-4 ms-2">Bulk Delete</button>
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
                                        <th><input type="checkbox" id="checkAll" class="form-check-input"></th>
                                        <th>BIB Number</th>
                                        <th>Event & Category</th>
                                        <th>Price</th>
                                        <th>Status</th>
                                        <th>Reg. Date</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customers as $customer)
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input"></td>
                                        
                                        {{-- Runner ID --}}
                                        <td><strong>#{{ $customer->bib_number }}</strong></td>
                                        
                                        {{-- Event and Category combined --}}
                                        <td>
                                            <span class="text-black fw-bold">{{ $customer->event }}</span><br>
                                            <small class="text-muted">{{ $customer->category }}</small>
                                        </td>
                                        
                                        {{-- Price --}}
                                        <td>{{ $customer->price }} MMK</td>
                                        
                                        {{-- Status with Badge --}}
                                        <td>
                                            @if($customer->status == 'pending')
                                                <span class="badge light badge-warning">Pending Approval</span>
                                            @elseif($customer->status == 'approved')
                                                <span class="badge light badge-success">Approved</span>
                                            @elseif($customer->status == 'rejected')
                                                <span class="badge light badge-danger">Rejected</span>
                                            @else
                                                <span class="badge light badge-dark">{{ ucfirst($customer->status) }}</span>
                                            @endif
                                        </td>
                                        
                                        {{-- Created At --}}
                                        <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                                        
                                        {{-- Action Buttons --}}
{{-- Action Buttons --}}
<td>
    <div class="d-flex">
        {{-- Details Button --}}
        <button type="button" 
        class="btn btn-primary shadow btn-xs btn-square me-1 view-details" 
        data-bs-toggle="modal" 
        data-bs-target="#ticketDetailsModal"
        data-info="{{ json_encode($customer) }}"
        data-image="{{ $customer->transaction_id ? asset('uploads/payments/' . $customer->transaction_id) : asset('images/no-image.png') }}">
    <i class="fa fa-eye"></i>
</button>

        @if($customer->status == 'pending')
            {{-- Approve Button --}}
            <form action="{{ route('tickets.approve', $customer->id) }}" method="POST" class="me-1">
                @csrf
                <button type="submit" class="btn btn-success shadow btn-xs btn-square" title="Approve">
                    <i class="fa fa-check"></i>
                </button>
            </form>

            {{-- Reject Button --}}
            <form action="{{ route('tickets.reject', $customer->id) }}" method="POST" class="me-1">
                @csrf
                <button type="submit" class="btn btn-warning shadow btn-xs btn-square" title="Reject">
                    <i class="fa fa-times"></i>
                </button>
            </form>
        @endif
    </div>
</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No tickets found.</td>
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
<div class="modal fade" id="ticketDetailsModal" tabindex="-1" aria-labelledby="ticketDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ticketDetailsModalLabel">Runner Registration Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Personal Information</h6>
                        <table class="table table-sm">
                            <tr><td><strong>BIB Name:</strong></td> <td id="modal-bib-name"></td></tr>
                            <tr><td><strong>BIB Number:</strong></td> <td id="modal-bib-number"></td></tr>
                            <tr><td><strong>T-Shirt Size:</strong></td> <td id="modal-tshirt"></td></tr>
                            <tr><td><strong>Blood Type:</strong></td> <td id="modal-blood"></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Event Details</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Event:</strong></td> <td id="modal-event"></td></tr>
                            <tr><td><strong>Category:</strong></td> <td id="modal-category"></td></tr>
                            <tr><td><strong>Exp. Level:</strong></td> <td id="modal-exp"></td></tr>
                            <tr><td><strong>Price:</strong></td> <td id="modal-price"></td></tr>
                        </table>
                    </div>
                </div>

                <hr>

                <div class="row align-items-center">
        <div class="col-md-5">
            <h6 class="fw-bold text-success">Transaction Image Proof</h6>
            <p class="small text-muted">This image is retrieved from the transaction_id record.</p>
            <div class="alert alert-info py-2">
                <strong>Medical Info:</strong><br>
                <span id="modal-medical" class="small"></span>
            </div>
        </div>
        <div class="col-md-7 text-center">
            <div class="border rounded p-1 bg-light shadow-sm">
                {{-- This is where the transaction image displays --}}
                <img id="modal-transaction-img" src="" alt="Transaction Proof" class="img-fluid rounded" style="max-height: 350px; cursor: pointer;" onclick="window.open(this.src)">
            </div>
            <p class="small text-muted mt-2">Click image to open full size</p>
        </div>
    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const detailButtons = document.querySelectorAll('.view-details');
    
    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const data = JSON.parse(this.getAttribute('data-info'));
            const transactionImageUrl = this.getAttribute('data-image');

            // Force "16 mile" to display as "16km" in the modal
            let categoryLabel = data.category || 'N/A';
            if (categoryLabel.toLowerCase().includes('16 mile')) {
                categoryLabel = '16km';
            }

            // Fill text fields
            document.getElementById('modal-bib-name').innerText = data.bib_name || 'N/A';
            document.getElementById('modal-bib-number').innerText = data.bib_number || 'Not Assigned';
            document.getElementById('modal-tshirt').innerText = data.t_shirt_size || 'N/A';
            document.getElementById('modal-blood').innerText = data.blood_type || 'N/A';
            document.getElementById('modal-category').innerText = categoryLabel;
            document.getElementById('modal-price').innerText = data.price + ' MMK';
            document.getElementById('modal-exp').innerText = data.experience_level || 'N/A';
            document.getElementById('modal-medical').innerText = data.medical_details || 'None';

            // Set the Image Source to the Transaction ID image
            const imgContainer = document.getElementById('modal-transaction-img');
            imgContainer.src = transactionImageUrl;
        });
    });
});
</script>
@endsection