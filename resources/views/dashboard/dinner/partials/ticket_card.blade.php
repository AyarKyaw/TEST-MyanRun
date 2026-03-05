<div class="col-xl-4 col-md-6 mb-4">
    <div class="card dinner-card h-100">
        <img src="{{ asset('storage/' . $dinner->image_path) }}" 
             class="card-img-top dinner-card-img" 
             alt="{{ $dinner->name }}"
             onerror="this.src='{{ asset('images/default-dinner.jpg') }}'">
        
        <div class="card-body d-flex flex-column">
            <div class="mb-2">
                <span class="badge badge-date px-3 py-2">
                    <i class="far fa-calendar-alt mr-1"></i> 
                    {{ \Carbon\Carbon::parse($dinner->date)->format('d M Y') }}
                </span>
            </div>

            <h4 class="card-title font-weight-bold mb-1">{{ $dinner->name }}</h4>
            <p class="text-primary small font-weight-bold mb-3">
                <i class="fas fa-building mr-1"></i> {{ $dinner->company }}
            </p>

            <div class="bg-light p-3 rounded mb-4 mt-auto">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-success stat-label">
                        <i class="fas fa-check-circle mr-1"></i> Sold / Confirmed
                    </span>
                    <span class="h6 mb-0 font-weight-bold text-dark">{{ $dinner->confirmed_count }}</span>
                </div>
                <div style="height: 1px; background: #e2e8f0; margin: 8px 0;"></div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-warning stat-label">
                        <i class="fas fa-clock mr-1"></i> Pending Approval
                    </span>
                    <span class="h6 mb-0 font-weight-bold text-dark">{{ $dinner->pending_count }}</span>
                </div>
            </div>

            <a href="{{ route('admin.dinner.tickets.show', $dinner->id) }}" class="btn btn-primary btn-block font-weight-bold py-3">
                VIEW ATTENDEES <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</div>