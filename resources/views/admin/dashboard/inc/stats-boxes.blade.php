<div class="row">
	@if (isset($countUnactivatedPosts))
	<div class="col-lg-3 col-6">
		
		<div class="card bg-orange rounded shadow">
			<div class="card-body">
				<div class="row py-1">
					<div class="col-8 d-flex align-items-center">
						<div>
							<h2 class="fw-light">
								<a href="{{ admin_url('posts?active=0') }}" class="text-white" style="font-weight: bold;">
								{{ $countUnactivatedPosts }}
								</a>
							</h2>
							<h6 class="text-white">
								<a href="{{ admin_url('posts?active=0') }}" class="text-white">
								{{ trans('admin.Unactivated listings') }}
								</a>
							</h6>
						</div>
					</div>
					<div class="col-4 d-flex align-items-center justify-content-end">
						<span class="text-white display-6">
							<a href="{{ admin_url('posts?active=0') }}" class="text-white">
							<i class="fa fa-edit"></i>
							</a>
						</span>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	@endif
	
	@if (isset($countActivatedPosts))
	<div class="col-lg-3 col-6">
		
		<div class="card bg-success rounded shadow">
			<div class="card-body">
				<div class="row py-1">
					<div class="col-8 d-flex align-items-center">
						<div>
							<h2 class="fw-light">
								<a href="{{ admin_url('posts?active=1') }}" class="text-white" style="font-weight: bold;">
								{{ $countActivatedPosts }}
								</a>
							</h2>
							<h6 class="text-white">
								<a href="{{ admin_url('posts?active=1') }}" class="text-white">
								{{ trans('admin.Activated listings') }}
								</a>
							</h6>
						</div>
					</div>
					<div class="col-4 d-flex align-items-center justify-content-end">
						<span class="text-white display-6">
							<a href="{{ admin_url('posts?active=1') }}" class="text-white">
							<i class="far fa-check-circle"></i>
							</a>
						</span>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	@endif
	
	@if (isset($countUsers))
	<div class="col-lg-3 col-6">
		
		<div class="card bg-info rounded shadow">
			<div class="card-body">
				<div class="row py-1">
					<div class="col-8 d-flex align-items-center">
						<div>
							<h2 class="fw-light">
								<a href="{{ admin_url('users') }}" class="text-white" style="font-weight: bold;">
								{{ $countUsers }}
								</a>
							</h2>
							<h6 class="text-white">
								<a href="{{ admin_url('users') }}" class="text-white">
								{{ mb_ucfirst(trans('admin.users')) }}
								</a>
							</h6>
						</div>
					</div>
					<div class="col-4 d-flex align-items-center justify-content-end">
						<span class="text-white display-6">
							<a href="{{ admin_url('users') }}" class="text-white">
							<i class="far fa-user-circle"></i>
							</a>
						</span>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	@endif
	
	@if (isset($countCountries))
	<div class="col-lg-3 col-6">
		
		<div class="card bg-inverse text-white rounded shadow">
			<div class="card-body">
				<div class="row py-1">
					<div class="col-8 d-flex align-items-center">
						<div>
							<h2 class="fw-light">
								<a href="{{ admin_url('countries') }}" class="text-white" style="font-weight: bold;">
								{{ $countCountries }}
								</a>
							</h2>
							<h6 class="text-white">
								<a href="{{ admin_url('countries') }}" class="text-white">
								{{ trans('admin.Activated countries') }}
								</a>
								<span class="badge bg-light text-dark"
									  data-bs-placement="bottom"
									  data-bs-toggle="tooltip"
									  type="button"
									  title="{!! trans('admin.launch_your_website_for_several_countries') . ' ' . trans('admin.disabling_or_removing_a_country_info') !!}"
								>
									{{ trans('admin.Help') }} <i class="far fa-life-ring"></i>
								</span>
							</h6>
						</div>
					</div>
					<div class="col-4 d-flex align-items-center justify-content-end">
						<span class="text-white display-6">
							<a href="{{ admin_url('countries') }}" class="text-white">
							<i class="fa fa-globe"></i>
							</a>
						</span>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	@endif


</div>


<div class="row">
    <!-- Денег в системе -->
    <div class="col-lg-3 col-3">
        <div class="card bg-primary rounded shadow">
            <div class="card-body">
                <div class="row py-1">
                    <div class="col-8 d-flex align-items-center">
                        <div>
                            <h2 class="fw-light">
                                <a href="{{ admin_url('users') }}" class="text-white" style="font-weight: bold;">
                                    {{ number_format($totalBalance, 2) }}
                                </a>
                            </h2>
                            <h6 class="text-white">
                                <a href="{{ admin_url('users') }}" class="text-white">
                                    Денег в системе
                                </a>
                            </h6>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center justify-content-end">
                        <span class="text-white display-6">
                            <a href="{{ admin_url('users') }}" class="text-white">
                                <i class="fa fa-wallet"></i>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ожидает выплаты -->
    <div class="col-lg-3 col-3">
        <div class="card bg-warning rounded shadow">
            <div class="card-body">
                <div class="row py-1">
                    <div class="col-8 d-flex align-items-center">
                        <div>
                            <h2 class="fw-light">
                                <a href="{{ admin_url('withdraw_requests?status=pending') }}" class="text-white" style="font-weight: bold;">
                                    {{ number_format($pendingWithdrawals, 2) }}
                                </a>
                            </h2>
                            <h6 class="text-white">
                                <a href="{{ admin_url('withdraw_requests?status=pending') }}" class="text-white">
                                    Ожидают выплаты
                                </a>
                            </h6>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center justify-content-end">
                        <span class="text-white display-6">
                            <a href="{{ admin_url('withdraw_requests?status=pending') }}" class="text-white">
                                <i class="fa fa-clock"></i>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Выплачено -->
    <div class="col-lg-3 col-3">
        <div class="card bg-success rounded shadow">
            <div class="card-body">
                <div class="row py-1">
                    <div class="col-8 d-flex align-items-center">
                        <div>
                            <h2 class="fw-light">
                                <a href="{{ admin_url('withdraw_requests?status=approved') }}" class="text-white" style="font-weight: bold;">
                                    {{ number_format($approvedWithdrawals, 2) }}
                                </a>
                            </h2>
                            <h6 class="text-white">
                                <a href="{{ admin_url('withdraw_requests?status=approved') }}" class="text-white">
                                    Выплачено
                                </a>
                            </h6>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center justify-content-end">
                        <span class="text-white display-6">
                            <a href="{{ admin_url('withdraw_requests?status=approved') }}" class="text-white">
                                <i class="fa fa-check-circle"></i>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="col-lg-3 col-3">
    <div class="card bg-warning rounded shadow">
        <div class="card-body">
            <div class="row py-1">
                <div class="col-8 d-flex align-items-center">
                    <div>
                        <h2 class="fw-light">
                            <span class="text-white" style="font-weight: bold;">
                                {{ number_format($totalCommissions, 2) }} ₽
                            </span>
                        </h2>
                        <h6 class="text-white">
                            Заработано комиссий
                        </h6>
                    </div>
                </div>
                <div class="col-4 d-flex align-items-center justify-content-end">
                    <span class="text-white display-6">
                        <i class="fa fa-money-bill-alt"></i>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

@push('dashboard_styles')
@endpush

@push('dashboard_scripts')
@endpush
