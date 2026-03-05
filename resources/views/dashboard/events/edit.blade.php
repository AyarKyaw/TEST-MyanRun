@extends('dashboard.layouts.master')

@section('content')
<style>
    /* 1. Fix the "Hidden Top" by adding more padding-top for the Header */
    /* 2. Push content to the right to clear the Sidebar */
    .main-dashboard-content {
        margin-left: 260px; /* Sidebar Width */
        padding-top: 90px;  /* Space for Top Header - Adjust if header is taller */
        padding-left: 30px;
        padding-right: 30px;
        padding-bottom: 50px;
        min-height: 100vh;
        background-color: #f8fafc;
        position: relative;
    }

    .edit-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        background: #fff;
    }

    .form-label {
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
    }

    .img-preview {
        width: 100%;
        max-width: 250px;
        height: 140px;
        object-fit: cover;
        border-radius: 12px;
        border: 2px dashed #cbd5e1;
    }

    .btn-save {
        background: #ef4444;
        border: none;
        border-radius: 10px;
        padding: 12px 40px;
        font-weight: 700;
        color: white;
    }

    /* Mobile Fix */
    @media (max-width: 991.98px) {
        .main-dashboard-content {
            margin-left: 0;
            padding-top: 100px; /* Usually need more room on mobile headers */
            padding-left: 15px;
            padding-right: 15px;
        }
    }
</style>

<div class="main-dashboard-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card edit-card">
                    <div class="card-header bg-white py-4 border-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 font-weight-bold" style="color: #1e293b;">Edit Event</h4>
                            <p class="text-muted mb-0 small">Modify event details and registration status</p>
                        </div>
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary rounded-pill px-4 btn-sm">
                            <i class="fas fa-arrow-left mr-2"></i> Back
                        </a>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-8 mb-4">
                                    <label class="form-label">Event Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $event->name }}" required>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Status</label>
                                    <select name="is_active" class="form-control">
                                        <option value="1" {{ $event->is_active == 1 ? 'selected' : '' }}>🟢 Live</option>
                                        <option value="2" {{ $event->is_active == 2 ? 'selected' : '' }}>🟡 Coming Soon</option>
                                        <option value="0" {{ $event->is_active == 0 ? 'selected' : '' }}>⚪ Past</option>
                                    </select>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <label class="form-label">Event Date</label>
                                    <input type="date" name="date" class="form-control" style="position: relative; z-index: 5;"
       value="{{ $event->date ? \Carbon\Carbon::parse($event->date)->format('Y-m-d') : '' }}" required>
                                </div>

                                <div class="col-md-8 mb-4">
                                    <label class="form-label">Location</label>
                                    <input type="text" name="location" class="form-control" value="{{ $event->location }}" placeholder="e.g. Yangon, Myanmar">
                                </div>

                                <div class="col-md-12 mb-4">
                                    <label class="form-label">YouTube Video ID</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text bg-light"><i class="fab fa-youtube text-danger"></i></span>
                                        </div>
                                        <input type="text" name="video_url" class="form-control" value="{{ $event->video_url }}" placeholder="K_FvDL_anrs">
                                    </div>
                                    <small class="text-muted">Only the ID after <strong>v=</strong></small>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <label class="form-label">Event Banner Image</label>
                                    <input type="file" name="image" class="form-control-file mb-3">
                                    
                                    <div class="p-2 bg-light rounded d-inline-block">
                                        @if($event->image_path)
                                            <img src="{{ asset('storage/' . $event->image_path) }}" class="img-preview shadow-sm">
                                        @else
                                            <div class="img-preview d-flex align-items-center justify-content-center bg-white">
                                                <span class="text-muted small">No Banner Set</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12 mb-5">
                                    <label class="form-label">Event Description</label>
                                    <textarea name="description" class="form-control" rows="6">{{ $event->description }}</textarea>
                                </div>
                            </div>

                            <div class="border-top pt-4 text-right">
                                <button type="submit" class="btn btn-save shadow-lg">
                                    UPDATE EVENT
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection