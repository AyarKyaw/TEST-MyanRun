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
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row mb-4">
                <div class="col-xl-12">
                    <div class="card border-primary">
                        <div class="card-header bg-primary py-2">
                            <h4 class="card-title text-white">Global Identity Settings</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label class="form-label text-black fw-bold">Global Contact Email</label>
                                    <input type="email" name="global_email" class="form-control" 
                                        value="{{ old('global_email', $global_info->email ?? '') }}">
                                </div>
                                <div class="mb-3 col-md-8">
                                    <label class="form-label text-black fw-bold">Global Street Address</label>
                                    <input type="text" name="global_address" class="form-control" 
                                        value="{{ old('global_address', $global_info->street_address ?? '') }}">
                                </div>
                            </div>

                            <hr>
                            <label class="form-label text-black fw-bold">Phone Numbers</label>
                            <div id="phone-repeater">
                                @php
                                    // Decode the JSON phone numbers from your global_info
                                    $phones = isset($global_info->phone_numbers) ? json_decode($global_info->phone_numbers, true) : [''];
                                @endphp

                                @foreach($phones as $phone)
                                <div class="row mb-2 phone-row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light"><i class="fa fa-phone"></i></span>
                                            <input type="text" name="global_phones[]" class="form-control" value="{{ $phone }}" placeholder="+959...">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger w-100 remove-phone-btn">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button type="button" id="add-phone-btn" class="btn btn-info btn-sm mt-2">
                                <i class="fa fa-plus"></i> Add Another Phone Number
                            </button>
                            <hr>
<label class="form-label text-black fw-bold">Social Media Links</label>
<div id="social-repeater">
    @php
        // Decode the JSON socials: e.g., [{"platform":"facebook", "url":"..."}, {"platform":"tiktok", "url":"..."}]
        $socials = isset($global_info->social_links) ? json_decode($global_info->social_links, true) : [];
    @endphp

    @forelse($socials as $social)
    <div class="row mb-2 social-row">
        <div class="col-md-3">
            <select name="social_platforms[]" class="form-control">
                <option value="facebook" {{ $social['platform'] == 'facebook' ? 'selected' : '' }}>Facebook</option>
                <option value="tiktok" {{ $social['platform'] == 'tiktok' ? 'selected' : '' }}>TikTok</option>
                <option value="instagram" {{ $social['platform'] == 'instagram' ? 'selected' : '' }}>Instagram</option>
                <option value="youtube" {{ $social['platform'] == 'youtube' ? 'selected' : '' }}>YouTube</option>
            </select>
        </div>
        <div class="col-md-7">
            <input type="url" name="social_urls[]" class="form-control" value="{{ $social['url'] }}" placeholder="https://...">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger w-100 remove-social-btn"><i class="fa fa-trash"></i></button>
        </div>
    </div>
    @empty
    <div class="row mb-2 social-row">
        <div class="col-md-3">
            <select name="social_platforms[]" class="form-control">
                <option value="facebook">Facebook</option>
                <option value="tiktok">TikTok</option>
                <option value="instagram">Instagram</option>
                <option value="youtube">YouTube</option>
            </select>
        </div>
        <div class="col-md-7">
            <input type="url" name="social_urls[]" class="form-control" placeholder="https://...">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger w-100 remove-social-btn"><i class="fa fa-trash"></i></button>
        </div>
    </div>
    @endforelse
</div>
<button type="button" id="add-social-btn" class="btn btn-secondary btn-sm mt-2 mb-4">
    <i class="fa fa-plus"></i> Add Social Link
</button>
</div>
<button type="submit" class="btn btn-primary px-5">Save Global Settings & Event</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Add new phone row
    document.getElementById('add-phone-btn').addEventListener('click', function() {
        const container = document.getElementById('phone-repeater');
        const newRow = document.createElement('div');
        newRow.className = 'row mb-2 phone-row';
        newRow.innerHTML = `
            <div class="col-md-10">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa fa-phone"></i></span>
                    <input type="text" name="global_phones[]" class="form-control" placeholder="+959...">
                </div>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 remove-phone-btn"><i class="fa fa-trash"></i></button>
            </div>
        `;
        container.appendChild(newRow);
    });

    // Remove phone row (Event Delegation)
    document.getElementById('phone-repeater').addEventListener('click', function(e) {
        if (e.target.closest('.remove-phone-btn')) {
            const rows = document.querySelectorAll('.phone-row');
            if (rows.length > 1) {
                e.target.closest('.phone-row').remove();
            } else {
                alert('At least one phone number is required.');
            }
        }
    });

    // Add new social row
document.getElementById('add-social-btn').addEventListener('click', function() {
    const container = document.getElementById('social-repeater');
    const newRow = document.createElement('div');
    newRow.className = 'row mb-2 social-row';
    newRow.innerHTML = `
        <div class="col-md-3">
            <select name="social_platforms[]" class="form-control">
                <option value="facebook">Facebook</option>
                <option value="tiktok">TikTok</option>
                <option value="instagram">Instagram</option>
                <option value="youtube">YouTube</option>
            </select>
        </div>
        <div class="col-md-7">
            <input type="url" name="social_urls[]" class="form-control" placeholder="https://...">
        </div>
        <div class="col-md-2">
            <button type="button" class="btn btn-danger w-100 remove-social-btn"><i class="fa fa-trash"></i></button>
        </div>
    `;
    container.appendChild(newRow);
});

// Remove social row
document.getElementById('social-repeater').addEventListener('click', function(e) {
    if (e.target.closest('.remove-social-btn')) {
        e.target.closest('.social-row').remove();
    }
});
</script>