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
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-xl-8">
                    <div class="card">
                        <div class="card-header"><h4 class="card-title">Event Details</h4></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label class="form-label text-black fw-bold">Event Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Cherry Run 2026" value="{{ old('name') }}" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Company / Organizer</label>
                                    <input type="text" name="company" class="form-control" placeholder="e.g. Myanmar Runners Association" value="{{ old('company') }}" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Location</label>
                                    <input type="text" name="location" class="form-control" placeholder="e.g. Kalaw, Shan State" value="{{ old('location') }}">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Event Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ old('date') }}" required>
                                    <small class="text-muted">Today is: {{ date('d M, Y') }}</small>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">YouTube Video ID</label>
                                    <input type="text" name="video_url" class="form-control" placeholder="e.g. K_FvDL_anrs" value="{{ old('video_url') }}">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Registration Limit</label>
                                    <select name="ticket_limit_type" id="ticket_limit_type" class="form-control default-select" onchange="toggleLimitInput()">
                                        <option value="unlimited" {{ old('ticket_limit_type') == 'unlimited' ? 'selected' : '' }}>Unlimited Tickets</option>
                                        <option value="limited" {{ old('ticket_limit_type') == 'limited' ? 'selected' : '' }}>Limited Tickets</option>
                                    </select>
                                </div>

                                <div class="mb-3 col-md-6" id="limit_input_container" style="{{ old('ticket_limit_type') == 'limited' ? '' : 'display: none;' }}">
                                    <label class="form-label text-black fw-bold">Total Event Capacity</label>
                                    <input type="number" name="total_max_slots" class="form-control" value="{{ old('total_max_slots') }}" placeholder="Enter total slots (e.g. 500)">
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-warning fw-bold">Global Early Bird Limit</label>
                                    <input type="number" name="early_bird_limit" class="form-control border-warning" value="{{ old('early_bird_limit') }}" placeholder="e.g. 200">
                                    <small class="text-muted">Applied to total runners across all ticket types.</small>
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label class="form-label text-black fw-bold">Event Description</label>
                                    <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label text-black fw-bold">Display Status</label>
                                <select name="is_active" class="form-control default-select">
                                    <option value="2">Coming Event</option>
                                    <option value="1">Now Event (Live)</option>
                                    <option value="0">Past Event (Archived)</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-black fw-bold">Event Banner</label>
                                <input type="file" name="image" class="form-control" required>
                                <small class="text-muted">High-quality cover image.</small>
                            </div>

                            <div class="mb-4">
                                <label class="form-label font-w600 text-black fw-bold">Assign Event Admins</label>
                                <select name="admin_ids[]" class="form-control default-select" multiple>
                                    @foreach($eventAdmins as $admin)
                                        <option value="{{ $admin->id }}">{{ $admin->email }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-2">Save Event</button>
                            <a href="{{ route('events.index', 'coming') }}" class="btn btn-light w-100">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Ticket Types</h4>
                    <button type="button" onclick="addTicket()" class="btn btn-sm btn-dark">+ Add New Ticket Type</button>
                </div>
                <div class="card-body">
                    <div id="ticket-types">
                        <div class="card shadow-sm mb-4 p-3 ticket-row border border-light">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Name</label>
                                    <input type="text" name="tickets[0][name]" class="form-control" placeholder="10KM Solo" required>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold text-danger">Gender BIB?</label>
                                    <select name="tickets[0][has_gender_bib]" class="form-control">
                                        <option value="0">No</option>
                                        <option value="1">Yes (M/F Prefix)</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold text-primary">Ticket PNG (Template)</label>
                                    <input type="file" name="tickets[0][ticket_png]" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Prefix</label>
                                    <input type="text" name="tickets[0][prefix]" class="form-control" placeholder="MCTR">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Start No.</label>
                                    <input type="number" name="tickets[0][start_number]" class="form-control" value="1">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label small fw-bold">Type</label>
                                    <select name="tickets[0][type]" class="form-control">
                                        <option value="solo">Solo</option>
                                        <option value="relay">Relay</option>
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">National (MMK)</label>
                                    <input type="number" name="tickets[0][national_price]" class="form-control" placeholder="35000">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Foreign (MMK)</label>
                                    <input type="number" name="tickets[0][foreign_price]" class="form-control" placeholder="50000">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold text-warning">Early Bird Limit</label>
                                    <input type="number" name="tickets[0][early_bird_limit]" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold text-warning">EB Discount</label>
                                    <input type="number" name="tickets[0][early_bird_discount]" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Slots</label>
                                    <input type="number" name="tickets[0][max_slots]" class="form-control">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Category</label>
                                    <input type="text" name="tickets[0][category]" class="form-control" placeholder="10K">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">National Display Image (Front Page)</label>
                                    <input type="file" name="tickets[0][national_image]" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Foreign Display Image (Front Page)</label>
                                    <input type="file" name="tickets[0][foreign_image]" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let ticketIndex = 1;

function addTicket() {
    const container = document.getElementById('ticket-types');
    const html = `
        <div class="card shadow-sm mb-4 p-3 ticket-row border border-primary bg-light">
            <div class="row g-3">
                <div class="col-md-3"><label class="form-label small fw-bold">Name</label><input type="text" name="tickets[${ticketIndex}][name]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold text-danger">Gender BIB?</label><select name="tickets[${ticketIndex}][has_gender_bib]" class="form-control"><option value="0">No</option><option value="1">Yes</option></select></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Ticket PNG</label><input type="file" name="tickets[${ticketIndex}][ticket_png]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Prefix</label><input type="text" name="tickets[${ticketIndex}][prefix]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Start No.</label><input type="number" name="tickets[${ticketIndex}][start_number]" class="form-control" value="1"></div>
                <div class="col-md-1"><label class="form-label small fw-bold">Type</label><select name="tickets[${ticketIndex}][type]" class="form-control"><option value="solo">Solo</option><option value="relay">Relay</option></select></div>
                
                <div class="col-md-2"><label class="form-label small fw-bold">National MMK</label><input type="number" name="tickets[${ticketIndex}][national_price]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Foreign MMK</label><input type="number" name="tickets[${ticketIndex}][foreign_price]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold text-warning">EB Limit</label><input type="number" name="tickets[${ticketIndex}][early_bird_limit]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold text-warning">EB Discount</label><input type="number" name="tickets[${ticketIndex}][early_bird_discount]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Slots</label><input type="number" name="tickets[${ticketIndex}][max_slots]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Category</label><input type="text" name="tickets[${ticketIndex}][category]" class="form-control"></div>
                
                <div class="col-md-6"><label class="form-label small fw-bold">National Display Image</label><input type="file" name="tickets[${ticketIndex}][national_image]" class="form-control"></div>
                <div class="col-md-6"><label class="form-label small fw-bold">Foreign Display Image</label><input type="file" name="tickets[${ticketIndex}][foreign_image]" class="form-control"></div>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    ticketIndex++;
}

function toggleLimitInput() {
    const type = document.getElementById('ticket_limit_type').value;
    const container = document.getElementById('limit_input_container');
    container.style.display = (type === 'limited') ? 'block' : 'none';
}
</script>
@endsection