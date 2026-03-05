@extends('layouts.master')

@section('title', 'Events - MYANRUN')

@section('content')
<div class="container text-center">
    <div class="card mt-5">
        <div class="card-body">
            <h4 class="card-title">Scan to Pay with KBZPay</h4>
            <p class="text-muted">Runner ID: #{{ $ticket->runner_id }}</p>
            
            <div class="py-4">
                <div id="qrcode">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $qrString }}" alt="QR Code">
                </div>
            </div>

            <h2 class="text-primary">{{ number_format($ticket->price) }} MMK</h2>
            <p>Please do not close this page after scanning.</p>
            
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Waiting for payment...</span>
            </div>
        </div>
    </div>
</div>
@endsection