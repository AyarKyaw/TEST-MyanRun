@extends('dashboard.layouts.master')
@section('content')

<div class="content-body">
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.home-settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- 1. HERO BANNERS MANAGE BLOCK --}}
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="card border-primary">
                        <div class="card-header bg-primary py-2">
                            <h4 class="card-title text-white">Homepage Hero Banners</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">Upload visual backdrop slices cascading through your frontend hero layout carousel viewport grids.</p>
                            
                            <div id="banner-repeater">
                                @forelse($banners as $index => $banner)
                                <div class="card border mb-3 bg-light banner-row">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-md-11 mb-2">
                                                <label class="form-label fw-bold text-dark">Banner Slide Background</label>
                                                @if(!empty($banner['image_path']))
                                                    <div class="mb-2">
                                                        <img src="{{ asset($banner['image_path']) }}" alt="Banner preview" class="img-thumbnail" style="max-height: 80px;">
                                                    </div>
                                                @endif
                                                <input type="file" name="banner_images[{{ $index }}]" class="form-control">
                                                <input type="hidden" name="existing_banner_images[{{ $index }}]" value="{{ $banner['image_path'] ?? '' }}">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end mb-2">
                                                <button type="button" class="btn btn-danger w-100 remove-banner-btn">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="card border mb-3 bg-light banner-row">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-md-11 mb-2">
                                                <label class="form-label fw-bold text-dark">Banner Slide Background</label>
                                                <input type="file" name="banner_images[0]" class="form-control" required>
                                                <input type="hidden" name="existing_banner_images[0]" value="">
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end mb-2">
                                                <button type="button" class="btn btn-danger w-100 remove-banner-btn">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforelse
                            </div>

                            <button type="button" id="add-banner-btn" class="btn btn-info btn-sm mt-1">
                                <i class="fa fa-plus"></i> Add New Carousel Slide Banner
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- NEW COMPONENT: 2. EVENT TICKETS MANAGE BLOCK --}}
            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="card border-success">
                        <div class="card-header bg-success py-2">
                            <h4 class="card-title text-white">Event Duathlon Tickets</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">Manage dynamic card structures containing categorization, metrics, scheduling, and monetary rules across layouts.</p>
                            
                            <div id="ticket-repeater">
                                {{-- Assumes $tickets variable is sent from your SettingsController --}}
                                @forelse($tickets ?? [] as $index => $ticket)
                                <div class="card border mb-3 bg-light ticket-row">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <!-- Ticket Image/Photo Info -->
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-dark">Ticket Image / Icon</label>
                                                @if(!empty($ticket['image_path']))
                                                    <div class="mb-2">
                                                        <img src="{{ asset($ticket['image_path']) }}" alt="Ticket preview" class="img-thumbnail" style="max-height: 80px;">
                                                    </div>
                                                @endif
                                                <input type="file" name="ticket_images[{{ $index }}]" class="form-control">
                                                <input type="hidden" name="existing_ticket_images[{{ $index }}]" value="{{ $ticket['image_path'] ?? '' }}">
                                            </div>

                                            <!-- Ticket Meta Fields Group -->
                                            <div class="col-md-7">
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label text-dark small fw-bold">Ticket Title / Category</label>
                                                        <input type="text" name="ticket_titles[{{ $index }}]" class="form-control form-control-sm" value="{{ $ticket['title'] ?? '' }}" placeholder="e.g., Amateur (Local)" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label text-dark small fw-bold">Location</label>
                                                        <input type="text" name="ticket_locations[{{ $index }}]" class="form-control form-control-sm" value="{{ $ticket['location'] ?? '' }}" placeholder="e.g., Mandalay, Myanmar" required>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label text-dark small fw-bold">Date</label>
                                                        <input type="date" name="ticket_dates[{{ $index }}]" class="form-control form-control-sm" value="{{ $ticket['date'] ?? '' }}" required>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label text-dark small fw-bold">Time</label>
                                                        <input type="time" name="ticket_times[{{ $index }}]" class="form-control form-control-sm" value="{{ $ticket['time'] ?? '' }}" required>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label text-dark small fw-bold">Price (MMK / USD)</label>
                                                        <input type="text" name="ticket_prices[{{ $index }}]" class="form-control form-control-sm" value="{{ $ticket['price'] ?? '' }}" placeholder="e.g., 120,000 MMK" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Remove Action Button -->
                                            <div class="col-md-1 d-flex align-items-end mb-3">
                                                <button type="button" class="btn btn-danger w-100 remove-ticket-btn">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                {{-- Default skeleton placeholder row if data array is empty --}}
                                <div class="card border mb-3 bg-light ticket-row">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label fw-bold text-dark">Ticket Image / Icon</label>
                                                <input type="file" name="ticket_images[0]" class="form-control" required>
                                                <input type="hidden" name="existing_ticket_images[0]" value="">
                                            </div>
                                            <div class="col-md-7">
                                                <div class="row">
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label text-dark small fw-bold">Ticket Title / Category</label>
                                                        <input type="text" name="ticket_titles[0]" class="form-control form-control-sm" placeholder="e.g., Amateur (Local)" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label text-dark small fw-bold">Location</label>
                                                        <input type="text" name="ticket_locations[0]" class="form-control form-control-sm" placeholder="e.g., Mandalay, Myanmar" required>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label text-dark small fw-bold">Date</label>
                                                        <input type="date" name="ticket_dates[0]" class="form-control form-control-sm" required>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label text-dark small fw-bold">Time</label>
                                                        <input type="time" name="ticket_times[0]" class="form-control form-control-sm" required>
                                                    </div>
                                                    <div class="col-md-4 mb-2">
                                                        <label class="form-label text-dark small fw-bold">Price (MMK / USD)</label>
                                                        <input type="text" name="ticket_prices[0]" class="form-control form-control-sm" placeholder="e.g., 120,000 MMK" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1 d-flex align-items-end mb-3">
                                                <button type="button" class="btn btn-danger w-100 remove-ticket-btn">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforelse
                            </div>

                            <button type="button" id="add-ticket-btn" class="btn btn-success btn-sm mt-1">
                                <i class="fa fa-plus"></i> Add New Event Ticket Tier
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. INTERACTIVE SAVE ACTION BUTTON --}}
            <div class="row mb-5">
                <div class="col-12 text-start">
                    <button type="submit" class="btn btn-primary px-5 btn-lg shadow-sm">
                        <i class="fa fa-save me-2"></i> Commit Homepage Configuration Updates
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // ----------------------------------------------------
    // HERO CAROUSEL REPEATER INTERACTION DOM CONTROLS
    // ----------------------------------------------------
    document.getElementById('add-banner-btn').addEventListener('click', function() {
        const container = document.getElementById('banner-repeater');
        const uniqueIndex = document.querySelectorAll('.banner-row').length;

        const newCard = document.createElement('div');
        newCard.className = 'card border mb-3 bg-light banner-row';
        newCard.innerHTML = `
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-11 mb-2">
                        <label class="form-label fw-bold text-dark">Banner Slide Background</label>
                        <input type="file" name="banner_images[${uniqueIndex}]" class="form-control" required>
                        <input type="hidden" name="existing_banner_images[${uniqueIndex}]" value="">
                    </div>
                    <div class="col-md-1 d-flex align-items-end mb-2">
                        <button type="button" class="btn btn-danger w-100 remove-banner-btn"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(newCard);
    });

    document.getElementById('banner-repeater').addEventListener('click', function(e) {
        if (e.target.closest('.remove-banner-btn')) {
            const rows = document.querySelectorAll('.banner-row');
            if (rows.length > 1) {
                e.target.closest('.banner-row').remove();
                reindexBannerRows();
            } else {
                alert('At least one operational hero banner asset config mapping is required.');
            }
        }
    });

    function reindexBannerRows() {
        document.querySelectorAll('.banner-row').forEach((row, index) => {
            const fileInput = row.querySelector('input[type="file"]');
            const hiddenInput = row.querySelector('input[type="hidden"]');
            if(fileInput) fileInput.setAttribute('name', `banner_images[${index}]`);
            if(hiddenInput) hiddenInput.setAttribute('name', `existing_banner_images[${index}]`);
        });
    }

    // ----------------------------------------------------
    // EVENT TICKET REPEATER INTERACTION DOM CONTROLS
    // ----------------------------------------------------
    document.getElementById('add-ticket-btn').addEventListener('click', function() {
        const container = document.getElementById('ticket-repeater');
        const uniqueIndex = document.querySelectorAll('.ticket-row').length;

        const newCard = document.createElement('div');
        newCard.className = 'card border mb-3 bg-light ticket-row';
        newCard.innerHTML = `
            <div class="card-body p-3">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold text-dark">Ticket Image / Icon</label>
                        <input type="file" name="ticket_images[${uniqueIndex}]" class="form-control" required>
                        <input type="hidden" name="existing_ticket_images[${uniqueIndex}]" value="">
                    </div>
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label text-dark small fw-bold">Ticket Title / Category</label>
                                <input type="text" name="ticket_titles[${uniqueIndex}]" class="form-control form-control-sm" placeholder="e.g., Amateur (Local)" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label text-dark small fw-bold">Location</label>
                                <input type="text" name="ticket_locations[${uniqueIndex}]" class="form-control form-control-sm" placeholder="e.g., Mandalay, Myanmar" required>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label text-dark small fw-bold">Date</label>
                                <input type="date" name="ticket_dates[${uniqueIndex}]" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label text-dark small fw-bold">Time</label>
                                <input type="time" name="ticket_times[${uniqueIndex}]" class="form-control form-control-sm" required>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="form-label text-dark small fw-bold">Price (MMK / USD)</label>
                                <input type="text" name="ticket_prices[${uniqueIndex}]" class="form-control form-control-sm" placeholder="e.g., 120,000 MMK" required>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-1 d-flex align-items-end mb-3">
                        <button type="button" class="btn btn-danger w-100 remove-ticket-btn"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
            </div>
        `;
        container.appendChild(newCard);
    });

    document.getElementById('ticket-repeater').addEventListener('click', function(e) {
        if (e.target.closest('.remove-ticket-btn')) {
            const rows = document.querySelectorAll('.ticket-row');
            if (rows.length > 1) {
                e.target.closest('.ticket-row').remove();
                reindexTicketRows();
            } else {
                alert('At least one operational event ticket mapping configuration is required.');
            }
        }
    });

    function reindexTicketRows() {
        document.querySelectorAll('.ticket-row').forEach((row, index) => {
            if(row.querySelector('input[type="file"]')) row.querySelector('input[type="file"]').setAttribute('name', `ticket_images[${index}]`);
            if(row.querySelector('input[type="hidden"]')) row.querySelector('input[type="hidden"]').setAttribute('name', `existing_ticket_images[${index}]`);
            if(row.querySelector('input[name^="ticket_titles"]')) row.querySelector('input[name^="ticket_titles"]').setAttribute('name', `ticket_titles[${index}]`);
            if(row.querySelector('input[name^="ticket_locations"]')) row.querySelector('input[name^="ticket_locations"]').setAttribute('name', `ticket_locations[${index}]`);
            if(row.querySelector('input[name^="ticket_dates"]')) row.querySelector('input[name^="ticket_dates"]').setAttribute('name', `ticket_dates[${index}]`);
            if(row.querySelector('input[name^="ticket_times"]')) row.querySelector('input[name^="ticket_times"]').setAttribute('name', `ticket_times[${index}]`);
            if(row.querySelector('input[name^="ticket_prices"]')) row.querySelector('input[name^="ticket_prices"]').setAttribute('name', `ticket_prices[${index}]`);
        });
    }
</script>

@endsection