<div class="col-xl-4 col-md-6 mb-4">
    <div class="card dinner-card h-100">
        <img src="{{ asset('storage/' . $dinner->image_path) }}" 
             class="card-img-top dinner-card-img" 
             alt="{{ $dinner->name }}"
             onerror="this.src='{{ asset('images/default-dinner.jpg') }}'">
        
        <div class="card-body d-flex flex-column">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <span class="badge badge-date px-3 py-2">
                    <i class="far fa-calendar-alt mr-1"></i> 
                    {{ \Carbon\Carbon::parse($dinner->date)->format('d M Y') }}
                </span>
                <span class="badge badge-success px-3 py-2">
                    {{ number_format($dinner->total_balance ?? 0) }} <small>MMK</small>
                </span>
            </div>

            <h4 class="card-title font-weight-bold mb-1">{{ $dinner->name }}</h4>
            <p class="text-primary small font-weight-bold mb-3">
                <i class="fas fa-building mr-1"></i> {{ $dinner->company }}
            </p>

            <div class="bg-light p-3 rounded mb-4 mt-auto">
                {{-- Public Seats Remaining --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted stat-label">
                        <i class="fas fa-users mr-1"></i> Public Seats
                    </span>
                    <span class="h6 mb-0 font-weight-bold">
                        @php
                            $publicLeft = ($dinner->public_capacity ?? 0) - ($dinner->public_seats_count ?? 0);
                        @endphp
                        <span class="{{ $publicLeft <= 0 ? 'text-danger' : 'text-success' }}">
                            {{ $publicLeft }} Left
                        </span>
                        <small class="text-muted">/ {{ $dinner->public_capacity ?? 0 }}</small>
                    </span>
                </div>

                {{-- Sponsor Seats Remaining --}}
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-info stat-label">
                        <i class="fas fa-handshake mr-1"></i> Sponsor Seats
                    </span>
                    <span class="h6 mb-0 font-weight-bold">
                        @php
                            $sponsorLeft = $dinner->sponsor_capacity - ($dinner->sponsor_seats_count ?? 0);
                        @endphp
                        <span class="{{ $sponsorLeft <= 0 ? 'text-danger' : 'text-info' }}">
                            {{ $dinner->sponsor_capacity }} Left
                        </span>
                        <small class="text-muted">/ {{ $dinner->sponsor_capacity}}</small>
                    </span>
                </div>

                <div style="height: 1px; background: #e2e8f0; margin: 8px 0;"></div>

                {{-- Pending Count --}}
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-warning stat-label">
                        <i class="fas fa-clock mr-1"></i> Pending
                    </span>
                    <span class="h6 mb-0 font-weight-bold text-dark">{{ $dinner->pending_count ?? 0 }}</span>
                </div>
            </div>

            <a href="{{ route('admin.dinner.tickets.show', $dinner->id) }}" class="btn btn-primary btn-block font-weight-bold py-3">
                VIEW ATTENDEES <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>