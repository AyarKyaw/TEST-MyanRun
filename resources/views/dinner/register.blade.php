<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dinner Registration - MYAN RUN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/icon/Myan Run icon.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .section-card { background: white; padding: 40px; border-radius: 40px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); border: 1px solid #f1f5f9; }
        .input-field { width: 100%; padding: 16px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; outline: none; transition: all 0.3s; font-weight: 600; color: #334155; }
        .input-field:focus { border-color: #f59e0b; background-color: white; box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1); }
        .label-text { font-size: 10px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-left: 8px; margin-bottom: 8px; display: block; }
    </style>
</head>

<body class="py-16 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-10">
            <img src="{{ asset('images/MyanRun_Orange_RM2.png') }}" alt="Myan Run Logo" class="h-28 w-auto mx-auto object-contain">
            <p class="text-slate-400 font-bold mt-4 uppercase text-[10px] tracking-[0.4em]">Gala Dinner Guest Registration</p>
        </div>

        <div class="alert alert-info mb-4 p-4 bg-blue-50 text-blue-700 rounded-2xl border border-blue-100 text-sm">
            Registering for: <strong>{{ $dinner->name }}</strong>
        </div>

        <form action="{{ route('dinner.checkout') }}" method="GET">
            {{-- Pass everything to the next page --}}
            <input type="hidden" name="selected_type" value="{{ $selected_type }}">
            <input type="hidden" name="selected_price" value="{{ $selected_price }}">
            <input type="hidden" name="dinner_id" value="{{ request('dinner_id') }}">
            <input type="hidden" name="quantity" value="{{ request('quantity', 1) }}">

            @php
                $qty = (int)request('quantity', 1);
                // We do NOT multiply here because $selected_price is already the total
                $totalToDisplay = (int)str_replace(',', '', $selected_price);
            @endphp

            <div class="mb-8 p-6 bg-slate-900 rounded-[32px] text-white flex items-center justify-between shadow-xl border-b-4 border-[#f59e0b]">
                <div class="flex items-center gap-5">
                    <div class="w-12 h-12 bg-[#f59e0b] rounded-2xl flex items-center justify-center text-white text-lg">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Selected Ticket</p>
                        <h4 class="text-lg font-black italic uppercase tracking-tighter text-[#f59e0b]">
                            {{ $selected_type }} <span class="text-white opacity-60 text-sm ml-2">x {{ $qty }}</span>
                        </h4>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Total Price</p>
                    <p class="text-xl font-black tracking-tighter">
                        {{ number_format($totalToDisplay) }} <span class="text-xs opacity-50">MMK</span>
                    </p>
                </div>
            </div>

            <div class="section-card mb-8">
                <div class="flex items-center mb-10">
                    <span class="w-10 h-10 bg-[#f59e0b] text-white rounded-xl flex items-center justify-center font-black mr-4 shadow-lg shadow-amber-100">
                        <i class="fas fa-address-card"></i>
                    </span>
                    <h3 class="text-xl font-black text-slate-800 uppercase italic tracking-tighter">Guest Information</h3>
                </div>

                <div class="space-y-8">
                    {{-- Display-only Quantity Info --}}
                    <div class="flex items-center justify-between px-4 py-3 bg-slate-50 rounded-xl border border-slate-100">
                        <span class="text-[10px] font-black text-slate-400 uppercase">Number of Tickets:</span>
                        <span class="font-bold text-slate-700">{{ $qty }} Tickets</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="label-text">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="input-field" placeholder="First Name" required>
                        </div>
                        <div>
                            <label class="label-text">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name') }}" class="input-field" placeholder="Optional">
                        </div>
                        <div>
                            <label class="label-text">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="input-field" placeholder="Last Name" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="label-text">Email Address</label>
                            <input type="email" name="guest_email" value="{{ old('guest_email') }}" class="input-field" placeholder="email@example.com" required>
                        </div>
                        <div>
                            <label class="label-text">Phone Number</label>
                            <input type="tel" name="guest_phone" value="{{ old('guest_phone') }}" class="input-field" placeholder="09..." required>
                        </div>
                        <div class="mt-6">
                            <label class="label-text">Viber Number</label>
                            <div class="relative">
                                <input type="tel" name="viber" value="{{ old('viber') }}" class="input-field pr-12" placeholder="09...">
                                <div class="absolute right-5 top-1/2 -translate-y-1/2 text-[#7360F2] text-xl">
                                    <i class="fab fa-viber"></i>
                                </div>
                            </div>
                            <p class="text-[9px] text-slate-400 mt-2 ml-2 font-bold uppercase tracking-wider">
                                * We will use this to send your digital ticket and event updates.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#f59e0b] hover:bg-slate-800 text-white font-black py-5 rounded-[2rem] shadow-xl shadow-amber-100 transition-all uppercase tracking-widest text-sm">
                Proceed to Review & Pay
            </button>
        </form>

        <p class="text-center text-slate-400 text-[10px] mt-8 uppercase font-bold tracking-widest">
            Secured by Myan Run Registration System
        </p>
    </div>
</body>
</html>