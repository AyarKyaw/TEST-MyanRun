@extends('layouts.master')

@section('title', 'Dinner Tickets - MYANRUN')

@push('styles')
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');

    #dinner-ticket-scope {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        background-color: #F8FAFC;
    }

    .ticket-card { 
        position: relative !important;
        overflow: hidden !important;
        display: flex !important;
        flex-direction: column !important;
        align-items: center !important;      
        justify-content: flex-end !important; 
        padding-bottom: 3rem !important;      
        transition: all 0.5s ease !important;
        cursor: pointer !important;
        border-radius: 32px !important;
        min-height: 450px !important; 
        border: 4px solid transparent !important;
        background-size: cover !important;
        background-position: center center !important;
        background-repeat: no-repeat !important;
        width: 100% !important;
    }

    .ticket-card::before {
        content: '' !important;
        position: absolute !important;
        inset: 0 !important;
        background: linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.2) 60%) !important;
        transition: opacity 0.5s !important;
        opacity: 0.9 !important;
        z-index: 1 !important;
    }
    
    .ticket-card.active { 
        border-color: #f59e0b !important;
        transform: translateY(-10px) !important;
        box-shadow: 0 25px 50px -12px rgba(245, 158, 11, 0.4) !important;
    }

    .price-tag { 
        position: relative !important;
        z-index: 10 !important;
        color: #ffffff !important;
        font-weight: 900 !important;
        font-size: 2.5rem !important; 
        text-align: center !important;
        letter-spacing: -0.05em !important;
    }

    .ticket-card.active .price-tag { color: #f59e0b !important; }

    .category-badge { 
        position: absolute !important;
        top: 2rem !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        white-space: nowrap !important;
        padding: 0.5rem 1.25rem !important;
        border-radius: 9999px !important;
        font-size: 12px !important;
        font-weight: 900 !important;
        text-transform: uppercase !important;
        z-index: 10 !important;
        color: white !important;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(12px) !important;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .ticket-card.active .category-badge {
        background-color: #f59e0b !important;
        color: #0f172a !important;
    }

    .qty-btn {
        @apply w-10 h-10 rounded-full border border-white/20 flex items-center justify-center transition-all hover:bg-white/10 active:scale-90;
    }
</style>
@endpush

@section('content')
<div id="dinner-ticket-scope" class="py-20 px-4 mt-20">
    <form action="{{ route('dinner.register') }}" method="GET">
        <input type="hidden" name="dinner_id" value="{{ $dinner->id }}">
        {{-- Set default price to 55000 --}}
        <input type="hidden" name="selected_type" id="input-type" value="Standard Guest">
        <input type="hidden" name="selected_price" id="input-price" value="55000">
        <input type="hidden" name="quantity" id="input-qty" value="1">

        <div class="max-w-6xl mx-auto">
            <header class="text-center mb-16">
                <h2 class="text-[#f59e0b] font-black uppercase tracking-widest text-lg mb-2">Yangon International 2026</h2>
                <h1 class="text-6xl font-black italic tracking-tighter uppercase text-slate-900">
                    Gala <span class="text-[#f59e0b]">Dinner</span>
                </h1>
                <p class="text-slate-400 font-bold mt-4 uppercase text-[10px] tracking-[0.4em]">Choose your seating quantity</p>
            </header>

            {{-- Centered single card layout --}}
            <div class="flex justify-center">
                <div class="ticket-card active max-w-md" 
                    style="background-image: url('https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&q=80&w=800');"
                    data-name="Standard Guest" 
                    data-raw-price="55000"
                    onclick="selectTicket(this)">
                    <span class="category-badge">Standard</span>
                    <div class="text-white relative z-10 text-center mb-2 px-6">
                        <p class="text-xs uppercase font-bold tracking-widest opacity-70">Full Course Dinner</p>
                    </div>
                    <div class="price-tag">55,000 <span class="text-lg opacity-60">MMK</span></div>
                </div>
            </div>

            <div class="mt-16 bg-slate-900 p-8 text-white flex flex-col lg:flex-row items-center justify-between gap-8 shadow-2xl rounded-[40px]">
                
                <div class="flex items-center gap-6">
                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-2xl">
                        <i class="fas fa-wine-glass text-[#f59e0b]"></i>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black uppercase italic tracking-tighter">
                            <span id="summary-name" class="text-[#f59e0b]">Standard Guest</span>
                        </h4>
                        <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest">Lotte Hotel Yangon • Dec 2026</p>
                    </div>
                </div>

                <div class="flex items-center gap-6 bg-white/5 px-6 py-3 rounded-3xl border border-white/10">
                    <p class="text-slate-400 font-black uppercase text-[10px] tracking-widest">Quantity</p>
                    <div class="flex items-center gap-5">
                        <button type="button" onclick="updateQty(-1)" class="qty-btn"><i class="fas fa-minus"></i></button>
                        <span id="display-qty" class="text-2xl font-black w-8 text-center">1</span>
                        <button type="button" onclick="updateQty(1)" class="qty-btn"><i class="fas fa-plus"></i></button>
                    </div>
                </div>
                
                <div class="flex items-center gap-10">
                    <div class="text-right">
                        <p class="text-slate-400 font-black uppercase text-[10px] tracking-widest">Total Price</p>
                        <div id="summary-price" class="text-4xl font-black tracking-tighter text-[#f59e0b]">55,000 MMK</div>
                    </div>
                    <button type="submit" class="bg-[#f59e0b] hover:scale-105 active:scale-95 text-white px-12 py-5 font-black uppercase tracking-widest text-sm transition-all shadow-xl rounded-full">
                        CONFIRM
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    let currentPricePerTicket = 55000; // Base price updated
    let currentQty = 1;

    function selectTicket(card) {
        // Even though there is only one, this keeps the logic robust
        document.querySelectorAll('.ticket-card').forEach(c => c.classList.remove('active'));
        card.classList.add('active');
        
        const name = card.getAttribute('data-name');
        currentPricePerTicket = parseInt(card.getAttribute('data-raw-price'));
        
        document.getElementById('summary-name').innerText = name;
        document.getElementById('input-type').value = name;
        
        calculateTotal();
    }

    function updateQty(val) {
        currentQty += val;
        if (currentQty < 1) currentQty = 1; 
        if (currentQty > 10) currentQty = 10; 
        
        document.getElementById('display-qty').innerText = currentQty;
        document.getElementById('input-qty').value = currentQty;
        
        calculateTotal();
    }

    function calculateTotal() {
        const total = currentPricePerTicket * currentQty;
        const formattedTotal = total.toLocaleString();
        
        document.getElementById('summary-price').innerText = formattedTotal + ' MMK';
        document.getElementById('input-price').value = total;
    }
</script>
@endpush