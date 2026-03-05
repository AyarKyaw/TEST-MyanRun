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
                        <li><h1>Registration (Level 1)</h1></li>
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">Registration (Level 1)</li>
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
                                        <th>Runner ID</th>
                                        <th>Customer Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Date Join</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
									@forelse($customers as $customer)
									<tr>
										<td><input type="checkbox" class="form-check-input"></td>
										
										{{-- Using runner_id from your table --}}
										<td>#{{ $customer->runner_id }}</td>
										
										{{-- Concatenating First, Middle, and Last Name --}}
										<td>
											{{ $customer->first_name }} 
											{{ $customer->middle_name ? $customer->middle_name . ' ' : '' }} 
											{{ $customer->last_name }}
										</td>
										
										<td class="text-primary font-w500">{{ $customer->email }}</td>
										
										{{-- Displaying Phone (or email) since you don't have a 'location' column yet --}}
										<td>{{ $customer->phone ?? 'N/A' }}</td>
										
										{{-- Displaying Email --}}
										
                                        <td>{{ $customer->created_at->format('d/m/Y') }}</td>
										<td>
											<div class="d-flex">
												<a href="#" class="btn btn-primary shadow btn-xs btn-square me-1"><i class="fas fa-pencil-alt"></i></a>
												{{-- Action remains empty for now as you don't have a delete route --}}
												<button type="button" class="btn btn-danger shadow btn-xs btn-square"><i class="fa fa-trash"></i></button>
											</div>
										</td>
									</tr>
									@empty
									<tr>
										<td colspan="7" class="text-center">No customers found.</td>
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