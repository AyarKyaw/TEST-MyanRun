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
    .nav-tabs { border-bottom: 2px solid #f1f1f1; margin-bottom: 20px; border-top: 0; }
    .nav-tabs .nav-link { border: none; color: #6e6e6e; font-weight: 600; padding: 1rem 1.5rem; }
    .nav-tabs .nav-link.active {
        color: #C3E92D !important; 
        background: transparent;
        border-bottom: 3px solid #C3E92D;
    }

    /* Table Content Styling */
    .table td { font-size: 16px !important; padding: 15px 15px !important; color: #333; }
    .table th { font-size: 13px !important; text-transform: uppercase; letter-spacing: 0.5px; color: #888; background: #fcfcfc; }
    
    #tableSearch { font-size: 16px !important; height: 50px !important; border-radius: 10px 0 0 10px !important; }
    .table td small.text-muted { font-size: 13px !important; }

    /* Modal Styling Enhancements */
    .modal-content { border-radius: 20px; border: none; overflow: hidden; }
    .modal-header { background: #fff; border-bottom: 1px solid #f1f1f1; padding: 25px 30px; }
    .info-section { background: #f8f9fa; border-radius: 15px; padding: 20px; height: 100%; border: 1px solid #eee; }
    .section-title { font-size: 12px; text-transform: uppercase; letter-spacing: 1.2px; color: #999; margin-bottom: 15px; display: block; font-weight: 800; }
    
    .custom-table tr td { border: none; padding: 6px 0; font-size: 14px; }
    .custom-table tr td:first-child { color: #777; width: 45%; }
    
    .nrc-group .form-control { 
        border: 1px solid #e2e2e2; 
        font-size: 13px; 
        padding: 6px 8px;
        background-color: #fff;
        border-radius: 6px;
    }
    
    #modal-transaction-img {
        transition: all 0.3s ease;
        border: 5px solid #fff;
        border-radius: 12px;
    }
    #modal-transaction-img:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }

    /* Stats Card Styling */
    .stat-widget { background: #fff; padding: 20px; border-radius: 15px; height: 100%; }
    .progress-thin { height: 6px; border-radius: 10px; background: #eee; }
</style>       

<main class="content-body">
    <div class="container-fluid">
        <div class="page-title d-flex align-items-center justify-content-between mb-4">
            <div>
                <h1>Event Tickets</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">Event Tickets</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-xl-4 col-md-5 mb-3">
                <div class="d-flex flex-column gap-2">
                    <a href="#" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center rounded-3 shadow-sm py-3">
                        <i class="fa fa-plus-circle me-2 fs-20"></i> <span class="fw-bold">New Customer Registration</span>
                    </a>

                    <div class="dropdown">
                        <button type="button" class="btn btn-light border btn-lg w-100 rounded-3 dropdown-toggle d-flex align-items-center justify-content-between" data-bs-toggle="dropdown">
                            <span><i class="fa fa-file-excel me-2 text-success"></i>Export {{ ucfirst(request('status', 'all')) }}</span>
                        </button>
                        <ul class="dropdown-menu w-100 shadow-lg border-0 mt-2">
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('dashboard.tickets.export', ['status' => 'all', 'event' => $event->id, 'category' => 'all']) }}">
                                    All Status
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('dashboard.tickets.export', ['event' => $event->id, 'category' => 'all', 'status' => request('status', 'all')]) }}">
                                    All Categories
                                </a>
                            </li>
                            
                            <div class="dropdown-divider"></div>

                            @foreach($event->ticketTypes as $type)
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('dashboard.tickets.export', ['event' => $event->id, 'category' => $type->name, 'status' => request('status', 'all')]) }}">
                                        {{ $type->name }} Category
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-8 col-md-7">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <div class="col-md-7">
                                <div class="d-flex align-items-center gap-4 mb-3">
                                    <div>
                                        <p class="mb-0 fs-12 text-muted text-uppercase fw-bold">Approved</p>
                                        <h2 class="mb-0 text-black fw-bold">{{ number_format($counts['approved'] ?? 0) }}</h2>
                                    </div>
                                    <div class="vr mx-2" style="height: 40px; opacity: 0.1;"></div>
                                    <div>
                                        <p class="mb-0 fs-12 text-muted text-uppercase fw-bold">Remaining</p>
                                        @if(!is_null($eventLimit))
                                            <h2 class="mb-0 text-danger fw-bold">{{ number_format(max(0, $eventLimit - ($counts['approved'] ?? 0))) }}</h2>
                                        @else
                                            <h2 class="mb-0 text-success fw-bold">Unlimited</h2>
                                        @endif
                                    </div>
                                </div>
                                @if(!is_null($eventLimit))
                                    <div class="progress progress-thin">
                                        @php $percent = (($counts['approved'] ?? 0) / $eventLimit) * 100; @endphp
                                        <div class="progress-bar bg-primary" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <p class="fs-12 text-muted mt-2 mb-0">Event Capacity: {{ number_format($eventLimit) }} total spots</p>
                                @endif
                            </div>
                            
                            <div class="col-md-5 mt-3 mt-md-0">
                                <form action="{{ URL::current() }}" method="GET">
                                    <input type="hidden" name="status" value="{{ request('status', 'pending') }}">
                                    <div class="input-group shadow-sm">
                                        <input type="text" id="tableSearch" name="search" class="form-control border-0" placeholder="Name or BIB..." value="{{ request('search') }}">
                                        <button class="btn btn-primary px-3" type="submit"><i class="fa fa-search"></i></button>
                                        @if(request('search'))
                                            <a href="{{ route('dashboard.events.ticket', ['event' => $eventName, 'status' => request('status', 'pending')]) }}" class="btn btn-dark d-flex align-items-center">
                                                <i class="fa fa-times"></i>
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
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header bg-white pt-4 px-4 border-0">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ request('status') == 'all' ? 'active' : '' }}" 
               href="{{ route('dashboard.events.ticket', ['status' => 'all', 'event' => $eventName, 'search' => request('search')]) }}">
                All 
                <span class="badge rounded-pill bg-secondary ms-2 fs-10" style="vertical-align: middle;">
                    {{ $counts['all'] ?? 0 }}
                </span>
            </a>
        </li>

        @foreach(['pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger'] as $status => $color)
            <li class="nav-item">
                <a class="nav-link {{ request('status', 'pending') == $status && request('status') != 'all' ? 'active' : '' }}" 
                   href="{{ route('dashboard.events.ticket', ['status' => $status, 'event' => $eventName, 'search' => request('search')]) }}">
                   {{ ucfirst($status) }} 
                   <span class="badge rounded-pill bg-{{ $color }} ms-2 fs-10" style="vertical-align: middle;">
                       {{ $counts[$status] ?? 0 }}
                   </span>
                </a>
            </li>
        @endforeach
    </ul>
</div>
                    
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="ticketTable" style="min-width: 1200px;">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="80">View</th>
                                        <th>Athlete Details</th>
                                        <th>BIB Number</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Event / Category</th>
                                        <th>Registration Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customers as $customer)
                                    <tr>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-light btn-sm border view-details" 
                                                data-bs-toggle="modal" data-bs-target="#ticketDetailsModal" 
                                                data-info="{{ json_encode($customer) }}" 
                                                data-image="{{ $customer->transaction_id ? asset('uploads/payments/' . $customer->transaction_id) : asset('images/no-image.png') }}">
                                                <i class="fa fa-eye text-primary"></i>
                                            </button>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold text-black">{{ $customer->athlete?->user?->full_name ?? 'Guest Runner' }}</span>
                                                <small class="text-muted">BIB: {{ $customer->bib_name }}</small>
                                            </div>
                                        </td>
                                        <td><span class="badge badge-outline-dark fs-14">{{ $customer->bib_number }}</span></td>
                                        <td>
                                            @php $badgeClass = match($customer->status) { 'approved' => 'success', 'rejected' => 'danger', default => 'warning' }; @endphp
                                            <span class="badge badge-{{ $badgeClass }} text-dark badge-sm">{{ ucfirst($customer->status) }}</span>
                                        </td>
                                        <td><span class="fw-bold text-dark">{{ number_format($customer->price) }} <small>MMK</small></span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="me-2 rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                                    <i class="fa fa-running text-primary fs-12"></i>
                                                </div>
                                                <div>
                                                    <span class="d-block fw-bold fs-14">{{ $customer->event }}</span>
                                                    <small class="text-muted">{{ $customer->category }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $customer->created_at->format('M d, Y') }}<br><small class="text-muted">{{ $customer->created_at->format('h:i A') }}</small></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="py-4">
                                                <img src="{{ asset('images/no-data.png') }}" alt="" style="width: 60px; opacity: 0.3;">
                                                <p class="mt-3 text-muted">No {{ request('status', 'pending') }} tickets found.</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-0 px-4 py-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted fs-13">
                                Showing {{ $customers->firstItem() }} to {{ $customers->lastItem() }} of <strong>{{ $customers->total() }}</strong>
                            </div>
                            <div>
                                {{ $customers->links('pagination::bootstrap-5') }}
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
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0">
                <h4 class="modal-title fw-bold">Runner Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/dashboard/update-ticket-info" method="POST" id="update-ticket-form">
                @csrf
                <input type="hidden" name="id" id="modal-ticket-id-input">
                <div class="modal-body px-4 pt-0">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="info-section">
                                <span class="section-title"><i class="fa fa-user-circle me-1"></i> Identity Info</span>
                                <table class="table table-sm custom-table mb-0">
                                    <tr><td>Full Name</td> <td id="modal-name-container"></td></tr>
                                    <tr><td>Bib Name</td> <td id="modal-bib-container"></td></tr>
                                    <tr><td>BIB Number</td> <td id="modal-bib-number" class="text-primary fw-bold"></td></tr>
                                    <tr><td>T-Shirt</td> <td id="modal-tshirt-container"></td></tr>
                                    <tr><td>ID Type</td> <td id="modal-nat" class="text-capitalize"></td></tr>
                                    <tr><td>ID / NRC</td> <td><div id="modal-id-container" class="nrc-group"></div></td></tr>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="info-section">
                                <span class="section-title"><i class="fa fa-flag me-1"></i> Participation Details</span>
                                <table class="table table-sm custom-table mb-0">
                                    <tr><td>Event</td> <td id="modal-event" class="fw-bold text-black"></td></tr>
                                    <tr><td>Category</td> <td id="modal-category"></td></tr>
                                    <tr><td>Level</td> <td id="modal-exp"></td></tr>
                                    <tr><td>Price</td> <td id="modal-price" class="fw-bold text-success"></td></tr>
                                    <tr><td>Blood Type</td> <td id="modal-blood" class="text-danger fw-bold"></td></tr>
                                    <tr><td>Region</td> <td id="modal-state" class="text-capitalize"></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <small class="text-muted d-block mb-1 text-uppercase fw-bold fs-10">Medical Information</small>
                                <div id="modal-medical" class="text-dark small"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <small class="text-muted d-block mb-1 text-uppercase fw-bold fs-10">ITRA Details</small>
                                <div id="modal-itra-container" class="text-dark small"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="text-center p-4 bg-light rounded-4 border-dashed border-2">
                            <h6 class="fw-bold mb-3"><i class="fa fa-receipt me-1 text-success"></i> Payment Receipt</h6>
                            <img id="modal-transaction-img" src="" alt="Proof" class="img-fluid shadow-sm" style="max-height: 350px; cursor: zoom-in;" onclick="window.open(this.src)">
                            <p class="text-muted small mt-3 mb-0">Click the image to expand to full size</p>
                        </div>
                    </div>

                    <div class="mt-4 d-none" id="modal-save-button-container">
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 shadow-sm fw-bold">
                            <i class="fa fa-save me-2"></i>SAVE UPDATED INFORMATION
                        </button>
                    </div>
                </div>
            </form>
            <div class="modal-footer border-0 bg-light p-4">
    <button type="button" class="btn btn-outline-secondary px-4 py-2" data-bs-dismiss="modal">Close</button>
    
    {{-- Check the role directly from the guard --}}
    @if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->role === 'super_admin')
    <div class="ms-auto d-flex gap-2" id="modal-action-buttons">
        <form id="reject-form" action="" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger px-4 py-2 rounded-2">
                <i class="fa fa-times me-1"></i> Reject
            </button>
        </form>
        <form id="approve-form" action="" method="POST">
            @csrf
            <button type="submit" class="btn btn-success px-4 py-2 rounded-2">
                <i class="fa fa-check me-1"></i> Approve Payment
            </button>
        </form>
    </div>
    @endif
