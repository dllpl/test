<div class="lk__left">
	<div class="menu-nav">
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
								<a href="{{ $value['url'] }}" class="link link--grey {!! (isset($value['isActive']) && $value['isActive']) ? 'active' : '' !!}">
									<i class="{{ $value['icon'] }}" style="color: #79B285"></i>&nbsp; {{ $value['name'] }}
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
			@if(\App\Helpers\Role::isSuperUser())
				<span class="search__link link link--btn link--accent" style="padding: 10px;"><i class="fa fa-check-circle"></i> Сертифицирован</span>
			@else
				@if(\App\Helpers\SuperUser::status() === null)
					<button data-url="{{route('super-user.send-request')}}" id="super_user_send_request" class="btn-reset search__link link link--btn link--accent" style="padding: 10px;">Пройти сертификацию</button>
				@elseif(\App\Helpers\SuperUser::status() === 0)
					<span class="search__link link link--btn link--accent" style="padding: 10px;"><i class="fa fa-clock"></i> Завявка в обработке</span>
				@elseif(\App\Helpers\SuperUser::status() === 2)
					<span class="search__link link link--btn link--accent" style="padding: 10px;"><i class="fa fa-times-circle"></i> Завявка отклонена</span>
				@endif
			@endif
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
