@extends('layouts.master')

@section('title', 'About - MYANRUN')

@section('content')
<div class="p-6 md:p-12 max-w-2xl mx-auto">
    <div class="bg-white p-8 md:p-12 rounded-[40px] shadow-sm border border-slate-50">
        <h2 class="text-2xl font-black text-slate-900 mb-8 uppercase italic">Change Password</h2>
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 font-bold rounded-2xl text-sm">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 text-red-700 font-bold rounded-2xl text-sm">
                <ul class="list-disc pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.password.update') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Current Password</label>
                <input type="password" name="current_password" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold" required>
            </div>
            
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">New Password</label>
                <input type="password" name="new_password" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold" required>
            </div>

            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="w-full bg-slate-50 border-none rounded-2xl p-4 font-bold" required>
            </div>

            <button type="submit" class="w-full bg-brand text-black font-black py-4 rounded-2xl uppercase tracking-widest hover:bg-brandDark transition-all">
                Update Password
            </button>
        </form>
    </div>
</div>
@endsection