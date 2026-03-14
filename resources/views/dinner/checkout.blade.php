@extends('layouts.master')

@section('title', 'Dinner Checkout - MYANRUN')

@section('content')
    <section class="tf-spacing-1">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="checkout-wrap" style="background: #fff; padding: 30px; border-radius: 12px; border: 1px solid #eee;">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 style="font-weight: 800; margin: 0;">Guest Details</h3>
                            <p class="text-muted mb-0" style="font-size: 13px;">Please confirm your invitation info</p>
                        </div>

                        <div class="row g-4">
                            {{-- Name and Ticket info stays same --}}
                            <div class="col-md-6">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Full Name</label>
                                <p style="font-size: 16px; font-weight: 600; color: #000;">
                                    {{ request('first_name', Auth::user()->first_name ?? 'Guest') }} 
                                    {{ request('middle_name') ? request('middle_name') . ' ' : (Auth::user()->middle_name ?? '') }}
                                    {{ request('last_name', Auth::user()->last_name ?? 'User') }}
                                </p>
                            </div>
                            
                            <div class="col-md-6">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Ticket Details</label>
                                <p style="font-size: 16px; font-weight: 600; color: #f59e0b; text-transform: uppercase;">
                                    {{ request('selected_type', 'Standard Guest') }} 
                                    <span style="color: #000; font-size: 14px;">(x{{ request('quantity', 1) }})</span>
                                </p>
                            </div>

                            <div class="col-md-6">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Email Address</label>
                                <p style="font-size: 16px; font-weight: 600; color: #000;">
                                    {{ request('guest_email', Auth::user()->email ?? 'Not provided') }}
                                </p>
                            </div>
                            
                            <div class="col-md-3">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Phone Number</label>
                                <p style="font-size: 16px; font-weight: 600; color: #000;">
                                    {{ request('guest_phone', Auth::user()->phone ?? 'N/A') }}
                                </p>
                            </div>

                            {{-- ADDED: Viber Display Field --}}
                            <div class="col-md-3">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Viber Number</label>
                                <p style="font-size: 16px; font-weight: 600; color: #7360F2;">
                                    {{ request('viber') ?? request('guest_phone') }}
                                </p>
                            </div>

                            <div class="col-12">
                                <hr style="border-top: 1px solid #eee;">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="checkout-cart" style="background: #f9f9f9; padding: 30px; border-radius: 12px; border: 1px solid #eee;">
                        <h4 class="mb-4">Review Order</h4>
                        
                        {{-- Product Details --}}
                    <div class="checkout-product-wrap">
                        <div class="checkout-product-item d-flex align-items-center mb-3" style="gap: 15px;">
                            {{-- Updated from black box to image --}}
                            <div class="image" style="width: 80px; height: 80px; border-radius: 12px; flex-shrink: 0; position: relative; overflow: hidden; border: 1px solid #eee;">
                                <img src="{{ asset('images/ticket1_1.jpg') }}" 
                                    alt="Dinner Image" 
                                    style="width: 100%; height: 100%; object-fit: cover;">
                            </div>
                            
                            <div class="content flex-grow-1">
                                <span class="text-title d-block" style="font-weight: 700;">{{ $dinner->name }}</span>
                                <small class="text-muted">Type: {{ request('selected_type', 'Standard Guest') }} (x{{ request('quantity', 1) }})</small>
                            </div>
                        </div>
                    </div>

                        {{-- DISCOUNT CODE SECTION --}}
                        <div class="discount-section mt-4 mb-4">
                            <label style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #666;">Have a Member Code?</label>
                            <div class="input-group">
                                <input type="text" id="coupon_code" class="form-control" placeholder="Enter unique code" style="border-radius: 4px 0 0 4px; border: 1px solid #ddd;">
                                <button type="button" class="btn btn-dark" onclick="applyDiscount()" style="border-radius: 0 4px 4px 0; font-weight: 700;">APPLY</button>
                            </div>
                            <small id="discount_message" class="d-block mt-1" style="display: none;"></small>
                        </div>

                        <div class="line my-3" style="border-top: 1px dashed #ddd;"></div>

                        {{-- PRICE CALCULATION --}}
                        <div class="checkout-cart-by">
                            @php
                                $unitPrice = (int)str_replace(',', '', request('selected_price', 50000));
                                $qty = (int)request('quantity', 1);
                            @endphp

                            <div class="d-flex justify-content-between mb-2">
                                <div class="text-muted">Subtotal</div>
                                <div>{{ number_format($unitPrice) }} MMK</div>
                            </div>

                            <div id="discount_row" class="d-flex justify-content-between mb-2 text-success" style="display: none !important;">
                                <div>Discount (<span id="discount_percent">0</span>%)</div>
                                <div>-<span id="discount_amount">0</span> MMK</div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-3 mb-4">
                                <h4 style="font-weight: 800; margin: 0;">Total</h4>
                                <h4 id="final_total_display" style="font-weight: 800; color: #000; margin: 0;">{{ number_format($unitPrice) }} MMK</h4>
                            </div>

                            {{-- FINAL PAYMENT FORM --}}
                            <form action="{{ route('dinner.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="dinner_id" value="{{ request('dinner_id') }}">
                                <input type="hidden" name="type" value="{{ request('selected_type') }}">
                                <input type="hidden" name="quantity" value="{{ $qty }}">
                                
                                <input type="hidden" name="first_name" value="{{ request('first_name') }}">
                                <input type="hidden" name="middle_name" value="{{ request('middle_name') }}">
                                <input type="hidden" name="last_name" value="{{ request('last_name') }}">
                                <input type="hidden" name="email" value="{{ request('guest_email') }}">
                                <input type="hidden" name="phone" value="{{ request('guest_phone') }}">
                                
                                {{-- ADDED: Viber Hidden Input --}}
                                <input type="hidden" name="viber" value="{{ request('viber') ?? request('guest_phone') }}">

                                <input type="hidden" name="applied_code" id="applied_code_input" value="">
                                <input type="hidden" name="total_price" id="final_total_input" value="{{ $unitPrice }}">

                                <button type="submit" class="tf-btn w-100 justify-content-center" 
                                    style="background-color: #f59e0b; color: #fff; border: none; padding: 20px; font-weight: 800; font-size: 18px; border-radius: 4px;">
                                    PROCEED TO PAYMENT
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection