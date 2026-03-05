@extends('layouts.master')

@section('title', 'Dinner Checkout - MYANRUN')

@section('content')
    <div class="page-title page-shop">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-heading">
                        <h1 class="title">Dinner Checkout</h1>
                    </div>
                    <div class="breadcrumbs">
                        <ul>
                            <li><a href="{{ url('/') }}">Homepage</a></li>
                            <li><i class="icon-Arrow---Right-2"></i></li>
                            <li><a>Dinner Checkout</a></li>
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
                            <h3 style="font-weight: 800; margin: 0;">Guest Details</h3>
                            <p class="text-muted mb-0" style="font-size: 13px;">Please confirm your invitation info</p>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-6">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Full Name</label>
                                <p style="font-size: 16px; font-weight: 600; color: #000;">
                                    {{ request('first_name', Auth::user()->first_name ?? 'Guest') }} 
                                    {{ request('middle_name') ? request('middle_name') . ' ' : (Auth::user()->middle_name ?? '') }}
                                    {{ request('last_name', Auth::user()->last_name ?? 'User') }}
                                </p>
                            </div>
                            
                            <div class="col-md-6">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Ticket Type</label>
                                <p style="font-size: 16px; font-weight: 600; color: #f59e0b; text-transform: uppercase;">
                                    {{ request('selected_type', 'Standard Guest') }}
                                </p>
                            </div>

                            <div class="col-md-6">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Email Address</label>
                                <p style="font-size: 16px; font-weight: 600; color: #000;">
                                    {{ request('guest_email', Auth::user()->email ?? 'Not provided') }}
                                </p>
                            </div>
                            
                            <div class="col-md-6">
                                <label style="font-size: 12px; text-transform: uppercase; color: #999; font-weight: 700;">Phone Number</label>
                                <p style="font-size: 16px; font-weight: 600; color: #000;">
                                    {{ request('guest_phone', Auth::user()->phone ?? 'Not provided') }}
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
                        
                        <div class="checkout-product-wrap">
                            <div class="checkout-product-item d-flex align-items-center mb-3" style="gap: 15px;">
                                <div class="image" style="width: 70px; height: 70px; background: #1a1a1a; border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; position: relative; overflow: hidden;">
                                    <i class="fas fa-utensils" style="color: #f59e0b; font-size: 24px;"></i>
                                    <div style="position: absolute; width: 40px; height: 40px; background: #f59e0b; filter: blur(25px); opacity: 0.2;"></div>
                                </div>
                                
                                <div class="content flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="text-title d-block" style="font-weight: 700; line-height: 1.2;">
                                                Yangon International 2026
                                            </span>
                                            <small class="text-muted">Gala Dinner Seat</small>
                                            <div class="mt-1">
                                                <small class="d-block" style="font-size: 11px; color: #888;">Type: {{ request('selected_type', 'Standard Guest') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="line my-3" style="border-top: 1px dashed #ddd;"></div>

                        <div class="checkout-cart-by">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="text-title text-muted">Ticket Price</div>
                                <div class="text-title">{{ number_format((int)str_replace(',', '', request('selected_price', 50000))) }} MMK</div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-3 mb-4">
                                <h4 style="font-weight: 800; margin: 0;">Total</h4>
                                <h4 style="font-weight: 800; color: #000; margin: 0;">{{ number_format((int)str_replace(',', '', request('selected_price', 50000))) }} MMK</h4>
                            </div>
                            <div class="alert alert-info">
                                Registering for: <strong>{{ $dinner->name }}</strong> (ID: #{{ $dinner->id }})
                            </div>
                            {{-- FINAL PAYMENT FORM --}}
                            <form action="{{ route('dinner.process') }}" method="POST">
                                @csrf
                                {{-- Hidden Ticket Metadata --}}
                                <input type="hidden" name="type" value="{{ request('selected_type') }}">
                                <input type="hidden" name="price" value="{{ request('selected_price') }}">
                                <input type="hidden" name="dinner_id" value="{{ request('dinner_id') }}">

                                {{-- Hidden Guest Data for Database Storage --}}
                                <input type="hidden" name="first_name" value="{{ request('first_name') }}">
                                <input type="hidden" name="middle_name" value="{{ request('middle_name') }}">
                                <input type="hidden" name="last_name" value="{{ request('last_name') }}">
                                <input type="hidden" name="email" value="{{ request('guest_email') }}">
                                <input type="hidden" name="phone" value="{{ request('guest_phone') }}">
                                
                                <button type="submit" class="tf-btn w-100 justify-content-center" 
                                    style="background-color: #f59e0b; color: #fff; border: none; padding: 20px; font-weight: 800; font-size: 18px; text-transform: uppercase; letter-spacing: 1.5px; border-radius: 4px; box-shadow: 0 4px 14px rgba(245, 158, 11, 0.4); transition: transform 0.2s;">
                                    PROCEED TO PAYMENT
                                </button>
                            </form>
                            
                            <p class="text-center mt-3" style="font-size: 11px; text-transform: uppercase; color: #999; letter-spacing: 1px;">
                                Secured by MYANRUN Booking System
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection