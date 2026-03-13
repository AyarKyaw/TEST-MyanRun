@extends('dashboard.layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-8 col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Dinner: {{ $dinner->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.dinner.update', $dinner->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Dinner Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $dinner->name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Company</label>
                            <input type="text" name="company" class="form-control" value="{{ old('company', $dinner->company) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Location / Venue</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" name="location" class="form-control" 
                                    placeholder="e.g. Lotte Hotel, Yangon" 
                                    value="{{ old('location', $dinner->location) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Date</label>
                                    <input type="date" name="date" class="form-control" value="{{ $dinner->date ? $dinner->date->format('Y-m-d') : '' }}" style="position: relative; z-index: 5;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label font-weight-bold">Status</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1" {{ $dinner->is_active == 1 ? 'selected' : '' }}>ACTIVE</option>
                                        <option value="0" {{ $dinner->is_active == 0 ? 'selected' : '' }}>PAST</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- SEAT CAPACITIES SECTION --}}
                        {{-- SEAT CAPACITIES SECTION --}}
<div class="p-3 rounded mb-4 border" style="background-color: #f8fafc;">
    <label class="form-label font-weight-bold text-primary mb-3">
        <i class="fas fa-chair mr-1"></i> Capacity Management
    </label>
    <div class="row">
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label small font-weight-bold">Public Capacity</label>
                <input type="number" name="public_capacity" id="public_capacity" class="form-control cap-calc" 
                    value="{{ old('public_capacity', $dinner->public_capacity) }}" min="0">
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label small font-weight-bold">Sponsor Capacity</label>
                <input type="number" name="sponsor_capacity" id="sponsor_capacity" class="form-control cap-calc" 
                    value="{{ old('sponsor_capacity', $dinner->sponsor_capacity) }}" min="0">
            </div>
        </div>
        <div class="col-md-4">
            <div class="mb-3">
                <label class="form-label small font-weight-bold">Total Capacity (DB: capacity)</label>
                {{-- Note: name="capacity" maps to your database column --}}
                <input type="number" id="total_capacity" class="form-control bg-white" 
                    value="{{ old('capacity', $dinner->capacity) }}" readonly>
                <small class="text-muted font-italic">Calculated automatically</small>
            </div>
        </div>
    </div>
</div>

                        <hr class="my-4">

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label font-weight-bold">Card Display Image (Thumbnail)</label>
                                <div class="mb-2">
                                    @if($dinner->image_path)
                                        <img src="{{ asset('storage/' . $dinner->image_path) }}" width="120" class="rounded shadow-sm border">
                                    @endif
                                </div>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label font-weight-bold">Detailed Info Image (Poster)</label>
                                <div class="mb-2">
                                    @if($dinner->info_image)
                                        <img src="{{ asset('storage/' . $dinner->info_image) }}" width="120" class="rounded shadow-sm border">
                                    @endif
                                </div>
                                <input type="file" name="info_image" class="form-control" accept="image/*">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4">Update Dinner</button>
                            <a href="{{ route('admin.dinner.manage', 'now') }}" class="btn btn-danger light ms-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Logic to auto-calculate total capacity
    const publicInput = document.getElementById('public_capacity');
    const sponsorInput = document.getElementById('sponsor_capacity');
    const totalInput = document.getElementById('total_capacity');

    function calculateTotal() {
        const publicVal = parseInt(publicInput.value) || 0;
        const sponsorVal = parseInt(sponsorInput.value) || 0;
        totalInput.value = publicVal + sponsorVal;
    }

    publicInput.addEventListener('input', calculateTotal);
    sponsorInput.addEventListener('input', calculateTotal);
</script>
@endpush
@endsection