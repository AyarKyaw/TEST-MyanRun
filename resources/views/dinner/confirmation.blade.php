@extends('layouts.master')

@section('title', 'Payment & Registration - MYANRUN')

@section('content')
<section class="tf-spacing-1">
    <div class="themeflat-container">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="success-card" style="background: #fff; padding: 60px 40px; border-radius: 40px; border: 1px solid #f1f5f9; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                    
                    @php 
                        // Try to get the ticket if it exists
                        $ticket = $registration->tickets->first(); 
                        
                        // If no ticket exists yet, it means they MUST pay/upload
                        $isPending = !$ticket || $ticket->status === 'pending';
                        
                        // Get price from ticket OR from the URL if ticket doesn't exist yet
                        $displayPrice = $ticket ? $ticket->price : (int)str_replace(',', '', request('price', 0));
                    @endphp

                    <div class="qr-wrap mb-4 text-center">
                        <p style="font-weight: 800; color: #1a1a1a; margin-bottom: 15px; letter-spacing: 1px;">
                            {{ $isPending ? 'SCAN TO PAY (KPAY / WAVE)' : 'YOUR TICKET QR' }}
                        </p>
                        <div style="padding: 15px; border: 2px solid #f59e0b; border-radius: 20px; background: #fff; display: inline-block;">
                            <img src="{{ asset('images/kbz_qr.jpg') }}" alt="Payment QR" style="width: 240px; height: auto; border-radius: 12px;">
                        </div>
                    </div>

                    <h2 style="font-weight: 800; margin-bottom: 10px; text-transform: uppercase;">
                        {{ $isPending ? 'Payment Required' : 'Payment Confirmed!' }}
                    </h2>
                    
                    @if($isPending)
                        <p class="text-muted mb-4">
                            Please scan the QR code above to pay <strong>{{ number_format($displayPrice) }} MMK</strong>. Once paid, upload your screenshot below.
                        </p>

                        <div class="upload-box" style="background: #f8fafc; padding: 25px; border-radius: 20px; border: 1px solid #e2e8f0;">
                            {{-- Point to Registration ID because Ticket doesn't exist yet --}}
                            <form action="{{ route('dinner.upload.payment', $registration->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="dinner_id" value="{{ request('dinner_id') }}">
                                <input type="hidden" name="type" value="{{ request('type') }}">
                                <input type="hidden" name="price" value="{{ request('price') }}">
                                
                                <div class="mb-3 text-start">
                                    <label class="form-label" style="font-weight: 700; font-size: 12px; color: #64748b;">UPLOAD TRANSACTION SLIP</label>
                                    <input type="file" name="payment_slip" class="form-control" required style="border-radius: 10px; padding: 10px;">
                                </div>
                                <button type="submit" class="tf-btn w-100" style="background: #f59e0b; color: #fff; border-radius: 12px; padding: 15px; border: none; font-weight: 800;">
                                    SUBMIT PAYMENT PROOF
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-success" style="border-radius: 15px; background: #dcfce7; color: #166534; border: none; font-weight: 700;">
                            <i class="fas fa-check-circle"></i> YOUR REGISTRATION IS NOW ACTIVE
                        </div>
                    @endif

                    <div class="details-box mt-4 mb-4" style="background: #f8fafc; padding: 20px; border-radius: 20px; text-align: left; border: 1px solid #f1f5f9;">
                        <div class="d-flex justify-content-between mb-2">
                            <span style="color: #94a3b8; font-size: 11px; font-weight: 700;">TICKET NO:</span>
                            {{-- Show "Pending Upload" if ticket doesn't exist --}}
                            <span style="font-weight: 800;">{{ $ticket ? '#' . $ticket->ticket_no : 'WAITING FOR SLIP' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span style="color: #94a3b8; font-size: 11px; font-weight: 700;">GUEST:</span>
                            <span style="font-weight: 800;">{{ $registration->first_name }} {{ $registration->last_name }}</span>
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