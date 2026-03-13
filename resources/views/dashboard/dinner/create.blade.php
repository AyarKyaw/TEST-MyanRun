@extends('dashboard.layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-title">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dinner.manage', 'now') }}">Dinners</a></li>
                    <li class="breadcrumb-item active">Add New Dinner</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-xl-8 col-lg-10">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Dinner Details</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.dinner.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <label class="form-label text-black fw-bold">Dinner Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="e.g. Annual Gala Dinner 2026" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Company / Sponsor</label>
                                    <input type="text" name="company" class="form-control" placeholder="e.g. KBZ Bank" required>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">Location</label>
                                    <input type="text" name="location" class="form-control" placeholder="e.g. Lotte Hotel, Yangon" required>
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label class="form-label text-black fw-bold">Dinner Date (DD/MM/YYYY)</label>
                                    <input type="text" 
                                        name="date" 
                                        class="form-control" 
                                        placeholder="DD/MM/YYYY"
                                        id="dinner_date"
                                        maxlength="10"
                                        required>
                                    <small class="text-muted">Auto-formats as you type (Today: {{ date('d/m/Y') }})</small>
                                </div>

                                {{-- CAPACITY SECTION --}}
                                <div class="col-md-12">
                                    <div class="p-3 rounded mb-3 border bg-light">
                                        <label class="form-label fw-bold text-primary"><i class="fas fa-users-cog me-1"></i> Seat Capacity Management</label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold">Public Capacity</label>
                                                <input type="number" name="public_capacity" id="public_capacity" class="form-control cap-calc" placeholder="300" min="0" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold">Sponsor Capacity</label>
                                                <input type="number" name="sponsor_capacity" id="sponsor_capacity" class="form-control cap-calc" placeholder="100" min="0" required>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label small fw-bold">Total Capacity</label>
                                                <input type="number" name="capacity" id="total_capacity" class="form-control bg-white" placeholder="400" readonly>
                                                <small class="text-muted italic">Auto-calculated</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4 col-md-12">
                                    <label class="form-label text-black fw-bold">Dinner Image / Poster (Card Thumbnail)</label>
                                    <div class="form-file">
                                        <input type="file" name="image" class="form-file-input form-control" required>
                                    </div>
                                    <small class="text-danger">Recommended size: 800x400px (JPG, PNG)</small>
                                </div>

                                <div class="mb-3 col-md-12">
                                    <label class="form-label text-black fw-bold">Initial Status</label>
                                    <select name="is_active" class="form-control default-select">
                                        <option value="1">Active (Now Dinner)</option>
                                        <option value="0">Inactive (Past Dinner)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary px-5">Save Dinner</button>
                                <a href="{{ route('admin.dinner.manage', 'now') }}" class="btn btn-light ms-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Scripts --}}
<script>
    // 1. Date Auto-format Logic
    document.getElementById('dinner_date').addEventListener('input', function (e) {
        let input = e.target.value;
        let values = input.replace(/\D/g, '');
        let output = '';
        if (values.length > 0) {
            output += values.substring(0, 2);
            if (values.length > 2) { output += '/' + values.substring(2, 4); }
            if (values.length > 4) { output += '/' + values.substring(4, 8); }
        }
        e.target.value = output;
    });

    // 2. Capacity Calculation Logic
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
@endsection