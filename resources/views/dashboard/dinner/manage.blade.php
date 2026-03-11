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
                    <p class="text-muted text-center">No upcoming dinners.</p>
                @endforelse
            </div>
            <div class="card-footer justify-content-between border-0 d-flex fs-14">
                <span>{{ $dinners->count() }} records found</span>
            </div>
        </div>
    </div>

    <main class="content-body">
        <div class="container-fluid">
            
            <div class="page-title">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li><h1>{{ $title ?? 'Dinner Management' }}</h1></li>
                        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Dinners</li>
                    </ol>
                </nav>
            </div>

            <div class="row mb-5 align-items-center">
                <div class="col-xl-3 mb-4 mb-xl-0">
                    <a href="{{ route('admin.dinner.create') }}" class="btn btn-primary light btn-lg d-block rounded fs-18">+ Create New Dinner</a>
                </div>

                <div class="col-xl-9">
                    <div class="card m-0">
                        <div class="card-body py-3">
                            <div class="row align-items-center">
                                <div class="col-md-5 d-flex align-items-center">
                                    <div class="ms-1">
                                        <p class="mb-0 fs-14">Status: {{ ucfirst($timeframe) }}</p>
                                        <h3 class="mb-0 text-black fw-semibold fs-16">{{ $dinners->count() }} Dinners</h3>
                                    </div>
                                </div>
                                <div class="col-md-7 text-md-end">
                                    <a href="{{ route('admin.dinner.manage', 'now') }}" class="btn btn-success {{ $timeframe == 'now' ? '' : 'light' }} btn-xs px-4">Active</a>
                                    <a href="{{ route('admin.dinner.manage', 'past') }}" class="btn btn-secondary {{ $timeframe == 'past' ? '' : 'light' }} btn-xs px-4 ms-2">Past</a>
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
                                        <th>Dinner Name</th>
                                        <th>Company</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dinners as $dinner)
                                    <tr>
                                        <td>
                                            @if($dinner->image_path)
                                                <img src="{{ asset('storage/' . $dinner->image_path) }}" alt="" class="rounded" width="50" height="50" style="object-fit: cover;">
                                            @else
                                                <div class="rounded bg-light d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                                    <i class="fa fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </td>
                                        
                                        <td><strong>{{ $dinner->name }}</strong></td>
                                        <td>{{ $dinner->company ?? 'N/A' }}</td>
                                        <td>{{ $dinner->date ? $dinner->date->format('d/m/Y') : 'No Date' }}</td>
                                        
                                        <td>
                                            @if($dinner->is_active == 1)
                                                <span class="badge light badge-success">ACTIVE</span>
                                            @else
                                                <span class="badge light badge-secondary">PAST</span>
                                            @endif
                                        </td>
                                        
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('admin.dinner.edit', $dinner->id) }}" 
                                                class="btn btn-primary shadow btn-xs btn-square me-1">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            </button>
                                                <form action="{{ route('admin.dinner.destroy', $dinner->id) }}" method="POST" onsubmit="return confirm('Delete this dinner?')">
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
                                        <td colspan="6" class="text-center">No dinner records found in this category.</td>
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