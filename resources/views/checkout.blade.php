@extends('layouts.master')

@section('title', 'Event - MYANRUN')

@section('content')
    <div class="page-title page-shop">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-heading">
                        <h1 class="title">Check Out</h1>
                    </div>
                    <div class="breadcrumbs">
                        <ul>
                            <li><a href="{{ url('/') }}">Homepage</a></li>
                            <li><i class="icon-Arrow---Right-2"></i></li>
                            <li><a>Check Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="tf-spacing-1">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-lg-7">
    <div class="checkout-wrap" style="background: #fff; padding: 30px; border-radius: 12px; border: 1px solid #eee;">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 style="font-weight: 800; margin: 0;">Runner Details</h3>
            <a href="{{ route('athlete.register') }}" class="text-muted" style="font-size: 13px; text-decoration: underline;">Edit Info</a>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Full Name</label>
                <p style="font-size: 16px; font-weight: 600; color: #000;">{{ $fullName ?? 'Not provided' }}</p>
            </div>
            <div class="col-md-6">
                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">
                    {{ ($order['nat_type'] ?? '') == 'national' ? 'NRC Number' : 'Passport ID' }}
                </label>
                <p style="font-size: 16px; font-weight: 600; color: #000;">{{ Auth::user()->athlete->id_number ?? '---' }}</p>
            </div>

            <div class="col-md-6">
                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Date of Birth</label>
                <p style="font-size: 16px; font-weight: 600; color: #000;">{{ Auth::user()->athlete->dob ?? '---' }}</p>
            </div>
            <div class="col-md-6">
                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Gender</label>
                <p style="font-size: 16px; font-weight: 600; color: #000; text-transform: capitalize;">{{ Auth::user()->athlete->gender ?? '---' }}</p>
            </div>

            <div class="col-12">
                <hr style="border-top: 1px solid #eee;">
            </div>

            <div class="col-md-6">
                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Email Address</label>
                <p style="font-size: 16px; font-weight: 600; color: #000;">{{ Auth::user()->email }}</p>
            </div>
            <div class="col-md-6">
                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Phone Number</label>
                <p style="font-size: 16px; font-weight: 600; color: #000;">{{ Auth::user()->athlete->phone_2 ?? 'No secondary phone' }}</p>
            </div>

            <div class="col-12">
                <div class="p-3" style="background: #fdfdfd; border-radius: 8px; border: 1px solid #f0f0f0;">
                    <label style="font-size: 11px; text-transform: uppercase; color: #999; font-weight: 700; display: block; margin-bottom: 5px;">
                        <i class="icon-shield-check" style="color: #CEF531;"></i> Race Verification
                    </label>
                    <p class="mb-0" style="font-size: 13px; color: #666;">
                        Please ensure the ID above matches the one you will bring to the race kit collection.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

                <div class="col-lg-5">
    <div class="checkout-cart" style="background: #f9f9f9; padding: 30px; border-radius: 12px; border: 1px solid #eee;">
        <h4 class="mb-4">Review Order</h4>
        
        <div class="checkout-product-wrap">
            <div class="checkout-product-item d-flex align-items-center mb-3" style="gap: 15px;">
                <div class="image" style="width: 70px; height: 70px; background: #000; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; overflow: hidden;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M15 5C15 6.10457 14.1046 7 13 7C11.8954 7 11 6.10457 11 5C11 3.89543 11.8954 3 13 3C14.1046 3 15 3.89543 15 5Z" fill="#CEF531"/>
                        <path d="M13.5 19L9.5 13L11 11L13.5 15L18 14L17 12L14.5 12.5L12 8H8L4 13L5.5 14.5L8 11.5L9 14.5L6 21H8.5L10.5 17.5L13.5 19Z" fill="#CEF531"/>
                    </svg>
                    <div style="position: absolute; width: 40px; height: 40px; background: #CEF531; filter: blur(25px); opacity: 0.2;"></div>
                </div>
                
                <div class="content flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <span class="text-title d-block" style="font-weight: 700; line-height: 1.2;">
                                {{ $order['event'] ?? 'Race Registration' }}
                            </span>
                            <small class="text-muted">{{ $order['category'] ?? 'General' }}</small>
                            <div class="mt-1">
                                <small class="d-block" style="font-size: 11px; color: #888;">Runner: {{ $order['runner_name'] ?? 'Guest' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="line my-3" style="border-top: 1px dashed #ddd;"></div>

        <div claass="checkout-cart-by">
            <div class="d-flex justify-content-between mb-2">
                <div class="text-title text-muted">Subtotal</div>
                <div class="text-title">${{ number_format($subtotal, 2) }}</div>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <div class="text-title text-muted">Service Fee</div>
                <div class="text-title">${{ number_format($serviceFee, 2) }}</div>
            </div>
            
            <div class="d-flex justify-content-between mt-3 mb-4">
                <h4 style="font-weight: 800; margin: 0;">Total</h4>
                <h4 style="font-weight: 800; color: #000; margin: 0;">${{ number_format($total, 2) }}</h4>
            </div>

            <form action="{{ route('initiatePayment', ['id' => Auth::user()->runner_id]) }}" method="POST">
                @csrf
                <button type="submit" class="tf-btn w-100 justify-content-center" 
                    style="background-color: #CEF531; color: #000; border: none; padding: 20px; font-weight: 800; font-size: 18px; text-transform: uppercase; letter-spacing: 1.5px; border-radius: 4px; box-shadow: 0 4px 14px rgba(206, 245, 49, 0.4); transition: transform 0.2s;">
                    PROCEED TO PAYMENT
                </button>
            </form>
            
            <p class="text-center mt-3" style="font-size: 11px; text-transform: uppercase; color: #999; letter-spacing: 1px;">
                Final step before secure checkout
            </p>
        </div>
    </div>
</div> </div> </div> </section>
@endsection