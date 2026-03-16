@extends('dashboard.layouts.master')

@section('content')
<div class="fixed inset-0 bg-[#0f172a] flex flex-col overflow-hidden overscroll-none select-none">
    
    {{-- 1. Top Header: Minimal for more camera space --}}
    <div class="pt-12 pb-4 px-6 bg-slate-900/80 backdrop-blur-md border-b border-slate-800 z-20">
        <div class="flex justify-between items-end">
            <div>
                <h1 class="text-white font-black text-xl uppercase tracking-tighter">
                    Terminal<span class="text-amber-500">.01</span>
                </h1>
                <p class="text-slate-500 text-[9px] font-bold uppercase tracking-[0.2em]">
                    {{ Str::limit($dinner->name ?? 'Event Access', 25) }}
                </p>
            </div>
            <div class="text-right">
                <span id="session-count" class="text-amber-500 font-mono text-xs bg-amber-500/10 px-2 py-1 rounded border border-amber-500/20">
                    SESS: 0
                </span>
            </div>
        </div>
    </div>

    {{-- 2. Scanner Viewport: Expanded for better focus --}}
    <div class="relative flex-1 bg-black">
        <div id="reader" class="w-full h-full"></div>
        
        {{-- High-Tech Targeting Overlay --}}
        <div id="scan-overlay" class="absolute inset-0 z-10 pointer-events-none flex items-center justify-center">
            <div class="w-64 h-64 relative">
                <div class="absolute top-0 left-0 w-10 h-10 border-t-4 border-l-4 border-amber-500 rounded-tl-2xl transition-all duration-300 shadow-[0_0_15px_rgba(245,158,11,0.4)]"></div>
                <div class="absolute top-0 right-0 w-10 h-10 border-t-4 border-r-4 border-amber-500 rounded-tr-2xl transition-all duration-300 shadow-[0_0_15px_rgba(245,158,11,0.4)]"></div>
                <div class="absolute bottom-0 left-0 w-10 h-10 border-b-4 border-l-4 border-amber-500 rounded-bl-2xl transition-all duration-300 shadow-[0_0_15px_rgba(245,158,11,0.4)]"></div>
                <div class="absolute bottom-0 right-0 w-10 h-10 border-b-4 border-r-4 border-amber-500 rounded-br-2xl transition-all duration-300 shadow-[0_0_15px_rgba(245,158,11,0.4)]"></div>
                
                <div class="absolute w-full h-[2px] bg-amber-500/60 shadow-[0_0_10px_#f59e0b] animate-scan-line"></div>
            </div>
        </div>
    </div>

    {{-- 3. Interaction Zone: The "Thumb Area" --}}
    <div id="interaction-zone" class="bg-slate-900 border-t border-slate-800 pb-10 pt-6 px-6 z-20 transition-colors duration-300">
        <div id="status-display" class="flex items-center gap-4">
            <div id="status-icon-bg" class="w-14 h-14 rounded-2xl bg-slate-800 flex items-center justify-center text-2xl text-slate-400 shrink-0">
                <i class="fas fa-qrcode" id="main-icon"></i>
            </div>
            <div class="flex-1 overflow-hidden">
                <h2 id="status-text" class="text-white font-bold text-lg uppercase leading-none truncate">Ready to Scan</h2>
                <p id="status-subtext" class="text-slate-500 text-xs font-medium mt-1 truncate">Point at ticket QR code</p>
            </div>
            <button onclick="location.reload()" class="w-12 h-12 rounded-xl bg-slate-800 flex items-center justify-center text-slate-400 border border-slate-700 active:scale-90 transition-transform">
                <i class="fas fa-redo-alt text-sm"></i>
            </button>
        </div>
    </div>

</div>

{{-- Hidden Audio --}}
<audio id="beep-success" src="https://assets.mixkit.co/active_storage/sfx/2568/2568-preview.mp3" preload="auto"></audio>
<audio id="beep-error" src="https://assets.mixkit.co/active_storage/sfx/2573/2573-preview.mp3" preload="auto"></audio>

