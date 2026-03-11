@extends('dashboard.layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        <div class="page-title mb-5">
            <h1 class="font-weight-bold text-dark">Add New Sponsor</h1>
            <p class="text-muted">Register a company profile. You can generate invitation codes in the next step.</p>
        </div>

        <div class="row">
            <div class="col-xl-8 col-lg-10">
                <div class="card shadow-sm" style="border-radius: 15px; border: none; border-top: 5px solid #22c55e;">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.sponsor.store') }}" method="POST">
                            @csrf
                            
                            <div class="row">
                                {{-- Company Name --}}
                                <div class="col-md-12 mb-4">
                                    <label class="form-label font-weight-bold text-dark">Company Name</label>
                                    <input type="text" name="company" class="form-control @error('company') is-invalid @enderror" placeholder="e.g. KBZ Bank" required value="{{ old('company') }}">
                                    @error('company') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                {{-- Contact Person --}}
                                <div class="col-md-6 mb-4">
                                    <label class="form-label font-weight-bold text-dark">Contact Person Name</label>
                                    <input type="text" name="contact_name" class="form-control" placeholder="e.g. U Kyaw Kyaw" required value="{{ old('contact_name') }}">
                                </div>

                                {{-- Email --}}
                                <div class="col-md-6 mb-4">
                                    <label class="form-label font-weight-bold text-dark">Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="sponsor@company.com" required value="{{ old('email') }}">
                                </div>

                                {{-- Phone --}}
                                <div class="col-md-6 mb-4">
                                    <label class="form-label font-weight-bold text-dark">Phone Number</label>
                                    <input type="text" name="phone" class="form-control" placeholder="09..." required value="{{ old('phone') }}">
                                </div>

                                {{-- Viber Number (New) --}}
                                <div class="col-md-6 mb-4">
                                    <label class="form-label font-weight-bold text-dark">Viber Number</label>
                                    <input type="text" name="viber" class="form-control" placeholder="09..." value="{{ old('viber') }}">
                                </div>

                                {{-- Quantity / Ticket Quota (New) --}}
                                <div class="col-md-6 mb-4">
                                    <label class="form-label font-weight-bold text-dark">Ticket Quantity (Quota)</label>
                                    <input type="number" name="quantity" class="form-control" placeholder="e.g. 50" required value="{{ old('quantity', 0) }}">
                                    <small class="text-muted">Total number of tickets allowed for this sponsor.</small>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.sponsor.index', 'now') }}" class="btn btn-light px-4 mr-2" style="border-radius: 10px;">Cancel</a>
                                <button type="submit" class="btn btn-success px-5 font-weight-bold" style="border-radius: 10px;">
                                    <i class="fas fa-save mr-2"></i> SAVE SPONSOR PROFILE
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