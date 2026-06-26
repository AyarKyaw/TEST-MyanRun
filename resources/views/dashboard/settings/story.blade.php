@extends('dashboard.layouts.master')
@section('content')

<div class="content-body">
    <div class="container-fluid">
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- STORIES REPEATER FORM --}}
        <form action="{{ route('admin.settings.stories.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card border-primary">
                <div class="card-header bg-primary py-2 d-flex justify-content-between align-items-center">
                    <h4 class="card-title text-white mb-0">Success Stories Management</h4>
                    <button type="button" class="btn btn-light btn-sm fw-bold text-primary" onclick="addStoryRow()">
                        <i class="fa fa-plus-circle me-1"></i> Add New Story
                    </button>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Manage and showcase customer success stories, enterprise project milestones, and company spotlights across your front-end platform.</p>
                    
                    <div id="stories-wrapper" class="row">
                        @forelse($stories as $index => $story)
                            <div class="col-xl-4 col-md-6 story-item mb-4" id="story_row_{{ $index }}">
                                <div class="card border p-3 bg-light h-100 position-relative shadow-sm">
                                    <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 m-2 rounded-circle" onclick="removeStoryRow({{ $index }})" style="z-index:10;">
                                        <i class="fa fa-times text-white"></i>
                                    </button>

                                    <h6 class="text-primary fw-bold mb-3"><i class="fa fa-bookmark me-2"></i> Story #<span class="row-counter">{{ $index + 1 }}</span></h6>
                                    
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted mb-1">Story Title</label>
                                        <input type="text" name="story_titles[]" class="form-control" value="{{ $story['title'] ?? '' }}" placeholder="e.g., Elite Runner Spotlight" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted mb-1">Company Name</label>
                                        <input type="text" name="story_companies[]" class="form-control" value="{{ $story['company'] ?? '' }}" placeholder="e.g., Nike Run Club" required>
                                    </div>

                                    <div>
                                        <label class="form-label small fw-bold text-muted mb-1">Representative Image</label>
                                        @if(!empty($story['image_path']))
                                            <div class="mb-2 border rounded text-center bg-white p-1">
                                                <img src="{{ asset($story['image_path']) }}" class="img-fluid rounded" style="max-height: 110px; object-fit: contain;">
                                            </div>
                                        @endif
                                        <input type="hidden" name="existing_story_images[]" value="{{ $story['image_path'] ?? '' }}">
                                        <input type="file" name="story_images[{{ $index }}]" class="form-control form-control-sm" accept="image/*">
                                    </div>
                                </div>
                            </div>
                        @empty
                            {{-- Default Initial Row if Database contains no items --}}
                            <div class="col-xl-4 col-md-6 story-item mb-4" id="story_row_0">
                                <div class="card border p-3 bg-light h-100 position-relative shadow-sm">
                                    <h6 class="text-primary fw-bold mb-3"><i class="fa fa-bookmark me-2"></i> Story #<span class="row-counter">1</span></h6>
                                    <div class="mb-2">
                                        <label class="form-label small fw-bold text-muted mb-1">Story Title</label>
                                        <input type="text" name="story_titles[]" class="form-control" placeholder="e.g., Dynamic Milestone Record" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted mb-1">Company Name</label>
                                        <input type="text" name="story_companies[]" class="form-control" placeholder="e.g., Enterprise Corp" required>
                                    </div>
                                    <div>
                                        <label class="form-label small fw-bold text-muted mb-1">Representative Image</label>
                                        <input type="hidden" name="existing_story_images[]" value="">
                                        <input type="file" name="story_images[0]" class="form-control form-control-sm" accept="image/*" required>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="card-footer bg-white text-start py-3">
                    <button type="submit" class="btn btn-primary px-5 shadow-sm btn-lg">
                        <i class="fa fa-save me-2"></i> Commit Stories Configuration
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Repeater Node UI Injection Scripts --}}
<script>
    let storyCounter = {{ max(count($stories ?? []), 1) }};

    function addStoryRow() {
        const wrapper = document.getElementById('stories-wrapper');
        const html = `
            <div class="col-xl-4 col-md-6 story-item mb-4" id="story_row_${storyCounter}">
                <div class="card border p-3 bg-light h-100 position-relative shadow-sm">
                    <button type="button" class="btn btn-danger btn-xs position-absolute top-0 end-0 m-2 rounded-circle" onclick="removeStoryRow(${storyCounter})" style="z-index:10;">
                        <i class="fa fa-times text-white"></i>
                    </button>
                    <h6 class="text-primary fw-bold mb-3"><i class="fa fa-bookmark me-2"></i> Story #<span class="row-counter"></span></h6>
                    <div class="mb-2">
                        <label class="form-label small fw-bold text-muted mb-1">Story Title</label>
                        <input type="text" name="story_titles[]" class="form-control" placeholder="e.g., Milestone Headline" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted mb-1">Company Name</label>
                        <input type="text" name="story_companies[]" class="form-control" placeholder="e.g., Strategic Partner" required>
                    </div>
                    <div>
                        <label class="form-label small fw-bold text-muted mb-1">Representative Image</label>
                        <input type="hidden" name="existing_story_images[]" value="">
                        <input type="file" name="story_images[${storyCounter}]" class="form-control form-control-sm" accept="image/*" required>
                    </div>
                </div>
            </div>
        `;
        wrapper.insertAdjacentHTML('beforeend', html);
        storyCounter++;
        reorderStoryCounters();
    }

    function removeStoryRow(index) {
        const row = document.getElementById(`story_row_${index}`);
        if(row) {
            row.remove();
            reorderStoryCounters();
        }
    }

    function reorderStoryCounters() {
        const items = document.querySelectorAll('.story-item');
        items.forEach((item, index) => {
            item.querySelector('.row-counter').innerText = index + 1;
        });
    }
</script>
@endsection