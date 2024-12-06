

<div class="lk__left">
	<div class="menu-nav">
	    {{-- Вывод баланса и ссылки на пополнение --}}
        @auth
            <li class="menu-nav__item">
                <div class="d-flex justify-content-between">
                    <a href="#" class="menu-nav__link link link--flex">
                        <h4 class="title title--small">Баланс - {{ auth()->user()->balance }} руб.</h4>
                    </a>
                    <button class="btn-reset text-decoration-underline font-weight-bold d-block d-md-none" onclick="document.querySelector('.menu-nav').classList.remove('menu-nav-open-js')">{{t('Close')}}</button>
                </div>


                <!-- Ссылка для открытия попапа -->
                <a href="#balanceFormPopup " data-fancybox class="topup_balance_btn">
                    Пополнить баланс
                </a>
            </li>
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





