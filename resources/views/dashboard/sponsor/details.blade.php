@extends('dashboard.layouts.master')

@section('content')
<div class="content-body">
    <div class="container-fluid">
        {{-- Header Info --}}
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm border-0" style="border-radius: 20px;">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="font-weight-bold text-dark mb-0">{{ $sponsor->name }}</h2>
                            <span class="badge badge-pill badge-info text-uppercase mt-2">
                                Contact: {{ $sponsor->contact_name }} | {{ $sponsor->phone }}
                            </span>
                        </div>
                        <div class="text-right">
                            <a href="{{ route('admin.sponsor.index', 'now') }}" class="btn btn-light btn-sm rounded-pill px-3">
                                <i class="fas fa-chevron-left mr-1"></i> Back
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Statistics --}}
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-dark shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body">
                        <h6 class="text-uppercase opacity-7">Shared Sponsor Code</h6>
                        <h2 class="font-weight-bold">{{ $sponsor->sponsorCode->code ?? 'N/A' }}</h2>
                        <small>{{ $sponsor->sponsorCode->discount }}% Discount Applied</small>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-success shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body">
                        <h6 class="text-uppercase opacity-7">Quota Used</h6>
                        <h2 class="font-weight-bold">{{ $sponsor->sponsorCode->used_count ?? 0 }} / {{ $sponsor->sponsorCode->max_uses ?? 0 }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card text-white bg-warning shadow-sm h-100" style="border-radius: 15px;">
                    <div class="card-body">
                        <h6 class="text-uppercase opacity-7">Remaining Slots</h6>
                        <h2 class="font-weight-bold">{{ ($sponsor->sponsorCode->max_uses ?? 0) - ($sponsor->sponsorCode->used_count ?? 0) }}</h2>
                    </div>
                </div>
            </div>

            {{-- Usage Log (The real list of people) --}}
            <div class="col-12">
                <div class="card shadow-sm" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                        <h4 class="font-weight-bold">Registered Guests under {{ $sponsor->name }}</h4>
                        {{-- Future Batch Print Button --}}
                        <button class="btn btn-primary">
                            <i class="fas fa-print mr-2"></i> Batch Print PNG Tickets
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="text-muted" style="font-size: 11px; text-transform: uppercase;">
                                    <tr>
                                        <th>Guest Name</th>
                                        <th>Email</th>
                                        <th>Ticket No</th>
                                        <th>Status</th>
                                        <th>Registration Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Here we loop through DinnerTickets that belong to this sponsor --}}
                                    @forelse($sponsor->tickets as $ticket)
                                    <tr>
                                        <td class="font-weight-bold">{{ $ticket->registration->first_name }} {{ $ticket->registration->last_name }}</td>
                                        <td>{{ $ticket->registration->email }}</td>
                                        <td><code class="text-primary font-weight-bold">{{ $ticket->ticket_no }}</code></td>
                                        <td>
                                            @if($ticket->status === 'confirmed')
                                                <span class="badge badge-success text-white px-3" style="background-color: #1eff00 !important; color: #ffffff !important;">
                                                    <i class="fas fa-check-circle mr-1"></i> CONFIRMED
                                                </span>
                                            @else
                                                {{-- Added 'text-dark' and inline style to guarantee visibility --}}
                                                <span class="badge badge-warning text-dark px-3" style="background-color: #ffc107 !important; color: #000 !important;">
                                                    <i class="fas fa-clock mr-1"></i> PENDING
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ $ticket->created_at->format('M d, Y - h:i A') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            No guests have registered using this sponsor's quota yet.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection