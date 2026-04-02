@extends('dashboard.layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-title">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('events.index', 'coming') }}">Events</a></li>
                    <li class="breadcrumb-item active">Edit Event</li>
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

        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="card">
                                <div class="card-header"><h4 class="card-title">Event Details</h4></div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label text-black fw-bold">Event Name</label>
                                            <input type="text" name="name" class="form-control" value="{{ old('name', $event->name) }}" required>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label text-black fw-bold">Company / Organizer</label>
                                            <input type="text" name="company" class="form-control" value="{{ old('company', $event->company) }}" required>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label text-black fw-bold">Location</label>
                                            <input type="text" name="location" class="form-control" value="{{ old('location', $event->location) }}">
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label text-black fw-bold">Event Date</label>
                                            <input type="date" name="date" class="form-control" value="{{ $event->date ? \Carbon\Carbon::parse($event->date)->format('Y-m-d') : '' }}" required>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label text-black fw-bold">YouTube Video ID</label>
                                            <input type="text" name="video_url" class="form-control" value="{{ old('video_url', $event->video_url) }}">
                                        </div>

                                        <div class="mb-3 col-md-12">
                                            <label class="form-label text-black fw-bold">Event Description</label>
                                            <textarea name="description" class="form-control" rows="5">{{ old('description', $event->description) }}</textarea>
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
                                            <option value="2" {{ $event->is_active == 2 ? 'selected' : '' }}>Coming Event</option>
                                            <option value="1" {{ $event->is_active == 1 ? 'selected' : '' }}>Now Event (Live)</option>
                                            <option value="0" {{ $event->is_active == 0 ? 'selected' : '' }}>Past Event (Archived)</option>
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="form-label text-black fw-bold">Event Banner</label>
                                        @if($event->image_path)
                                            <img src="{{ asset('storage/' . $event->image_path) }}" class="img-fluid rounded mb-2 shadow-sm" style="max-height: 150px; width: 100%; object-fit: cover;">
                                        @endif
                                        <input type="file" name="image" class="form-control">
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 mb-2">Update Event</button>
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
                                @foreach($event->ticketTypes as $index => $type)
                                <div class="card shadow-sm mb-4 p-3 ticket-row border border-light" id="ticket-row-{{ $type->id }}">
                                    <input type="hidden" name="tickets[{{ $index }}][id]" value="{{ $type->id }}">
                                    
                                    <div class="row g-3 align-items-end">
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold">Name</label>
                                            <input type="text" name="tickets[{{ $index }}][name]" class="form-control" value="{{ $type->name }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">Type</label>
                                            <select name="tickets[{{ $index }}][type]" class="form-control">
                                                <option value="solo" {{ $type->type == 'solo' ? 'selected' : '' }}>Solo</option>
                                                <option value="relay" {{ $type->type == 'relay' ? 'selected' : '' }}>Relay</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-primary">Update National Image</label>
                                            <input type="file" name="tickets[{{ $index }}][national_image]" class="form-control">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small fw-bold text-primary">Update Foreign Image</label>
                                            <input type="file" name="tickets[{{ $index }}][foreign_image]" class="form-control">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">National (MMK)</label>
                                            <input type="number" name="tickets[{{ $index }}][national_price]" class="form-control" value="{{ $type->national_price }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">Foreign (MMK)</label>
                                            <input type="number" name="tickets[{{ $index }}][foreign_price]" class="form-control" value="{{ $type->foreign_price }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">Slots</label>
                                            <input type="number" name="tickets[{{ $index }}][max_slots]" class="form-control" value="{{ $type->max_slots }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">Prefix</label>
                                            <input type="text" name="tickets[{{ $index }}][prefix]" class="form-control" value="{{ $type->prefix }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">Start No.</label>
                                            <input type="number" name="tickets[{{ $index }}][start_number]" class="form-control" value="{{ $type->start_number }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small fw-bold">Category</label>
                                            <input type="text" name="tickets[{{ $index }}][category]" class="form-control" value="{{ $type->category }}">
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Start index from the count of existing tickets
let ticketIndex = {{ $event->ticketTypes->count() }};

function addTicket() {
    const container = document.getElementById('ticket-types');
    const html = `
        <div class="card shadow-sm mb-4 p-3 ticket-row border border-primary bg-light">
            <div class="row g-3 align-items-end">
                <div class="col-md-3"><label class="form-label small fw-bold">Name</label><input type="text" name="tickets[${ticketIndex}][name]" class="form-control" placeholder="New Ticket Name"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Type</label><select name="tickets[${ticketIndex}][type]" class="form-control"><option value="solo">Solo</option><option value="relay">Relay</option></select></div>
                <div class="col-md-3"><label class="form-label small fw-bold">National Image</label><input type="file" name="tickets[${ticketIndex}][national_image]" class="form-control"></div>
                <div class="col-md-3"><label class="form-label small fw-bold">Foreign Image</label><input type="file" name="tickets[${ticketIndex}][foreign_image]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">National (MMK)</label><input type="number" name="tickets[${ticketIndex}][national_price]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Foreign (MMK)</label><input type="number" name="tickets[${ticketIndex}][foreign_price]" class="form-control"></div>
                <div class="col-md-2"><label class="form-label small fw-bold">Slots</label><input type="number" name="tickets[${ticketIndex}][max_slots]" class="form-control"></div>
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