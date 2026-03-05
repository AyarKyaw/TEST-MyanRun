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
        <li><h1>Analytics</h1></li>
        <li class="breadcrumb-item">
            <a href="index.html">
                <svg width="20" height="20" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2.125 6.375L8.5 1.41667L14.875 6.375V14.1667C14.875 14.5424 14.7257 14.9027 14.4601 15.1684C14.1944 15.4341 13.8341 15.5833 13.4583 15.5833H3.54167C3.16594 15.5833 2.80561 15.4341 2.53993 15.1684C2.27426 14.9027 2.125 14.5424 2.125 14.1667V6.375Z" stroke="var(--bs-body-color)" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6.375 15.5833V8.5H10.625V15.5833" stroke="var(--bs-body-color)" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Home
            </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Analytics</li>
    </ol>
</nav>
				</div>
				<!-- End - Page Title & Breadcrumb -->
				
				<div class="row">
					<div class="col-xl-6 col-xxl-6 col-lg-12 col-md-12 col-sm-12">
						<div class="row">

							<!-- Start - Trending Items -->
							<div class="col-xl-12">
								<div class="card">
									<div class="card-header border-0 pb-0 d-sm-flex d-block">
										<h4 class="card-title mb-1">Trending Items</h4>
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
									<div class="card-body pt-0 p-0">
										<div class="align-items-center d-flex mx-0 border-bottom p-4">
											<span class="col-2 col-sm-1 px-0 align-self-center fs-16 text-black d-sm-inline-block">#1</span>
											<div class="outline border rounded-circle p-3 me-3 d-none d-sm-inline-block">
												<svg class="primary-icon" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M11.9042 5.18413L0.556031 16.5323C0.281453 16.8068 0.281453 17.2521 0.556031 17.5266L3.24911 20.2197C3.44481 20.4154 3.73705 20.4781 3.99582 20.3799C5.0289 19.9878 6.20067 20.2386 6.98098 21.0189C7.76129 21.7992 8.01214 22.971 7.62003 24.0041C7.52178 24.2628 7.5845 24.5551 7.78019 24.7508L10.4733 27.4439C10.7479 27.7185 11.1931 27.7185 11.4677 27.4439L22.8158 16.0958L11.9042 5.18413Z" fill="var(--bs-primary)"></path>
												<path d="M27.4439 10.4734L24.7508 7.78025C24.5551 7.58456 24.2628 7.52185 24.0041 7.62009C22.971 8.0122 21.7993 7.76132 21.019 6.98101C20.2386 6.2007 19.9878 5.02893 20.3799 3.99585C20.4781 3.73711 20.4154 3.44484 20.2197 3.24914L17.5266 0.556062C17.252 0.281484 16.8068 0.281484 16.5322 0.556062L12.8985 4.18975L23.8102 15.1014L27.4439 11.4677C27.7185 11.1932 27.7185 10.7479 27.4439 10.4734Z" fill="var(--bs-primary)"></path>
												</svg>
											</div>
											<div class="col-sm-6 col-6 col-xxl-5 my-3 my-sm-0 px-0">
												<h5 class="my-0"><a class="text-black" href="javascript:void(0);">Envato International Meetup 2020</a></h5>
											</div>
											<div class="ms-auto col-2 col-sm-2 px-0 d-flex align-self-center align-items-center">
												<div class="text-center">
													<h4 class="mb-0 d-block text-black">454</h4>
													<span class="fs-14">Sales</span>
												</div>
											</div>
											<div class="me-3">
												<span class="peity-success" data-style="width:100%;" style="display: none;">0,2,1,4</span>
												<svg  class="primary-icon" width="26" height="27" viewBox="0 0 26 27" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect width="3.71426" height="27" rx="1.85713" transform="matrix(-1 0 0 1 26 0)" fill="var(--bs-primary)"></rect>
													<rect width="3.71426" height="19.6364" rx="1.85713" transform="matrix(-1 0 0 1 18.5723 7.36365)" fill="var(--bs-primary)"></rect>
													<rect width="3.71426" height="8.59091" rx="1.85713" transform="matrix(-1 0 0 1 11.1436 18.4091)" fill="var(--bs-primary)"></rect>
													<rect width="4.19045" height="16.6154" rx="2.09522" transform="matrix(-1 0 0 1 4.19043 10.3846)" fill="var(--bs-primary)"></rect>
												</svg>
											</div>
											<svg width="22" height="11" class="col-sm-1 col-2" viewBox="0 0 22 11" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M0 11L11 -4.72849e-07L22 11" fill="var(--bs-primary)"></path>
											</svg>
										</div>
										<div class="align-items-center d-flex mx-0 border-bottom p-4">
											<span class="col-2 col-sm-1 px-0 align-self-center fs-16 text-black d-sm-inline-block">#2</span>
											<div class="outline border rounded-circle p-3 me-3 d-none d-sm-inline-block">
												<svg class="primary-icon" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M11.9042 5.18413L0.556031 16.5323C0.281453 16.8068 0.281453 17.2521 0.556031 17.5266L3.24911 20.2197C3.44481 20.4154 3.73705 20.4781 3.99582 20.3799C5.0289 19.9878 6.20067 20.2386 6.98098 21.0189C7.76129 21.7992 8.01214 22.971 7.62003 24.0041C7.52178 24.2628 7.5845 24.5551 7.78019 24.7508L10.4733 27.4439C10.7479 27.7185 11.1931 27.7185 11.4677 27.4439L22.8158 16.0958L11.9042 5.18413Z" fill="var(--bs-primary)"></path>
												<path d="M27.4439 10.4734L24.7508 7.78025C24.5551 7.58456 24.2628 7.52185 24.0041 7.62009C22.971 8.0122 21.7993 7.76132 21.019 6.98101C20.2386 6.2007 19.9878 5.02893 20.3799 3.99585C20.4781 3.73711 20.4154 3.44484 20.2197 3.24914L17.5266 0.556062C17.252 0.281484 16.8068 0.281484 16.5322 0.556062L12.8985 4.18975L23.8102 15.1014L27.4439 11.4677C27.7185 11.1932 27.7185 10.7479 27.4439 10.4734Z" fill="var(--bs-primary)"></path>
												</svg>
											</div>
											<div class="col-sm-6 col-6 col-xxl-5 my-3 my-sm-0 px-0">
												<h5 class="mt-0 mb-0"><a class="text-black" href="javascript:void(0);">Jakarta Indie Music Festival 2020</a></h5>
											</div>
											<div class="ms-auto col-2 col-sm-2 px-0 d-flex align-self-center align-items-center">
												<div class="text-center">
													<h4 class="mb-0 d-block text-black">124</h4>
													<span class="fs-14">Sales</span>
												</div>
											</div>
											<div class="me-3">
												<span class="peity-success" data-style="width:100%;" style="display: none;">0,2,1,4</span>
												<svg  class="primary-icon" width="26" height="27" viewBox="0 0 26 27" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect width="3.71426" height="27" rx="1.85713" transform="matrix(-1 0 0 1 26 0)" fill="var(--bs-primary)"></rect>
													<rect width="3.71426" height="19.6364" rx="1.85713" transform="matrix(-1 0 0 1 18.5723 7.36365)" fill="var(--bs-primary)"></rect>
													<rect width="3.71426" height="8.59091" rx="1.85713" transform="matrix(-1 0 0 1 11.1436 18.4091)" fill="var(--bs-primary)"></rect>
													<rect width="4.19045" height="16.6154" rx="2.09522" transform="matrix(-1 0 0 1 4.19043 10.3846)" fill="var(--bs-primary)"></rect>
												</svg>
											</div>
											<svg width="22" height="11" class="col-sm-1 col-2" viewBox="0 0 22 11" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M0 11L11 -4.72849e-07L22 11" fill="var(--bs-primary)"></path>
											</svg>
										</div>
										<div class="align-items-center d-flex mx-0 border-bottom p-4">
											<span class="col-2 col-sm-1 px-0 align-self-center fs-16 text-black d-sm-inline-block">#3</span>
											<div class="outline border rounded-circle p-3 me-3 d-none d-sm-inline-block">
												<svg class="primary-icon" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M11.9042 5.18413L0.556031 16.5323C0.281453 16.8068 0.281453 17.2521 0.556031 17.5266L3.24911 20.2197C3.44481 20.4154 3.73705 20.4781 3.99582 20.3799C5.0289 19.9878 6.20067 20.2386 6.98098 21.0189C7.76129 21.7992 8.01214 22.971 7.62003 24.0041C7.52178 24.2628 7.5845 24.5551 7.78019 24.7508L10.4733 27.4439C10.7479 27.7185 11.1931 27.7185 11.4677 27.4439L22.8158 16.0958L11.9042 5.18413Z" fill="var(--bs-primary)"></path>
												<path d="M27.4439 10.4734L24.7508 7.78025C24.5551 7.58456 24.2628 7.52185 24.0041 7.62009C22.971 8.0122 21.7993 7.76132 21.019 6.98101C20.2386 6.2007 19.9878 5.02893 20.3799 3.99585C20.4781 3.73711 20.4154 3.44484 20.2197 3.24914L17.5266 0.556062C17.252 0.281484 16.8068 0.281484 16.5322 0.556062L12.8985 4.18975L23.8102 15.1014L27.4439 11.4677C27.7185 11.1932 27.7185 10.7479 27.4439 10.4734Z" fill="var(--bs-primary)"></path>
												</svg>
											</div>
											<div class="col-sm-6 col-6 col-xxl-5 my-3 my-sm-0 px-0">
												<h5 class="mt-0 mb-0"><a class="text-black" href="javascript:void(0);">Live Choir in Sydney 2020</a></h5>
											</div>
											<div class="ms-auto col-2 col-sm-2 px-0 d-flex align-self-center align-items-center">
												<div class="text-center">
													<h4 class="mb-0 d-block text-black">250</h4>
													<span class="fs-14">Sales</span>
												</div>
											</div>
											<div class="me-3">
												<span class="peity-success" data-style="width:100%;" style="display: none;">0,2,1,4</span>
												<svg  class="primary-icon" width="26" height="27" viewBox="0 0 26 27" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect width="3.71426" height="27" rx="1.85713" transform="matrix(-1 0 0 1 26 0)" fill="var(--bs-primary)"></rect>
													<rect width="3.71426" height="19.6364" rx="1.85713" transform="matrix(-1 0 0 1 18.5723 7.36365)" fill="var(--bs-primary)"></rect>
													<rect width="3.71426" height="8.59091" rx="1.85713" transform="matrix(-1 0 0 1 11.1436 18.4091)" fill="var(--bs-primary)"></rect>
													<rect width="4.19045" height="16.6154" rx="2.09522" transform="matrix(-1 0 0 1 4.19043 10.3846)" fill="var(--bs-primary)"></rect>
												</svg>
											</div>
											<svg width="22" height="11" class="col-sm-1 col-2" viewBox="0 0 22 11" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M0 11L11 -4.72849e-07L22 11" fill="var(--bs-primary)"></path>
											</svg>
										</div>
										<div class="align-items-center d-flex mx-0 border-bottom p-4">
											<span class="col-2 col-sm-1 px-0 align-self-center fs-16 text-black d-sm-inline-block">#4</span>
											<div class="outline border rounded-circle p-3 me-3 d-none d-sm-inline-block">
												<svg class="primary-icon" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M11.9042 5.18413L0.556031 16.5323C0.281453 16.8068 0.281453 17.2521 0.556031 17.5266L3.24911 20.2197C3.44481 20.4154 3.73705 20.4781 3.99582 20.3799C5.0289 19.9878 6.20067 20.2386 6.98098 21.0189C7.76129 21.7992 8.01214 22.971 7.62003 24.0041C7.52178 24.2628 7.5845 24.5551 7.78019 24.7508L10.4733 27.4439C10.7479 27.7185 11.1931 27.7185 11.4677 27.4439L22.8158 16.0958L11.9042 5.18413Z" fill="var(--bs-primary)"></path>
												<path d="M27.4439 10.4734L24.7508 7.78025C24.5551 7.58456 24.2628 7.52185 24.0041 7.62009C22.971 8.0122 21.7993 7.76132 21.019 6.98101C20.2386 6.2007 19.9878 5.02893 20.3799 3.99585C20.4781 3.73711 20.4154 3.44484 20.2197 3.24914L17.5266 0.556062C17.252 0.281484 16.8068 0.281484 16.5322 0.556062L12.8985 4.18975L23.8102 15.1014L27.4439 11.4677C27.7185 11.1932 27.7185 10.7479 27.4439 10.4734Z" fill="var(--bs-primary)"></path>
												</svg>
											</div>
											<div class="col-sm-6 col-6 col-xxl-5 my-3 my-sm-0 px-0">
												<h5 class="mt-0 mb-0"><a class="text-black" href="javascript:void(0);">Artist Performing Festival In Aus..</a></h5>
											</div>
											<div class="ms-auto col-2 col-sm-2 px-0 d-flex align-self-center align-items-center">
												<div class="text-center">
													<h4 class="mb-0 d-block text-black">500</h4>
													<span class="fs-14">Sales</span>
												</div>
											</div>
											<div class="me-3">
												<span class="peity-success" data-style="width:100%;" style="display: none;">0,2,1,4</span>
												<svg  class="primary-icon" width="26" height="27" viewBox="0 0 26 27" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect width="3.71426" height="27" rx="1.85713" transform="matrix(-1 0 0 1 26 0)" fill="var(--bs-primary)"></rect>
													<rect width="3.71426" height="19.6364" rx="1.85713" transform="matrix(-1 0 0 1 18.5723 7.36365)" fill="var(--bs-primary)"></rect>
													<rect width="3.71426" height="8.59091" rx="1.85713" transform="matrix(-1 0 0 1 11.1436 18.4091)" fill="var(--bs-primary)"></rect>
													<rect width="4.19045" height="16.6154" rx="2.09522" transform="matrix(-1 0 0 1 4.19043 10.3846)" fill="var(--bs-primary)"></rect>
												</svg>
											</div>
											<svg width="22" height="11" class="col-sm-1 col-2" viewBox="0 0 22 11" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M0 11L11 -4.72849e-07L22 11" fill="var(--bs-primary)"></path>
											</svg>
										</div>
										<div class="align-items-center d-flex mx-0 p-4">
											<span class="col-2 col-sm-1 px-0 align-self-center fs-16 text-black d-sm-inline-block">#5</span>
											<div class="outline border rounded-circle p-3 me-3 d-none d-sm-inline-block">
												<svg class="primary-icon" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M11.9042 5.18413L0.556031 16.5323C0.281453 16.8068 0.281453 17.2521 0.556031 17.5266L3.24911 20.2197C3.44481 20.4154 3.73705 20.4781 3.99582 20.3799C5.0289 19.9878 6.20067 20.2386 6.98098 21.0189C7.76129 21.7992 8.01214 22.971 7.62003 24.0041C7.52178 24.2628 7.5845 24.5551 7.78019 24.7508L10.4733 27.4439C10.7479 27.7185 11.1931 27.7185 11.4677 27.4439L22.8158 16.0958L11.9042 5.18413Z" fill="var(--bs-primary)"></path>
												<path d="M27.4439 10.4734L24.7508 7.78025C24.5551 7.58456 24.2628 7.52185 24.0041 7.62009C22.971 8.0122 21.7993 7.76132 21.019 6.98101C20.2386 6.2007 19.9878 5.02893 20.3799 3.99585C20.4781 3.73711 20.4154 3.44484 20.2197 3.24914L17.5266 0.556062C17.252 0.281484 16.8068 0.281484 16.5322 0.556062L12.8985 4.18975L23.8102 15.1014L27.4439 11.4677C27.7185 11.1932 27.7185 10.7479 27.4439 10.4734Z" fill="var(--bs-primary)"></path>
												</svg>
											</div>
											<div class="col-sm-6 col-6 col-xxl-5 my-3 my-sm-0 px-0">
												<h5 class="mt-0 mb-0"><a class="text-black" href="javascript:void(0);">[LIVE] Football Charity Event 2020</a></h5>
											</div>
											<div class="ms-auto col-2 col-sm-2 px-0 d-flex align-self-center align-items-center">
												<div class="text-center">
													<h4 class="mb-0 d-block text-black">426</h4>
													<span class="fs-14">Sales</span>
												</div>
											</div>
											<div class="me-3">
												<span class="peity-success" data-style="width:100%;" style="display: none;">0,2,1,4</span>
												<svg  class="primary-icon" width="26" height="27" viewBox="0 0 26 27" fill="none" xmlns="http://www.w3.org/2000/svg">
													<rect width="3.71426" height="27" rx="1.85713" transform="matrix(-1 0 0 1 26 0)" fill="var(--bs-primary)"></rect>
													<rect width="3.71426" height="19.6364" rx="1.85713" transform="matrix(-1 0 0 1 18.5723 7.36365)" fill="var(--bs-primary)"></rect>
													<rect width="3.71426" height="8.59091" rx="1.85713" transform="matrix(-1 0 0 1 11.1436 18.4091)" fill="var(--bs-primary)"></rect>
													<rect width="4.19045" height="16.6154" rx="2.09522" transform="matrix(-1 0 0 1 4.19043 10.3846)" fill="var(--bs-primary)"></rect>
												</svg>
											</div>
											<svg width="22" height="11" class="col-sm-1 col-2" viewBox="0 0 22 11" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M0 11L11 -4.72849e-07L22 11" fill="var(--bs-primary)"></path>
											</svg>
										</div>
										
									</div>
								</div>
							</div>
							<!-- End - Trending Items -->

							<!-- Start - Sales Comparison -->
							<div class="col-xl-12">
								<div class="card bg-warning">
									<div class="card-header border-0 pb-0 d-sm-flex d-block align-items-start">
										<h2 class="mb-1 text-white fs-35">94%
											<svg width="22" height="11" viewBox="0 0 22 11" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M0 11L11 -4.72849e-07L22 11" fill="#fff"></path>
											</svg>
										</h2>
										<div class="dropdown ms-auto text-end">
											<h4 class="text-white">Sales Comparison</h4>
											<span class="text-white fw-light">Than last day</span>
										</div>
									</div>
									<div class="card-body p-2 height250">
										<canvas id="ticketSold"></canvas>
									</div>
								</div>
							</div>
							<!-- End - Sales Comparison -->

							<!-- Start - Revenue Chart -->
							<div class="col-xl-6 col-sm-6">
								<div class="card overflow-hidden">
									<div class="card-header border-0 pb-0">
										<div>
											<h3 class="mb-0">$124,136</h3>
											<p class="mb-1">Revenue</p>
										</div>
									</div>
									<div class="card-body p-0 height90">	
										<canvas id="revenueChart" height="85"></canvas>
									</div>
								</div>
							</div>
							<!-- End - Revenue Chart -->

							<!-- Start - Ticket Ordered Chart -->
							<div class="col-xl-6 col-sm-6">
								<div class="card overflow-hidden">
									<div class="card-header border-0 pb-0">
										<div>
											<h3 class="mb-0 text-black font-w600">639 Pcs</h3>
											<p class="mb-1">Ticket Ordered</p>
										</div>
									</div>
									<div class="card-body p-0">	
										<div class="col-7 px-0 offset-5 height90">	
											<canvas id="chart_widget_2" height="85"></canvas>
										</div>
									</div>
								</div>
							</div>
							<!-- End - Ticket Ordered Chart -->

						</div>
					</div>
					<div class="col-xl-3 col-xxl-3 col-lg-6 col-md-6 col-sm-12">
						<div class="row">

							<!-- Start - Revenue -->
							<div class="col-xl-12">
								<div class="card">
									<div class="card-header border-0 pb-0">
										<h4 class="card-title">Revenue</h4>
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
									<div class="card-body">
										 <canvas id="areaChart_2" height="350"></canvas>
									</div>
								</div>
							</div>
							<!-- End - Revenue -->

							<!-- Start - Ticket Sold Progressbar -->
							<div class="col-xl-12">
								<div class="card">
									<div class="card-header pb-0 border-0">
										<div>
											<h5 class="mb-0 text-black font-weight-bold">456k Pcs</h5>
											<p class="fs-14 mb-0">Ticket Sold</p>
										</div>
										<div class="clearfix">
											<select class="selectpicker form-select form-select-sm" data-width="auto">
												<option selected>Daily</option>
												<option value="1">Newest</option>
												<option value="2">Old</option>
											</select>
										</div>
									</div>
									<div class="card-body">
										<div class="progress mb-0" style="height:8px; width:100%;">
											<div class="progress-bar progress-animated" style="width:80%; height:8px;" role="progressbar">
												<span class="sr-only">80% Complete</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- End - Ticket Sold Progressbar -->

							<!-- Start - Ticket Sold Pie Chart -->
							<div class="col-xl-12">
								<div class="card">
									<div class="card-header pb-0 border-0">
										<div>
											<h5 class="mb-0 text-black font-weight-bold">Pie Chart</h5>
											<p class="fs-14 mb-0">Ticket Sold</p>
										</div>
										<div class="clearfix">
											<select class="selectpicker form-select form-select-sm" data-width="auto">
												<option selected>Daily</option>
												<option value="1">Newest</option>
												<option value="2">Old</option>
											</select>
										</div>
									</div>
									<div class="card-body pt-2">
										<div id="chartCircle"></div>
									</div>
								</div>
							</div>
							<!-- End - Ticket Sold Pie Chart -->

						</div>
					</div>
					<div class="col-xl-3 col-xxl-3 col-lg-6 col-md-6 col-sm-12">
						<div class="row">

							<!-- Start - Income -->
							<div class="col-xl-12">
								<div class="card">
									<div class="card-body">
										<div class="d-flex align-items-center">
											<span class="me-4">
												<svg  class="primary-icon" width="50" height="53" viewBox="0 0 50 53" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect width="7.11688" height="52.1905" rx="3" transform="matrix(-1 0 0 1 49.8184 0)" fill="var(--bs-primary)"></rect>
												<rect width="7.11688" height="37.9567" rx="3" transform="matrix(-1 0 0 1 35.585 14.2338)" fill="var(--bs-primary)"></rect>
												<rect width="7.11688" height="16.6061" rx="3" transform="matrix(-1 0 0 1 21.3516 35.5844)" fill="var(--bs-primary)"></rect>
												<rect width="8.0293" height="32.1172" rx="3" transform="matrix(-1 0 0 1 8.0293 20.0732)" fill="var(--bs-primary)"></rect>
												</svg>
											</span>
											<div class="ms-1">
												<p class="mb-1">Income</p>
												<h4 class="mb-0 text-black">$126,000</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- End - Income -->

							<!-- Start - Customer -->
							<div class="col-xl-12">
								<div class="card">
									<div class="card-body">
										<div class="d-flex align-items-center">
											<span class="me-4">
												<svg  class="primary-icon" width="51" height="31" viewBox="0 0 51 31" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path fill-rule="evenodd" clip-rule="evenodd" d="M49.3228 0.840214C50.7496 2.08096 50.9005 4.24349 49.6597 5.67035L34.6786 22.8987C32.284 25.6525 28.1505 26.0444 25.281 23.7898L18.1758 18.2072L5.77023 29.883C4.3933 31.1789 2.22651 31.1133 0.930578 29.7363C-0.365358 28.3594 -0.299697 26.1926 1.07723 24.8967L13.4828 13.2209C15.9494 10.8993 19.7428 10.7301 22.4063 12.8229L29.5115 18.4055L44.4926 1.1772C45.7334 -0.249661 47.8959 -0.400534 49.3228 0.840214Z" fill="var(--bs-primary)"></path>
												</svg>
											</span>
											<div class="ms-1">
												<p class="mb-1">Customer</p>
												<h4 class="mb-0">109,511</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- End - Customer -->

							<!-- Start - Last Year Sale -->
							<div class="col-xl-12">
								<div class="card">
									<div class="card-body">
										<div class="d-flex align-items-center">
											<span class="me-4">
												<svg  class="primary-icon" width="51" height="52" viewBox="0 0 51 52" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path fill-rule="evenodd" clip-rule="evenodd" d="M25.5 43C34.8888 43 42.5 35.3888 42.5 26C42.5 16.6112 34.8888 9 25.5 9C16.1112 9 8.5 16.6112 8.5 26C8.5 35.3888 16.1112 43 25.5 43ZM25.5 51.5C39.5833 51.5 51 40.0833 51 26C51 11.9167 39.5833 0.5 25.5 0.5C11.4167 0.5 0 11.9167 0 26C0 40.0833 11.4167 51.5 25.5 51.5Z" fill="white" fill-opacity="0.18"></path>
													<path fill-rule="evenodd" clip-rule="evenodd" d="M33.9997 1.95836C31.2809 0.997147 28.4073 0.5 25.4997 0.5V8.86605C28.5687 8.86605 31.5815 9.6904 34.223 11.253C36.8645 12.8155 39.0379 15.0589 40.5159 17.7486C41.9939 20.4384 42.7223 23.4757 42.625 26.5433C42.5277 29.6108 41.6082 32.5959 39.9627 35.1866C38.3172 37.7772 36.006 39.8783 33.2707 41.2703C30.5355 42.6623 27.4766 43.294 24.4136 43.0995C21.3507 42.905 18.3963 41.8913 15.8591 40.1645C13.3219 38.4376 11.2952 36.061 9.99062 33.283L2.41797 36.8391C3.65388 39.4709 5.32535 41.8607 7.35106 43.9131C8.50759 45.0848 9.77958 46.1466 11.1519 47.0806C14.9279 49.6506 19.3249 51.1592 23.8834 51.4487C28.4418 51.7382 32.9943 50.798 37.0652 48.7264C41.136 46.6548 44.5756 43.5277 47.0246 39.6721C49.4736 35.8165 50.842 31.3739 50.9868 26.8085C51.1317 22.2432 50.0476 17.7228 47.8479 13.7197C45.6482 9.71663 42.4137 6.37787 38.4824 4.05236C37.0536 3.2072 35.5519 2.50715 33.9997 1.95836Z" fill="var(--bs-primary)"></path>
												</svg>
											</span>
											<div class="ms-1">
												<p class="mb-1">Last Than Year</p>
												<div class="d-flex align-items-center">
													<h4 class="mb-0 me-3">59%</h4>
													<svg width="29" height="15" viewBox="0 0 29 15" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M0 15L14.5 -1.27353e-06L29 15" fill="var(--bs-primary)"></path>
													</svg>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- End - Last Year Sale -->

							<!-- Start - Sales Summary -->
							<div class="col-md-12">
								<div class="card">
									<div class="card-header border-0">
										<h4 class="card-title">Sales Summary</h4>
										<div class="clearfix">
											<select class="selectpicker form-select form-select-sm" data-width="auto">
												<option selected>Daily</option>
												<option value="1">Newest</option>
												<option value="2">Old</option>
											</select>
										</div>
									</div>
									<div class="card-body pt-2">
										<div class="border p-3 d-flex justify-content-between fs-14 rounded-2 mb-4">
											<span class="text-black">Tuesday</span>
											<span class="text-black">215,523 pcs</span>
										</div>
										
										<div class="text-center">
											<div id="polarAreaCharts"></div>
										</div>
										<div class="row mx-0">
											<div class="col-6 px-0 d-flex align-items-center mb-3">
												<div class="bg-primary rounded me-3 d-block" style="width:25px; height:25px;"></div>
												<div>
													<h5 class="mb-1 text-black">VIP</h5>
													<span>30%</span>
												</div>
											</div>
											<div class="col-6 px-0 d-flex align-items-center mb-3">
												<div class="bg-success rounded me-3 d-block" style="width:25px; height:25px;"></div>
												<div>
													<h5 class="mb-1 text-black">Exclusive</h5>
													<span>24%</span>
												</div>
											</div>
											<div class="col-6 px-0 d-flex align-items-center">
												<div class="bg-warning rounded me-3 d-block" style="width:25px; height:25px;"></div>
												<div>
													<h5 class="mb-1 text-black">Reguler</h5>
													<span>30%</span>
												</div>
											</div>
											<div class="col-6 px-0 d-flex align-items-center">
												<div class="bg-secondary rounded me-3 d-block" style="width:25px; height:25px;"></div>
												<div>
													<h5 class="mb-1 text-black">Economic</h5>
													<span>2%</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- End - Sales Summary -->

							<!-- Start - Ticket Refunded -->
							<div class="col-md-12">
								<div class="card overflow-hidden">
									<div class="card-header border-0 pb-2">
										<div>
											<h3 class="mb-0">2%</h3>
											<p class="mb-1">Ticket Refunded</p>
										</div>
									</div>
									<div class="card-body p-0">	
										<span class="updating-chart">1,2,4,3,2,5,4,2,3,4,5,3,2,3,5,4,3,5,5,2</span>
									</div>
								</div>
							</div>
							<!-- End - Ticket Refunded -->

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