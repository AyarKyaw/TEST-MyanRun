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
                            <label class="form-label">Dinner Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $dinner->name) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Company</label>
                            <input type="text" name="company" class="form-control" value="{{ old('company', $dinner->company) }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="date" class="form-control" value="{{ $dinner->date ? $dinner->date->format('Y-m-d') : '' }}" style="position: relative; z-index: 5;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-control">
                                <option value="1" {{ $dinner->is_active == 1 ? 'selected' : '' }}>ACTIVE</option>
                                <option value="0" {{ $dinner->is_active == 0 ? 'selected' : '' }}>PAST</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Current Image</label>
                            <div class="mb-2">
                                @if($dinner->image_path)
                                    <img src="{{ asset('storage/' . $dinner->image_path) }}" width="150" class="rounded">
                                @else
                                    <p class="text-muted">No image uploaded</p>
                                @endif
                            </div>
                            <label class="form-label">Replace Image</label>
                            <input type="file" name="image" class="form-control">
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Dinner</button>
                            <a href="{{ route('admin.dinner.manage', 'now') }}" class="btn btn-danger light ms-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection