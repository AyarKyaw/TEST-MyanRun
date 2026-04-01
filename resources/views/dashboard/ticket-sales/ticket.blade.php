@extends('dashboard.layouts.master')

@section('content') 
<style>
    /* Table & Scrollbar Styling */
    .table-responsive {
        width: 100%;
        overflow-x: auto; 
        display: block;
        -webkit-overflow-scrolling: touch;
    }
    .table-responsive::-webkit-scrollbar { height: 6px; }
    .table-responsive::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
    .table-responsive::-webkit-scrollbar-track { background: #f1f1f1; }

    #ticketTable td, #ticketTable th {
        white-space: nowrap;
        vertical-align: middle;
    }

    /* Tab Styling */
    .nav-tabs { border-bottom: 2px solid #f1f1f1; margin-bottom: 20px; }
    .nav-tabs .nav-link { border: none; color: #6e6e6e; font-weight: 600; padding: 1rem 1.5rem; }
    .nav-tabs .nav-link.active {
        color: #C3E92D !important; 
        background: transparent;
        border-bottom: 3px solid #C3E92D;
    }

    /* Table Content Styling */
    .table td { font-size: 17px !important; padding: 18px 15px !important; }
    .table th { font-size: 16px !important; text-transform: uppercase; letter-spacing: 0.5px; }
    .table td .fw-bold { font-size: 19px !important; display: inline-block; margin-bottom: 3px; }
    
    #tableSearch { font-size: 18px !important; height: 55px !important; }
    .table td small.text-muted { font-size: 14px !important; }

    /* Modal Styling Enhancements */
    .modal-content { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
    .modal-header { background: #f8f9fa; border-bottom: 1px solid #eee; border-radius: 15px 15px 0 0; }
    .info-section { background: #fdfdfd; border: 1px solid #f1f1f1; border-radius: 10px; padding: 15px; height: 100%; }
    .section-title { font-size: 13px; text-transform: uppercase; letter-spacing: 1px; color: #888; margin-bottom: 15px; display: block; font-weight: 700; }
    
    .custom-table tr td { border: none; padding: 8px 0; font-size: 15px; }
    .custom-table tr td:first-child { color: #666; width: 40%; }
    
    .nrc-group .form-control { 
        border: 1px solid #e2e2e2; 
        font-size: 14px; 
        padding: 5px 8px;
        background-color: #fff;
    }
    .nrc-group select { cursor: pointer; }
    
    #modal-transaction-img {
        transition: transform 0.3s ease;
        border: 4px solid #fff;
    }
    #modal-transaction-img:hover { transform: scale(1.02); }
</style>       

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
                <a href="#" class="btn btn-primary light btn-lg d-block rounded fs-18">
                    <i class="fa fa-plus me-2"></i>New Customer
                </a>

                <div class="dropdown">
                    <button type="button" class="btn btn-outline-success btn-lg d-block w-100 rounded fs-18 dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fa fa-file-excel me-2"></i>Export {{ ucfirst(request('status', 'all')) }} List
                    </button>
                    <ul class="dropdown-menu w-100">
                        <li><a class="dropdown-item py-2" href="{{ route('dashboard.tickets.export', ['category' => 'all', 'status' => request('status', 'all')]) }}">
                            All Categories ({{ ucfirst(request('status', 'all')) }})
                        </a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('dashboard.tickets.export', ['category' => '16km', 'status' => request('status', 'all')]) }}">16KM ({{ ucfirst(request('status', 'all')) }})</a></li>
                        <li><a class="dropdown-item py-2" href="{{ route('dashboard.tickets.export', ['category' => '36km', 'status' => request('status', 'all')]) }}">36KM ({{ ucfirst(request('status', 'all')) }})</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xl-9">
                <div class="card m-0">
                    <div class="card-body py-3">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                            <div>
                                <p class="mb-0 fs-14 text-muted">Total Approved</p>
                                <h3 class="mb-0 text-black fw-bold fs-18">{{ $counts['approved'] ?? 0 }} Persons</h3>
                            </div>
                            
                            <div style="min-width: 400px; max-width: 500px;" class="ms-auto">
                                <form action="{{ URL::current() }}" method="GET">
                                    <input type="hidden" name="status" value="{{ request('status', 'pending') }}">
                                    <div class="input-group">
                                        <input type="text" id="tableSearch" name="search" class="form-control border-primary" placeholder="Search Name or BIB..." value="{{ request('search') }}">
                                        <button class="btn btn-primary px-4" type="submit"><i class="fa fa-search"></i></button>
                                        @if(request('search'))
                                            <a href="{{ route('dashboard.events.ticket', [
                                                'event' => $eventName,
                                                'status' => request('status', 'pending')
                                            ]) }}" class="btn btn-light border-primary d-flex align-items-center">
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
                        <ul class="nav nav-tabs">
                            @foreach(['pending' => 'Warning', 'approved' => 'Success', 'rejected' => 'Danger'] as $status => $color)
                                <li class="nav-item">
                                    <a class="nav-link {{ request('status', 'pending') == $status ? 'active' : '' }}" 
                                       href="{{ route('dashboard.events.ticket', ['status' => $status, 'event' => $eventName, 'search' => request('search')]) }}">
                                         {{ ucfirst($status) }} ({{ $counts[$status] ?? 0 }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover mb-0" id="ticketTable" style="min-width: 1200px;">
                                <thead>
                                    <tr>
                                        <th class="text-center">Action</th>
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
                                    @forelse($customers as $customer)
                                    <tr>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-primary shadow btn-xs btn-square view-details" 
                                                data-bs-toggle="modal" data-bs-target="#ticketDetailsModal" 
                                                data-info="{{ json_encode($customer) }}" 
                                                data-image="{{ $customer->transaction_id ? asset('uploads/payments/' . $customer->transaction_id) : asset('images/no-image.png') }}">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                        <td><strong>{{ $customer->athlete?->user?->full_name ?? 'Guest Runner' }}</strong></td>
                                        <td><strong>{{ $customer->bib_name }}</strong></td>
                                        <td><strong>{{ $customer->bib_number }}</strong></td>
                                        <td>
                                            @php $badgeClass = match($customer->status) { 'approved' => 'success', 'rejected' => 'danger', default => 'warning' }; @endphp
                                            <span class="badge light badge-{{ $badgeClass }}">{{ ucfirst($customer->status) }}</span>
                                        </td>
                                        <td>{{ number_format($customer->price) }} MMK</td>
                                        <td>
                                            <span class="text-black fw-bold">{{ $customer->event }}</span><br>
                                            <small class="text-muted">{{ $customer->category }}</small>
                                        </td>
                                        <td>{{ $customer->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center p-5">
                                            <img src="{{ asset('images/no-data.png') }}" alt="" style="width: 80px; opacity: 0.5;"><br>
                                            <p class="mt-3 text-muted">No {{ request('status', 'pending') }} tickets found.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            
                            <div class="card-footer d-flex justify-content-between align-items-center bg-white border-top-0 pt-0 pb-4">
                                <div class="text-muted fs-14">
                                    Showing <strong>{{ $customers->firstItem() }}</strong> to <strong>{{ $customers->lastItem() }}</strong> of <strong>{{ $customers->total() }}</strong> entries
                                </div>
                                <div class="pagination-container">
                                    {{ $customers->links('pagination::bootstrap-5') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- Ticket Details Modal --}}
<div class="modal fade" id="ticketDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header px-4">
                <h5 class="modal-title fw-bold text-black">Runner Registration Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/dashboard/update-ticket-info" method="POST" id="update-ticket-form">
                @csrf
                <input type="hidden" name="id" id="modal-ticket-id-input">
                <div class="modal-body p-4">
                    <div class="row g-4">
                        {{-- Personal Info Card --}}
                        <div class="col-md-6">
                            <div class="info-section">
                                <span class="section-title">Personal Information</span>
                                <table class="table table-sm custom-table mb-0">
                                    <tr><td>Name</td> <td id="modal-name" class="fw-bold text-black"></td></tr>
                                    <tr>
                                        <td>Bib Name</td> 
                                        <td id="modal-bib-container"></td>
                                    </tr>
                                    <tr><td>BIB Number</td> <td id="modal-bib-number" class="text-primary fw-bold"></td></tr>
                                    <tr>
                                        <td>T-Shirt Size</td> 
                                        <td id="modal-tshirt-container"></td>
                                    </tr>
                                    <tr><td>Blood Type</td> <td id="modal-blood" class="text-danger fw-bold"></td></tr>
                                    <tr><td>National Type</td> <td id="modal-nat" class="text-capitalize"></td></tr>
                                    <tr>
                                        <td>ID Number</td> 
                                        <td><div id="modal-id-container" class="nrc-group"></div></td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        {{-- Event Info Card --}}
                        <div class="col-md-6">
                            <div class="info-section">
                                <span class="section-title">Event Details</span>
                                <table class="table table-sm custom-table mb-0">
                                    <tr><td>Event</td> <td id="modal-event" class="fw-bold text-black"></td></tr>
                                    <tr><td>Category</td> <td id="modal-category"></td></tr>
                                    <tr><td>Exp. Level</td> <td id="modal-exp"></td></tr>
                                    <tr><td>Price</td> <td id="modal-price" class="fw-bold text-success"></td></tr>
                                    <tr><td>State</td> <td id="modal-state" class="text-capitalize"></td></tr>
                                </table>
                                
                                <div class="mt-4 pt-2">
                                    <div class="alert alert-light border-0 small p-2 mb-2">
                                        <i class="fa fa-notes-medical me-1 text-info"></i> <strong>Medical:</strong> 
                                        <span id="modal-medical" class="text-muted"></span>
                                    </div>
                                    <div class="alert alert-light border-0 small p-2">
                                        <i class="fa fa-running me-1 text-info"></i> <strong>ITRA:</strong> 
                                        <span id="modal-itra" class="text-muted"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Transaction Image --}}
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="text-center p-3 bg-light rounded-3">
                                <h6 class="fw-bold mb-3"><i class="fa fa-receipt me-1 text-success"></i> Transaction Proof</h6>
                                <img id="modal-transaction-img" src="" alt="Transaction Proof" class="img-fluid rounded shadow-sm" style="max-height: 400px; cursor: zoom-in;" onclick="window.open(this.src)">
                                <p class="text-muted small mt-2 mb-0">Click image to view full size</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3 d-none" id="modal-save-button-container">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary w-100 py-2 fs-16"><i class="fa fa-save me-2"></i>Update Runner Information</button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
                <div class="d-flex align-items-center" id="modal-action-buttons">
                    <form id="reject-form" action="" method="POST" class="me-2">
                        @csrf
                        <button type="submit" class="btn btn-danger px-4"><i class="fa fa-times me-1"></i> Reject</button>
                    </form>
                    <form id="approve-form" action="" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success px-4"><i class="fa fa-check me-1"></i> Approve</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const districtOptions = {
    "1": ["ကပတ", "ကမတ", "ခပန", "ခလဖ", "ဆဒန", "ဆပရ", "ဆဘန", "တဆလ", "တနန", "ဒဖယ", "နမန", "ပတအ", "ပနဒ", "ပဝန", "ဖကန", "ဗမန", "မကတ", "မကန", "မခဘ", "မစန", "မညန", "မမန", "မလန", "ရှကန", "ရှဗယ", "လဂျန", "ဟပန", "အဂျယ", "၀မန"],
    "2": ["ဒမဆ", "ဖဆန", "ဖရဆ", "ဘလခ", "မစန", "ရတန", "ရသန", "လကန"],
    "3": ["ကကရ", "ကဆက", "ကဒတ", "ကဒန", "ကမမ", "စကလ", "ပကန", "ဖပန", "ဘဂလ", "ဘသဆ", "ဘအန", "မဝတ", "ရသန", "လဘန", "လသန", "ဝလမ", "သတက", "သတန"],
    "4": ["ကခန", "ကပလ", "ဆမန", "တဇန", "တတန", "ထတလ", "ပလဝ", "ဖလန", "မတန", "မတပ", "ရခဒ", "ရဇန", "ဟခန"],
    "5": ["ကနန", "ကဘလ", "ကမန", "ကလတ", "ကလထ", "ကလန", "ကလဝ", "ကသန", "ခတန", "ခပန", "ခဥတ", "ခဥန", "ငဇန", "စကန", "ဆလက", "တဆန", "တမန", "ထခန", "ဒပယ", "နယန", "ပလန", "ပလဘ", "ဖပန", "ဗမန", "ဘတလ", "မကန", "မမတ", "မမန", "မရန", "မလန", "ယမပ", "ရဘန", "ရဥန", "လရန", "လဟန", "ဝလန", "ဝသန", "ဟမလ", "အတန", "အရတ"],
    "6": ["ကစန", "ကရရ", "ကလအ", "ကသန", "ခမန", "တသရ", "ထဝန", "ပလတ", "ပလန", "ဘပန", "မတန", "မမန", "ရဖြန", "လလန", "သရခ"],
    "7": ["ကကန", "ကတခ", "ကပက", "ကဝန", "ဇကန", "ညလပ", "တငန", "ထတပ", "ဒဥန", "နတလ", "ပခတ", "ပခန", "ပတဆ", "ပတတ", "ပတန", "ပနက", "ပမန", "ဖမန", "မညန", "မလန", "ရကန", "ရတန", "ရတရှ", "လပတ", "ဝမန", "သကန", "သဆန", "သနပ", "သဝတ", "အတန", "အဖန"],
    "8": ["ကထန", "ကမရ", "ခဆန", "ခဇန", "ပမန", "ဘလန", "မဒန", "မလမ", "ရမန", "လမန", "သထန", "သဖြရ"],
    "9": ["ကဆန", "ကပတ", "ခမစ", "ခအစ", "ငဇန", "ငသရ", "စကတ", "စကန", "ဇဗသ", "ဇယသ", "ညဥန", "တကတ", "တကန", "တတဥ", "တသန", "ဒခသ", "နထက", "ပကခ", "ပဗသ", "ပဘန", "ပမန", "ပသက", "ပဥလ", "မကန", "မခန", "မတရ", "မထလ", "မမန", "မလန", "မသန", "မဟမ", "ရမသ", "လဝန", "ဝတန", "သစန", "သပက", "အမစ", "အမရ", "ဥတသ"],
    "10": ["ကထန", "ကမရ", "ခဆန", "ခဇန", "ပမန", "ဘလန", "မဒန", "မလမ", "ရမန", "လမန", "သထန", "သဖြရ"],
    "11": ["ကတန", "ကတလ", "ကဖန", "ဂမန", "စတန", "တကန", "တပဝ", "ပဏတ", "ပတန", "ဗတထ", "ဘသတ", "မတန", "မပတ", "မပန", "မအတ", "မအန", "မဥန", "ရဗန", "ရသတ", "သတန", "အမန"],
    "12": ["ကကက", "ကခက", "ကတတ", "ကတန", "ကမတ", "ကမန", "ကမရ", "ခရန", "စခန", "ဆကခ", "ဆကန", "တကန", "တတထ", "တတန", "တမန", "ထတပ", "ဒဂဆ", "ဒဂတ", "ဒဂန", "ဒဂမ", "ဒဂရ", "ဒပန", "ဒလန", "ပဇတ", "ပဘတ", "ဗဟန", "မဂတ", "မဂဒ", "မဘန", "မရက", "ရကန", "ရပသ", "လကန", "လမတ", "လမန", "လသန", "လသယ", "သကတ", "သခန", "သဃက", "သလန", "အစန", "အလန", "ဥကတ", "ဥကန", "ဥကမ"],
    "13": ["ကခန", "ကတတ", "ကတန", "ကတလ", "ကမဆ", "ကမန", "ကရန", "ကလတ", "ကလဒ", "ကလန", "ကလဖ", "ကသန", "ကဟန", "ခမန", "ခရဟ", "ခလန", "ဆဆန", "ဆဖန", "ညရန", "တကန", "တခလ", "တမည", "တယန", "တလန", "နကန", "နခတ", "နခန", "နခဝ", "နဆန", "နတန", "နတယ", "နဖန", "နမတ", "နဝန", "ပခန", "ပဆန", "ပတယ", "ပပက", "ပယန", "ပလတ", "ပလန", "ပဝန", "ဖခန", "မကန", "မခန", "မငန", "မဆတ", "မဆန", "မတတ", "မတန", "မနန", "မပန", "မဖန", "မဗတ", "မဘန", "မမဆ", "မမတ", "မမန", "မယန", "မရတ", "မရန", "မလန", "မဟရ", "ယလန", "ရငန", "ရစန", "ရဖန", "လကတ", "လခတ", "လခန", "လရန", "လလန", "လဟန", "သနန", "သပန", "ဟတန", "ဟပတ", "ဟပန", "အခန", "အတန"],
    "14": ["ကကထ", "ကကန", "ကခန", "ကပန", "ကလန", "ငဆန", "ငပတ", "ငရက", "ငသခ", "ငသယ", "ဇလန", "ညတန", "ဒဒရ", "ဒနဖြ", "ပစလ", "ပတန", "ပသန", "ဖပန", "ဘကလ", "မမက", "မမန", "မအန", "မအပ", "ရကန", "ရသယ", "လပတ", "လမန", "ဝခမ", "သပန", "ဟကကျ", "ဟသတ", "အဂပ", "အမတ", "အမန"]
};

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('tableSearch');
    const ticketTable = document.getElementById('ticketTable');

    // 1. Search Logic
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const searchTerm = this.value.toLowerCase().trim();
            const rows = ticketTable.querySelectorAll('tbody tr:not(.no-results)');
            let visibleCount = 0;

            rows.forEach(row => {
                const isMatch = row.textContent.replace(/\s+/g, ' ').toLowerCase().includes(searchTerm);
                row.style.display = isMatch ? '' : 'none';
                if (isMatch) visibleCount++;
            });

            let noResRow = ticketTable.querySelector('.no-results');
            if (visibleCount === 0 && searchTerm !== "") {
                if (!noResRow) {
                    const tr = document.createElement('tr');
                    tr.className = 'no-results';
                    tr.innerHTML = `<td colspan="8" class="text-center p-5"><div class="text-muted">No results found</div></td>`;
                    ticketTable.querySelector('tbody').appendChild(tr);
                }
            } else if (noResRow) noResRow.remove();
        });
    }

    // 2. Open Modal Logic
    const detailButtons = document.querySelectorAll('.view-details');
    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const data = JSON.parse(this.getAttribute('data-info'));
            const transactionImageUrl = this.getAttribute('data-image');
            const athlete = data.athlete || {};
            
            document.getElementById('modal-ticket-id-input').value = data.id;

            document.getElementById('modal-name').innerText = [athlete.first_name, athlete.middle_name, athlete.last_name].filter(Boolean).join(' ') || 'Guest Runner';
            document.getElementById('modal-itra').innerText = athlete.itra_details || 'None';
            document.getElementById('modal-bib-number').innerText = data.bib_number || 'Not Assigned';
            document.getElementById('modal-category').innerText = data.category || 'N/A';
            document.getElementById('modal-event').innerText = data.event || 'N/A';
            document.getElementById('modal-price').innerText = new Intl.NumberFormat().format(data.price || 0) + ' MMK';
            document.getElementById('modal-exp').innerText = data.experience_level || 'N/A';
            document.getElementById('modal-blood').innerText = athlete.blood_type || 'N/A';
            document.getElementById('modal-medical').innerText = athlete.medical_details || 'None';
            document.getElementById('modal-state').innerText = athlete.state || 'None';
            document.getElementById('modal-nat').innerText = athlete.nat_type || 'None';
            document.getElementById('modal-transaction-img').src = transactionImageUrl;

            document.getElementById('approve-form').action = `/dashboard/tickets/approve/${data.id}`;
            document.getElementById('reject-form').action = `/dashboard/tickets/reject/${data.id}`;
            
            const actionContainer = document.getElementById('modal-action-buttons');
            const saveBtnContainer = document.getElementById('modal-save-button-container');
            const isEditable = ['pending', 'approved'].includes(data.status);

            if (data.status === 'pending') {
                actionContainer.classList.remove('d-none');
            } else {
                actionContainer.classList.add('d-none');
            }

            if (isEditable) {
                saveBtnContainer.classList.remove('d-none');
                
                document.getElementById('modal-bib-container').innerHTML = `
                    <input type="text" name="bib_name" class="form-control form-control-sm border-primary" value="${data.bib_name || ''}" placeholder="Enter BIB Name">
                `;

                const sizes = ["XS", "S", "M", "L", "XL", "2XL", "3XL", "5XL"];
                let sizeOptions = sizes.map(s => `<option value="${s}" ${data.t_shirt_size === s ? 'selected' : ''}>${s}</option>`).join('');
                document.getElementById('modal-tshirt-container').innerHTML = `
                    <select name="t_shirt_size" class="form-control form-control-sm border-primary">
                        ${sizeOptions}
                    </select>
                `;

                const idContainer = document.getElementById('modal-id-container');
                const currentId = athlete.id_number || '';

                if (athlete.nat_type === 'national') {
                    let state = '', district = '', type = 'နိုင်', num = '';
                    const match = currentId.match(/^(\d+)\/([^\(]+)\(([^)]+)\)(\d+)$/);
                    if(match) { state = match[1]; district = match[2]; type = match[3]; num = match[4]; }

                    idContainer.innerHTML = `
                        <div class="d-flex gap-1 flex-wrap">
                            <select name="nrc_state" id="edit_nrc_state" class="form-control" style="width: 55px;" required>
                                <option value="">St</option>
                                ${[...Array(14)].map((_,i)=>`<option value="${i+1}" ${state == i+1 ? 'selected':''}>${i+1}/</option>`).join('')}
                            </select>
                            <select name="nrc_district" id="edit_nrc_district" class="form-control" style="width: 85px;" required>
                                <option value="${district}">${district || 'Dist'}</option>
                            </select>
                            <select name="nrc_type" id="edit_nrc_type" class="form-control" style="width: 65px;">
                                ${['နိုင်','ဧည့်','စ','ပြု','သ','သီ'].map(t => `<option value="${t}" ${type == t ? 'selected':''}>${t}</option>`).join('')}
                            </select>
                            <input type="text" name="nrc_number" id="edit_nrc_number" class="form-control" style="flex: 1; min-width: 80px;" value="${num}" placeholder="123456" maxlength="6" required>
                        </div>
                    `;
                } else {
                    idContainer.innerHTML = `
                        <input type="text" name="id_number" id="edit_passport" class="form-control form-control-sm border-primary" value="${currentId}" placeholder="Passport Number" required>
                    `;
                }
                
                if(athlete.nat_type === 'national' && state) {
                    setTimeout(() => document.getElementById('edit_nrc_state').dispatchEvent(new Event('change')), 50);
                }

            } else {
                saveBtnContainer.classList.add('d-none');
                document.getElementById('modal-bib-container').innerHTML = `<span class="fw-bold text-black">${data.bib_name || 'N/A'}</span>`;
                document.getElementById('modal-tshirt-container').innerHTML = `<span class="badge light badge-primary">${data.t_shirt_size || 'N/A'}</span>`;
                document.getElementById('modal-id-container').innerHTML = `<span class="fw-bold text-black">${athlete.id_number || 'None'}</span>`;
            }
        });
    });
});

document.addEventListener('change', function(e) {
    if (e.target.id === 'edit_nrc_state') {
        const state = e.target.value;
        const districtSelect = document.getElementById('edit_nrc_district');
        if (!districtSelect) return;

        const currentVal = districtSelect.value;
        districtSelect.innerHTML = '<option value="">District</option>';
        if (districtOptions[state]) {
            districtOptions[state].forEach(d => {
                const opt = document.createElement('option');
                opt.value = d;
                opt.textContent = d;
                if(d === currentVal) opt.selected = true;
                districtSelect.appendChild(opt);
            });
        }
    }
});
</script>
@endsection