@extends('dashboard.layouts.master')

@section('content')
<main class="content-body">
    <div class="container-fluid">

        <div class="page-title mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><h1>Story Management</h1></li>
                    <li class="breadcrumb-item"><a href="javascript:void(0);">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New Story</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            {{-- col-xl-12 makes the row go from edge to edge --}}
            <div class="col-xl-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h4 class="card-title">Create New Story</h4>
                    </div>
                    <div class="card-body">
                        {{-- Alert Messages --}}
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show mb-4">
                                <strong>Success!</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('stories.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                {{-- Left Column: Text & Content --}}
                                <div class="col-lg-8">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Company Name <span class="text-danger">*</span></label>
                                        <input type="text" name="company" class="form-control @error('company') is-invalid @enderror" 
                                               placeholder="Enter company name..." value="{{ old('company') }}" required>
                                        @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>

                                    <div class="mb-0">
                                        <label class="form-label fw-bold">Story Title/Content <span class="text-danger">*</span></label>
                                        <textarea id="ckeditor" name="title" class="form-control">{{ old('title') }}</textarea>
                                        @error('title') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
                                    </div>
                                </div>

                                {{-- Right Column: Image & Final Actions --}}
                                <div class="col-lg-4 border-start">
                                    <div class="ps-lg-3">
                                        <label class="form-label fw-bold mb-3">Featured Image</label>
                                        
                                        <div class="image-upload-wrapper text-center mb-4">
                                            <div class="preview-container mb-3" style="width: 100%; height: 220px; border-radius: 12px; overflow: hidden; background: #fcfcfc; border: 2px dashed #ebebeb; display: flex; align-items: center; justify-content: center;">
                                                <img id="showImage" src="{{ asset('assets/images/avatar/placeholder.avif') }}" 
                                                     style="max-width: 100%; max-height: 100%; object-fit: cover;">
                                            </div>
                                            
                                            <input type="file" name="image" id="imageUpload" class="d-none" accept="image/*">
                                            <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-4" onclick="document.getElementById('imageUpload').click();">
                                                <i class="fa fa-camera me-2"></i>Choose Photo
                                            </button>
                                            @error('image') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="publish-section p-3 rounded" style="background: #f8f9fa;">
                                            <p class="text-muted small mb-3"><i class="fa fa-info-circle me-1"></i> Ensure all required fields are filled before publishing.</p>
                                            <button type="submit" class="btn btn-primary w-100 mb-2 py-2">
                                                <i class="fa-solid fa-paper-plane me-2"></i>Publish Story
                                            </button>
                                            <a href="{{ url()->previous() }}" class="btn btn-link w-100 text-muted btn-sm">Save as Draft</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

{{-- Live Image Preview Script --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#imageUpload').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e){
                $('#showImage').attr('src', e.target.result).css('width', '100%').css('height', '100%');
            }
            reader.readAsDataURL(e.target.files[0]);
        });
    });
</script>
@endsection