<style>
    @keyframes scan-line {
        0% { top: 0%; opacity: 0; }
        20% { opacity: 1; }
        80% { opacity: 1; }
        100% { top: 100%; opacity: 0; }
    }
    .animate-scan-line {
        animation: scan-line 2.5s linear infinite;
    }

    /* Target direct video tag to ensure mobile filling */
    #reader video { 
        width: 100% !important; 
        height: 100% !important; 
        object-fit: cover !important; 
    }
    
    /* Force hide HTML5-QRCODE branding */
    #reader__dashboard, #reader img, #reader span { display: none !important; }
</style>

<script src="https://unpkg.com/html5-qrcode" defer></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const interactionZone = document.getElementById('interaction-zone');
    const statusIconBg = document.getElementById('status-icon-bg');
    const statusText = document.getElementById('status-text');
    const statusSub = document.getElementById('status-subtext');
    const mainIcon = document.getElementById('main-icon');
    const overlayCorners = document.querySelectorAll('#scan-overlay > div > div');
    
    let isScanning = true;
    let scanCount = 0;

    function triggerFeedback(type) {
        // Haptic feedback for mobile
        if (window.navigator.vibrate) {
            window.navigator.vibrate(type === 'success' ? [50] : [100, 50, 100]);
        }
        
        // Audio
        document.getElementById(type === 'success' ? 'beep-success' : 'beep-error').play();
    }

    function updateUI(status, title, sub, iconClass) {
        if(status === 'success') {
            interactionZone.classList.replace('bg-slate-900', 'bg-emerald-600');
            statusIconBg.classList.replace('bg-slate-800', 'bg-emerald-700');
            statusText.innerText = "Access Granted";
            statusSub.innerText = sub.toUpperCase();
            mainIcon.className = `fas fa-check-circle text-white`;
            statusText.classList.add('text-white');
            statusSub.classList.replace('text-slate-500', 'text-emerald-100');
            overlayCorners.forEach(c => c.style.borderColor = '#10b981');
            scanCount++;
            document.getElementById('session-count').innerText = `SESS: ${scanCount}`;
            triggerFeedback('success');
        } else {
            interactionZone.classList.replace('bg-slate-900', 'bg-rose-600');
            statusIconBg.classList.replace('bg-slate-800', 'bg-rose-700');
            statusText.innerText = title;
            statusSub.innerText = sub;
            mainIcon.className = `fas fa-times-circle text-white`;
            statusText.classList.add('text-white');
            statusSub.classList.replace('text-slate-500', 'text-rose-100');
            overlayCorners.forEach(c => c.style.borderColor = '#f43f5e');
            triggerFeedback('error');
        }

        setTimeout(() => {
            isScanning = true;
            resetUI();
        }, 2500);
    }

    function resetUI() {
        interactionZone.className = "bg-slate-900 border-t border-slate-800 pb-10 pt-6 px-6 z-20 transition-colors duration-300";
        statusIconBg.className = "w-14 h-14 rounded-2xl bg-slate-800 flex items-center justify-center text-2xl text-slate-400 shrink-0";
        statusText.className = "text-white font-bold text-lg uppercase leading-none truncate";
        statusText.innerText = "Ready to Scan";
        statusSub.className = "text-slate-500 text-xs font-medium mt-1 truncate";
        statusSub.innerText = "Point at ticket QR code";
        mainIcon.className = "fas fa-qrcode";
        overlayCorners.forEach(c => c.style.borderColor = '#f59e0b');
    }

    const html5QrCode = new Html5Qrcode("reader");
    html5QrCode.start(
        { facingMode: "environment" }, 
        { fps: 20, qrbox: { width: 250, height: 250 } },
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
                    updateUI('success', 'Verified', data.guest_name, 'fa-check-circle');
                } else {
                    updateUI('error', 'Denied', data.message, 'fa-exclamation-triangle');
                }
            })
            .catch(() => updateUI('error', 'Network Error', 'No Server Sync', 'fa-wifi'));
        }
    );
});
</script>
@endsection