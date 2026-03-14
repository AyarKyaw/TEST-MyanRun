@extends('layouts.master')

@section('title', 'Payment & Registration - MYANRUN')

@section('content')
<section class="tf-spacing-1">
    <div class="themeflat-container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="success-card" style="background: #fff; padding: 60px 40px; border-radius: 40px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                    
                    @php 
                        // DATA FROM URL
                        $qty = (int)request('quantity', 1);
                        $displayPrice = (int)str_replace(',', '', request('price', 0));
                        $firstName = request('first_name', 'Guest');
                        $lastName = request('last_name', 'User');
                        $appliedCode = request('applied_code');
                        // Capture Viber from URL, fallback to phone if missing
                        $viber = request('viber') ?? request('phone');
                    @endphp

                    <div class="qr-wrap mb-4 text-center">
                        <p style="font-weight: 800; color: #1a1a1a; margin-bottom: 15px; letter-spacing: 1px;">
                            SCAN TO PAY (MMQR)
                        </p>
                        <div style="padding: 15px; border: 2px solid #f59e0b; border-radius: 20px; background: #fff; display: inline-block;">
                            <img src="{{ asset('images/kbz_qr.jpg') }}" alt="Payment QR" style="width: 240px; height: auto; border-radius: 12px;">
                        </div>
                    </div>

                    <h2 style="font-weight: 800; margin-bottom: 10px; text-transform: uppercase;">
                        Payment Required
                    </h2>
                    
                    <div class="alert alert-warning py-2 mb-4" style="border-radius: 12px; font-size: 14px;">
                        Total for <strong>{{ $qty }} person(s)</strong>: <strong>{{ number_format($displayPrice) }} MMK</strong>
                    </div>

                    <div class="upload-box" style="background: #f8fafc; padding: 25px; border-radius: 20px; border: 1px solid #e2e8f0;">
                        <form action="{{ route('dinner.upload.payment', ['id' => 'new']) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            {{-- 1. TICKET DATA --}}
                            <input type="hidden" name="dinner_id" value="{{ request('dinner_id') }}">
                            <input type="hidden" name="type" value="{{ request('type') }}">
                            <input type="hidden" name="price" value="{{ $displayPrice }}">
                            <input type="hidden" name="quantity" value="{{ $qty }}">
                            <input type="hidden" name="applied_code" value="{{ $appliedCode }}">

                            {{-- 2. GUEST DATA --}}
                            <input type="hidden" name="first_name" value="{{ $firstName }}">
                            <input type="hidden" name="middle_name" value="{{ request('middle_name') }}">
                            <input type="hidden" name="last_name" value="{{ $lastName }}">
                            <input type="hidden" name="email" value="{{ request('email') }}">
                            <input type="hidden" name="phone" value="{{ request('phone') }}">
                            
                            {{-- IMPORTANT FIX: ADD VIBER HIDDEN INPUT --}}
                            <input type="hidden" name="viber" value="{{ $viber }}">

                            <div class="mb-3 text-start">
                                <label class="form-label" style="font-weight: 700; font-size: 12px; color: #64748b;">UPLOAD TRANSACTION SLIP</label>
                                <input type="file" name="payment_slip" class="form-control" required style="border-radius: 10px; padding: 10px;">
                                @error('payment_slip')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <button type="submit" class="tf-btn w-100" style="background: #f59e0b; color: #fff; border-radius: 12px; padding: 15px; border: none; font-weight: 800;">
                                SUBMIT PAYMENT PROOF
                            </button>
                        </form>
                    </div>

                    <div class="details-box mt-4 mb-4" style="background: #f8fafc; padding: 20px; border-radius: 20px; text-align: left; border: 1px solid #f1f5f9;">
                        {{-- ... (Status and Guest name rows) ... --}}
                        
                        {{-- Show Viber in details so user can verify --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span style="color: #94a3b8; font-size: 11px; font-weight: 700;">VIBER:</span>
                            <span style="font-weight: 800;">{{ $viber }}</span>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <span style="color: #94a3b8; font-size: 11px; font-weight: 700;">QUANTITY:</span>
                            <span style="font-weight: 800;">{{ $qty }} Person(s)</span>
                        </div>
                    </div>

                    <a href="{{ url('/') }}" class="text-muted" style="font-weight: 700; text-decoration: none; font-size: 13px;">
                        <i class="fas fa-arrow-left me-1"></i> BACK TO HOME
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection