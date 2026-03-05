@extends('dashboard.layouts.master')

@section('content')        
    <div class="event-sidebar dz-scroll" id="eventSidebar">
        <div class="card shadow-none rounded-0 bg-transparent h-auto mb-0">
            <div class="card-body text-center event-calender pb-2">
                <input type='text' class="form-control d-none" id='datetimepicker1'>
            </div>
        </div>
        
        <div class="card shadow-none rounded-0 bg-transparent h-auto">
            <div class="card-header border-0 pb-0">
                <h4 class="text-black">Coming Soon</h4>
            </div>
            <div class="card-body">
                {{-- Always show manually set 'Coming' events in sidebar --}}
                @forelse($sidebarEvents as $sidebarEvent)
                    <div class="d-flex mb-5 align-items-center event-list">
                        <div class="p-3 text-center rounded me-3 date-bx bgl-primary">
                            <h2 class="mb-0 text-black">{{ $sidebarEvent->date->format('d') }}</h2>
                            <h5 class="mb-1 text-black">{{ $sidebarEvent->date->format('D') }}</h5>
                        </div>
                        <div class="px-0">
                            <h6 class="mt-0 mb-1 fs-14">
                                <a class="text-black" href="#">{{ $sidebarEvent->name }}</a>
                            </h6>
                            <small class="text-muted d-block mb-2">{{ $sidebarEvent->company }}</small>
                            <div class="progress mb-0" style="height:4px; width:100%;">
                                <div class="progress-bar bg-warning" style="width:100%;" role="progressbar"></div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center">No upcoming events.</p>
                @endforelse
            </div>
            <div class="card-footer justify-content-between border-0 d-flex fs-14">
                <span>{{ $events->count() }} events in this view</span>
            </div>
        </div>
    </div>

    <main class="content-body">
        <div class="container-fluid">
            
            <div class="page-title">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li><h1>{{ $title ?? 'Event Management' }}</h1></li>
                        <li class="breadcrumb-item"><a href="">Dashboard</a></li>
                        <li class="breadcrumb-item active">Events</li>
                    </ol>
                </nav>
            </div>

            <div class="row mb-5 align-items-center">
                <div class="col-xl-3 mb-4 mb-xl-0">
                    <a href="{{ route('events.create') }}" class="btn btn-primary light btn-lg d-block rounded fs-18">+ Create New Event</a>
                </div>

                <div class="col-xl-9">
                    <div class="card m-0">
                        <div class="card-body py-3">
                            <div class="row align-items-center">
                                <div class="col-md-5 d-flex align-items-center">
                                    <div class="ms-1">
                                        <p class="mb-0 fs-14">Filtered Total</p>
                                        <h3 class="mb-0 text-black fw-semibold fs-16">{{ $events->count() }} Events</h3>
                                    </div>
                                </div>
                                <div class="col-md-7 text-md-end">
                                    {{-- Manual Filter Buttons --}}
                                    <a href="{{ route('events.index', 'now') }}" class="btn btn-success {{ $status == 'now' ? '' : 'light' }} btn-xs px-4">Now</a>
                                    <a href="{{ route('events.index', 'coming') }}" class="btn btn-info {{ $status == 'coming' ? '' : 'light' }} btn-xs px-4 ms-2">Coming</a>
                                    <a href="{{ route('events.index', 'past') }}" class="btn btn-secondary {{ $status == 'past' ? '' : 'light' }} btn-xs px-4 ms-2">Past</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover dataTablesCard" id="example5">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Event Name</th>
                                        <th>Company</th>
                                        <th>Event Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($events as $event)
                                    <tr>
                                        <td>
                                            @if($event->image_path)
                                                <img src="{{ asset('storage/' . $event->image_path) }}" alt="" class="rounded" width="50" height="50" style="object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                                    <i class="fa fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        
                                        <td><strong>{{ $event->name }}</strong></td>
                                        <td>{{ $event->company }}</td>
                                        <td>{{ $event->date ? $event->date->format('d/m/Y') : 'No Date' }}</td>
                                        
                                        {{-- Manual Status Badges based on is_active --}}
                                        <td>
                                            @if($event->is_active == 1)
                                                <span class="badge light badge-success">LIVE NOW</span>
                                            @elseif($event->is_active == 2)
                                                <span class="badge light badge-info">COMING</span>
                                            @else
                                                <span class="badge light badge-secondary">PAST</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-primary shadow btn-xs btn-square me-1">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                <form action="{{ route('events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Delete this event?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger shadow btn-xs btn-square">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No events assigned to this category.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection