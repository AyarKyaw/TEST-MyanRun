@extends('dashboard.layouts.master')

@section('content') 
<style>
    .table-responsive {
        width: 100%;
        overflow-x: auto; 
        display: block;
        -webkit-overflow-scrolling: touch;
    }

    .table-responsive::-webkit-scrollbar {
        height: 6px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 10px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    #example5 td, #example5 th, .custom-table td, .custom-table th {
        white-space: nowrap;
        vertical-align: middle;
    }

    /* Tab Styling */
    .nav-tabs {
        border-bottom: 2px solid #f1f1f1;
        margin-bottom: 20px;
    }
    .nav-tabs .nav-link {
        border: none;
        color: #6e6e6e;
        font-weight: 600;
        padding: 1rem 1.5rem;
    }
    .nav-tabs .nav-link.active {
        color: #C3E92D !important; /* Your theme primary color */
        background: transparent;
        border-bottom: 3px solid #C3E92D;
    }
    /* Increase Table Font Size */
    .table td {
        font-size: 17px !important;
        padding: 18px 15px !important; /* Added padding for better breathing room */
    }

    .table th {
        font-size: 16px !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Target the Name specifically */
    .table td .fw-bold {
        font-size: 19px !important;
        display: inline-block;
        margin-bottom: 3px;
    }

    /* Make the Search Input bigger and easier to read */
    #tableSearch {
        font-size: 18px !important;
        height: 55px !important;
    }

    .input-group .btn {
        padding: 0 25px !important;
        font-size: 18px !important;
    }

    /* Small text (Category/BIB sub-text) */
    .table td small.text-muted {
        font-size: 14px !important;
    }
