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
                            <button type="submit" class="btn btn-primary px-5">Save Global Settings & Event</button>
                        </div>
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
</script>