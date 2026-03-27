<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agent Portal - MYAN RUN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/icon/Myan Run icon.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="font-sans flex items-center justify-center min-h-screen relative" 
      style="
        /* Full-page fixed background image */
        background-image: 
            /* Gradient overlay for text readability */
            /* linear-gradient(rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 0.9)),  */
            url('{{ asset('images/home_banner/Home Banner (1).jpeg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
      ">
    <div class="max-w-md w-full bg-white/95 p-10 rounded-2xl shadow-2xl border border-slate-200 mx-4 backdrop-blur-sm">
        <div class="text-center mb-10">
            <div class="flex items-center justify-center gap-4 mb-4">
                <img src="{{ asset('images/MyanRun_Orange_RM2.png') }}" alt="Myan Run Logo" class="h-24 w-auto object-contain">
            </div>
            <span class="px-3 py-1 bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-widest rounded-full border border-slate-200">
                Administrative Access
            </span>
            <p class="text-slate-500 mt-4 text-sm">Authorized personnel only. Please sign in to manage the system.</p>
        </div>

        @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 text-sm rounded-r-lg">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <form id="loginForm" class="space-y-6" action="{{ route('agent.login.submit') }}" method="POST">
            @csrf
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-2">Agent Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-user-tie"></i>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="agent@myanrun.com" required 
                        class="w-full pl-10 p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#C3E92D] focus:border-transparent outline-none transition-all">
                </div>
                @error('email')
                    <p class="text-red-500 text-[10px] mt-1 italic">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex justify-between mb-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600">Secure Password</label>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-key"></i>
                    </span>
                    <input type="password" name="password" id="password" placeholder="••••••••" required 
                        class="w-full pl-10 pr-10 p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#C3E92D] focus:border-transparent outline-none transition-all">
                    
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-slate-400 hover:text-slate-600" id="togglePassword">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="w-full bg-slate-900 hover:bg-black text-[#C3E92D] font-bold py-4 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 active:scale-95 uppercase tracking-widest text-sm">
                Authorize Agent Access
            </button>
        </form>
    </div>

    <div class="absolute bottom-8 text-center w-full">
        <p class="text-white/50 text-[14px] uppercase tracking-[0.25em] font-medium">
            Powered by 
            <a href="https://itplus.net.mm/" target="_blank" 
               class="text-[#C3E92D] hover:text-white transition-colors duration-300 font-bold ml-1">
                IT PLUS
            </a>
        </p>
    </div>

    <script>
        // Password Visibility Toggle
        const togglePassword = document.querySelector('#togglePassword');
        const passwordInput = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
        });
    </script>
</body>
</html>