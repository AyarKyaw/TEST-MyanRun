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
        min-height: 500px !important; 
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
        border-color: #f59e0b !important; /* Gold/Amber for Dinner */
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

    .ticket-card.active .price-tag {
        color: #f59e0b !important;
    }

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
</style>
@endpush

@section('content')
<div id="dinner-ticket-scope" class="py-20 px-4 mt-20">
    <form action="{{ route('dinner.register') }}" method="GET">
        <input type="hidden" name="dinner_id" value="{{ $dinner->id }}">
    
        <input type="hidden" name="selected_type" id="input-type" value="Standard Guest">
        <input type="hidden" name="selected_price" id="input-price" value="50,000">

        <div class="max-w-5xl mx-auto">
            <header class="text-center mb-16">
                <h2 class="text-[#f59e0b] font-black uppercase tracking-widest text-lg mb-2">Yangon International 2026</h2>
                <h1 class="text-6xl font-black italic tracking-tighter uppercase text-slate-900">
                    Gala <span class="text-[#f59e0b]">Dinner</span>
                </h1>
                <p class="text-slate-400 font-bold mt-4 uppercase text-[10px] tracking-[0.4em]">Choose your seating experience</p>
            </header>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="ticket-card active" 
                    style="background-image: url('https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&q=80&w=800');"
                    data-name="Standard Guest" data-price="50,000"
                    onclick="selectTicket(this)">
                    <span class="category-badge">Standard</span>
                    <div class="text-white relative z-10 text-center mb-2 px-6">
                        <p class="text-xs uppercase font-bold tracking-widest opacity-70">Full Course Dinner</p>
                    </div>
                    <div class="price-tag">50,000 <span class="text-lg opacity-60">MMK</span></div>
                </div>

                <div class="ticket-card" 
                    style="background-image: url('https://images.unsplash.com/photo-1519671482749-fd09be7ccebf?auto=format&fit=crop&q=80&w=800');"
                    data-name="VIP Table" data-price="120,000"
                    onclick="selectTicket(this)">
                    <span class="category-badge">VIP Premium</span>
                    <div class="text-white relative z-10 text-center mb-2 px-6">
                        <p class="text-xs uppercase font-bold tracking-widest opacity-70">Front Row + Wine Service</p>
                    </div>
                    <div class="price-tag">120,000 <span class="text-lg opacity-60">MMK</span></div>
                </div>
            </div>

            <div class="mt-16 bg-slate-900 p-8 text-white flex flex-col md:flex-row items-center justify-between gap-8 shadow-2xl rounded-[40px]">
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
                
                <div class="flex items-center gap-10">
                    <div class="text-right">
                        <p class="text-slate-400 font-black uppercase text-[10px] tracking-widest">Total Price</p>
                        <div id="summary-price" class="text-4xl font-black tracking-tighter text-[#f59e0b]">50,000 MMK</div>
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
    function selectTicket(card) {
        // Remove active class from others
        document.querySelectorAll('.ticket-card').forEach(c => c.classList.remove('active'));
        
        // Add active class to clicked card
        card.classList.add('active');
        
        // Update hidden inputs and summary text
        const name = card.getAttribute('data-name');
        const price = card.getAttribute('data-price');
        
        document.getElementById('summary-name').innerText = name;
        document.getElementById('summary-price').innerText = price + ' MMK';
        
        document.getElementById('input-type').value = name;
        document.getElementById('input-price').value = price;
    }
</script>
@endpush