@extends('layouts.master')

@section('title', 'Payment & Registration - MYANRUN')

@section('content')
<section class="tf-spacing-1">
    <div class="themeflat-container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="success-card" style="background: #fff; padding: 60px 40px; border-radius: 40px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                    
                    @php 
                        // DATA FROM SESSION OR REQUEST
                        // It is safer to use the session data we saved earlier
                        $order = session('pending_registration');
                        $qty = 1; // Forced single ticket as per your rule
                        $price = $order['price'] ?? request('price', 0);
                        $bibName = $order['bib_name'] ?? request('bib_name', 'Runner');
                        $category = $order['category'] ?? request('category', 'Race');
                    @endphp

                    <div class="qr-wrap mb-4 text-center">
                        <p style="font-weight: 800; color: #1a1a1a; margin-bottom: 15px; letter-spacing: 1px; text-transform: uppercase;">
                            Scan to Pay (MMQR)
                        </p>
                        <div style="padding: 15px; border: 2px solid #CEF531; border-radius: 20px; background: #fff; display: inline-block;">
                            {{-- Ensure this path is correct in your public folder --}}
                            <img src="{{ asset('images/kbz_qr.jpg') }}" alt="Payment QR" style="width: 240px; height: auto; border-radius: 12px;">
                        </div>
                    </div>

                    <h2 style="font-weight: 800; margin-bottom: 10px; text-transform: uppercase;">
                        Complete Your Registration
                    </h2>
                    
                    <div class="alert alert-warning py-3 mb-4" style="border-radius: 12px; background: #fefce8; border: 1px solid #fef08a;">
                        <span style="display: block; font-size: 12px; color: #854d0e; font-weight: 700; text-transform: uppercase;">Total Amount</span>
                        <strong style="font-size: 24px; color: #000;">{{ $price }} MMK</strong>
                    </div>

                    <div class="upload-box" style="background: #f8fafc; padding: 25px; border-radius: 20px; border: 1px solid #e2e8f0;">
                        <form action="{{ route('payment.verify') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            {{-- PASSING ALL NECESSARY DATA TO THE CONTROLLER --}}
                            <input type="hidden" name="bib_name" value="{{ $bibName }}">
                            <input type="hidden" name="category" value="{{ $category }}">
                            <input type="hidden" name="amount" value="{{ $price }}">
                            <input type="hidden" name="runner_id" value="{{ Auth::user()->runner_id }}">

                            <div class="mb-4 text-start">
                                <label class="form-label" style="font-weight: 700; font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: 1px;">
                                    Upload Transaction Screenshot
                                </label>
                                <input type="file" name="payment_slip" class="form-control" required 
                                    style="border-radius: 10px; padding: 12px; border: 2px dashed #cbd5e1; background: #fff;">
                                <small style="color: #94a3b8; font-size: 11px;">Max file size: 2MB (JPG, PNG)</small>
                            </div>

                            <button type="submit" class="tf-btn w-100" 
                                style="background: #000; color: #CEF531; border-radius: 12px; padding: 18px; border: none; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">
                                Submit Payment Proof
                            </button>
                        </form>
                    </div>

                    <div class="details-box mt-4 mb-4" style="background: #f8fafc; padding: 20px; border-radius: 20px; text-align: left; border: 1px solid #f1f5f9;">
                        <div class="d-flex justify-content-between mb-2">
                            <span style="color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase;">Runner:</span>
                            <span style="font-weight: 800;">{{ $bibName }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span style="color: #94a3b8; font-size: 11px; font-weight: 700; text-transform: uppercase;">Category:</span>
                            <span style="font-weight: 800;">{{ $category }}</span>
                        </div>
                    </div>

                    <a href="{{ url('/') }}" class="text-muted" style="font-weight: 700; text-decoration: none; font-size: 13px; text-transform: uppercase;">
                        <i class="icon-Arrow---Left-2"></i> Cancel & Return Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection