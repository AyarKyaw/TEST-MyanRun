@extends('layouts.master')

@section('title', 'Score - MYANRUN')

@section('content')
<div class="page-title">
    <div class="themeflat-container">
        <div class="row">
            <div class="col-md-12">
                <div class="page-title-heading">
                    <h1 class="title">Score</h1>
                </div>
                <div class="breadcrumbs">
                    <ul>
                        <li><a href="/">Homepage</a></li>
                        <li><i class="icon-Arrow---Right-2"></i></li>
                        <li><a>Results</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="max-w-6xl mx-auto mt-10 mb-10 px-4 space-y-8"
    x-data="{ 
        search: '',            
        participantSearch: '', 
        activeEventId: '{{ $raceData['Events'][0]['EventId'] ?? '' }}',
        activeGender: 'All',
        scores: {{ json_encode($scores) }},
        athletes: {{ json_encode($athletes) }},
        events: {{ json_encode($raceData['Events']) }},
        genderMap: {'男': 'Male', '女': 'Female'},
        
        showModal: false,
        selectedRunner: null,

        scorePage: 1,
        athletePage: 1,
        perPage: 10,

        init() {
            this.$watch('search', () => this.scorePage = 1);
            this.$watch('participantSearch', () => this.athletePage = 1);
            this.$watch('activeEventId', () => this.scorePage = 1);
            this.$watch('activeGender', () => this.scorePage = 1);
        },

        get activeEventName() {
            let found = this.events.find(e => String(e.EventId) === String(this.activeEventId));
            return found ? found.EventName : 'Results';
        },

        openRunner(score) {
            // This logic ensures only unique Timing Point names are shown
            let uniquePoints = [];
            if (score.TimingPoints) {
                const seen = new Set();
                uniquePoints = score.TimingPoints.filter(tp => {
                    const duplicate = seen.has(tp.TpName);
                    seen.add(tp.TpName);
                    return !duplicate;
                });
            }

            this.selectedRunner = {
                ...score,
                EventName: this.activeEventName,
                TimingPoints: uniquePoints 
            };
            this.showModal = true;
        },

        openAthlete(athlete) {
            let score = this.scores.find(s => String(s.AthleteId) === String(athlete.AthleteId));
            
            if (score) {
                this.selectedRunner = {
                    ...score,
                    Name: athlete.Name,
                    BIB: athlete.BIB,
                    Gender: athlete.Gender,
                    EventName: this.activeEventName,
                    TimingPoints: this.cleanSplits(score.TimingPoints)
                };
            } else {
                this.selectedRunner = {
                    Name: athlete.Name,
                    BIB: athlete.BIB,
                    EventName: 'Registered',
                    FinishStatus: 'Registered',
                    GunTime: '--:--',
                    NetTime: '--:--',
                    OverallPosition: 'N/A',
                    TimingPoints: []
                };
            }
            this.showModal = true;
        },

        get filteredScores() {
            let term = this.search.toLowerCase().trim();
            let eventScores = this.scores.filter(s => String(s.EventId) === String(this.activeEventId));
            let enriched = eventScores.map(score => {
                let athlete = this.athletes.find(a => String(a.AthleteId) === String(score.AthleteId));
                return {
                    ...score,
                    BIB: athlete ? String(athlete.BIB || '') : '',
                    Name: athlete ? String(athlete.Name || 'Unknown') : 'Unknown',
                    Gender: athlete ? (this.genderMap[athlete.Gender] || athlete.Gender || 'N/A') : 'N/A'
                };
            });

            if (this.activeGender !== 'All') {
                enriched = enriched.filter(s => s.Gender === this.activeGender);
            }

            let results = enriched;
            if (term) {
                results = enriched.filter(s => s.Name.toLowerCase().includes(term) || s.BIB.toLowerCase().includes(term));
            } else {
                results = enriched.sort((a, b) => (parseInt(a.OverallPosition) || 9999) - (parseInt(b.OverallPosition) || 9999));
            }

            // SLICE FOR PAGINATION
            let start = (this.scorePage - 1) * this.perPage;
            return results.slice(start, start + this.perPage);
        },

        get totalFinishedCount() {
            return this.scores.filter(s => {
                let matchesEvent = String(s.EventId) === String(this.activeEventId);
                if (this.activeGender === 'All') return matchesEvent;
                
                let athlete = this.athletes.find(a => String(a.AthleteId) === String(s.AthleteId));
                let gender = athlete ? (this.genderMap[athlete.Gender] || athlete.Gender) : '';
                return matchesEvent && gender === this.activeGender;
            }).length;
        },

        get totalScorePages() {
            return Math.ceil(this.totalFinishedCount / this.perPage);
        },

        cleanSplits(points) {
            if (!points) return [];
            const seen = new Set();
            return points.filter(tp => {
                const duplicate = seen.has(tp.TpName);
                seen.add(tp.TpName);
                return !duplicate;
            });
        },

        formatTime(val) {
            if (!val || val === '--:--') return '--:--';
            
            // Remove milliseconds if present (e.g., .278)
            let cleanTime = val.split('.')[0];

            // If the string contains a date (e.g., 2026-03-20 09:44:48), take only the time
            if (cleanTime.includes(' ')) {
                cleanTime = cleanTime.split(' ')[1];
            }

            // Return only the HH:MM:SS
            return cleanTime;
        },
        get filteredAthletes() {
            let term = this.participantSearch.toLowerCase().trim();
            let results = this.athletes;
            
            if (term) {
                results = this.athletes.filter(a => {
                    let nameStr = String(a.Name || '').toLowerCase();
                    let bibStr = String(a.BIB || '').toLowerCase();
                    return nameStr.includes(term) || bibStr.includes(term);
                });
            }
            
            let start = (this.athletePage - 1) * this.perPage;
            return results.slice(start, start + this.perPage);
        },

        get totalAthletePages() {
            let term = this.participantSearch.toLowerCase().trim();
            let count = term ? this.athletes.filter(a => String(a.Name).toLowerCase().includes(term)).length : this.athletes.length;
            return Math.ceil(count / this.perPage);
        }
            
    }">
    
    <div class="bg-white rounded-2xl shadow-sm border p-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex-1">
                <h1 class="text-3xl font-black text-slate-900">KBZ Community 10-mile Run 2026</h1>
                <p class="text-slate-500 font-medium">📍 {{ $raceData['Address'] }} | 📅 {{ $raceData['RaceTime'] }}</p>
            </div>
            <div class="w-full md:w-auto text-right">
                <p class="text-[10px] font-black uppercase text-slate-400 mb-1 mr-2">Select Category</p>
                <select x-model="activeEventId" class="w-full md:w-64 px-4 py-3 bg-white border border-slate-200 rounded-xl font-bold text-blue-700 outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer shadow-sm">
                    @foreach($raceData['Events'] as $event)
                        <option value="{{ $event['EventId'] }}">{{ $event['EventName'] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="bg-slate-900 px-6 py-4 flex justify-between items-center">
            <h2 class="text-white font-bold text-lg uppercase tracking-wider">🏆 <span x-text="activeEventName"></span> Leaderboard</h2>
            <div class="flex items-center gap-4">
                <div class="flex bg-white/10 p-1 rounded-lg border border-white/10">
                    <template x-for="g in ['All', 'Male', 'Female']">
                        <button @click="activeGender = g" 
                            :class="activeGender === g ? 'bg-white text-slate-900' : 'text-white hover:bg-white/5'"
                            class="px-3 py-1 rounded-md text-[10px] font-black uppercase transition-all" 
                            x-text="g">
                        </button>
                    </template>
                </div>

                <input type="text" x-model="search" placeholder="Search..." class="hidden md:block bg-white/10 border border-white/20 rounded-lg px-3 py-1 text-xs text-black placeholder-slate-400 outline-none focus:bg-white focus:text-slate-900">
                
                <div class="text-right flex flex-col items-end">
                    <span class="text-white text-lg font-black leading-none" x-text="totalFinishedCount"></span>
                    <span class="text-slate-400 text-[9px] font-bold uppercase tracking-tighter" x-text="activeGender + ' Finished'"></span>
                </div>
</div>
        </div>
        
        <div class="overflow-x-auto min-h-[400px]">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b text-slate-500 text-[11px] font-black uppercase">
                    <tr>
                        <th class="px-6 py-4">Rank</th>
                        <th class="px-6 py-4">BIB</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Net Time</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y text-sm">
                    <template x-for="(score, index) in filteredScores" :key="index">
                        <tr @click="openRunner(score)" class="hover:bg-blue-50 cursor-pointer transition-colors group">
                            <td class="px-6 py-4 font-black text-blue-600" x-text="`#${score.OverallPosition}`"></td>
                            <td class="px-6 py-4 font-mono font-bold text-slate-600" x-text="score.BIB"></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center mr-3 border border-slate-200 overflow-hidden shadow-sm">
                                        <img src="{{ asset('images/icon/Myan Run icon.png') }}" class="h-5 w-auto object-contain">
                                    </div>
                                    <span class="font-bold text-slate-900" x-text="score.Name"></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-bold text-slate-800" x-text="score.NetTime"></td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase"
                                      :class="score.FinishStatus === 'Finished' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'"
                                      x-text="score.FinishStatus">
                                </span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <div class="bg-white border-t px-6 py-4 flex items-center justify-between">
    <button @click="scorePage--" :disabled="scorePage <= 1" class="px-4 py-2 bg-slate-100 rounded-lg text-xs font-bold disabled:opacity-50">Previous</button>
    <span class="text-xs font-bold text-slate-500" x-text="`Page ${scorePage} of ${totalScorePages}`"></span>
    <button @click="scorePage++" :disabled="scorePage >= totalScorePages" class="px-4 py-2 bg-slate-100 rounded-lg text-xs font-bold disabled:opacity-50">Next</button>
</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="bg-blue-600 px-6 py-4 flex flex-col md:flex-row justify-between items-center gap-4 min-h-[80px]">
            <div class="flex items-center gap-3">
                <h2 class="text-white font-bold text-lg uppercase tracking-wider">👥 Participant List</h2>
                <span class="bg-blue-500 text-white text-[10px] px-2 py-1 rounded-lg font-black" x-text="athletes.length"></span>
            </div>
            <div class="relative w-full md:w-80">
                <input type="text" x-model.debounce.300ms="participantSearch" placeholder="Search Name or BIB..." 
                    class="w-full pl-4 pr-4 py-2 bg-white/20 border border-white/30 rounded-xl text-black placeholder-blue-100 outline-none focus:bg-white focus:text-slate-900 transition-all text-sm shadow-inner">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6 bg-slate-50/50 min-h-[400px] content-start">
            <template x-for="athlete in filteredAthletes" :key="athlete.AthleteId || athlete.BIB">
                <div @click="openAthlete(athlete)" class="flex items-center p-4 bg-white rounded-xl border border-slate-200 shadow-sm hover:border-blue-400 hover:shadow-md cursor-pointer transition-all h-[80px] group">
                    <div class="h-10 w-10 bg-slate-100 rounded-full flex items-center justify-center mr-4 border border-slate-200 overflow-hidden group-hover:bg-white transition-colors">
                        <img src="{{ asset('images/icon/Myan Run icon.png') }}" class="h-6 w-auto object-contain">
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-slate-900 truncate" x-text="athlete.Name"></p>
                        <p class="text-xs text-slate-500 uppercase font-medium">BIB: <span class="text-slate-900" x-text="athlete.BIB"></span> | <span x-text="genderMap[athlete.Gender] || athlete.Gender"></span></p>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <div x-show="showModal" x-transition.opacity @keydown.escape.window="showModal = false"
        class="fixed inset-0 z-[9999] bg-slate-900/80 backdrop-blur-md flex items-start justify-center p-4 pt-32 overflow-y-auto" style="display: none;">
        
        <div x-show="showModal" x-transition.scale.95 @click.away="showModal = false"
            class="bg-white rounded-3xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] w-full max-w-sm overflow-hidden mb-20 border border-white/20">
            
            <div class="bg-blue-600 p-5 text-white relative text-center">
                <button @click="showModal = false" class="absolute top-3 right-4 text-white/80 hover:text-white text-2xl font-light">&times;</button>
                <div class="h-14 w-14 bg-white rounded-full flex items-center justify-center mx-auto mb-2 shadow-inner p-1">
                    <img src="{{ asset('images/icon/Myan Run icon.png') }}" class="h-10 w-auto object-contain">
                </div>
                <h3 class="text-lg font-black uppercase leading-tight truncate px-4" x-text="selectedRunner?.Name"></h3>
                <div class="flex justify-center gap-2 mt-1">
                    <span class="text-blue-100 text-[10px] font-bold bg-blue-700 px-2 py-0.5 rounded uppercase" x-text="`BIB: ${selectedRunner?.BIB}`"></span>
                    <span class="text-blue-100 text-[10px] font-bold bg-blue-700 px-2 py-0.5 rounded uppercase" x-text="selectedRunner?.EventName"></span>
                </div>
            </div>

            <div class="p-5 space-y-5 max-h-[60vh] overflow-y-auto custom-scrollbar">
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Gun Time</p>
                        <p class="text-md font-black text-slate-800" x-text="selectedRunner?.GunTime || '--:--'"></p>
                    </div>
                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-center">
                        <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Net Time</p>
                        <p class="text-md font-black text-blue-600" x-text="selectedRunner?.NetTime || '--:--'"></p>
                    </div>
                </div>

                <div class="border-t pt-4">
                    <div class="flex justify-between items-end mb-3 px-1">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Timing Analysis</p>
                        <span class="text-[9px] text-slate-400 font-bold uppercase" x-text="`${selectedRunner?.TimingPoints?.length || 0} Points`"></span>
                    </div>

                    <div class="space-y-2">
                        <div class="grid grid-cols-4 gap-1 px-2 text-[8px] font-black text-slate-400 uppercase tracking-tighter">
                            <div>Timing Pt</div>
                            <!-- <div class="text-center">Pass Time</div> -->
                            <div class="text-center">Gun Time</div>
                            <div class="text-right">Net Time</div>
                        </div>

                        <template x-if="selectedRunner?.TimingPoints && selectedRunner.TimingPoints.length > 0">
                            <template x-for="(tp, index) in selectedRunner.TimingPoints" :key="index">
                                <div class="grid grid-cols-4 gap-1 items-center bg-slate-50 p-2 rounded-xl border border-slate-100">
                                    <span class="font-bold text-slate-700 text-[9px] leading-tight truncate" x-text="tp.TpName || 'Point'"></span>
                                    
                                    <!-- <span class="font-mono font-bold text-slate-500 text-[9px] text-center" x-text="formatTime(tp.PassTime)"></span> -->
                                    
                                    <span class="font-mono font-bold text-slate-500 text-[9px] text-center" x-text="formatTime(tp.GunTime)"></span>
                                    
                                    <span class="font-mono font-bold text-blue-700 text-[9px] text-right" x-text="formatTime(tp.NetTime)"></span>
                                </div>
                            </template>
                        </template>

                        <template x-if="!selectedRunner?.TimingPoints || selectedRunner.TimingPoints.length === 0">
                            <div class="py-8 text-center bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                                <p class="text-[11px] text-slate-400 font-medium italic">No split data available.</p>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex justify-between items-center bg-slate-900 text-white p-4 rounded-2xl shadow-lg">
                    <div>
                        <p class="text-[9px] text-slate-400 font-bold uppercase">Race Status</p>
                        <p class="font-bold uppercase text-xs" x-text="selectedRunner?.FinishStatus"></p>
                    </div>
                    <div class="text-right">
                        <p class="text-[9px] text-slate-400 font-bold uppercase">Overall Rank</p>
                        <p class="text-lg font-black text-blue-400" x-text="`#${selectedRunner?.OverallPosition}`"></p>
                    </div>
                </div>
            </div>
            <button @click="showModal = false" class="w-full py-4 bg-slate-50 text-slate-400 font-bold hover:bg-slate-100 hover:text-slate-600 transition-colors uppercase text-[10px] tracking-widest border-t">Close Details</button>
        </div>
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>

<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection