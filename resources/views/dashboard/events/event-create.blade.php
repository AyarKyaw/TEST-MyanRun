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
                                    <input type="date" 
                                        name="date" 
                                        class="form-control native-datepicker" 
                                        style="position: relative; z-index: 5;" 
                                        required>
                                    <small class="text-muted">Today is: {{ date('d M, Y') }}</small>
                                </div>

                                <div class="mb-3 col-md-6">
                                    <label class="form-label text-black fw-bold">YouTube Video ID</label>
                                    <input type="text" name="video_url" class="form-control" placeholder="e.g. K_FvDL_anrs">
                                    <small class="text-muted">The ID after <strong>v=</strong> in the URL</small>
                                </div>

                                <div class="mb-4 col-md-12">
                                    <label class="form-label text-black fw-bold">Event Banner / Image</label>
                                    <div class="form-file">
                                        <input type="file" name="image" class="form-file-input form-control" required>
                                    </div>
                                    <small class="text-danger">Recommended size: 800x400px (JPG, PNG)</small>
                                </div>
                            </div>

                            <div class="col-sm-6 m-b30">
                                <label class="form-label text-black fw-bold">Display Status</label>
                                <select name="is_active" class="form-control default-select">
                                    <option value="2">Coming Event</option>
                                    <option value="1">Now Event (Live)</option>
                                    <option value="0">Past Event (Archived)</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label text-black fw-bold">Event Description</label>
                                <textarea name="description" class="form-control" rows="5" placeholder="Describe the event details, rules, or prizes..."></textarea>
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
@endsection