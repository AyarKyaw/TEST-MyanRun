<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYAN RUN - Login</title>
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
            <p class="text-slate-500 mt-2">Welcome back to the ultimate running experience</p>
        </div>

        <form id="loginForm" class="space-y-6" action="{{ route('login.submit') }}" method="POST">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Email Address</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-user"></i>
                    </span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="runner@example.com" required 
                           oninput="this.value = this.value.replace(/[^a-zA-Z0-9@._-]/g, '');"
                           class="w-full pl-10 p-3 border @error('email') border-red-500 @else border-slate-300 @enderror rounded-lg focus:ring-2 focus:ring-[#C3E92D] outline-none transition-all">
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="flex justify-between mb-2">
                    <label class="block text-sm font-semibold text-slate-700">Password</label>
                    <a href="#" class="text-xs text-[#C3E92D] hover:underline">Forgot Password?</a>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" id="password" placeholder="••••••••" required 
                           oninput="this.value = this.value.replace(/[^\x00-\x7F]/g, '');"
                           class="w-full pl-10 pr-10 p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#C3E92D] outline-none transition-all">
                    
                    <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-slate-400 hover:text-[#C3E92D]" id="togglePassword">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="h-4 w-4 text-[#C3E92D] border-slate-300 rounded focus:ring-[#C3E92D]">
                <label for="remember" class="ml-2 text-sm text-slate-600">Remember me</label>
            </div>

            <button type="submit" class="w-full bg-[#C3E92D] hover:bg-slate-800 text-slate-900 hover:text-white font-bold py-3.5 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5 active:scale-95">
                LOGIN
            </button>

            <p class="text-center mt-8 text-slate-600">
                Don't have an account? 
                <a href="{{ route('register') }}" class="text-[#C3E92D] font-bold hover:underline ml-1">Register Here</a>
            </p>
        </form>
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

        // Final validation check on submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const emailField = document.querySelector('input[name="email"]');
            const englishPattern = /^[a-zA-Z0-9@._-]*$/;
            
            if (!englishPattern.test(emailField.value)) {
                e.preventDefault();
                alert('Please use only English characters for your email.');
            }
        });
    </script>
</body>
</html>