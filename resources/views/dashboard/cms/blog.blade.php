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
							<h6 class="mt-0 mb-3 fs-14"><a class="text-black" href="http://../events.html">Live Concert Choir Charity Event 2020</a></h6>
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
							<h6 class="mt-0 mb-3 fs-14"><a class="text-black" href="http://../events.html">Beautiful Fireworks Show In The New Year Night</a></h6>
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
							<h6 class="mt-0 mb-3 fs-14"><a class="text-black" href="http://../events.html">The Story Of Danau Toba (Musical Drama)</a></h6>
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
					<a href="http://../events.html" class="text-primary">View more <i class="las la-long-arrow-alt-right scale5 ms-2"></i></a>
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
        <li><h1>Blog</h1></li>
        <li class="breadcrumb-item">
            <a href="http://../index.html">
                <svg width="20" height="20" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z" stroke="var(--bs-body-color)" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="var(--bs-body-color)" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Home
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Blog</li>
    </ol>
</nav>
				</div>
				<!-- end - Page Title & Breadcrumb -->
			
				<div class="row">
					<div class="col-xl-12">

						<!-- Start - Filtering -->
						<div class="card card-collapse">
							<div class="card-header">
								<h4 class="card-title"><i class="fa-sharp fa-solid fa-filter me-1 text-primary"></i> Filter</h4>
								<a class="collapse-indicator" data-bs-toggle="collapse" href="#collapseFilter" role="button" aria-expanded="false" aria-controls="collapseFilter">
									<i class="fa fa-angle-down"></i>
								</a>
							</div>
							<div class="collapsed collapse show" id="collapseFilter">
								<div class="card-body">
									<div class="row">
										<div class="col-xxl-3 col-lg-4 col-sm-6 mb-3 mb-xxl-0">
											<input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Title">
										</div>
										<div class="col-xxl-3 col-lg-4 col-sm-6 mb-3 mb-xxl-0">
											<select class="selectpicker form-select">
												<option selected>Select Status</option>
												<option value="1">Published</option>
												<option value="2">Draft</option>
												<option value="3">Trash</option>
												<option value="4">Private</option>
												<option value="5">Pending</option>
											</select> 
										</div>
										<div class="col-xxl-3 col-lg-4 col-sm-6 mb-3 mb-xxl-0">
											<input class="form-control bs-datepicker" type="text" value="03/13/2025">
										</div>
										<div class="col-xxl-3 col-lg-12 col-sm-6">
											<button class="btn btn-primary me-2" title="Click here to Search" type="button">
												<i class="fa-sharp fa-solid fa-filter"></i>Filter
											</button>
											<button class="btn btn-danger light" title="Click here to remove filter" type="button">Remove Filter</button>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!-- End - Filtering -->

						<!-- Start - Blog Button -->
						<div class="mb-4">
							<ul class="d-flex align-items-center flex-wrap">
								<li><a href="http://../cms/add-blog.html" class="btn btn-primary ">Add Blog</a></li>
								<li><a href="http://../cms/blog-category.html" class="btn btn-primary mx-1">Blog Category</a></li>
								<li><a href="http://../cms/blog-category.html" class="btn btn-primary mt-sm-0 mt-1">Add Blog Category</a></li>
							</ul>
						</div>
						<!-- End - Blog Button -->
						
						<!-- Start - Blog lists -->
						<div class="card card-collapse">
							<div class="card-header">
								<h4 class="card-title"><i class="fa-solid fa-file-lines me-1 text-primary"></i>Blogs lists</h4>
								<a class="collapse-indicator" data-bs-toggle="collapse" href="#collapseContactList" role="button" aria-expanded="false" aria-controls="collapseContactList">
									<i class="fa fa-angle-down"></i>
								</a>
							</div>
							<div class="collapsed collapse show" id="collapseContactList">
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-bordered table-striped">
											<thead>
												<tr>
													<th class="mw-80">S.No</th>
													<th class="mw-150">Title</th>
													<th class="mw-150">Status</th>
													<th class="mw-150">Modified</th>
													<th class="mw-150 text-end">Actions</th>
												</tr>
											</thead>
											<tbody class="text-nowrap">
												<tr>
													<td>1</td>
													<td>Title of first blog post entry</td>
													<td>Published</td>
													<td>09 Jan, 2024</td>
													<td class="text-end">
														<a href="javascript:void(0);" class="btn btn-square btn-warning btn-sm">
															<i class="fa-solid fa-pen-to-square"></i>
														</a>
														<a href="javascript:void(0);" class="btn btn-square btn-danger btn-sm">
															<i class="fa-solid fa-trash"></i>
														</a>
													</td>
												</tr>
												<tr>
													<td>2</td>
													<td>Why Go For A VFX Course?</td>
													<td>Published</td>
													<td>13 May, 20224</td>
													<td class="text-end">
														<a href="javascript:void(0);" class="btn btn-square btn-warning btn-sm">
															<i class="fa-solid fa-pen-to-square"></i>
														</a>
														<a href="javascript:void(0);" class="btn btn-square btn-danger btn-sm">
															<i class="fa-solid fa-trash"></i>
														</a>
													</td>
												</tr>
												<tr>
													<td>3</td>
													<td>Reasons To Choose Animation Courses</td>
													<td>Published</td>
													<td>13 Apr, 2024</td>
													<td class="text-end">
														<a href="javascript:void(0);" class="btn btn-square btn-warning btn-sm">
															<i class="fa-solid fa-pen-to-square"></i>
														</a>
														<a href="javascript:void(0);" class="btn btn-square btn-danger btn-sm">
															<i class="fa-solid fa-trash"></i>
														</a>
													</td>
												</tr>
												<tr>
													<td>4</td>
													<td>Blue Screen Vs. Green Screen For VFX</td>
													<td>Published</td>
													<td>13 June, 2024</td>
													<td class="text-end">
														<a href="javascript:void(0);" class="btn btn-square btn-warning btn-sm">
															<i class="fa-solid fa-pen-to-square"></i>
														</a>
														<a href="javascript:void(0);" class="btn btn-square btn-danger btn-sm">
															<i class="fa-solid fa-trash"></i>
														</a>
													</td>
												</tr>
												<tr>
													<td>5</td>
													<td>All About Animation</td>
													<td>Published</td>
													<td>13 Apr, 2024</td>
													<td class="text-end">
														<a href="javascript:void(0);" class="btn btn-square btn-warning btn-sm">
															<i class="fa-solid fa-pen-to-square"></i>
														</a>
														<a href="javascript:void(0);" class="btn btn-square btn-danger btn-sm">
															<i class="fa-solid fa-trash"></i>
														</a>
													</td>
												</tr>
												
											</tbody>
										</table>
									</div>
									<div class="d-flex align-items-center gap-3 justify-content-xl-between flex-wrap justify-content-start justify-content-xl-center">
										<small>Page 1 of 5, showing 2 records out of 8 total, starting on record 1, ending on 2</small>
										<nav aria-label="Page navigation">
											<ul class="pagination mb-2 mb-sm-0">
												<li class="page-item"><a class="page-link" href="javascript:void(0);"><i class="fa-solid fa-angle-left"></i></a></li>
												<li class="page-item"><a class="page-link" href="javascript:void(0);">1</a></li>
												<li class="page-item"><a class="page-link" href="javascript:void(0);">2</a></li>
												<li class="page-item"><a class="page-link" href="javascript:void(0);">3</a></li>
												<li class="page-item"><a class="page-link " href="javascript:void(0);"><i class="fa-solid fa-angle-right"></i></a></li>
											</ul>
										</nav>
									</div>
								</div>
							</div>
						</div>
						<!-- End - Blog lists -->
						
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

        <!-- Start - Footer -->
