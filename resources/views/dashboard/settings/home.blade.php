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
                                {{-- Uses $banners variable directly from SettingsController@homeindex --}}
                                @forelse($banners as $index => $banner)
                                <div class="card border mb-3 bg-light banner-row">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-md-11 mb-2">
                                                <label class="form-label fw-bold text-dark">Banner Slide Background</label>
                                                @if(!empty($banner['image_path']))
                                                    <div class="mb-2">
                                                        {{-- Points to the public directory path used by your move() operation --}}
                                                        <img src="{{ asset($banner['image_path']) }}" alt="Banner preview" class="img-thumbnail" style="max-height: 80px;">
                                                    </div>
                                                @endif
                                                <input type="file" name="banner_images[{{ $index }}]" class="form-control">
                                                {{-- Hidden tracker to keep existing files stable if a new file isn't uploaded --}}
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
                                {{-- Default skeleton placeholder configuration structure if table record is clear --}}
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

            {{-- 2. INTERACTIVE SAVE ACTION BUTTON --}}
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
        
        // Generate a clean, unique sequential tracking index for the newly injected DOM row elements
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
                reindexBannerRows(); // Reset indices so layout rows match sequentially
            } else {
                alert('At least one operational hero banner asset config mapping is required.');
            }
        }
    });

    // Helper to rewrite array indexes dynamically if rows are tossed out
    function reindexBannerRows() {
        document.querySelectorAll('.banner-row').forEach((row, index) => {
            const fileInput = row.querySelector('input[type="file"]');
            const hiddenInput = row.querySelector('input[type="hidden"]');
            if(fileInput) fileInput.setAttribute('name', `banner_images[${index}]`);
            if(hiddenInput) hiddenInput.setAttribute('name', `existing_banner_images[${index}]`);
        });
    }
</script>

@endsection