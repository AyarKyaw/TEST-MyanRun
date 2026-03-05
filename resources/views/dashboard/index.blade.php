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
        <div class="content-body rightside-event">
			<div class="container-fluid">
				<div class="row">
					
					<!-- Start - Welcome Card -->
					<div class="col-xl-12">
						<div class="welcome-card rounded ps-5 pt-4 pb-4 mt-3 position-relative mb-5">
							<h4 class="fw-semibold text-warning">Welcome to Tixia!</h4>
							<p class="lh-lg">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dumm.</p>
							<a class="btn btn-sm btn-warning rounded-pill fs-14" href="javascript:void(0);">Learn More <i class="las la-long-arrow-alt-right ms-sm-4 ms-2"></i></a>
							<a class="btn-link text-gray ms-3" href="javascript:void(0);">Remind Me Later</a>
							<img src="assets/images/svg/welcom-card.svg" alt="" class="position-absolute">
						</div>
					</div>
					<!-- End - Welcome Card -->

					<!-- Start - Sales Revenue -->
					<div class="col-xl-12">
						<div id="user-activity" class="card">
							<div class="card-header border-0 pb-0 d-flex flex-wrap">
								<div>
									<h4 class="fs-20 card-title mb-1">Sales Revenue</h4>
								</div>
								<div>
									<ul class="nav nav-pills nav-pills nav-pills-md nav-pills-card mb-3 gap-1 p-0 rounded-pill" id="justify-tab1" role="tablist">
										<li class="nav-item " role="presentation">
											<button class="nav-link active rounded-pill" id="justify-home-tab1" data-bs-toggle="pill" data-bs-target="#justify-home1" type="button" role="tab" aria-controls="justify-home" aria-selected="true">Monthly</button>
										</li>
										<li class="nav-item" role="presentation">
											<button class="nav-link rounded-pill" id="justify-profile-tab1" data-bs-toggle="pill" data-bs-target="#justify-profile1" type="button" role="tab" aria-controls="justify-profile" aria-selected="false" tabindex="-1">Weekly</button>
										</li>
										<li class="nav-item" role="presentation">
											<button class="nav-link rounded-pill" id="justify-contact-tab1" data-bs-toggle="pill" data-bs-target="#justify-contact1" type="button" role="tab" aria-controls="justify-contact" aria-selected="false" tabindex="-1">Today</button>
										</li>
									</ul>
								</div>
							</div>
							<div class="card-body">
								<div class="tab-content" id="myTabContent">
									<div class="tab-pane fade active show" id="user" role="tabpanel"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
										<canvas id="activityLine" class="chartjs chartjs-render-monitor" height="350" style="display: block; width: 1041px; height: 350px;" width="1041"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- End - Sales Revenue -->

					<!-- Start - Ticket Stats -->
					<div class="col-xl-6 col-xxxl-12 col-lg-6">
						<div class="card">
							<div class="card-header border-0 pb-3 d-sm-flex d-block ">
								<h4 class="card-title">Ticket Status</h4>
								<div class="d-flex mt-3 mt-sm-0">
									<select class="selectpicker form-select form-select-sm me-3">
										<option selected>Weekly</option>
										<option value="1">Daily</option>
										<option value="2">Monthly</option>
									</select>
									<select class="selectpicker form-select form-select-sm">
										<option selected>2023</option>
										<option value="1">2023</option>
										<option value="2">2024</option>
									</select>
								</div>
							</div>
							<div class="card-body">
								<div class="row mx-0 align-items-center">
									<div class="col-sm-8 col-md-12 col-xl-12 col-xxl-7 mb-0 mb-md-3 text-center mb-3 mb-sm-0">
										<div id="chart" class="d-inline-block"></div>
									</div>
									<div class="col-sm-4 col-md-12 col-xl-12 col-xxl-5">
										<div>
											<div class="d-flex mb-5">
												<span class="avatar avatar-xs bg-warning border-0"></span>	
												<div class="mx-3">
													<p class="fs-14 mb-1">Ticket Left</p>
													<h3 class="fs-22 mb-0 text-black fw-semibold">21,512</h3>
												</div>
											</div>
											<div class="d-flex mb-5">
												<span class="avatar avatar-xs bg-primary border-0"></span>	
												<div class="mx-3">
													<p class="fs-14 mb-1">Ticket Sold</p>
													<h3 class="fs-22 mb-0 text-black fw-semibold">456,72</h3>
												</div>
											</div>
											<div class="d-flex mb-5">
												<span class="avatar avatar-xs bg-success border-0"></span>	
												<div class="mx-3">
													<p class="fs-14 mb-1">Event Held</p>
													<h3 class="fs-22 mb-0 text-black fw-semibold">235</h3>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- End - Ticket Stats -->
					 
					<!-- Start - Latest Sale  -->
					<div class="col-xl-6 col-xxxl-12 col-lg-6">
						<div class="card">
							<div class="card-header border-0 pb-0 ">
								<h4 class="card-title">Latest Sales</h4>
								<div class="dropdown ms-auto text-end">
									<div class="btn-link" data-bs-toggle="dropdown">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"></rect><circle fill="#000000" cx="12" cy="5" r="2"></circle><circle fill="#000000" cx="12" cy="12" r="2"></circle><circle fill="#000000" cx="12" cy="19" r="2"></circle></g></svg>
									</div>
									<div class="dropdown-menu dropdown-menu-end">
										<a class="dropdown-item" href="javascript:void(0);">View Detail</a>
										<a class="dropdown-item" href="javascript:void(0);">Edit</a>
										<a class="dropdown-item" href="javascript:void(0);">Delete</a>
									</div>
								</div>
							</div>
							<div class="card-body pb-2 dz-scroll height370 loadmore-content" id="RecentActivityContent">
								<div class="d-flex pb-3 mb-3 border-bottom align-items-end">
									<div class="me-3">
										<img class="rounded-circle" alt="image" width="50" src="assets/images/avatar/1.webp">
									</div>
									<div>
										<h5 class="mb-1 fw-semibold "><a class="text-black" href="javascript:void(0);">Olivia Johnson</a></h5>
										<p class="mb-0 text-primary"><i class="las la-ticket-alt me-2 scale5 ms-1"></i>Height Performance conert 2020</p>
									</div>
									<small class="mb-0 ms-auto">2m ago</small>
								</div>
								<div class="d-flex pb-3 mb-3 border-bottom align-items-end">
									<div class="me-3">
										<img class="rounded-circle" alt="image" width="50" src="assets/images/avatar/2.webp">
									</div>
									<div>
										<h5 class="mb-1"><a class="text-black" href="javascript:void(0);">Griezerman</a></h5>
										<p class="mb-0 text-primary"><i class="las la-ticket-alt me-2 scale5 ms-1"></i>Fireworks Show New Year 2020</p>
									</div>
									<small class="mb-0 ms-auto">5m ago</small>
								</div>
								<div class="d-flex pb-3 mb-3 border-bottom align-items-end">
									<div class="me-3">
										<img class="rounded-circle" alt="image" width="50" src="assets/images/avatar/3.webp">
									</div>
									<div>
										<h5 class="mb-1"><a class="text-black" href="javascript:void(0);">Uli Trumb</a></h5>
										<p class="mb-0 text-primary"><i class="las la-ticket-alt me-2 scale5 ms-1"></i>Height Performance conert 2020</p>
									</div>
									<small class="mb-0  ms-auto">8m ago</small>
								</div>
								<div class="d-flex pb-3 mb-3 border-bottom align-items-end">
									<div class="me-3">
										<img class="rounded-circle" alt="image" width="50" src="assets/images/avatar/4.webp">
									</div>
									<div>
										<h5 class="mb-1"><a class="text-black" href="javascript:void(0);">Oconner</a></h5>
										<p class="mb-0 text-primary"><i class="las la-ticket-alt me-2 scale5 ms-1"></i>Fireworks Show New Year 2020</p>
									</div>
									<small class="mb-0 ms-auto">12m ago</small>
								</div>
							</div>
							<div class="card-footer border-0 pt-0 text-center">
								<a href="javascript:void(0);" class="btn-link dz-load-more btn" id="RecentActivity" rel="ajax/recentactivity.html">View more<i class="fa fa-angle-down ms-2 scale-2"></i></a>
							</div>
						</div>
					</div>
					<!-- End - Latest Sale  -->

					<!-- Start - Ticket Sold Today -->
					<div class="col-xl-12">
						<div class="card">
							<div class="card-body">
								<div class="row mx-0">
									<div class="col-sm-12 col-lg-4 px-0">
										<h2 class="fs-40 text-black fw-semibold">862,441 <small class="fs-18 ms-2 font-w600 mb-1">pcs</small></h2>
										<p class="fw-light fs-20 text-black">Ticket Sold Today</p>
										<div class="justify-content-between border-0 d-flex fs-14 align-items-end">
											<a href="analytics.html" class="text-primary">View more <i class="las la-long-arrow-alt-right scale5 ms-2"></i></a>
											<div class="text-end">
												<span class="peity-primary" data-style="width:100%;">0,2,1,4</span>
												<h3 class="mt-2 mb-1">+4%</h3>
												<span>than last day</span>
											</div>
										</div>
									</div>
									<div class="col-sm-12 col-lg-8 px-0">
										<canvas id="ticketSold" height="200"></canvas>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- End - Ticket Sold Today -->

				</div>
            </div>
        </div>
        <!-- End - Content Body -->

		<!-- Start - Footer -->
<div class="footer">
    <div class="copyright">
        <p>Copyright © Designed &amp; Developed by <a href="http://dexignzone.com/" target="_blank">DexignZone</a> <span class="current-year">2023</span></p>
    </div>
</div>
<!-- End - Footer -->
		
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
@push('scripts')
<script>
    $(function () {
        // Initialize the Inline Calendar
        if(jQuery('#datetimepicker1').length > 0) {
            $('#datetimepicker1').datetimepicker({
                inline: true,
                format: 'YYYY-MM-DD'
            });
        }
        
        // Ensure the sidebar scroll is active
        if(jQuery('.dz-scroll').length > 0) {
            $(".dz-scroll").each(function(){
                var scroolWidgetId = $(this).attr('id');
                const ps = new PerfectScrollbar('#' + scroolWidgetId);
                ps.update();
            });
        }
    });
</script>
@push('scripts')