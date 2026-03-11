@extends('dashboard.layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-title mb-5 d-flex justify-content-between align-items-center">
            <div>
                <h1 class="font-weight-bold text-dark">
                    {{ ucfirst($status) }} Sponsor Management
                </h1>
                <p class="text-muted">Viewing {{ $status }} event partners and their invitation quotas.</p>
            </div>
            <a href="{{ route('admin.sponsor.create') }}" class="btn btn-success shadow-sm" style="border-radius: 10px; font-weight: 700;">
                <i class="fas fa-plus mr-2"></i> ADD SPONSOR
            </a>
        </div>

        <div class="row">
            @forelse($sponsors as $sponsor)
                <div class="col-xl-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm border-0" style="border-radius: 15px; overflow: hidden; transition: transform 0.2s;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="bg-light p-3 rounded-circle">
                                    <i class="fas fa-building fa-2x {{ $status === 'now' ? 'text-success' : 'text-muted' }}"></i>
                                </div>
                                {{-- Show the Discount % instead of Tier --}}
                                <span class="badge badge-pill badge-outline-dark">
                                    {{ $sponsor->sponsorCode->discount ?? 0 }}% OFF
                                </span>
                            </div>

                            <h4 class="font-weight-bold text-dark mb-1">{{ $sponsor->company }}</h4>
                            <p class="text-muted small mb-3">
                                <i class="fas fa-user-tie mr-1"></i> {{ $sponsor->contact_name }} <br>
                                <i class="fas fa-phone-alt mr-1"></i> {{ $sponsor->phone }}
                            </p>

                            <div class="bg-light p-3 rounded mb-3">
                                @php
                                    // Get data from the related SponsorCode model
                                    $code = $sponsor->sponsorCode; // Assuming a hasOne relationship
                                    $max = $code->max_uses ?? 0;
                                    $used = $code->used_count ?? 0;
                                    $percent = ($max > 0) ? ($used / $max) * 100 : 0;
                                @endphp

                                <div class="d-flex justify-content-between small">
                                    <span class="text-muted">Invitation Quota:</span>
                                    <span class="font-weight-bold text-dark">{{ $sponsor->quantity }} Guests</span>
                                </div>
                                
                                <div class="progress mt-2" style="height: 10px; border-radius: 10px;">
                                    <div class="progress-bar {{ $percent >= 100 ? 'bg-danger' : 'bg-success' }}" 
                                         role="progressbar" 
                                         style="width: {{ $percent }}%" 
                                         aria-valuenow="{{ $percent }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between small mt-2">
                                    <span class="text-muted">Registered: <strong>{{ $used }}</strong></span>
                                    <span class="text-muted">Remaining: <strong>{{ max(0, $max - $used) }}</strong></span>
                                </div>
                            </div>

                            <div class="small text-center mb-3">
                                <code class="p-2 bg-white border rounded d-block" style="font-size: 1.1rem; letter-spacing: 2px;">
                                    {{ $code->code ?? 'NO CODE SET' }}
                                </code>
                            </div>

                            <hr>

                            {{-- Link to Batch Print/Details --}}
                            <a href="{{ route('admin.sponsor.batchPrint', $sponsor->id) }}" class="btn btn-primary">
                                <i class="fas fa-print mr-2"></i> Batch Print Tickets
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fas fa-folder-open fa-4x text-light mb-3"></i>
                    <h4 class="text-muted">No {{ $status }} sponsors found.</h4>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection