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
        .section-card { background: white; padding: 40px; border-radius: 40px; box-shadow: 0 10px 30px -12px rgb(0 0 0 / 0.05); border: 1px solid #f1f5f9; }
        .input-field { width: 100%; padding: 16px; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; outline: none; transition: all 0.3s; font-weight: 600; color: #334155; }
        .input-field:focus { border-color: #f59e0b; background-color: white; box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1); }
        .input-field.error { border-color: #ef4444; background-color: #fef2f2; }
        .label-text { font-size: 10px; font-weight: 900; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; margin-left: 8px; margin-bottom: 8px; display: block; }
        .error-msg { font-size: 9px; color: #ef4444; font-weight: 800; text-transform: uppercase; margin-top: 6px; margin-left: 8px; display: none; }
    </style>
</head>

<body class="py-16 px-4">
    <div class="max-w-2xl mx-auto">
        <div class="text-center mb-10">
            <img src="{{ asset('images/MyanRun_Orange_RM2.png') }}" alt="Myan Run Logo" class="h-20 w-auto mx-auto object-contain">
            <p class="text-slate-400 font-bold mt-4 uppercase text-[10px] tracking-[0.4em]">Gala Dinner Guest Registration</p>
        </div>

        <div class="mb-4 p-4 bg-amber-50 text-amber-700 rounded-2xl border border-amber-100 text-xs font-bold text-center uppercase tracking-wider">
            Registering for: <span class="text-slate-900">{{ $dinner->name }}</span>
        </div>

        <form action="{{ route('dinner.checkout') }}" method="GET" id="registrationForm">
            <input type="hidden" name="selected_type" value="{{ $selected_type }}">
            <input type="hidden" name="selected_price" value="{{ $selected_price }}">
            <input type="hidden" name="dinner_id" value="{{ request('dinner_id') }}">
            <input type="hidden" name="quantity" value="{{ request('quantity', 1) }}">

            @php
                $qty = (int)request('quantity', 1);
                $totalToDisplay = (int)str_replace(',', '', $selected_price);
            @endphp

            <div class="mb-8 p-6 bg-slate-900 rounded-[32px] text-white flex items-center justify-between shadow-xl border-b-4 border-[#f59e0b]">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-[#f59e0b] rounded-2xl flex items-center justify-center text-white text-lg shadow-lg shadow-amber-500/20">
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

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="label-text">First Name</label>
                            <input type="text" name="first_name" pattern="[A-Za-z\s]+" title="Please use only letters" value="{{ old('first_name') }}" class="input-field alpha-only" placeholder="First Name" required>
                        </div>
                        <div>
                            <label class="label-text">Middle Name</label>
                            <input type="text" name="middle_name" pattern="[A-Za-z\s]+" title="Please use only letters" value="{{ old('middle_name') }}" class="input-field alpha-only" placeholder="Optional">
                        </div>
                        <div>
                            <label class="label-text">Last Name</label>
                            <input type="text" name="last_name" pattern="[A-Za-z\s]+" title="Please use only letters" value="{{ old('last_name') }}" class="input-field alpha-only" placeholder="Last Name" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label class="label-text">Phone Number</label>
        <input type="tel" id="guest_phone" name="guest_phone" maxlength="11" value="{{ old('guest_phone') }}" class="input-field numeric-only" placeholder="eg. 091234567" required>
        <p id="phone_error" class="error-msg">Must be between 9 and 11 digits</p>
    </div>
    <div>
        <label class="label-text">Viber Number</label>
        <div class="relative">
            <input type="tel" id="viber" name="viber" maxlength="11" value="{{ old('viber') }}" class="input-field pr-12 numeric-only" placeholder="eg. 091234567" required>
            <div class="absolute right-5 top-1/2 -translate-y-1/2 text-[#7360F2] text-xl">
                <i class="fab fa-viber"></i>
            </div>
        </div>
        <p id="viber_error" class="error-msg">Must be between 9 and 11 digits</p>
    </div>
</div>

                    <div>
                        <label class="label-text">Email Address</label>
                        <input type="email" name="guest_email" value="{{ old('guest_email') }}" class="input-field" placeholder="email@example.com" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-[#f59e0b] hover:bg-slate-800 text-white font-black py-5 rounded-[2rem] shadow-xl shadow-amber-200/50 transition-all uppercase tracking-widest text-sm active:scale-95">
                Proceed to Review & Pay
            </button>
        </form>
    </div>

    <script>
    const form = document.getElementById('registrationForm');
    const phoneInput = document.getElementById('guest_phone');
    const viberInput = document.getElementById('viber');
    const phoneError = document.getElementById('phone_error');
    const viberError = document.getElementById('viber_error');

    // 1. Alpha-only Restriction (Names)
    document.querySelectorAll('.alpha-only').forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^A-Za-z\s]/g, '');
        });
    });

    // 2. Numeric Restriction
    document.querySelectorAll('.numeric-only').forEach(input => {
        input.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
            
            // Live validation: Hide error if it falls within 9-11 range
            if (this.value.length >= 9 && this.value.length <= 11) {
                this.classList.remove('error');
                const errorId = this.id === 'guest_phone' ? 'phone_error' : 'viber_error';
                document.getElementById(errorId).style.display = 'none';
            }
        });
    });

    // 3. Form Submission Validation
    form.addEventListener('submit', function(e) {
        let hasError = false;

        // Validate Phone (Required, 9-11 digits)
        const phoneLen = phoneInput.value.length;
        if (phoneLen < 9 || phoneLen > 11) {
            phoneInput.classList.add('error');
            phoneError.style.display = 'block';
            hasError = true;
        }

        // Validate Viber (Optional, but if filled must be 9-11 digits)
        const viberLen = viberInput.value.length;
        if (viberLen > 0 && (viberLen < 9 || viberLen > 11)) {
            viberInput.classList.add('error');
            viberError.style.display = 'block';
            hasError = true;
        }

        if (hasError) {
            e.preventDefault();
            const firstError = document.querySelector('.error');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
</script>
</body>
</html>