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

        <form action="{{ route('admin.settings.about.update') }}" method="POST">
            @csrf

            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="card border-info">
                        <div class="card-header bg-info py-2">
                            <h4 class="card-title text-white">About Us Core Metrics Configuration</h4>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-4">Update the numerical values and descriptive labels showcased across your About Us platform statistics deck.</p>
                            
                            <div class="row">
                                {{-- 1. AWARDS BLOCK --}}
                                <div class="col-md-6 mb-4">
                                    <div class="card border p-3 bg-light h-100">
                                        <h5 class="text-dark fw-bold mb-3"><i class="fa fa-trophy text-warning me-2"></i> Awards</h5>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small fw-bold">Value / Counter</label>
                                            <input type="text" name="metrics[awards][value]" class="form-control" value="{{ $aboutSettings['awards']['value'] ?? '' }}" placeholder="e.g., 15+, 5" required>
                                        </div>
                                        <div>
                                            <label class="form-label text-muted small fw-bold">Description / Label</label>
                                            <input type="text" name="metrics[awards][title]" class="form-control" value="{{ $aboutSettings['awards']['title'] ?? 'Official Awards Won' }}" placeholder="e.g., Official Awards Won" required>
                                        </div>
                                    </div>
                                </div>

                                {{-- 2. FOLLOWERS BLOCK --}}
                                <div class="col-md-6 mb-4">
                                    <div class="card border p-3 bg-light h-100">
                                        <h5 class="text-dark fw-bold mb-3"><i class="fa fa-users text-primary me-2"></i> Followers</h5>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small fw-bold">Value / Counter</label>
                                            <input type="text" name="metrics[followers][value]" class="form-control" value="{{ $aboutSettings['followers']['value'] ?? '' }}" placeholder="e.g., 150K+, 10,000" required>
                                        </div>
                                        <div>
                                            <label class="form-label text-muted small fw-bold">Description / Label</label>
                                            <input type="text" name="metrics[followers][title]" class="form-control" value="{{ $aboutSettings['followers']['title'] ?? 'Community Members' }}" placeholder="e.g., Community Members" required>
                                        </div>
                                    </div>
                                </div>

                                {{-- 3. EVENTS BLOCK --}}
                                <div class="col-md-6 mb-4">
                                    <div class="card border p-3 bg-light h-100">
                                        <h5 class="text-dark fw-bold mb-3"><i class="fa fa-calendar-check text-success me-2"></i> Events</h5>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small fw-bold">Value / Counter</label>
                                            <input type="text" name="metrics[events][value]" class="form-control" value="{{ $aboutSettings['events']['value'] ?? '' }}" placeholder="e.g., 45, 120+" required>
                                        </div>
                                        <div>
                                            <label class="form-label text-muted small fw-bold">Description / Label</label>
                                            <input type="text" name="metrics[events][title]" class="form-control" value="{{ $aboutSettings['events']['title'] ?? 'Successful Marathons Conducted' }}" placeholder="e.g., Events Completed" required>
                                        </div>
                                    </div>
                                </div>

                                {{-- 4. MILES BLOCK --}}
                                <div class="col-md-6 mb-4">
                                    <div class="card border p-3 bg-light h-100">
                                        <h5 class="text-dark fw-bold mb-3"><i class="fa fa-road text-secondary me-2"></i> Miles</h5>
                                        <div class="mb-2">
                                            <label class="form-label text-muted small fw-bold">Value / Counter</label>
                                            <input type="text" name="metrics[miles][value]" class="form-control" value="{{ $aboutSettings['miles']['value'] ?? '' }}" placeholder="e.g., 5,000+ Miles, 8K" required>
                                        </div>
                                        <div>
                                            <label class="form-label text-muted small fw-bold">Description / Label</label>
                                            <input type="text" name="metrics[miles][title]" class="form-control" value="{{ $aboutSettings['miles']['title'] ?? 'Total Distance Covered' }}" placeholder="e.g., Total Tracked Miles" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- SAVE BUTTON --}}
            <div class="row mb-5">
                <div class="col-12 text-start">
                    <button type="submit" class="btn btn-primary px-5 btn-lg shadow-sm">
                        <i class="fa fa-save me-2"></i> Commit About Us Configuration
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection