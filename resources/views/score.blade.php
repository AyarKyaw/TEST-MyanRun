@extends('layouts.master')

@section('title', 'Score - MYANRUN')

@section('content')
<div class="page-title">
        <div class="themeflat-container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title-heading">
                        <h1 class="title">our score</h1>
                    </div><!-- /.page-title-captions -->
                    <div class="breadcrumbs">
                        <ul>
                            <li><a href="index.html">Homepage</a></li>
                            <li> <i class="icon-Arrow---Right-2"></i></li>
                           
                            <li><a>Our Score</a></li>
                        </ul>
                    </div><!-- /.breadcrumbs -->

                </div><!-- /.col-md-12 -->
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.page-title -->
<div class="max-w-6xl mx-auto mt-10 mb-10 space-y-8"
     x-data="{ 
        search: '',
        scores: {{ json_encode($scores) }},
        athletes: {{ json_encode($athletes) }},
        genderMap: {'男': 'Male', '女': 'Female'},
        
        get filteredScores() {
            if (!this.search) return this.scores;
            return this.scores.filter(s => 
                s.BIB.toString().includes(this.search.toLowerCase())
            );
        },
        get filteredAthletes() {
            if (!this.search) return this.athletes.slice(0, 30); // Show only 30 initially for performance
            return this.athletes.filter(a => 
                a.Name.toLowerCase().includes(this.search.toLowerCase()) || 
                a.BIB.toString().includes(this.search.toLowerCase())
            );
        }
     }">
    
    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h1 class="text-4xl font-black text-slate-900">{{ $raceData['RaceName'] }}</h1>
                <p class="text-slate-500 font-medium">📍 {{ $raceData['Address'] }} | 📅 {{ $raceData['RaceTime'] }}</p>
            </div>
            
            <div class="w-full md:w-96 relative">
                <input 
                    type="text" 
                    x-model="search" 
                    placeholder="Search Name or BIB..." 
                    class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 outline-none transition-all"
                >
                <!-- <svg class="w-6 h-6 absolute left-4 top-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg> -->
            </div>
        </div>

        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($raceData['Events'] as $event)
            <div class="bg-slate-50 rounded-xl p-4 border border-slate-200">
                <h3 class="font-black text-lg border-b pb-2 mb-3 text-blue-800">{{ $event['EventName'] }} ({{ $event['Distance'] }}mi)</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($event['TimingPoints'] as $tp)
                        <span class="bg-white px-3 py-1 rounded-full text-xs font-bold shadow-sm border">
                            🏁 {{ $tp['TpName'] }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="bg-slate-900 px-6 py-4 flex justify-between items-center">
            <h2 class="text-white font-bold text-xl uppercase tracking-wider">🏆 Leaderboard</h2>
            <span class="text-slate-400 text-xs font-bold uppercase tracking-wider" x-text="`Showing ${filteredScores.length} results`"></span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b text-slate-500 text-xs font-bold uppercase">
                    <tr>
                        <th class="px-6 py-3">Rank</th>
                        <th class="px-6 py-3">BIB</th>
                        <th class="px-6 py-3">Net Time</th>
                        <th class="px-6 py-3">Pace</th>
                        <th class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm">
                    <template x-for="score in filteredScores" :key="score.BIB">
                        <tr class="hover:bg-blue-50 transition-colors">
                            <td class="px-6 py-4 font-black text-blue-600" x-text="`#${score.OverallPosition}`"></td>
                            <td class="px-6 py-4 font-mono font-bold" x-text="score.BIB || 'N/A'"></td>
                            <td class="px-6 py-4 font-bold text-slate-800" x-text="score.NetTime"></td>
                            <td class="px-6 py-4" x-text="score.NetPace"></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-md text-[10px] font-black uppercase"
                                      :class="score.FinishStatus === 'Finished' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'"
                                      x-text="score.FinishStatus">
                                </span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
            <h2 class="text-white font-bold text-xl uppercase tracking-wider">👥 Participant List</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
            <template x-for="athlete in filteredAthletes" :key="athlete.BIB">
                <div class="flex items-center p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="h-10 w-10 bg-white rounded-full flex items-center justify-center font-bold text-blue-600 shadow-sm mr-4" x-text="athlete.Name.charAt(0)">
                    </div>
                    <div>
                        <p class="font-bold text-slate-900" x-text="athlete.Name"></p>
                        <p class="text-xs text-slate-500 uppercase font-medium">
                            BIB: <span x-text="athlete.BIB"></span> | 
                            <span x-text="genderMap[athlete.Gender] || athlete.Gender"></span>
                        </p>
                        <p class="text-[10px] text-blue-500 font-bold uppercase" x-text="athlete.TeamName || 'No Team'"></p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection