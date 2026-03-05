@extends('dashboard.layouts.master')
@section('content')		
		<!-- Start - Eventlist -->
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
					<div class="d-flex mb-5 align-items-center event-list">
						<div class="p-3 text-center rounded me-3 date-bx bgl-primary">
							<h2 class="mb-0 text-black">3</h2>
							<h5 class="mb-1 text-black">Wed</h5>
						</div>
						<div class="px-0">
							<h6 class="mt-0 mb-3 fs-14"><a class="text-black" href="events.html">Live Concert Choir Charity Event 2020</a></h6>
							<ul class="fs-14 list-inline mb-2 d-flex justify-content-between">
								<li>Ticket Sold</li>
								<li>561/650</li>
							</ul>
							<div class="progress mb-0" style="height:4px; width:100%;">
								<div class="progress-bar bg-warning progress-animated" style="width:85%; height:100%;" role="progressbar">
									<span class="sr-only">60% Complete</span>
								</div>
							</div>
						</div>
					</div>
					<div class="d-flex mb-5 align-items-center event-list">
						<div class="p-3 text-center rounded me-3 date-bx bgl-primary">
							<h2 class="mb-0 text-black">16</h2>
							<h5 class="mb-1 text-black">Tue</h5>
						</div>
						<div class="px-0">
							<h6 class="mt-0 mb-3 fs-14"><a class="text-black" href="events.html">Beautiful Fireworks Show In The New Year Night</a></h6>
							<ul class="fs-14 list-inline mb-2 d-flex justify-content-between">
								<li>Ticket Sold</li>
								<li>431/650</li>
							</ul>
							<div class="progress mb-0" style="height:4px; width:100%;">
								<div class="progress-bar bg-warning progress-animated" style="width:50%; height:100%;" role="progressbar">
									<span class="sr-only">60% Complete</span>
								</div>
							</div>
						</div>
					</div>
					<div class="d-flex mb-0 align-items-center event-list">
						<div class="p-3 text-center rounded me-3 date-bx bgl-success">
							<h2 class="mb-0 text-black">28</h2>
							<h5 class="mb-1 text-black">Fri</h5>
						</div>
						<div class="media-body px-0">
							<h6 class="mt-0 mb-3 fs-14"><a class="text-black" href="events.html">The Story Of Danau Toba (Musical Drama)</a></h6>
							<ul class="fs-14 list-inline mb-2 d-flex justify-content-between">
								<li>Ticket Sold</li>
								<li>650/650</li>
							</ul>
							<div class="progress mb-0" style="height:4px; width:100%;">
								<div class="progress-bar bg-success progress-animated" style="width:100%; height:100%;" role="progressbar">
									<span class="sr-only">60% Complete</span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer justify-content-between border-0 d-flex fs-14">
					<span>5 events more</span>
					<a href="events.html" class="text-primary">View more <i class="las la-long-arrow-alt-right scale5 ms-2"></i></a>
				</div>
			</div>
			<div class="card shadow-none rounded-0 bg-transparent h-auto mb-0">
				<div class="card-body text-center event-calender">
					<button class="btn btn-primary btn-sm rounded-pill shadow fs-16" data-bs-toggle="modal" data-bs-target="#exampleModal">
						+ New Event
					</button>
				</div>
			</div>
		</div>
		<!-- End - Eventlist -->
		
		<!-- Start - Content Body -->
        <main class="content-body">
			<div class="container-fluid">
				
				<!-- Start - Page Title & Breadcrumb -->
				<div class="page-title">
					<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li><h1>Order List</h1></li>
        <li class="breadcrumb-item">
            <a href="index.html">
                <svg width="20" height="20" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z" stroke="var(--bs-body-color)" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="var(--bs-body-color)" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Home
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Order List</li>
    </ol>
</nav>
				</div>
				<!-- End - Page Title & Breadcrumb -->
				
                <div class="row mb-5 align-items-center">

					<!-- Start - Generate Report -->
					<div class="col-xxl-3 col-xl-3 col-lg-12 align-self-start mb-xl-0 mb-3">
						<div class="card mb-0 bgl-primary">
							<a href="javascript:void(0);" class="btn btn-sm btn-primary light fs-16">
								<svg class="me-xl-4 me-3" width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M33.9991 9.91632C33.9992 9.66181 33.9307 9.41199 33.8008 9.19311C33.6709 8.97424 33.4845 8.79439 33.2611 8.67249L17.6777 0.172485C17.4696 0.0589066 17.2363 -0.000610352 16.9991 -0.000610352C16.762 -0.000610352 16.5287 0.0589066 16.3206 0.172485L0.737221 8.67249C0.51372 8.79433 0.327179 8.97415 0.197213 9.19303C0.0672465 9.41191 -0.00134277 9.66176 -0.00134277 9.91632C-0.00134277 10.1709 0.0672465 10.4207 0.197213 10.6396C0.327179 10.8585 0.51372 11.0383 0.737221 11.1602L4.94897 13.458L0.737221 15.7558C0.51372 15.8777 0.327179 16.0575 0.197213 16.2764C0.0672465 16.4952 -0.00134277 16.7451 -0.00134277 16.9997C-0.00134277 17.2542 0.0672465 17.5041 0.197213 17.7229C0.327179 17.9418 0.51372 18.1216 0.737221 18.2435L4.94897 20.5413L0.737221 22.8392C0.51372 22.961 0.327179 23.1408 0.197213 23.3597C0.0672465 23.5786 -0.00134277 23.8284 -0.00134277 24.083C-0.00134277 24.3375 0.0672465 24.5874 0.197213 24.8063C0.327179 25.0252 0.51372 25.205 0.737221 25.3268L16.3206 33.8268C16.5308 33.9337 16.7633 33.9894 16.9991 33.9894C17.235 33.9894 17.4675 33.9337 17.6777 33.8268L33.2611 25.3268C33.4846 25.205 33.6711 25.0252 33.8011 24.8063C33.931 24.5874 33.9996 24.3375 33.9996 24.083C33.9996 23.8284 33.931 23.5786 33.8011 23.3597C33.6711 23.1408 33.4846 22.961 33.2611 22.8392L29.0493 20.5413L33.2611 18.2449C33.4846 18.1231 33.6711 17.9432 33.8011 17.7244C33.931 17.5055 33.9996 17.2556 33.9996 17.0011C33.9996 16.7465 33.931 16.4967 33.8011 16.2778C33.6711 16.0589 33.4846 15.8791 33.2611 15.7572L29.0493 13.458L33.2611 11.1616C33.4847 11.0395 33.6713 10.8595 33.8012 10.6403C33.931 10.4212 33.9994 10.1711 33.9991 9.91632ZM29.6245 24.083L16.9991 30.9694L4.37381 24.083L7.90697 22.1535L16.3206 26.7435C16.5294 26.8547 16.7625 26.9129 16.9991 26.9129C17.2358 26.9129 17.4688 26.8547 17.6777 26.7435L26.0913 22.1535L29.6245 24.083ZM29.6245 16.9997L16.9991 23.8861L4.37381 16.9997L7.90697 15.0702L16.3206 19.6602C16.5291 19.7725 16.7623 19.8313 16.9991 19.8313C17.236 19.8313 17.4692 19.7725 17.6777 19.6602L26.0913 15.0702L29.6245 16.9997ZM16.9991 16.8027L4.37381 9.91632L16.9991 3.0299L29.6245 9.91632L16.9991 16.8027Z" fill="var(--bs-primary)"/>
								</svg>
								Generate Report
							</a>
						</div>
					</div>
					<!-- End - Generate Report -->

					<!-- Start - Order Stats -->
					<div class="col-xl-9 col-lg-12 mb-xl-0">
						<div class="card m-0 ">
							<div class="card-body px-3 py-3 py-xl-2">
								<div class="row align-items-center gx-2">
									<div class="col-xl-3 col-lg-3 col-md-3 d-md-block d-none">
										<p class="mb-0 fs-13">Lorem Ipsum is simply dummy text of the printing</p>
									</div>
									<div class="col-xl-7 col-lg-7 col-md-7">
										<div class="row align-items-center gx-2">
											<div class="col-xl-4 col-md-4 col-sm-4 col-4">
												<div class="d-flex align-items-center">
													<span class="me-3">
														<svg width="30" height="19" viewBox="0 0 30 19" fill="none" xmlns="http://www.w3.org/2000/svg">
															<path fill-rule="evenodd" clip-rule="evenodd" d="M29.3124 0.990819C30.1459 1.71561 30.234 2.97887 29.5092 3.81239L20.7578 13.8765C19.359 15.4851 16.9444 15.7141 15.2681 14.397L11.1176 11.1359L3.87074 17.9564C3.06639 18.7135 1.80064 18.6751 1.04361 17.8708C0.286573 17.0664 0.324929 15.8007 1.12928 15.0436L8.3761 8.22309C9.817 6.86695 12.0329 6.76812 13.5888 7.99062L17.7394 11.2518L26.4908 1.18767C27.2156 0.354158 28.4788 0.266024 29.3124 0.990819Z" fill="var(--bs-primary)"/>
														</svg>
													</span>
													<div class="ms-1">
														<p class="mb-0 fs-12">Customer</p>
														<h5 class="mb-0 text-black">765 Person</h5>
													</div>
												</div>
											</div>
											<div class="col-xl-4 col-md-4 col-sm-4 col-4">
												<div class="d-flex align-items-center">
													<span class="me-3">
														<svg width="25" height="26" viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
														<rect width="3.54545" height="26" rx="1.77273" transform="matrix(-1 0 0 1 24.8181 0)" fill="var(--bs-primary)"/>
														<rect width="3.54545" height="18.9091" rx="1.77273" transform="matrix(-1 0 0 1 17.7271 7.09088)" fill="var(--bs-primary)"/>
														<rect width="3.54545" height="8.27273" rx="1.77273" transform="matrix(-1 0 0 1 10.6362 17.7273)" fill="var(--bs-primary)"/>
														<rect width="4" height="16" rx="2" transform="matrix(-1 0 0 1 4 10)" fill="var(--bs-primary)"/>
														</svg>
													</span>
													<div class="ms-1">
														<p class="mb-0 fs-12">Income</p>
														<h5 class="mb-0 text-black">$126,000</h5>
													</div>
												</div>
											</div>
											<div class="col-xl-4 col-md-4 col-sm-4 col-4">
												<div class="d-flex align-items-center">
													<span class="me-3">
														<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
														<path fill-rule="evenodd" clip-rule="evenodd" d="M12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM12 24C18.6274 24 24 18.6274 24 12C24 5.37258 18.6274 0 12 0C5.37258 0 0 5.37258 0 12C0 18.6274 5.37258 24 12 24Z" fill="white" fill-opacity="0.18"/>
														<path fill-rule="evenodd" clip-rule="evenodd" d="M15.9999 0.686289C14.7205 0.233951 13.3682 0 11.9999 0V3.93696C13.4442 3.93696 14.8619 4.32489 16.105 5.06021C17.3481 5.79553 18.3708 6.85124 19.0664 8.117C19.7619 9.38277 20.1047 10.8121 20.0589 12.2557C20.0131 13.6992 19.5804 15.104 18.806 16.3231C18.0317 17.5422 16.9441 18.531 15.6569 19.186C14.3697 19.8411 12.9302 20.1384 11.4888 20.0468C10.0475 19.9553 8.65715 19.4783 7.46319 18.6656C6.26922 17.853 5.31544 16.7346 4.70154 15.4273L1.13794 17.1007C1.71955 18.3393 2.50612 19.4639 3.45939 20.4297C4.00364 20.9811 4.60223 21.4807 5.24803 21.9203C7.02498 23.1297 9.09416 23.8396 11.2393 23.9759C13.3845 24.1121 15.5268 23.6697 17.4425 22.6948C19.3582 21.7199 20.9768 20.2483 22.1293 18.4339C23.2818 16.6195 23.9257 14.5289 23.9939 12.3805C24.062 10.2321 23.5519 8.10484 22.5167 6.22104C21.4816 4.33724 19.9595 2.76605 18.1094 1.6717C17.4371 1.27398 16.7304 0.944541 15.9999 0.686289Z" fill="var(--bs-primary)"/>
														</svg>
													</span>
													<div class="ms-1">
														<p class="mb-0 fs-12">Than last week</p>
														<h4 class="mb-0 text-black">72%
															<svg class="ms-2" width="12" height="6" viewBox="0 0 12 6" fill="none" xmlns="http://www.w3.org/2000/svg">
																<path d="M0 6L6 2.62268e-07L12 6" fill="var(--bs-primary)"/>
															</svg>
														</h4>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xl-2 col-lg-2 col-md-2 d-md-block d-none text-end">
										<div class="clearfix">
											<select class="selectpicker form-select form-select-sm" data-width="auto">
												<option selected>Daily</option>
												<option value="1">Newest</option>
												<option value="2">Old</option>
											</select>
										</div>
									</div>
								</div>							
							</div>
						</div>
					</div>
					<!-- End - Order Stats -->

				</div>
                <div class="row">

					<!-- Start - Order Table -->
					<div class="col-lg-12">
						<div class="card">
							<div class="table-responsive table-hover">
								<table class="display dataTablesCard table-lg" id="example5">
									<thead>
										<tr>
											<th class="mw-110">Order ID</th>
											<th class="mw-130">Date</th>
											<th class="mw-150">Event NAME</th>
											<th class="mw-150">Customer Name</th>
											<th class="mw-120">Location</th>
											<th class="mw-110">Sold Ticket</th>
											<th class="mw-150">Available</th>
											<th class="mw-120">Refund</th>
											<th class="mw-150">Totle Revenue</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>#0012451</td>
											<td>04/08/2020 12:34 AM</td>
											<td>The Story Of Danaou Taba (Musical Drama)</td>
											<td>Bella Simatupang</td>
											<td>London, US</td>
											<td>1 pcs</td>
											<td>567k left</td>
											<td class="text-black fw-bold">NO</td>
											<td><a href="javascript:void(0);" class="btn btn-primary light btn-xs">$125,70</a></td>
										</tr>
										<tr>
											<td>#0012451</td>
											<td>04/08/2020 12:34 AM</td>
											<td>The Story Of Danaou Taba (Musical Drama)</td>
											<td>Bella Simatupang</td>
											<td>Medan, Indonesia</td>
											<td>2 pcs</td>
											<td>567k left</td>
											<td class="text-black fw-bold">NO</td>
											<td><a href="javascript:void(0);" class="btn btn-primary light btn-xs">$536,00</a></td>
										</tr>
										<tr>
											<td>#0012451</td>
											<td>04/08/2020 12:34 AM</td>
											<td>The Story Of Danaou Taba (Musical Drama)</td>
											<td>Bella Simatupang</td>
											<td>Jakarta, Indonesia</td>
											<td>3 pcs</td>
											<td>567k left</td>
											<td class="text-danger fw-bold">Refund</td>
											<td><a href="javascript:void(0);" class="btn btn-primary light btn-xs">$124,55</a></td>
										</tr>
										<tr>
											<td>#0012451</td>
											<td>04/08/2020 12:34 AM</td>
											<td>The Story Of Danaou Taba (Musical Drama)</td>
											<td>Bella Simatupang</td>
											<td>Penang, Malaysia</td>
											<td>4 pcs</td>
											<td>567k left</td>
											<td class="text-black fw-bold">NO</td>
											<td><a href="javascript:void(0);" class="btn btn-primary light btn-xs">$536,00</a></td>
										</tr>
										<tr>
											<td>#0012451</td>
											<td>04/08/2020 12:34 AM</td>
											<td>The Story Of Danaou Taba (Musical Drama)</td>
											<td>Bella Simatupang</td>
											<td>Sydney, Australia</td>
											<td>1 pcs</td>
											<td>567k left</td>
											<td class="text-black fw-bold">NO</td>
											<td><a href="javascript:void(0);" class="btn btn-primary light btn-xs">$65,22</a></td>
										</tr>
										<tr>
											<td>#0012451</td>
											<td>04/08/2020 12:34 AM</td>
											<td>The Story Of Danaou Taba (Musical Drama)</td>
											<td>Bella Simatupang</td>
											<td>London,US</td>
											<td>5 pcs</td>
											<td>567k left</td>
											<td class="text-black fw-bold">NO</td>
											<td><a href="javascript:void(0);" class="btn btn-primary light btn-xs">$44,00</a></td>
										</tr>
										<tr>
											<td>#0012451</td>
											<td>04/08/2020 12:34 AM</td>
											<td>The Story Of Danaou Taba (Musical Drama)</td>
											<td>Bella Simatupang</td>
											<td>London,US</td>
											<td>6 pcs</td>
											<td>567k left</td>
											<td class="text-danger fw-bold">Refund</td>
											<td><a href="javascript:void(0);" class="btn btn-primary light btn-xs">$51,50</a></td>
										</tr>
										<tr>
											<td>#0012451</td>
											<td>04/08/2020 12:34 AM</td>
											<td>The Story Of Danaou Taba (Musical Drama)</td>
											<td>Bella Simatupang</td>
											<td>Jakarta, Indonesia</td>
											<td>3 pcs</td>
											<td>567k left</td>
											<td class="text-danger fw-bold">Refund</td>
											<td><a href="javascript:void(0);" class="btn btn-primary light btn-xs">$124,55</a></td>
										</tr>
										<tr>
											<td>#0012451</td>
											<td>04/08/2020 12:34 AM</td>
											<td>The Story Of Danaou Taba (Musical Drama)</td>
											<td>Bella Simatupang</td>
											<td>Penang, Malaysia</td>
											<td>4 pcs</td>
											<td>567k left</td>
											<td class="text-black fw-bold">NO</td>
											<td><a href="javascript:void(0);" class="btn btn-primary light btn-xs">$536,00</a></td>
										</tr>
										<tr>
											<td>#0012451</td>
											<td>04/08/2020 12:34 AM</td>
											<td>The Story Of Danaou Taba (Musical Drama)</td>
											<td>Bella Simatupang</td>
											<td>Sydney, Australia</td>
											<td>1 pcs</td>
											<td>567k left</td>
											<td class="text-black fw-bold">NO</td>
											<td><a href="javascript:void(0);" class="btn btn-primary light btn-xs">$65,22</a></td>
										</tr>
										<tr>
											<td>#0012451</td>
											<td>04/08/2020 12:34 AM</td>
											<td>The Story Of Danaou Taba (Musical Drama)</td>
											<td>Bella Simatupang</td>
											<td>London,US</td>
											<td>5 pcs</td>
											<td>567k left</td>
											<td class="text-black fw-bold">NO</td>
											<td><a href="javascript:void(0);" class="btn btn-primary light btn-xs">$44,00</a></td>
										</tr>
										<tr>
											<td>#0012451</td>
											<td>04/08/2020 12:34 AM</td>
											<td>The Story Of Danaou Taba (Musical Drama)</td>
											<td>Bella Simatupang</td>
											<td>London,US</td>
											<td>6 pcs</td>
											<td>567k left</td>
											<td class="text-danger fw-bold">Refund</td>
											<td><a href="javascript:void(0);" class="btn btn-primary light btn-xs">$51,50</a></td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
                    </div>
					<!-- End - Order Table -->

				</div>
            </div>
        </main>
        <!-- End - Content Body -->

       	<!-- Start - Event  Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Event Title</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<div class="col-xl-12">
							<div class="mb-3">
								<label for="exampleFormControlInput1" class="form-label">Event Name</label>
								<input type="text" class="form-control" id="exampleFormControlInput1" placeholder="The Story Of Danau Toba">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-danger light" data-bs-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
			</div>
		</div>
		<!-- End - Event Modal -->
@endsection