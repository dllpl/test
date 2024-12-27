

<div class="lk__left">
	<div class="menu-nav">
	     @auth
		 <li class="menu-nav__item">
            <div class="d-flex justify-content-between mb-4">
                <a href="#" class="menu-nav__link link link--flex">
                    <h4 class="title title--small">Баланс - {{ auth()->user()->balance }} руб.</h4>
                </a>
                <button class="btn-reset text-decoration-underline font-weight-bold d-block d-md-none" onclick="document.querySelector('.menu-nav').classList.remove('menu-nav-open-js')">{{ t('Close') }}</button>
            </div>

            <!-- Финансы -->
            <a href="#" class="menu-nav__link link link--flex">
                <h4 class="title title--small">Финансы</h4>
            </a>
            <ul class="menu-nav__sublist list-reset">
                <!-- Баланс и вывод -->
                <li class="menu-nav__subitem">
                    <h5 class="title title--small">
                        Баланс и вывод
					</h5>
                    <ul class="menu-nav__sublist list-reset">
                        <li class="menu-nav__subitem">
                            <a href="#balanceFormPopup " data-fancybox class="menu-nav__link link">
                                <i class="fas fa-credit-card" style="color: var(--accent)"></i>&nbsp; Пополнить баланс
                            </a>
                        </li>
                        <li class="menu-nav__subitem">
                            <a href="#withdrawRequestPopup" data-fancybox class="menu-nav__link link">
                                <i class="fas fa-money-check-alt" style="color: var(--accent)"></i>&nbsp; Запрос вывода
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- История операций -->
                <li class="menu-nav__subitem">
					<h5 class="title title--small">
                        История операций
					</h5>
                    <ul class="menu-nav__sublist list-reset">
                        <li class="menu-nav__subitem">
                            <a href="{{ route('transactions.history') }}" class="menu-nav__link link">
                                <i class="fas fa-chart-line" style="color: var(--accent)"></i>&nbsp; Приходы и расходы
                            </a>
                        </li>
                        <li class="menu-nav__subitem">
                            <a href="{{ route('transactions.withdraw_requests') }}" class="menu-nav__link link">
                                <i class="fas fa-list-alt" style="color: var(--accent)"></i>&nbsp; Запросы вывода
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Мои счета -->
                <li class="menu-nav__subitem">
					<h5 class="title title--small">
                        Мои счета
					</h5>
                    <ul class="menu-nav__sublist list-reset">
                        <li class="menu-nav__subitem">
                            <a href="{{ route('bill.list') }}" class="menu-nav__link link">
                                <i class="fas fa-wallet" style="color: var(--accent)"></i>&nbsp; Список счетов
                            </a>
                        </li>
                        <li class="menu-nav__subitem">
                            <a href="{{ route('bill.add') }}" class="menu-nav__link link">
                                <i class="fas fa-plus-circle" style="color: var(--accent)"></i>&nbsp; Добавить счет
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>

            <!-- Дополнительные ссылки -->
            <a href="{{ route('devices.index') }}" class="menu-nav__link link mt-4">
                <i class="fas fa-cogs" style="color: var(--accent)"></i>&nbsp; Мои устройства
            </a>
            <a href="{{ route('deals.my') }}" class="menu-nav__link link">
                <i class="fas fa-exchange-alt" style="color: var(--accent)"></i>&nbsp; Мои сделки
            </a>
        </li>

		<style>
			.title--small {
				margin-top: 15px;
			}
		</style>
        @endauth
		@if (isset($userMenu) && !empty($userMenu))
			@php
				$userMenu = $userMenu->groupBy('group');
			@endphp
			@foreach($userMenu as $group => $menu)
				@php
					$boxId = str($group)->slug();
				@endphp

				<li class="menu-nav__item">
					<a href="#" class="menu-nav__link link link--flex">
						<h4 class="title title--small ">{{ $group }}&nbsp;</h4>
					</a>
					<ul class="menu-nav__sublist list-reset">
						@foreach($menu as $key => $value)
							<li class="menu-nav__subitem">
								@if(strpos($value['url'], 'account/close'))
									@continue
								@endif
								<a href="{{ $value['url'] }}" class="link link--grey {!! (isset($value['isActive']) && $value['isActive']) ? 'active__lk' : '' !!}">
									<i class="{{ $value['icon'] }}" style="color: var(--accent)"></i>&nbsp; {{ $value['name'] }}
									@if (isset($value['countVar']) && !empty($value['countVar']))
										<span class="{{ !empty($value['countCustomClass']) ? $value['countCustomClass'] . ' hide' : '' }}">
													({{ \App\Helpers\Number::short(data_get($stats, $value['countVar']) ?? 0) }})
										</span>
									@endif
								</a>
							</li>
						@endforeach
					</ul>
				</li>
			@endforeach
		@endif
{{--		<a class="link link--accent mt-5" href="{{route('user.cert.index')}}" style="padding: 10px 32px;">--}}
{{--			Аккредитация--}}
{{--		</a>--}}
	</div>
</div>

{{--<aside>--}}
{{--	<div class="inner-box">--}}
{{--		<div class="user-panel-sidebar">--}}

{{--			@if (isset($userMenu) && !empty($userMenu))--}}
{{--				@php--}}
{{--					$userMenu = $userMenu->groupBy('group');--}}
{{--				@endphp--}}
{{--				@foreach($userMenu as $group => $menu)--}}
{{--					@php--}}
{{--						$boxId = str($group)->slug();--}}
{{--					@endphp--}}
{{--					<div class="collapse-box">--}}
{{--						<h5 class="collapse-title no-border">--}}
{{--							{{ $group }}&nbsp;--}}
{{--							<a href="#{{ $boxId }}" data-bs-toggle="collapse" class="float-end"><i class="fa fa-angle-down"></i></a>--}}
{{--						</h5>--}}
{{--						@foreach($menu as $key => $value)--}}
{{--							<div class="panel-collapse collapse show" id="{{ $boxId }}">--}}
{{--								<ul class="acc-list">--}}
{{--									<li>--}}
{{--										<a {!! (isset($value['isActive']) && $value['isActive']) ? 'class="active"' : '' !!} href="{{ $value['url'] }}">--}}
{{--											<i class="{{ $value['icon'] }}"></i> {{ $value['name'] }}--}}
{{--											@if (isset($value['countVar']) && !empty($value['countVar']))--}}
{{--												<span class="badge badge-pill{{ !empty($value['countCustomClass']) ? $value['countCustomClass'] . ' hide' : '' }}">--}}
{{--													{{ \App\Helpers\Number::short(data_get($stats, $value['countVar']) ?? 0) }}--}}
{{--												</span>--}}
{{--											@endif--}}
{{--										</a>--}}
{{--									</li>--}}
{{--								</ul>--}}
{{--							</div>--}}
{{--						@endforeach--}}
{{--					</div>--}}
{{--				@endforeach--}}
{{--			@endif--}}

{{--		</div>--}}
{{--	</div>--}}
{{--</aside>--}}





