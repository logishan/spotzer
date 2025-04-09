				<div class="sticky">
					<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
						<div class="app-sidebar">
							<div class="side-header">
								<a class="header-brand1" href="{{url('/')}}">
									<img src="{{asset('build/assets/images/brand/spotzer-retina.png')}}" class="header-brand-img desktop-logo" alt="logo">
									<img src="{{asset('build/assets/images/brand/favicon.png')}}" class="header-brand-img toggle-logo"
										alt="logo">
									<img src="{{asset('build/assets/images/brand/favicon.png')}}" class="header-brand-img light-logo" alt="logo">
									<img src="{{asset('build/assets/images/brand/spotzer.png')}}" class="header-brand-img light-logo1"
										alt="logo">
								</a>
								<!-- LOGO -->
							</div>
							<div class="main-sidemenu">
								<div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg"
										fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
										<path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
									</svg></div>
								<ul class="side-menu">
									@if(Auth::user()->hasRole('Super Admin'))
									<li class="sub-category">
										<h3>Main</h3>
									</li>
									<li class="slide">
										<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-airplay"></i><span
											class="side-menu__label">Dashboards</span><i
											class="angle fe fe-chevron-right"></i>
										</a>
										<ul class="slide-menu">
											<li class="panel sidetab-menu">
												<div class="tab-menu-heading p-0 pb-2 border-0">
													<div class="tabs-menu ">
														<!-- Tabs -->
														<ul class="nav panel-tabs">
															<li><a href="#side1" class="d-flex active" data-bs-toggle="tab"><i class="fe fe-monitor me-2"></i><p>Home</p></a></li>
															<li><a href="#side2" data-bs-toggle="tab" class="d-flex"><i class="fe fe-message-square me-2"></i><p>Chat</p></a></li>
														</ul>
													</div>
												</div>
												<div class="panel-body tabs-menu-body p-0 border-0">
													<div class="tab-content">
														<div class="tab-pane active" id="side1">
															<ul class="sidemenu-list">
																<li class="side-menu-label1"><a href="javascript:void(0);">Dashboards</a></li>
																<li><a href="{{url('/')}}" class="slide-item"> Sales</a></li>
																<li><a href="{{url('index2')}}" class="slide-item"> Marketing</a></li>
																<li><a href="{{url('index3')}}" class="slide-item"> Service</a></li>
																<li><a href="{{url('index4')}}" class="slide-item">Finance</a></li>
																<li><a href="{{url('index5')}}" class="slide-item">IT</a></li>
															</ul>
															<div class="menutabs-content mt-5 p-0">
																<div class="Annoucement_card">
																	<div class="text-center">
																		<div>
																			<h5 class="title mt-0 mb-1 ms-2 font-weight-bold fs-12"> Go for Premium Account </h5>
																			<div class="bg-layer">
																				<img src="{{asset('build/assets/images/pngs/24.png')}}" alt="img" class="text-center mx-auto">
																			</div>
																			<p class="subtext mt-0 mb-0 ms-2 fs-17 fw-semibold text-center my-2"> $399.9 /Monthly</p>
																		</div>
																	</div>
																	<button class="btn btn-block btn-primary mt-4 fs-14"> Upgrade</button>
																</div>
															</div>
														</div>
														<div class="tab-pane" id="side2">
															<h5 class="mt-3 fw-bold mb-4 tab-title">Recent Chats</h5>
															<div class="main-chat-list tab-pane">
																<!-- Chat content -->
																<div class="media border-0 px-1 new border-top-0">
																	<div class="main-img-user online">
																		<img alt="" src="{{asset('build/assets/images/users/5.jpg')}}">
																	</div>
																	<div class="media-body">
																		<div class="media-contact-name">
																			<span>Raymart Santiago</span> <span>10 min</span>
																		</div>
																		<p> Hey! there I'm available </p>
																	</div>
																</div>
																<!-- ... more chat entries ... -->
															</div>
														</div>
													</div>
												</div>
											</li>
										</ul>
									</li>
									@endif
									
									<!-- HR Management Section Start -->
									<li class="sub-category">
										<h3>HR Management</h3>
									</li>
									<li class="slide">
										<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
											<i class="side-menu__icon fe fe-users"></i>
											<span class="side-menu__label">HR Management</span>
											<i class="angle fe fe-chevron-right"></i>
										</a>
										<ul class="slide-menu">
											<li><a href="{{ route('shift-schedules.index') }}" class="slide-item">Shift Schedules</a></li>
											<li><a href="{{ route('attendances.index') }}" class="slide-item">Attendance</a></li>
											<li><a href="{{ route('attendances.sheet') }}" class="slide-item">Attendance Sheet</a></li>
											<li><a href="{{ route('users.index') }}" class="slide-item">User Management</a></li>
										</ul>
									</li>
									<!-- HR Management Section End -->
									
									@if(Auth::user()->hasRole('Super Admin'))
									<li class="sub-category">
										<h3>Widgets & Maps</h3>
									</li>
									<li>
										<a class="side-menu__item has-link" href="{{url('widgets')}}"><i class="side-menu__icon fe fe-package"></i><span
												class="side-menu__label">Widgets</span></a>
									</li>
									<!-- ... Other menu items for Super Admin only ... -->
									@endif

									<li class="slide">
										<a class="side-menu__item" data-bs-toggle="slide" href="#">
											<i class="side-menu__icon fe fe-settings"></i>
											<span class="side-menu__label">Task Settings</span>
											<i class="angle fa fa-angle-right"></i>
										</a>
										<ul class="slide-menu">
											<li><a href="{{url('task-groups')}}" class="slide-item">Task Groups</a></li>
											<li><a href="{{ route('task-types.index') }}" class="slide-item">Task Types</a></li>
											<li><a href="{{ route('task-complexity.index') }}" class="slide-item">Task Complexity</a></li>
											<li><a href="{{ route('task-type-complexities.index') }}" class="slide-item">Task Type Complexities</a></li>
											<li><a href="{{ route('form-fields.index') }}" class="slide-item">Form Fields</a></li>
										</ul>
									</li>
									
									<li class="slide">
										<a class="side-menu__item" data-bs-toggle="slide" href="#">
											<i class="side-menu__icon fe fe-clipboard"></i>
											<span class="side-menu__label">Task Management</span>
											<i class="angle fa fa-angle-right"></i>
										</a>
										<ul class="slide-menu">
											<li><a href="{{ route('tasks.index') }}" class="slide-item">All Tasks</a></li>
											<li><a href="{{ route('tasks.kanban') }}" class="slide-item">Kanban Board</a></li>
											<li><a href="{{ route('tasks.create') }}" class="slide-item">Create New Task</a></li>
										</ul>
									</li>
									
									<li class="slide">
										<a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"><i class="side-menu__icon fe fe-home"></i><span
											class="side-menu__label">Spotzer Team</span><i
											class="angle fe fe-chevron-right"></i>
										</a>
										<ul class="slide-menu">
											<li class="panel sidetab-menu">
												<div class="tab-menu-heading p-0 pb-2 border-0">
													<div class="tabs-menu ">
														<!-- Tabs -->
														<ul class="nav panel-tabs">
															<li><a href="#spotzer-team" class="d-flex active" data-bs-toggle="tab"><i class="fe fe-users me-2"></i><p>Team</p></a></li>
														</ul>
													</div>
												</div>
												<div class="panel-body tabs-menu-body p-0 border-0">
													<div class="tab-content">
														<div class="tab-pane active" id="spotzer-team">
															<ul class="sidemenu-list">
																<li class="side-menu-label1"><a href="javascript:void(0);">Spotzer Team</a></li>
																<li><a href="{{url('users')}}" class="slide-item">Users</a></li>
																<li><a href="{{url('roles')}}" class="slide-item">Roles</a></li>
																<li><a href="{{url('departments')}}" class="slide-item">Departments</a></li>
															</ul>
														</div>
													</div>
												</div>
											</li>
										</ul>
									</li>

									@if(Auth::user()->hasRole('Super Admin'))
									<!-- Additional menu items only for Super Admin -->
									<!-- Continue with the rest of the sidebar menu items for Super Admin -->
									@endif
								</ul>
								<div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
										width="24" height="24" viewBox="0 0 24 24">
										<path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
									</svg></div>
							</div>
					</div>
				</div>