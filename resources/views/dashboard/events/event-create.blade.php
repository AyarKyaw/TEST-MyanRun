@extends('dashboard.layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-title">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('events.index', 'coming') }}">Events</a></li>
                    <li class="breadcrumb-item active">Add New Event</li>
                </ol>
            </nav>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-xl-8 col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Event Details</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label class="form-label text-black fw-bold">Event Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Cherry Run 2026" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Company / Organizer</label>
                                    <input type="text" name="company" class="form-control" placeholder="e.g. Myanmar Runners Association" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Location</label>
                                    <input type="text" name="location" class="form-control" placeholder="e.g. Inya Lake, Yangon">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Event Date</label>
                                    <input type="date" name="date" class="form-control native-datepicker" style="position: relative; z-index: 5;" required>
                                    <small class="text-muted">Today is: {{ date('d M, Y') }}</small>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">YouTube Video ID</label>
                                    <input type="text" name="video_url" class="form-control" placeholder="e.g. K_FvDL_anrs">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Registration Limit</label>
                                    <select name="ticket_limit_type" id="ticket_limit_type" class="form-control default-select" onchange="toggleLimitInput()">
                                        <option value="unlimited">Unlimited Tickets</option>
                                        <option value="limited">Limited Tickets</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-md-6" id="total_limit_container" style="display: none;">
                                    <label class="form-label text-black fw-bold">Total Event Capacity</label>
                                    <input type="number" name="total_max_slots" class="form-control" placeholder="Total tickets for whole event">
                                    <small class="text-muted">Max participants across all ticket types</small>
                                </div>

                                <div class="mb-4 col-md-12">
                                    <label class="form-label text-black fw-bold">Event Banner / Image</label>
                                    <div class="form-file">
                                        <input type="file" name="image" class="form-file-input form-control" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6 m-b30">
                                    <label class="form-label text-black fw-bold">Display Status</label>
                                    <select name="is_active" class="form-control default-select">
                                        <option value="2">Coming Event</option>
                                        <option value="1">Now Event (Live)</option>
                                        <option value="0">Past Event (Archived)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group mb-3">
    <label class="form-label font-w600">Assign Event Admins</label>
    <select name="admin_ids[]" class="form-control default-select" multiple>
        @foreach($eventAdmins as $admin)
            <option value="{{ $admin->id }}">{{ $admin->email }}</option>
        @endforeach
    </select>
    <small class="text-muted">Selected admins will be able to view and download reports for this event.</small>
</div>

                            <div class="mb-3 col-md-12">
                                <label class="form-label text-black fw-bold">Event Description</label>
                                <textarea name="description" class="form-control" rows="5" placeholder="Describe the event..."></textarea>
                            </div>
                            
                            <hr class="mt-4 mb-4">

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="text-black fw-bold mb-0">Ticket Types</h5>
                                <button type="button" onclick="addTicket()" class="btn btn-sm btn-dark">+ Add Ticket</button>
                            </div>

                            <div id="ticket-types">
                                <div class="card shadow-sm mb-3 p-3 ticket-row border-0">
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">Name</label>
                                            <input type="text" name="tickets[0][name]" class="form-control" placeholder="10KM Solo">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">Type</label>
                                            <select name="tickets[0][type]" class="form-control">
                                                <option value="solo">Solo</option>
                                                <option value="relay">Relay</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">National Image</label>
                                            <input type="file" name="tickets[0][national_image]" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">Foreign Image</label>
                                            <input type="file" name="tickets[0][foreign_image]" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">National (MMK)</label>
                                            <input type="number" name="tickets[0][national_price]" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">Foreign (MMK)</label>
                                            <input type="number" name="tickets[0][foreign_price]" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">Slots (Optional)</label>
                                            <input type="number" name="tickets[0][max_slots]" class="form-control" placeholder="Type Limit">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">Prefix</label>
                                            <input type="text" name="tickets[0][prefix]" class="form-control" placeholder="RUN">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">Start Number</label>
                                            <input type="number" name="tickets[0][start_number]" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">Category</label>
                                            <input type="text" name="tickets[0][category]" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary px-5">Save Event</button>
                                <a href="{{ route('events.index', 'coming') }}" class="btn btn-light ms-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let ticketIndex = 1;

function toggleLimitInput() {
    const type = document.getElementById('ticket_limit_type').value;
    const container = document.getElementById('total_limit_container');
    if(type === 'limited') {
        container.style.display = 'block';
    } else {
        container.style.display = 'none';
    }
}

function addTicket() {
    const container = document.getElementById('ticket-types');
    const html = `
        <div class="card shadow-sm mb-3 p-3 ticket-row border-0">
            <div class="row g-3 align-items-end">
                <div class="col-md-3"><label class="form-label small fw-bold">Name</label><input type="text" name="tickets[${ticketIndex}][name]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Type</label><select name="tickets[${ticketIndex}][type]" class="form-control"><option value="solo">Solo</option><option value="relay">Relay</option></select></div>
                <div class="col-md-3"><label class="form-label small fw-bold">National Image</label><input type="file" name="tickets[${ticketIndex}][national_image]" class="form-control"></div>
                <div class="col-md-3"><label class="form-label small fw-bold">Foreign Image</label><input type="file" name="tickets[${ticketIndex}][foreign_image]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">National (MMK)</label><input type="number" name="tickets[${ticketIndex}][national_price]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Foreign (MMK)</label><input type="number" name="tickets[${ticketIndex}][foreign_price]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Slots (Optional)</label><input type="number" name="tickets[${ticketIndex}][max_slots]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Prefix</label><input type="text" name="tickets[${ticketIndex}][prefix]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Start Number</label><input type="number" name="tickets[${ticketIndex}][start_number]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Category</label><input type="text" name="tickets[${ticketIndex}][category]" class="form-control"></div>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    ticketIndex++;
}
</script>
@endsection