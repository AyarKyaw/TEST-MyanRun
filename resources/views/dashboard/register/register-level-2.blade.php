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
                <h4 class="text-black">Upcoming Events</h4>
            </div>
            <div class="card-body">
                {{-- Example Event Item --}}
                <div class="d-flex mb-5 align-items-center event-list">
                    <div class="p-3 text-center rounded me-3 date-bx bgl-primary">
                        <h2 class="mb-0 text-black">3</h2>
                        <h5 class="mb-1 text-black">Wed</h5>
                    </div>
                    <div class="px-0">
                        <h6 class="mt-0 mb-3 fs-14"><a class="text-black" href="#">Live Concert Choir</a></h6>
                        <div class="progress mb-0" style="height:4px; width:100%;">
                            <div class="progress-bar bg-warning" style="width:85%;" role="progressbar"></div>
                        </div>
                    </div>
                </div>
                {{-- Add more event logic here --}}
            </div>
            <div class="card-footer justify-content-between border-0 d-flex fs-14">
                <span>5 events more</span>
                <a href="#" class="text-primary">View more <i class="las la-long-arrow-alt-right ms-2"></i></a>
            </div>
        </div>
    </div>

    <main class="content-body">
        <div class="container-fluid">
            
            <div class="page-title">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li><h1>Registration (Level 2)</h1></li>
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">Registration (Level 2)</li>
                    </ol>
                </nav>
            </div>

            <div class="row mb-5 align-items-center">
                <div class="col-xl-3 mb-4 mb-xl-0">
                    <a href="" class="btn btn-primary light btn-lg d-block rounded fs-18">+ New Customer</a>
                </div>

                <div class="col-xl-9">
                    <div class="card m-0">
                        <div class="card-body py-3">
                            <div class="row align-items-center">
                                <div class="col-md-5 d-flex align-items-center">
                                    <div class="ms-1">
                                        <p class="mb-0 fs-14">Total Customers</p>
                                        <h3 class="mb-0 text-black fw-semibold fs-16">{{ $customers->count() }} Person</h3>
                                    </div>
                                </div>
                                <div class="col-md-7 text-md-end">
                                    <button class="btn btn-outline-primary btn-xs px-4">Active</button>
                                    <button class="btn btn-danger btn-xs px-4 ms-2">Bulk Delete</button>
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
                                        <th><input type="checkbox" id="checkAll" class="form-check-input"></th>
                                        <th>Athlete & Face ID</th>
                                        <th>ID Verification</th>
                                        <th>Demographics</th>
                                        <th>Address & Contact</th>
                                        <th>Status</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customers as $customer)
                                    <tr>
                                        <td><input type="checkbox" class="form-check-input"></td>
                                        
                                        {{-- Face ID & Athlete Name --}}
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="position-relative">
                                                    <img src="{{ $customer->face_image_path ? asset('storage/' . $customer->face_image_path) : asset('images/no-face.png') }}" 
                                                        class="rounded-circle me-3 border {{ $customer->face_image_path ? 'border-success' : 'border-danger' }}" 
                                                        width="45" height="45" alt="Face ID" style="object-fit: cover;">
                                                    
                                                    @if($customer->face_image_path)
                                                        <span class="badge badge-success position-absolute bottom-0 end-0 p-1" style="font-size: 8px;">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fs-14 text-black">{{ $customer->first_name }} {{ $customer->last_name }}</h6>
                                                    <small class="text-muted">#{{ $customer->runner_id }}</small>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- KYC / Identification --}}
                                        <td>
                                            <span class="badge light badge-info mb-1">{{ $customer->nat_type }}</span><br>
                                            <span class="text-black fw-bold">{{ $customer->id_number }}</span><br>
                                            <small class="text-muted">S/o: {{ $customer->father_name }}</small>
                                        </td>

                                        {{-- Demographics --}}
                                        <td>
                                            <i class="fas fa-venus-mars text-primary me-1"></i> {{ ucfirst($customer->gender) }}<br>
                                            <i class="fas fa-calendar-alt text-primary me-1"></i> {{ \Carbon\Carbon::parse($customer->dob)->format('d M, Y') }}<br>
                                            <span class="text-primary fw-semibold">{{ $customer->nationality }}</span>
                                        </td>

                                        {{-- Address & Social --}}
                                        <td>
                                            <p class="mb-1 fs-12" style="max-width: 150px; white-space: normal;">
                                                <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ Str::limit($customer->address, 40) }}
                                            </p>
                                            <small class="text-primary"><i class="fab fa-facebook-messenger me-1"></i> {{ $customer->social_account ?? 'N/A' }}</small>
                                        </td>

                                        {{-- Security Status --}}
                                        <td>
                                            @if($customer->face_image_path && $customer->id_number)
                                                <span class="badge light badge-success">
                                                    <i class="fas fa-user-shield me-1"></i> KYC SECURE
                                                </span>
                                            @else
                                                <span class="badge light badge-warning">
                                                    <i class="fas fa-exclamation-triangle me-1"></i> INCOMPLETE
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Action --}}
                                        <td class="text-end">
                                            <div class="dropdown">
                                                <button type="button" class="btn btn-primary light sharp" data-bs-toggle="dropdown">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item text-success" href="#"><i class="fas fa-id-card me-2"></i> View KYC Profile</a>
                                                    <a class="dropdown-item text-primary" href="#"><i class="fas fa-camera me-2"></i> Update Face ID</a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger" href="#"><i class="fas fa-trash me-2"></i> Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <i class="fas fa-user-slash fs-30 text-muted mb-3 d-block"></i>
                                            No athletes found in the database.
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
    </main>
@endsection