</style>       

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
                <div class="col-xl-3 mb-4 mb-xl-0 d-flex flex-column gap-3">
                    <a href="" class="btn btn-primary light btn-lg d-block rounded fs-18">
                        + New Customer
                    </a>
                    <!-- <a href="{{ route('tickets.export.excel') }}" class="btn btn-success light btn-lg d-block rounded fs-18">
                        <i class="fa fa-file-excel me-2"></i>Export Excel
                    </a> -->
                </div>
                <div class="col-xl-9">
    <div class="card m-0">
        <div class="card-body py-3">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                
                <div class="d-flex align-items-center">
                    <div class="ms-1">
                        <p class="mb-0 fs-14 text-muted">Total Approved</p>
                        <h3 class="mb-0 text-black fw-bold fs-18">
                            {{ $customers->where('status', 'approved')->count() }} Persons
                        </h3>
                    </div>
                </div>
                
                <div style="min-width: 400px; max-width: 500px;" class="ms-auto">
                    <form action="" method="GET">
                        <div class="input-group">
                            <input type="text" 
                                   id="tableSearch" 
                                   name="search"
                                   class="form-control border-primary" 
                                   placeholder="Search Name or BIB..." 
                                   value="{{ request('search') }}"
                                   style="font-size: 18px; height: 50px;">
                            <button class="btn btn-primary px-4" type="submit">
                                <i class="fa fa-search"></i>
                            </button>
                            @if(request('search'))
                                <a href="" class="btn btn-light border-primary d-flex align-items-center">
                                    <i class="fa fa-times text-danger"></i>
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-block pb-0 border-0">
                            {{-- TABS NAVIGATION --}}
                            <ul class="nav nav-tabs" id="ticketTabs" role="tablist">
                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pending" type="button">
                                        Pending ({{ $customers->where('status', 'pending')->count() }})
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#approved" type="button">
                                        Approved ({{ $customers->where('status', 'approved')->count() }})
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#rejected" type="button">
                                        Rejected ({{ $customers->where('status', 'rejected')->count() }})
                                    </button>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="card-body p-0">
                            <div class="tab-content">
                                
                                {{-- TAB: PENDING --}}
                                <div class="tab-pane fade show active" id="pending">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-hover mb-0" style="min-width: 1200px;">
                                            <thead>
                                                <tr>
                                                    <th class="text-end">Action</th>
                                                    <th>User Name</th>
                                                    <th>BIB Name</th>
                                                    <th>BIB Number</th>
                                                    <th>Status</th>
                                                    <th>Price</th>
                                                    <th>Event & Category</th>
                                                    <th>Reg. Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($customers->where('status', 'pending') as $customer)
                                                <tr>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-primary shadow btn-xs btn-square view-details" data-bs-toggle="modal" data-bs-target="#ticketDetailsModal" data-info="{{ json_encode($customer) }}" data-image="{{ $customer->transaction_id ? asset('uploads/payments/' . $customer->transaction_id) : asset('images/no-image.png') }}">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </td>
                                                    <td><strong>{{ $customer->athlete?->user?->full_name ?? 'Guest Runner' }}</strong></td>
                                                    <td><strong>{{ $customer->bib_name }}</strong></td>
                                                    <td><strong>#{{ $customer->bib_number }}</strong></td>
                                                    <td><span class="badge light badge-warning">Pending</span></td>
                                                    <td>{{ number_format($customer->price) }} MMK</td>
                                                    <td><span class="text-black fw-bold">{{ $customer->event }}</span><br><small class="text-muted">{{ $customer->category }}</small></td>
                                                    <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="6" class="text-center p-5">No pending tickets.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- TAB: APPROVED --}}
                                <div class="tab-pane fade" id="approved">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-hover mb-0" style="min-width: 1200px;">
                                            <thead>
                                                <tr>
                                                    <th class="text-end">Action</th>
                                                    <th>User Name</th>
                                                    <th>BIB Name</th>
                                                    <th>BIB Number</th>
                                                    <th>Status</th>
                                                    <th>Price</th>
                                                    <th>Event & Category</th>
                                                    <th>Reg. Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($customers->where('status', 'approved') as $customer)
                                                <tr>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-primary shadow btn-xs btn-square view-details" data-bs-toggle="modal" data-bs-target="#ticketDetailsModal" data-info="{{ json_encode($customer) }}" data-image="{{ $customer->transaction_id ? asset('uploads/payments/' . $customer->transaction_id) : asset('images/no-image.png') }}">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </td>
                                                    <td><strong>{{ $customer->athlete?->user?->full_name ?? 'Guest Runner' }}</strong></td>
                                                    <td><strong>{{ $customer->bib_name }}</strong></td>
                                                    <td><strong>#{{ $customer->bib_number }}</strong></td>
                                                    <td><span class="badge light badge-success">Approved</span></td>
                                                    <td>{{ number_format($customer->price) }} MMK</td>
                                                    <td><span class="text-black fw-bold">{{ $customer->event }}</span><br><small class="text-muted">{{ $customer->category }}</small></td>
                                                    <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="6" class="text-center p-5">No approved tickets.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                {{-- TAB: REJECTED --}}
                                <div class="tab-pane fade" id="rejected">
                                    <div class="table-responsive">
                                        <table class="table table-borderless table-hover mb-0" style="min-width: 1200px;">
                                            <thead>
                                                <tr>
                                                    <th class="text-end">Action</th>
                                                    <th>User Name</th>
                                                    <th>BIB Name</th>
                                                    <th>BIB Number</th>
                                                    <th>Status</th>
                                                    <th>Price</th>
                                                    <th>Event & Category</th>
                                                    <th>Reg. Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($customers->where('status', 'rejected') as $customer)
                                                <tr>
                                                    <td class="text-end">
                                                        <button type="button" class="btn btn-primary shadow btn-xs btn-square view-details" data-bs-toggle="modal" data-bs-target="#ticketDetailsModal" data-info="{{ json_encode($customer) }}" data-image="{{ $customer->transaction_id ? asset('uploads/payments/' . $customer->transaction_id) : asset('images/no-image.png') }}">
                                                            <i class="fa fa-eye"></i>
                                                        </button>
                                                    </td>
                                                    <td><strong>{{ $customer->athlete?->user?->full_name ?? 'Guest Runner' }}</strong></td>
                                                    <td><strong>{{ $customer->bib_name }}</strong></td>
                                                    <td><strong>#{{ $customer->bib_number }}</strong></td>
                                                    <td><span class="badge light badge-danger">Rejected</span></td>
                                                    <td>{{ number_format($customer->price) }} MMK</td>
                                                    <td><span class="text-black fw-bold">{{ $customer->event }}</span><br><small class="text-muted">{{ $customer->category }}</small></td>
                                                    <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                                                </tr>
                                                @empty
                                                <tr><td colspan="6" class="text-center p-5">No rejected tickets.</td></tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div> {{-- End tab content --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

{{-- MODAL --}}
<div class="modal fade" id="ticketDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Runner Registration Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Personal Information</h6>
                        <table class="table table-sm">
                            <tr><td><strong>Name:</strong></td> <td id="modal-name"></td></tr>
                            <tr><td><strong>Bib Name:</strong></td> <td id="modal-bib-name"></td></tr>
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
                            <tr><td><strong>State:</strong></td> <td id="modal-state"></td></tr>
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <h6 class="fw-bold text-success">Transaction Image Proof</h6>
                        <div class="alert alert-info py-2">
                            <strong>Medical Info:</strong><br>
                            <span id="modal-medical" class="small"></span>
                        </div>
                    </div>
                    <div class="col-md-7 text-center">
                        <div class="border rounded p-1 bg-light shadow-sm">
                            <img id="modal-transaction-img" src="" alt="Transaction Proof" class="img-fluid rounded" style="max-height: 350px; cursor: pointer;" onclick="window.open(this.src)">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <div class="d-flex align-items-center" id="modal-action-buttons">
                    <form id="reject-form" action="" method="POST" class="me-2">
                        @csrf
                        <button type="submit" class="btn btn-warning px-4"><i class="fa fa-times mr-1"></i> Reject</button>
                    </form>
                    <form id="approve-form" action="" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success px-4"><i class="fa fa-check mr-1"></i> Approve</button>
                    </form>
                </div>
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

            const approveForm = document.getElementById('approve-form');
            const rejectForm = document.getElementById('reject-form');
            const actionContainer = document.getElementById('modal-action-buttons');

            if (approveForm) approveForm.action = `/tickets/approve/${data.id}`;
            if (rejectForm) rejectForm.action = `/tickets/reject/${data.id}`;

            if (data.status === 'pending') {
                actionContainer.classList.remove('d-none');
            } else {
                actionContainer.classList.add('d-none');
            }

            const athlete = data.athlete;
            const firstName = athlete.first_name || '';
            const midName = athlete.middle_name ? athlete.middle_name + " " : ""; // Add space only if it exists
            const lastName = athlete.last_name || '';

            document.getElementById('modal-name').innerText = `${firstName} ${midName}${lastName}`.trim() || 'N/A';
            document.getElementById('modal-bib-name').innerText = data.bib_name || 'N/A';
            document.getElementById('modal-bib-number').innerText = data.bib_number || 'Not Assigned';
            document.getElementById('modal-tshirt').innerText = data.t_shirt_size || 'N/A';
            document.getElementById('modal-category').innerText = data.category || 'N/A';
            document.getElementById('modal-event').innerText = data.event;
            document.getElementById('modal-price').innerText = (data.price || 0) + ' MMK';
            document.getElementById('modal-exp').innerText = data.experience_level || 'N/A';
            
            if (data.athlete) {
                document.getElementById('modal-blood').innerText = data.athlete.blood_type || 'N/A';
                document.getElementById('modal-medical').innerText = data.athlete.medical_details || 'None';
                document.getElementById('modal-state').innerText = data.athlete.state || 'None';
            }
            document.getElementById('modal-transaction-img').src = transactionImageUrl;
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('tableSearch');
    
    searchInput.addEventListener('keyup', function () {
        const searchTerm = this.value.toLowerCase();
        
        // Target all table bodies across all tabs
        const tableBodies = document.querySelectorAll('.tab-pane tbody');

        tableBodies.forEach(tbody => {
            const rows = tbody.querySelectorAll('tr');
            let hasVisibleRow = false;

            rows.forEach(row => {
                // Get text from the Name and BIB Number columns
                const text = row.textContent.toLowerCase();
                
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                    hasVisibleRow = true;
                } else {
                    row.style.display = 'none';
                }
            });

            // Handle "No results found" logic per tab
            const noResultsRow = tbody.querySelector('.no-results-found');
            if (!hasVisibleRow) {
                if (!noResultsRow) {
                    const tr = document.createElement('tr');
                    tr.className = 'no-results-found';
                    tr.innerHTML = `<td colspan="6" class="text-center p-5">No matching records found for "${searchTerm}"</td>`;
                    tbody.appendChild(tr);
                }
            } else if (noResultsRow) {
                noResultsRow.remove();
            }
        });
    });
});
</script>
@endsection