<div class="footer">
    <div class="copyright">
        <p>Copyright © Designed &amp; Developed by <a href="http://dexignzone.com/" target="_blank">DexignZone</a> <span class="current-year">2023</span></p>
    </div>
</div>
<!-- End - Footer -->

    </div>
    <!-- End - Main Wrapper -->

    <!-- Start - Scripts -->
    <script src="../..//assets/vendor/jquery/dist/jquery.min.js"></script>
	<script src="../..//assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
	<script src="../..//assets/vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
	<script src="../..//assets/vendor/metismenu/dist/metisMenu.min.js"></script>
	<script src="../..//assets/vendor/%40yaireo/tagify/dist/tagify.js"></script>
    <script src="../..//assets/vendor/chart-js/chart.bundle.min.js"></script>

	<script src="../..//assets/vendor/bootstrap-datetimepicker/js/moment.js"></script>
	<script src="../..//assets/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
	
	<!-- Script For Bootstrap Datepicker -->
	<script src="../..//assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
	
	<!-- Script For Multiple Languages -->
	<script src="../..//assets/vendor/i18n/i18n.js"></script>
	<script src="../..//assets/js/translator.js"></script>
	
	<!-- Script For Custom JS -->
	<script src="../..//assets/js/deznav-init.js"></script>
    <script src="../..//assets/js/custom.js"></script>
	
	<!-- Script For demo Styleswitcher -->
	<script src="../..//assets/js/demo.js"></script>
    <script src="../..//assets/js/styleSwitcher.js"></script>
	
</body>

<!-- Mirrored from tixia-html.vercel.app/cms/blog.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 05 Feb 2026 03:00:11 GMT -->
</html>