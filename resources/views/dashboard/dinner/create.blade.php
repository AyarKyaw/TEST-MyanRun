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

                                <div class="mb-3 col-md-6">
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

                                <div class="mb-4 col-md-12">
                                    <label class="form-label text-black fw-bold">Dinner Image / Poster</label>
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

{{-- Auto-format Script --}}
<script>
    document.getElementById('dinner_date').addEventListener('input', function (e) {
        let input = e.target.value;
        
        // Remove all non-numeric characters
        let values = input.replace(/\D/g, '');
        
        // Apply mask
        let output = '';
        if (values.length > 0) {
            output += values.substring(0, 2);
            if (values.length > 2) {
                output += '/' + values.substring(2, 4);
            }
            if (values.length > 4) {
                output += '/' + values.substring(4, 8);
            }
        }
        
        e.target.value = output;
    });

    // Prevent backspace from getting stuck on slashes
    document.getElementById('dinner_date').addEventListener('keydown', function (e) {
        if (e.key === 'Backspace') {
            let input = e.target.value;
            if (input.endsWith('/')) {
                e.target.value = input.substring(0, input.length - 1);
            }
        }
    });
</script>
@endsection