<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Runner Dashboard - MYAN RUN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="{{ asset('images/icon/Myan Run icon.png') }}">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#C3E92D',
                        brandDark: '#aacc28',
                    },
                    boxShadow: {
                        'brand': '0 10px 15px -3px rgba(195, 233, 45, 0.3)',
                    }
                }
            }
        }
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .sidebar-active { background: #C3E92D; color: #000; box-shadow: 0 4px 20px rgba(195, 233, 45, 0.4); transform: translateX(5px); }
        .sidebar-item { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-900 custom-scrollbar">
    <div class="flex h-screen overflow-hidden">
        <aside class="w-72 bg-white border-r border-slate-100 hidden lg:flex flex-col z-20">
            <div class="p-8">
    <a href="{{ url('/') }}" class="inline-block group">
        <img src="{{ asset('images/MyanRun_Orange_RM2.png') }}" 
             alt="Myan Run Logo" 
             class="h-10 w-auto object-contain group-hover:scale-105 transition-transform duration-300">
    </a>
</div>
            
            <nav class="flex-1 px-6 space-y-1.5 mt-4">
                <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-[2px] mb-4 px-2">Main Menu</p>
                
                <a href="{{ url('/user/dashboard') }}" class="sidebar-active flex items-center p-3.5 rounded-2xl group">
                    <div class="w-8"><i class="fas fa-th-large text-lg"></i></div>
                    <span class="font-bold text-sm tracking-tight">Dashboard</span>
                </a>

                <a href="#" class="sidebar-item flex items-center p-3.5 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 group">
                    <div class="w-8"><i class="fas fa-running text-lg group-hover:text-brandDark transition-colors"></i></div>
                    <span class="font-semibold text-sm">My Races</span>
                </a>

                <a href="#" class="sidebar-item flex items-center p-3.5 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 group">
                    <div class="w-8"><i class="fas fa-camera text-lg group-hover:text-brandDark transition-colors"></i></div>
                    <span class="font-semibold text-sm">My Photos</span>
                </a>

                <a href="#" class="sidebar-item flex items-center p-3.5 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 group">
                    <div class="w-8"><i class="fas fa-shopping-bag text-lg group-hover:text-brandDark transition-colors"></i></div>
                    <span class="font-semibold text-sm">Orders</span>
                </a>

                <a href="#" class="sidebar-item flex items-center p-3.5 rounded-2xl text-slate-500 hover:bg-slate-50 hover:text-slate-900 group">
                    <div class="w-8"><i class="fas fa-medal text-lg group-hover:text-brandDark transition-colors"></i></div>
                    <span class="font-semibold text-sm">Achievements</span>
                </a>
            </nav>


<div class="p-6 mx-4 mb-6 rounded-3xl bg-slate-900 text-white relative overflow-hidden group">
                <div class="relative z-10">
                    <p class="text-xs text-slate-400 font-medium">Want more perks?</p>
                    <p class="text-sm font-bold mt-1">Upgrade to Pro</p>
                    <button class="mt-3 bg-brand text-black text-[11px] px-4 py-2 rounded-xl font-bold hover:bg-white transition-colors uppercase tracking-wider">Get Started</button>
                </div>
                <i class="fas fa-bolt absolute -right-2 -bottom-2 text-white/10 text-6xl group-hover:scale-110 transition-transform"></i>
            </div>

            <div class="p-6 border-t border-slate-50">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center text-slate-400 font-bold p-3 hover:text-red-500 transition-all group">
                        <i class="fas fa-sign-out-alt mr-4 text-lg group-hover:rotate-12 transition-transform"></i> 
                        <span class="text-sm">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto custom-scrollbar relative">
            
            <div class="lg:hidden flex items-center justify-between p-6 bg-white border-b">
                <img src="{{ asset('images/MyanRun_Orange_RM2.png') }}" class="h-8">
                <button class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-600">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="relative w-full mb-12 group">
    <div class="relative h-[400px] w-full overflow-hidden shadow-2xl shadow-brand/10">
            <div class="w-full h-full bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                 <i class="fas fa-running text-white/10 text-[10rem] rotate-12"></i>
            </div>

        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-900/40 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950/60 via-transparent to-transparent"></div>

            <div class="absolute bottom-0 left-0 p-8 md:p-12 w-full flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-2">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-brand text-black text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-[3px] shadow-lg shadow-brand/20">Elite Member</span>
                        <span class="bg-white/10 backdrop-blur-md text-white text-[10px] font-black px-4 py-1.5 rounded-full uppercase tracking-[3px] border border-white/20">Verified Runner</span>
                    </div>
                    <h1 class="text-5xl md:text-7xl font-black text-white italic uppercase tracking-tighter leading-none">
                        {{ $user->first_name }} <span class="text-brand">{{ $user->last_name }}</span>
                    </h1>
                    <div class="flex items-center gap-6 mt-4">
                        <div class="flex items-center text-white/70 font-bold uppercase text-xs tracking-widest">
                            <i class="fas fa-map-marker-alt text-brand mr-2"></i> Yangon, MM
                        </div>
                        <div class="flex items-center text-white/70 font-bold uppercase text-xs tracking-widest">
                            <i class="fas fa-calendar-alt text-brand mr-2"></i> Joined {{ $user->created_at->format('Y') }}
                        </div>
                    </div>
                </div>

                <div class="bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-[32px] text-right">
                    <p class="text-white/50 text-[10px] font-black uppercase tracking-[4px] mb-1">Personal ID</p>
                    <p class="text-white text-3xl font-black tracking-tighter">{{ $user->runner_id }}</p>
                </div>
            </div>
        </div>
    </div>
            <div class="p-6 md:p-12 max-w-7xl mx-auto">
                
                <header class="flex flex-col md:flex-row justify-between md:items-center mb-12 gap-6">
                    <div>
                        <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight leading-none">Hello, {{ $user->first_name }}! 👋</h2>
                        <p class="text-slate-500 font-medium mt-3 text-lg italic">The road is calling. Are you ready?</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="hidden md:flex flex-col items-end mr-2">
                            <span class="text-sm font-bold text-slate-900">{{ $fullName }}</span>
                            <span class="text-[11px] font-bold text-brandDark uppercase tracking-widest">{{ $user->runner_id }}</span>
                        </div>
                        <button class="bg-white w-12 h-12 rounded-2xl border border-slate-100 text-slate-400 relative hover:shadow-lg transition-all flex items-center justify-center group">
                            <i class="fas fa-bell group-hover:rotate-12 transition-transform"></i>
                            <span class="absolute top-3 right-3 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white ring-2 ring-red-100"></span>
                        </button>
                        <div class="w-32 h-32 bg-white p-1 border-2 border-brand rounded-2xl shadow-brand overflow-hidden cursor-pointer hover:rotate-3 transition-transform">
                             @if($athlete && $athlete->face_image_path)
                        <img src="{{ asset('storage/' . $athlete->face_image_path) }}" 
                             class="w-full h-full object-contain bg-slate-800" 
                             alt="Profile">
                    @else
                        <div class="w-full h-full bg-slate-800 flex items-center justify-center font-black text-4xl text-brand uppercase">
                            {{ substr($user->first_name, 0, 1) }}
                        </div>
                    @endif
                        </div>
                    </div>
                </header>


<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8 mb-12">
                    <div class="bg-white p-6 md:p-8 rounded-[32px] border border-slate-50 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
                        <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-2xl flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                            <i class="fas fa-route text-xl"></i>
                        </div>
                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest mb-1">Distance</p>
                        <h3 class="text-3xl font-extrabold text-slate-900">124.5 <span class="text-sm font-medium text-slate-300">KM</span></h3>
                    </div>

                    <div class="bg-white p-6 md:p-8 rounded-[32px] border border-slate-50 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest mb-1">Races</p>
                        <h3 class="text-3xl font-extrabold text-slate-900">08 <span class="text-sm font-medium text-slate-300">DONE</span></h3>
                    </div>

                    <div class="bg-white p-6 md:p-8 rounded-[32px] border border-slate-50 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
                        <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-2xl flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                            <i class="fas fa-fire text-xl"></i>
                        </div>
                        <p class="text-slate-400 font-bold uppercase text-[10px] tracking-widest mb-1">Points</p>
                        <h3 class="text-3xl font-extrabold text-slate-900">2,450 <span class="text-sm font-medium text-slate-300">PTS</span></h3>
                    </div>

                    <div class="bg-brand p-6 md:p-8 rounded-[32px] shadow-brand relative overflow-hidden group hover:scale-[1.02] transition-all">
                        <div class="relative z-10">
                            <p class="text-black/50 font-bold uppercase text-[10px] tracking-widest mb-1">Upcoming</p>
                            <h3 class="text-xl font-extrabold text-black leading-tight">Yangon Half Marathon</h3>
                            <p class="text-xs font-bold text-black/60 mt-2"><i class="fas fa-clock mr-1"></i> In 12 Days</p>
                        </div>
                        <i class="fas fa-calendar absolute -right-4 -bottom-4 text-black/10 text-8xl transition-transform group-hover:rotate-12 group-hover:scale-110"></i>
                    </div>
                </div>


<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
<div class="lg:col-span-2 space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-2">
        <div>
            <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">My Race Tickets</h3>
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">{{ $tickets->total() }} Total Registrations</p>
        </div>
        
        <div class="flex bg-slate-100 p-1 rounded-2xl overflow-x-auto no-scrollbar border border-slate-200/50">
            @foreach(['all' => null, 'pending' => 'pending', 'accepted' => 'accepted', 'completed' => 'completed'] as $label => $val)
                <a href="{{ request()->fullUrlWithQuery(['status' => $val, 'page' => 1]) }}" 
                   class="px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider transition-all whitespace-nowrap
                   {{ request('status') == $val ? 'bg-white shadow-sm text-slate-900' : 'text-slate-500 hover:text-slate-700' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </div>

    <div class="space-y-6">
        @forelse($tickets as $ticket)
            <div class="bg-white rounded-[40px] shadow-sm border border-slate-50 overflow-hidden group hover:shadow-md transition-all">
                <div class="p-6 md:p-8 flex flex-col md:flex-row gap-8 items-center">
                    
                    <div class="relative shrink-0">
                        <div class="bg-slate-50 p-4 rounded-[24px] border-2 border-dashed border-slate-200 group-hover:border-brand transition-colors">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=TICKET-{{ $ticket->id }}" alt="QR" class="w-20 h-20 mix-blend-multiply opacity-80">
                        </div>
                    </div>
                    
                    <div class="flex-1 w-full">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h4 class="text-xl font-extrabold text-slate-900 tracking-tight uppercase">
                                    {{ $ticket->category }}
                                </h4>
                                <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">ID: #{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider 
                                {{ $ticket->status === 'pending' ? 'bg-amber-100 text-amber-700' : 
                                   ($ticket->status === 'accepted' ? 'bg-blue-100 text-blue-700' : 'bg-emerald-100 text-emerald-700') }}">
                                {{ $ticket->status }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <p class="text-[9px] text-slate-400 font-black uppercase tracking-tighter">Registered</p>
                                <p class="text-xs font-bold text-slate-700">{{ $ticket->created_at->format('M d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-slate-400 font-black uppercase tracking-tighter">Amount Paid</p>
                                <p class="text-xs font-bold text-slate-700">
                                    {{ $ticket->price }} MMK
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <!-- <a href="{{ route('ticket.download', $ticket->id) }}" 
                            class="flex-1 bg-slate-900 text-white text-[11px] text-center font-bold py-3 rounded-xl hover:bg-black transition-all">
                                <i class="fas fa-file-pdf mr-2 text-brand"></i> Download Ticket
                            </a> -->
                            <button class="px-4 bg-slate-50 text-slate-400 rounded-xl hover:bg-slate-100 transition-all">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                            <!-- <button onclick="openPdfPreview('{{ route('ticket.preview', $ticket->id) }}')" 
                                    class="bg-slate-100 hover:bg-[#C3E92D] p-3 rounded-xl transition-all group">
                                <i class="fas fa-eye text-slate-500 group-hover:text-slate-900"></i>
                            </button> -->
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-12 rounded-[40px] text-center border-2 border-dashed border-slate-100">
                <p class="text-slate-500 font-bold italic">No {{ request('status') ?? 'active' }} tickets found.</p>
                <a href="/races" class="text-brandDark font-black text-sm uppercase mt-2 inline-block">Browse Races →</a>
            </div>
        @endforelse
    </div>

    <div class="mt-8 px-2">
        {{ $tickets->links() }}
    </div>
</div>
                        <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-50">
                            <div class="flex justify-between items-center mb-8">
                                <h3 class="text-xl font-extrabold text-slate-900">Runner Gallery</h3>
                                <a href="#" class="text-brandDark font-extrabold text-xs uppercase tracking-widest hover:underline">See All Gallery</a>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="aspect-square bg-slate-100 rounded-[24px] overflow-hidden relative group cursor-pointer">
                                    <img src="https://images.unsplash.com/photo-1530549387634-e797ea9961f7?auto=format&fit=crop&q=80&w=300" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-brand/80 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                                        <i class="fas fa-expand text-black text-xl"></i>
                                    </div>
                                </div>
                                <div class="aspect-square bg-slate-100 rounded-[24px] overflow-hidden relative group cursor-pointer">
                                    <img src="https://images.unsplash.com/photo-1552674605-db6ffd4facb5?auto=format&fit=crop&q=80&w=300" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-brand/80 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-all">
                                        <i class="fas fa-expand text-black text-xl"></i>
                                    </div>
                                </div>
                                <div class="aspect-square bg-slate-50 border-2 border-dashed border-slate-200 rounded-[24px] flex flex-col items-center justify-center text-slate-300 hover:text-brandDark hover:border-brandDark transition-all cursor-pointer">
                                    <i class="fas fa-plus mb-2"></i>
                                    <span class="text-[10px] font-bold">More</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="bg-white p-8 rounded-[40px] shadow-sm border border-slate-50">
                            <h3 class="text-xl font-extrabold text-slate-900 mb-8">Recent Orders</h3>
                            <div class="space-y-4">
                                <div class="flex items-center space-x-4 p-4 rounded-3xl hover:bg-slate-50 transition-colors cursor-pointer group">
                                    <div class="w-12 h-12 bg-brand/20 rounded-2xl flex items-center justify-center text-slate-800 group-hover:scale-110 transition-transform"><i class="fas fa-tshirt"></i></div>
                                    <div class="flex-1">
                                        <p class="text-sm font-extrabold text-slate-900">Finisher Tee</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">Status: In Transit</p>
                                    </div>
                                    <p class="font-black text-sm">15kK</p>
                                </div>
                            </div>
                            <button class="w-full mt-8 py-4 border-2 border-dashed border-slate-100 rounded-3xl text-slate-400 text-xs font-black uppercase tracking-widest hover:border-brand hover:text-brandDark transition-all">
                                Open Official Store
                            </button>
                        </div>


<div class="bg-slate-900 p-8 rounded-[40px] text-white overflow-hidden relative">
                             <h3 class="text-lg font-extrabold mb-4 relative z-10">Next Level</h3>
                             <div class="w-full bg-white/10 h-2 rounded-full mb-2 overflow-hidden relative z-10">
                                 <div class="bg-brand w-2/3 h-full rounded-full"></div>
                             </div>
                             <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase relative z-10">67% to Silver Runner</p>
                             <i class="fas fa-medal absolute -right-6 -bottom-6 text-white/5 text-9xl"></i>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>

    <div class="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t flex justify-around p-4 z-50">
        <button class="text-brandDark"><i class="fas fa-th-large text-xl"></i></button>
        <button class="text-slate-300"><i class="fas fa-running text-xl"></i></button>
        <button class="text-slate-300"><i class="fas fa-shopping-bag text-xl"></i></button>
        <button class="text-slate-300"><i class="fas fa-user text-xl"></i></button>
    </div>
@if(session('success'))
<div id="successModal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[3rem] w-full max-w-sm p-10 shadow-2xl text-center transform transition-all animate-bounce-short">
        <div class="w-24 h-24 bg-[#C3E92D] rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg shadow-lime-200">
            <i class="fas fa-check text-4xl text-slate-900"></i>
        </div>
        <h2 class="text-2xl font-black text-slate-800 uppercase italic mb-2">Registration Sent!</h2>
        <p class="text-slate-500 text-sm font-semibold mb-8">{{ session('success') }}</p>
        
        <button onclick="document.getElementById('successModal').remove()" class="w-full py-4 bg-slate-900 text-white font-black rounded-2xl uppercase tracking-widest text-xs hover:bg-slate-800 transition-all">
            Awesome!
        </button>
    </div>
</div>

<style>
    @keyframes bounce-short {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    .animate-bounce-short { animation: bounce-short 0.5s ease-in-out; }
</style>
@endif
<!-- <div id="pdfModal" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm z-[110] hidden items-center justify-center p-4">
    <div class="bg-white rounded-[2rem] w-full max-w-4xl h-[80vh] flex flex-col overflow-hidden">
        <div class="p-6 border-b flex justify-between items-center">
            <h3 class="font-black uppercase italic text-slate-800">Ticket Preview</h3>
            <button onclick="closePdfPreview()" class="text-slate-400 hover:text-red-500 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="flex-1 bg-slate-100">
            <iframe id="pdfFrame" src="" class="w-full h-full border-none"></iframe>
        </div>

        <div class="p-4 bg-slate-50 flex justify-end gap-3">
             <a id="downloadLink" href="#" class="px-6 py-2 bg-slate-900 text-white text-[10px] font-black uppercase rounded-xl">
                Download PDF
             </a>
        </div>
    </div>
</div> -->

<script>
function openPdfPreview(url) {
    const modal = document.getElementById('pdfModal');
    const iframe = document.getElementById('pdfFrame');
    const downloadLink = document.getElementById('downloadLink');
    
    iframe.src = url;
    downloadLink.href = url.replace('preview', 'download'); // Assuming your naming convention
    modal.classList.replace('hidden', 'flex');
}

function closePdfPreview() {
    const modal = document.getElementById('pdfModal');
    const iframe = document.getElementById('pdfFrame');
    iframe.src = ""; // Clear src to stop loading
    modal.classList.replace('flex', 'hidden');
}
</script>
</body>
</html>