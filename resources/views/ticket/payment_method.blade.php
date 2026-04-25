@extends('layouts.master')
@section('title', 'Select Payment - MYAN RUN')

@section('content')
<div id="ticket-page-scope" class="py-20 px-4 min-h-screen bg-slate-50">
    <div class="max-w-3xl mx-auto">
        <header class="text-center mb-16">
            <h2 class="text-[#C3E92D] font-black uppercase tracking-widest text-lg mb-2">Checkout</h2>
            <h1 class="text-5xl font-black italic tracking-tighter uppercase text-slate-900 leading-tight">
                Choose <span class="text-[#C3E92D]">Payment</span>
            </h1>
            <p class="text-slate-400 font-bold mt-4 uppercase text-[10px] tracking-[0.4em]">Select your preferred method</p>
        </header>

        <form action="{{ route('payment.method.post') }}" method="POST">
            @csrf
            {{-- Payment Method Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
                
                {{-- KBZPay Option (Now as a full image) --}}
                <label class="ticket-card bg-slate-900 border-2 border-slate-200 cursor-pointer transition-all hover:border-[#C3E92D]" id="card-kbz">
                    <input type="radio" name="payment_method" value="mmqr" class="peer sr-only" checked>
                    <img src="{{ asset('images/MMQR.jpg') }}" alt="KBZPay QR Instruction" class="ticket-image rounded-[30px] w-full h-full object-contain p-4">
                </label>

                {{-- MMQR Option (Now as a full image) --}}
                <label class="ticket-card bg-slate-900 border-2 border-slate-200 cursor-pointer transition-all hover:border-[#C3E92D]" id="card-mmqr">
                    <input type="radio" name="payment_method" value="kbz" class="peer sr-only">
                    <img src="{{ asset('images/KBZ_Bank_logo.png') }}" alt="MMQR Instruction" class="ticket-image rounded-[30px] w-full h-full object-contain p-4">
                </label>
            </div>

            {{-- Footer Action Bar --}}
            <div class="bg-slate-900 p-8 shadow-2xl rounded-[32px] flex items-center justify-between">
                <div class="text-white">
                    <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest">Final Step</p>
                    <h4 class="text-xl text-white font-black uppercase italic">Confirm Selection</h4>
                    <p id="selected-method-text" class="text-white text-lg font-black uppercase tracking-widest mt-2">
                        Selected: 
                    </p>
                </div>
                <button type="submit" class="bg-[#C3E92D] hover:scale-105 active:scale-95 text-slate-900 px-16 py-5 font-black uppercase tracking-widest text-sm transition-all shadow-xl rounded-full">
                    Confirm
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .ticket-card { min-height: 300px !important; border-radius: 32px !important; overflow: hidden; position: relative; }
    
    /* Ensure the image fills the card and sits behind any text if added */
    .ticket-image { position: absolute; inset: 0; z-index: 1; }

    /* Apply the "active" style when the radio is checked */
    #card-kbz:has(input:checked), #card-mmqr:has(input:checked) { 
        border-color: #C3E92D !important; 
        transform: translateY(-10px); 
        box-shadow: 0 20px 40px rgba(195, 233, 45, 0.3); 
    }
</style>

<script>
    // Listen for changes on any radio button
    document.querySelectorAll('input[name="payment_method"]').forEach((radio) => {
        radio.addEventListener('change', function() {
            const methodText = this.value === 'kbz' ? 'KBZPay' : 'MMQR';
            document.getElementById('selected-method-text').innerText = 'Selected: ' + methodText;
        });
    });
</script>
@endsection