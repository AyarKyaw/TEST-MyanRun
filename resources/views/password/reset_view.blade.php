<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYAN RUN - New Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-50 font-sans flex items-center justify-center min-h-screen">

    <div class="max-w-md w-full bg-white p-10 rounded-2xl shadow-xl border border-slate-100 mx-4">
        <div class="text-center mb-10">
            <h2 class="text-2xl font-bold text-slate-800">Set New Password</h2>
            <p class="text-slate-500 mt-2">Choose a strong password for your account</p>
        </div>

        <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">New Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-lock"></i>
                    </span>
                    <input type="password" name="password" placeholder="••••••••" required 
                           class="w-full pl-10 p-3 border @error('password') border-red-500 @else border-slate-300 @enderror rounded-lg focus:ring-2 focus:ring-[#C3E92D] outline-none">
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Confirm Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                        <i class="fas fa-check-circle"></i>
                    </span>
                    <input type="password" name="password_confirmation" placeholder="••••••••" required 
                           class="w-full pl-10 p-3 border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#C3E92D] outline-none">
                </div>
            </div>

            <button type="submit" class="w-full bg-[#C3E92D] hover:bg-slate-800 text-slate-900 hover:text-white font-bold py-3.5 rounded-xl shadow-lg transition-all transform hover:-translate-y-0.5">
                UPDATE PASSWORD
            </button>
        </form>
    </div>
</body>
</html>