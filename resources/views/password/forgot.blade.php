<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYAN RUN - Account Recovery</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/icon/Myan Run icon.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans flex items-center justify-center min-h-screen relative">

    <div class="absolute top-6 left-6">
        <a href="/" class="flex items-center gap-2 text-slate-500 hover:text-[#C3E92D] transition-colors font-semibold group">
            <div class="w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center group-hover:shadow-[#C3E92D]/20 transition-all border border-slate-100">
                <i class="fas fa-home"></i>
            </div>
            <span class="hidden md:block">Home</span>
        </a>
    </div>

    <div class="max-w-md w-full bg-white p-10 rounded-2xl shadow-xl border border-slate-100 mx-4">
        <div class="text-center mb-10">
            <div class="flex items-center justify-center gap-4 mb-2">
                <img src="{{ asset('images/MyanRun_Orange_RM2.png') }}" alt="Myan Run Logo" class="h-32 w-auto object-contain">
            </div>
            <h2 class="text-2xl font-bold text-slate-800">Account Recovery</h2>
            <p class="text-slate-500 mt-2">Verify your details to reset your password</p>
        </div>

        <form id="forgotForm" class="space-y-6" action="{{ route('password.verify_user') }}" method="POST">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-id-card"></i>
                    </span>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required 
                        class="w-full pl-10 p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#C3E92D] outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Phone Number</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-phone"></i>
                    </span>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="09xxxxxxxxx" required 
                        class="w-full pl-10 p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#C3E92D] outline-none transition-all">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-envelope"></i>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="runner@example.com" required 
                        class="w-full pl-10 p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#C3E92D] outline-none transition-all">
                </div>
                @if(session('error'))
                    <p class="text-red-500 text-xs mt-1">{{ session('error') }}</p>
                @endif
            </div>

            <button type="submit" class="w-full bg-[#C3E92D] hover:bg-slate-800 text-slate-900 hover:text-white font-bold py-3.5 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 active:scale-95">
                VERIFY DETAILS
            </button>
        </form>
    </div>
</body>
</html>