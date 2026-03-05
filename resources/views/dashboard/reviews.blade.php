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
        <li><h1>Reviews</h1></li>
        <li class="breadcrumb-item">
            <a href="/dashboard">
                <svg width="20" height="20" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z" stroke="var(--bs-body-color)" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="var(--bs-body-color)" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Home
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Reviews</li>
    </ol>
</nav>
				</div>
				<!-- End - Page Title & Breadcrumb -->
				
                <div class="row">

					<!-- Start - Reviews Tab -->
					<div class="col-xl-12">
						<div class="card">
							<div class="card-body px-4 py-2">
								<div class="row align-items-center">
									<div class="col-sm-12 col-md-7">
										<ul class="nav nav-underline review-tab" id="nav-tab" role="tablist">
											<li class="nav-item" role="presentation">
												<button class="nav-link border-0 active" id="underline-home-tab" data-bs-toggle="tab" data-bs-target="#navpills-1" type="button" role="tab" aria-controls="underline-home" aria-selected="false" tabindex="-1">
													<span>All Reviews</span>
												</button>
											</li>
											<li class="nav-item" role="presentation">
												<button class="nav-link border-0" id="underline-profile-tab" data-bs-toggle="tab" data-bs-target="#navpills-2" type="button" role="tab" aria-controls="underline-profile" aria-selected="false" tabindex="-1">
													<span>Published</span>
												</button>
											</li>
											<li class="nav-item" role="presentation">
												<button class="nav-link border-0" id="underline-contact-tab" data-bs-toggle="tab" data-bs-target="#navpills-3" type="button" role="tab" aria-controls="underline-contact" aria-selected="true">
													<span>Deleted</span>
												</button>
											</li>
										</ul>
									</div>
									<div class="col-sm-12 col-md-5 text-md-end mt-md-0 mt-4">
										<a href="javascript:void(0);" class="btn btn-primary me-1 px-4 btn-sm">Publish</a>
										<a href="javascript:void(0);" class="btn btn-danger px-4 btn-sm">Delete</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- End - Reviews Tab -->

					<div class="col-xl-12">
						<div class="tab-content">

							<!-- Start - Table 1 -->
							<div id="navpills-1" class="tab-pane fade show active" aria-labelledby="navpills-1">
								<div class="table-responsive rounded table-hover fs-14">
									<table class="table table-lg table-borderless mb-4 dataTablesCard card-table p-0 review-table fs-14" id="example6">
										<thead>
											<tr>
												<th class="sorting-disabled">
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="checkAll">
													  <label class="form-check-label" for="checkAll">
													  </label>
													</div>
												</th>
												<th>Customer</th>
												<th class="mw-150">Event Name</th>
												<th class="mw-130">Stars Review</th>
												<th class="text-center">Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1">
													  <label class="form-check-label" for="flexCheckDefault1">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/1.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Cindy Hawkins</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">I've used Ventic for almost ten years. From small general admission church shows to complete turn key ticketing services at Jakarta. I use them for marketing and ticketing on every show. No questions.</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">
													  <label class="form-check-label" for="flexCheckDefault3">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/2.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">John Doe</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Ventic is one of the best vendors we've ever worked with. Thanks for your wonderful, helpful service across the board. It is greatly appreciated!</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded  btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault4">
													  <label class="form-check-label" for="flexCheckDefault4">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/3.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Margaretha Thomp</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Thank you so much. I love the contact information for marketing purposes especially in case we do this event again in the future. This was my first event running ticketing for STAR, and Ventic was amazing to work with. So helpful, fast to answer any questions, and super easy!</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault5">
													  <label class="form-check-label" for="flexCheckDefault5">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/4.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Louis Jovanny</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">I've used Ventic for almost ten years. From small general admission church shows to complete turn key ticketing services at Jakarta. I use them for marketing and ticketing on every show. No questions.</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault6">
													  <label class="form-check-label" for="flexCheckDefault6">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/5.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1 ">Cindy Hawkins</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">iTickets has been great from starting up our account to setting up the event. They are always there for questions and have the answers to those questions.	</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded  btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault7">
													  <label class="form-check-label" for="flexCheckDefault7">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/6.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1 ">Glee Smiley</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Ventic is one of the best vendors we've ever worked with. Thanks for your wonderful,</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault11">
													  <label class="form-check-label" for="flexCheckDefault11">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/1.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Cindy Hawkins</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">I've used Ventic for almost ten years. From small general admission church shows to complete turn key ticketing services at Jakarta. I use them for marketing and ticketing on every show. No questions.</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault31">
													  <label class="form-check-label" for="flexCheckDefault31">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/2.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">John Doe</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Ventic is one of the best vendors we've ever worked with. Thanks for your wonderful, helpful service across the board. It is greatly appreciated!</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded  btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault41">
													  <label class="form-check-label" for="flexCheckDefault41">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/3.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Margaretha Thomp</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Thank you so much. I love the contact information for marketing purposes especially in case we do this event again in the future. This was my first event running ticketing for STAR, and Ventic was amazing to work with. So helpful, fast to answer any questions, and super easy!</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault51">
													  <label class="form-check-label" for="flexCheckDefault51">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/4.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Louis Jovanny</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">I've used Ventic for almost ten years. From small general admission church shows to complete turn key ticketing services at Jakarta. I use them for marketing and ticketing on every show. No questions.</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault61">
													  <label class="form-check-label" for="flexCheckDefault61">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/5.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1 ">Cindy Hawkins</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">iTickets has been great from starting up our account to setting up the event. They are always there for questions and have the answers to those questions.	</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded  btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault71">
													  <label class="form-check-label" for="flexCheckDefault71">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/6.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Glee Smiley</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Ventic is one of the best vendors we've ever worked with. Thanks for your wonderful,</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<!-- End - Table 1 -->

							<!-- Start - Table 2 -->
							<div id="navpills-2" class="tab-pane fade" aria-labelledby="navpills-2">
								<div class="table-responsive rounded table-hover fs-14">
									<table class="table mb-4 dataTablesCard card-table p-0 review-table fs-14" id="example6">
										<thead>
											<tr>
												<th class="sorting-disabled width20">
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="checkAll">
													  <label class="form-check-label" for="checkAll">
													  </label>
													</div>
												</th>
												<th>Customer</th>
												<th class="mw-150">Event Name</th>
												<th class="mw-130">Stars Review</th>
												<th class="text-center">Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1">
													  <label class="form-check-label" for="flexCheckDefault1">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/1.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Cindy Hawkins</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">I've used Ventic for almost ten years. From small general admission church shows to complete turn key ticketing services at Jakarta. I use them for marketing and ticketing on every show. No questions.</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">
													  <label class="form-check-label" for="flexCheckDefault3">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/2.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">John Doe</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Ventic is one of the best vendors we've ever worked with. Thanks for your wonderful, helpful service across the board. It is greatly appreciated!</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded  btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault4">
													  <label class="form-check-label" for="flexCheckDefault4">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/3.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Margaretha Thomp</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Thank you so much. I love the contact information for marketing purposes especially in case we do this event again in the future. This was my first event running ticketing for STAR, and Ventic was amazing to work with. So helpful, fast to answer any questions, and super easy!</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault5">
													  <label class="form-check-label" for="flexCheckDefault5">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/4.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Louis Jovanny</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">I've used Ventic for almost ten years. From small general admission church shows to complete turn key ticketing services at Jakarta. I use them for marketing and ticketing on every show. No questions.</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault6">
													  <label class="form-check-label" for="flexCheckDefault6">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/5.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1 ">Cindy Hawkins</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">iTickets has been great from starting up our account to setting up the event. They are always there for questions and have the answers to those questions.	</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded  btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault7">
													  <label class="form-check-label" for="flexCheckDefault7">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/6.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1 ">Glee Smiley</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Ventic is one of the best vendors we've ever worked with. Thanks for your wonderful,</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault11">
													  <label class="form-check-label" for="flexCheckDefault11">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/1.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Cindy Hawkins</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">I've used Ventic for almost ten years. From small general admission church shows to complete turn key ticketing services at Jakarta. I use them for marketing and ticketing on every show. No questions.</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault31">
													  <label class="form-check-label" for="flexCheckDefault31">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/2.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">John Doe</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Ventic is one of the best vendors we've ever worked with. Thanks for your wonderful, helpful service across the board. It is greatly appreciated!</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded  btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault41">
													  <label class="form-check-label" for="flexCheckDefault41">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/3.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Margaretha Thomp</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Thank you so much. I love the contact information for marketing purposes especially in case we do this event again in the future. This was my first event running ticketing for STAR, and Ventic was amazing to work with. So helpful, fast to answer any questions, and super easy!</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault51">
													  <label class="form-check-label" for="flexCheckDefault51">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/4.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Louis Jovanny</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">I've used Ventic for almost ten years. From small general admission church shows to complete turn key ticketing services at Jakarta. I use them for marketing and ticketing on every show. No questions.</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault61">
													  <label class="form-check-label" for="flexCheckDefault61">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/5.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1 ">Cindy Hawkins</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">iTickets has been great from starting up our account to setting up the event. They are always there for questions and have the answers to those questions.	</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded  btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault71">
													  <label class="form-check-label" for="flexCheckDefault71">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/6.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Glee Smiley</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Ventic is one of the best vendors we've ever worked with. Thanks for your wonderful,</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<!-- End - Table 2 -->

							<!-- Start - Table 3 -->
							<div id="navpills-3" class="tab-pane fade" aria-labelledby="navpills-3">
								<div class="table-responsive rounded table-hover fs-14">
									<table class="table mb-4 dataTablesCard card-table p-0 review-table fs-14" id="example6">
										<thead>
											<tr>
												<th class="sorting-disabled width20">
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="checkAll">
													  <label class="form-check-label" for="checkAll">
													  </label>
													</div>
												</th>
												<th>Customer</th>
												<th class="mw-150">Event Name</th>
												<th class="mw-130">Stars Review</th>
												<th class="text-center">Action</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault1">
													  <label class="form-check-label" for="flexCheckDefault1">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/1.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Cindy Hawkins</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">I've used Ventic for almost ten years. From small general admission church shows to complete turn key ticketing services at Jakarta. I use them for marketing and ticketing on every show. No questions.</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault3">
													  <label class="form-check-label" for="flexCheckDefault3">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/2.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">John Doe</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Ventic is one of the best vendors we've ever worked with. Thanks for your wonderful, helpful service across the board. It is greatly appreciated!</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded  btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault4">
													  <label class="form-check-label" for="flexCheckDefault4">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/3.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Margaretha Thomp</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Thank you so much. I love the contact information for marketing purposes especially in case we do this event again in the future. This was my first event running ticketing for STAR, and Ventic was amazing to work with. So helpful, fast to answer any questions, and super easy!</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault5">
													  <label class="form-check-label" for="flexCheckDefault5">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/4.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Louis Jovanny</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">I've used Ventic for almost ten years. From small general admission church shows to complete turn key ticketing services at Jakarta. I use them for marketing and ticketing on every show. No questions.</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault6">
													  <label class="form-check-label" for="flexCheckDefault6">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/5.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1 ">Cindy Hawkins</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">iTickets has been great from starting up our account to setting up the event. They are always there for questions and have the answers to those questions.	</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded  btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault7">
													  <label class="form-check-label" for="flexCheckDefault7">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/6.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1 ">Glee Smiley</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Ventic is one of the best vendors we've ever worked with. Thanks for your wonderful,</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault11">
													  <label class="form-check-label" for="flexCheckDefault11">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/1.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Cindy Hawkins</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">I've used Ventic for almost ten years. From small general admission church shows to complete turn key ticketing services at Jakarta. I use them for marketing and ticketing on every show. No questions.</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault31">
													  <label class="form-check-label" for="flexCheckDefault31">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/2.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">John Doe</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Ventic is one of the best vendors we've ever worked with. Thanks for your wonderful, helpful service across the board. It is greatly appreciated!</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded  btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault41">
													  <label class="form-check-label" for="flexCheckDefault41">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/3.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Margaretha Thomp</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Thank you so much. I love the contact information for marketing purposes especially in case we do this event again in the future. This was my first event running ticketing for STAR, and Ventic was amazing to work with. So helpful, fast to answer any questions, and super easy!</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault51">
													  <label class="form-check-label" for="flexCheckDefault51">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/4.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Louis Jovanny</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">I've used Ventic for almost ten years. From small general admission church shows to complete turn key ticketing services at Jakarta. I use them for marketing and ticketing on every show. No questions.</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault61">
													  <label class="form-check-label" for="flexCheckDefault61">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/5.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1 ">Cindy Hawkins</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">iTickets has been great from starting up our account to setting up the event. They are always there for questions and have the answers to those questions.	</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded  btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
											<tr>
												<td>
													<div class="form-check checkbox-primary">
													  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault71">
													  <label class="form-check-label" for="flexCheckDefault71">
													  </label>
													</div>
												</td>
												<td>
													<div class="d-flex align-items-center tbl-img">
														<img class="img-fluid rounded me-3 d-none d-xl-inline-block" width="70" src="assets/images/avatar/6.webp" alt="DexignZone">
														<div>
															<h4 class="mb-1">Glee Smiley</h4>
															<span>Sunday, 24 July 2020 04:55 PM</span>
														</div>
													</div>
												</td>
												<td>
													<p>The Story of Danau Toba (Musical Drama)</p>
												</td>
												<td>
													<span class="d-inline-block mb-2 fs-16">
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-orange"></i>
														<i class="fa fa-star fs-16 text-gray"></i>
													</span>
													<p class="mb-0 d-none d-xl-inline-block">Ventic is one of the best vendors we've ever worked with. Thanks for your wonderful,</p>
												</td>
												<td>
													<div class="d-flex">
														<a href="javascript:void(0);" class="btn btn-primary btn-rounded text-white btn-sm px-4">Publish</a>
														<a href="javascript:void(0);" class="btn btn-outline-danger btn-rounded btn-sm ms-2 px-4">Delete</a>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
							<!-- End - Table 3 -->

						</div>
					</div>
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