<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYAN RUN - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('images/icon/Myan Run icon.png') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom style to hide number arrows */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="bg-slate-50 font-sans relative">

    <div class="absolute top-6 left-6">
        <a href="/" class="flex items-center gap-2 text-slate-500 hover:text-[#C3E92D] transition-colors font-semibold group">
            <div class="w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center group-hover:shadow-[#C3E92D]/20 transition-all border border-slate-100">
                <i class="fas fa-home"></i>
            </div>
            <span class="hidden md:block">Home</span>
        </a>
    </div>

    <div class="max-w-3xl mx-auto my-12 bg-white p-10 rounded-2xl shadow-xl border border-slate-100">
        <div class="text-center mb-10">
            <div class="flex items-center justify-center gap-4 mb-2">
                <img src="{{ asset('images/MyanRun_Orange_RM2.png') }}" alt="Myan Run Logo" class="h-32 w-auto object-contain">
            </div>
            <p class="text-slate-500 mt-2">Join the ultimate running experience</p>
        </div>

        <form id="registrationForm" class="space-y-8" action="{{ route('register.submit') }}" method="POST">
            @csrf
            
            <div>
                <h2 class="text-xl font-bold mb-6 text-slate-800 flex items-center border-b pb-2">
                    <span class="text-[#C3E92D] mr-3"><i class="fas fa-user-edit"></i></span>
                    Personal Information
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <input type="text" name="first_name" placeholder="First Name" required value="{{ old('first_name') }}"
                               oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '');"
                               class="w-full p-3 border @error('first_name') border-red-500 @else border-slate-300 @enderror rounded-lg focus:ring-2 focus:ring-[#C3E92D] outline-none">
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <input type="text" name="middle_name" placeholder="Middle Name (Opt)" value="{{ old('middle_name') }}"
                           oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '');"
                           class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#C3E92D] outline-none">
                    
                    <div>
                        <input type="text" name="last_name" placeholder="Last Name" required value="{{ old('last_name') }}"
                               oninput="this.value = this.value.replace(/[^a-zA-Z]/g, '');"
                               class="w-full p-3 border @error('last_name') border-red-500 @else border-slate-300 @enderror rounded-lg focus:ring-2 focus:ring-[#C3E92D] outline-none">
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <div class="flex border @error('phone') border-red-500 @else border-slate-300 @enderror rounded-lg overflow-hidden">
                            <select class="p-3 bg-slate-50 border-r text-slate-600 outline-none"><option>+95 9</option></select>
                            <input type="tel" name="phone" placeholder="912345678" required value="{{ old('phone') }}"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, '');" class="w-full p-3 outline-none">
                        </div>
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <input type="email" name="email" placeholder="Email Address" required value="{{ old('email') }}"
                               oninput="this.value = this.value.replace(/[^a-zA-Z0-9@._-]/g, '');"
                               class="w-full p-3 border @error('email') border-red-500 @else border-slate-300 @enderror rounded-lg focus:ring-2 focus:ring-[#C3E92D] outline-none">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-bold mb-6 text-slate-800 flex items-center border-b pb-2">
                    <span class="text-[#C3E92D] mr-3"><i class="fas fa-lock"></i></span>
                    Security
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="relative">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" placeholder="••••••••" required 
                                   oninput="this.value = this.value.replace(/[^\x00-\x7F]/g, '');"
                                   class="w-full p-3 border @error('password') border-red-500 @else border-slate-300 @enderror rounded-lg focus:ring-2 focus:ring-[#C3E92D] outline-none pr-10">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-slate-400 hover:text-[#C3E92D] toggle-password" data-target="password">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div class="relative">
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="confirm_password" placeholder="••••••••" required 
                                   oninput="this.value = this.value.replace(/[^\x00-\x7F]/g, '');"
                                   class="w-full p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#C3E92D] outline-none pr-10">
                            <span class="absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-slate-400 hover:text-[#C3E92D] toggle-password" data-target="confirm_password">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <p id="passwordError" class="text-red-500 text-xs mt-2 hidden">Passwords do not match!</p>
            </div>

            <div class="pt-4">
                <button type="submit" class="w-full bg-[#C3E92D] hover:bg-[#b5d82a] text-slate-900 font-bold py-4 rounded-xl shadow-lg transition-all active:scale-95 uppercase">
                    Register
                </button>
                <p class="text-center mt-6 text-slate-600">
                    Already have an account? <a href="{{ route('login') }}" class="text-[#C3E92D] font-bold hover:underline">Login Here</a>
                </p>
            </div>
        </form>
    </div>

    <script>
        // Password Visibility Toggle Logic
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                input.type = input.type === 'password' ? 'text' : 'password';
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        });

        // Form Validation
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const pass = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            
            // Password Match Check
            if (pass !== confirm) {
                e.preventDefault();
                document.getElementById('passwordError').classList.remove('hidden');
                return;
            }

            // Secondary check to ensure no non-English characters (Unicode) sneak through
            const englishPattern = /^[a-zA-Z0-9@._\-\s!@#$%^&*()_+<>?:"{}|]*$/;
            const fields = ['first_name', 'last_name', 'email'];
            
            for(let name of fields) {
                let input = document.querySelector(`input[name="${name}"]`);
                if (!englishPattern.test(input.value)) {
                    e.preventDefault();
                    alert(`Please use only English characters in the ${name.replace('_', ' ')} field.`);
                    return;
                }
            }
        });
    </script>
</body>
</html>