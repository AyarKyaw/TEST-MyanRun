@extends('dashboard.layouts.master')

@section('content')
<div class="min-h-screen bg-[#0f172a] flex items-center justify-center p-0 sm:p-4">
    <div class="w-full max-w-md h-screen sm:h-auto bg-[#1e293b] sm:rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col border border-slate-700/50">
        
        {{-- 1. Header: Compact & Professional --}}
        <div class="pt-10 pb-6 px-6 text-center border-b border-slate-700/50 bg-slate-900/50">
            <div class="inline-block px-3 py-1 rounded-full bg-amber-500/10 border border-amber-500/20 mb-3">
                <span class="text-amber-500 text-[10px] font-bold uppercase tracking-[0.2em]">Live Security Terminal</span>
            </div>
            <h1 class="text-white font-black text-2xl uppercase tracking-tighter">
                Staff <span class="text-amber-500">Scanner</span>
            </h1>
            <p class="text-slate-500 text-[10px] font-medium uppercase tracking-[0.3em] mt-1">
                {{ $dinner->name ?? 'Event Access Control' }}
            </p>
        </div>

        {{-- 2. Scanner Viewfinder: High-Tech Frame --}}
        <div class="relative flex-1 flex items-center justify-center bg-black overflow-hidden group">
            <div id="reader" class="w-full h-full"></div>
            
            {{-- Modern Corner Accents --}}
            <div id="scan-overlay" class="absolute inset-0 z-10 pointer-events-none border-[40px] border-black/40">
                <div class="absolute inset-0 border-2 border-amber-500/20"></div>
                <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-amber-500 rounded-tl-lg"></div>
                <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-amber-500 rounded-tr-lg"></div>
                <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-amber-500 rounded-bl-lg"></div>
                <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-amber-500 rounded-br-lg"></div>
                
                {{-- Scanning Line Animation --}}
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-amber-500/50 to-transparent shadow-[0_0_15px_rgba(245,158,11,0.5)] animate-scan"></div>
            </div>
        </div>

        {{-- 3. Dynamic Status Card --}}
        <div id="status-card" class="p-8 text-center bg-slate-900 transition-all duration-300">
            <div id="status-icon" class="text-4xl mb-3 text-slate-500">
                <i class="fas fa-expand animate-pulse"></i>
            </div>
            <h2 id="status-text" class="text-lg font-bold uppercase text-black tracking-widest">
                Ready to Scan
            </h2>
            <p id="status-subtext" class="text-slate-500 text-xs font-medium mt-2">
                Center the QR code within the markers
            </p>
        </div>

        {{-- 4. Bottom Actions --}}
        <div class="p-6 bg-slate-900 border-t border-slate-700/50 flex gap-3">
            <button onclick="location.reload()" class="flex-1 py-4 bg-slate-800 hover:bg-slate-700 text-slate-300 rounded-2xl font-bold text-[10px] uppercase tracking-widest transition-all border border-slate-700">
                <i class="fas fa-sync-alt mr-2"></i> Reset Camera
            </button>
        </div>
    </div>
</div>

{{-- Audio --}}
<audio id="beep-success" src="https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3"></audio>
<audio id="beep-error" src="https://assets.mixkit.co/active_storage/sfx/2573/2573-preview.mp3"></audio>

<style>
    /* Fullscreen Scan Animation */
    @keyframes scan {
        0% { top: 0%; }
        100% { top: 100%; }
    }
    .animate-scan {
        position: absolute;
        animation: scan 3s linear infinite;
    }

    #reader video { 
        object-fit: cover !important;
        filter: contrast(1.1) brightness(0.9);
    }
    
    /* Remove html5-qrcode's ugly default UI */
    #reader img[alt="Info icon"], #reader img[alt="Camera menu icon"] { display: none !important; }
    #reader__dashboard { display: none !important; }
    #reader__status_span { display: none !important; }
</style>

<script src="https://unpkg.com/html5-qrcode" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusCard = document.getElementById('status-card');
    const statusText = document.getElementById('status-text');
    const statusSub = document.getElementById('status-subtext');
    const statusIcon = document.getElementById('status-icon');
    const overlay = document.getElementById('scan-overlay');
    const beepSuccess = document.getElementById('beep-success');
    const beepError = document.getElementById('beep-error');

    let isScanning = true;

    function updateUI(status, title, sub, iconClass) {
        // Reset to default "Scanning" look before applying colors
        statusCard.className = 'p-8 text-center transition-all duration-300';
        overlay.querySelectorAll('.border-amber-500').forEach(el => {
            el.classList.remove('border-amber-500');
            el.classList.add(status === 'success' ? 'border-emerald-500' : 'border-rose-500');
        });

        if(status === 'success') {
            statusCard.classList.add('bg-emerald-600');
            statusText.className = 'text-xl font-black uppercase text-green tracking-tighter';
            statusIcon.className = 'text-5xl mb-3 text-white';
            beepSuccess.play();
        } else {
            statusCard.classList.add('bg-rose-600');
            statusText.className = 'text-xl font-black uppercase text-red tracking-tighter';
            statusIcon.className = 'text-5xl mb-3 text-white';
            beepError.play();
        }

        statusText.innerText = title;
        statusSub.innerText = sub;
        statusSub.className = "text-black/80 text-xs font-bold mt-1 uppercase";
        statusIcon.innerHTML = `<i class="fas ${iconClass}"></i>`;

        setTimeout(() => {
            if(!isScanning) {
                isScanning = true;
                resetUI();
            }
        }, 3000);
    }

    function resetUI() {
        statusCard.className = 'p-8 text-center bg-slate-900 transition-all duration-300';
        overlay.querySelectorAll('.border-emerald-500, .border-rose-500').forEach(el => {
            el.classList.remove('border-emerald-500', 'border-rose-500');
            el.classList.add('border-amber-500');
        });
        statusText.innerText = "Ready to Scan";
        statusText.className = "text-lg font-bold uppercase text-black tracking-widest";
        statusSub.innerText = "Center the QR code within the markers";
        statusSub.className = "text-slate-500 text-xs font-medium mt-2";
        statusIcon.className = 'text-4xl mb-3 text-slate-500';
        statusIcon.innerHTML = `<i class="fas fa-expand animate-pulse"></i>`;
    }

    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" }, 
        { fps: 15, qrbox: { width: 250, height: 250 } },
        (decodedText) => {
            if (!isScanning) return;
            isScanning = false;

            fetch("/api/verify-ticket", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ scanned_raw_data: decodedText })
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    updateUI('success', 'ACCESS GRANTED', data.guest_name, 'fa-check-circle');
                } else {
                    updateUI('error', 'ACCESS DENIED', data.message, 'fa-shield-alt');
                }
            })
            .catch(err => {
                updateUI('error', 'SYSTEM ERROR', 'Check Connection', 'fa-wifi');
            });
        }
    );
});
</script>
@endsection