</div>
        </div>
    </div>
</div>

<script>
// All existing JavaScript logic preserved exactly as requested
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
                    tr.innerHTML = `<td colspan="8" class="text-center p-5"><div class="text-muted">No results found for "${searchTerm}"</div></td>`;
                    ticketTable.querySelector('tbody').appendChild(tr);
                }
            } else if (noResRow) noResRow.remove();
        });
    }

    const detailButtons = document.querySelectorAll('.view-details');
    detailButtons.forEach(button => {
        button.addEventListener('click', function () {
            const data = JSON.parse(this.getAttribute('data-info'));
            const transactionImageUrl = this.getAttribute('data-image');
            const athlete = data.athlete || {};
            const fullName = [athlete.first_name, athlete.middle_name, athlete.last_name].filter(Boolean).join(' ') || 'Guest Runner';
            
            document.getElementById('modal-ticket-id-input').value = data.id;
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
                
                // Editable Full Name (Sends as full_name)
                document.getElementById('modal-name-container').innerHTML = `
                    <input type="text" name="full_name" class="form-control form-control-sm border-primary" value="${fullName}" placeholder="Enter Full Name">
                `;

                // Editable ITRA Details
                document.getElementById('modal-itra-container').innerHTML = `
                    <textarea name="itra_details" class="form-control form-control-sm border-primary" rows="2" placeholder="ITRA Score or Link">${athlete.itra_details || ''}</textarea>
                `;

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
                document.getElementById('modal-name-container').innerHTML = `<span class="fw-bold text-black">${fullName}</span>`;
                document.getElementById('modal-itra-container').innerHTML = athlete.itra_details || 'None';
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