<?php
// Search parameters
$queryString = (request()->getQueryString() ? ('?' . request()->getQueryString()) : '');

// Check if the Multi-Countries selection is enabled
$multiCountriesIsEnabled = false;
$multiCountriesLabel = '';
if (config('settings.geo_location.show_country_flag')) {
	if (!empty(config('country.code'))) {
		if (isset($countries) && $countries->count() > 1) {
			$multiCountriesIsEnabled = true;
			$multiCountriesLabel = 'title="' . t('Select a Country') . '"';
		}
	}
}

// Logo Label
$logoLabel = '';
if ($multiCountriesIsEnabled) {
	$logoLabel = config('settings.app.name') . ((!empty(config('country.name'))) ? ' ' . config('country.name') : '');
}
?>

<header class="header">
	<div class="header__container container-fluid">
		<a href="{{ url('/') }}">
			<img src="{{ config('settings.app.logo_url') }}"
				 alt="{{ strtolower(config('settings.app.name')) }}" class="main-logo" data-bs-placement="bottom"
				 data-bs-toggle="tooltip"
				 title="{!! $logoLabel !!}" style="height: 40px"/>
		</a>

		<button class="burger btn-reset" aria-label="Открыть меню" aria-expanded="false" data-burger>
		  <span class="burger__icon">
			<span class="line"></span>
			<span class="line"></span>
			<span class="line"></span>
		  </span>
		</button>

		<div class="header__menu menu" data-menu>
			<ul class="list-reset header__list">
				@if (!auth()->check())
					<li class="header__item dropdown no-arrow open-on-hover">
						<a href="#" class="link link--flex dropdown-toggle" data-bs-toggle="dropdown">
							<svg class="header__svg">
								<use xlink:href="/images/sprite.svg#user"></use>
							</svg>
							<span class="header__content-adaptive">Вход и регистрация</span>
						</a>
						<ul id="authDropdownMenu" class="dropdown-menu user-menu shadow-sm">
							<li class="dropdown-item">
								@if (config('settings.security.login_open_in_modal'))
									<a href="#quickLogin" class="link" data-bs-toggle="modal"><i class="fas fa-user"></i> {{ t('log_in') }}</a>
								@else
									<a href="{{ \App\Helpers\UrlGen::login() }}" class="link"><i class="fas fa-user"></i> {{ t('log_in') }}</a>
								@endif
							</li>
							<li class="dropdown-item">
								<a href="{{ \App\Helpers\UrlGen::register() }}" class="link"><i class="far fa-user"></i> {{ t('sign_up') }}</a>
							</li>
						</ul>
					</li>
{{--					<li class="header__item d-md-none d-sm-block d-block">--}}
{{--						@if (config('settings.security.login_open_in_modal'))--}}
{{--							<a href="#quickLogin" class="link" data-bs-toggle="modal"><i class="fas fa-user"></i> {{ t('log_in') }}</a>--}}
{{--						@else--}}
{{--							<a href="{{ \App\Helpers\UrlGen::login() }}" class="link"><i class="fas fa-user"></i> {{ t('log_in') }}</a>--}}
{{--						@endif--}}
{{--					</li>--}}
{{--					<li class="header__item d-md-none d-sm-block d-block">--}}
{{--						<a href="{{ \App\Helpers\UrlGen::register() }}" class="nav-link"><i class="far fa-user"></i> {{ t('sign_up') }}</a>--}}
{{--					</li>--}}
				@else
					<li class="header__item">
						<a href="/account/posts/favourite" class="link">
							<svg class="header__svg">
								<use xlink:href="/images/sprite.svg#heart"></use>
							</svg>
							<span class="header__content-adaptive">Избранное</span>
						</a>
					</li>
					<li class="header__item">
						<a href="/account/saved-searches" class=" link">
							<svg class="header__svg">
								<use xlink:href="/images/sprite.svg#bell"></use>
							</svg>
							<span class="header__content-adaptive">Уведомления</span>
						</a>
					</li>
					<li class="header__item">
						<a href="/account/messages" class="link">
							<svg class="header__svg">
								<use xlink:href="/images/sprite.svg#chat"></use>
							</svg>
							<span class="header__content-adaptive">Чат</span>
						</a>
					</li>

					<li class="header__item dropdown no-arrow open-on-hover">
						<a href="#" class="dropdown-toggle link" data-bs-toggle="dropdown">
							<svg class="header__svg">
								<use xlink:href="/images/sprite.svg#user"></use>
							</svg>
							<span>{{ auth()->user()->name }}</span>
						</a>
						<ul id="userMenuDropdown" class="dropdown-menu user-menu shadow-sm">
							@if (isset($userMenu) && !empty($userMenu))
								@php
									$menuGroup = '';
                                    $dividerNeeded = false;
								@endphp
								@foreach($userMenu as $key => $value)
									@continue(!$value['inDropdown'])
									@php
										if ($menuGroup != $value['group']) {
                                            $menuGroup = $value['group'];
                                            if (!empty($menuGroup) && !$loop->first) {
                                                $dividerNeeded = true;
                                            }
                                        } else {
                                            $dividerNeeded = false;
                                        }
									@endphp
									@if ($dividerNeeded)
										<li class="dropdown-divider"></li>
									@endif
									<li class="dropdown-item{{ (isset($value['isActive']) && $value['isActive']) ? ' active' : '' }}">
										<a href="{{ $value['url'] }}">
											<i class="{{ $value['icon'] }}"></i> {{ $value['name'] }}
											@if (isset($value['countVar'], $value['countCustomClass']) && !empty($value['countVar']) && !empty($value['countCustomClass']))
												<span class="badge badge-pill badge-important{{ $value['countCustomClass'] }}">0</span>
											@endif
										</a>
									</li>
								@endforeach
							@endif
						</ul>
					</li>
				@endif

				@if (config('plugins.currencyexchange.installed'))
					@include('currencyexchange::select-currency')
				@endif

				@if (config('settings.single.pricing_page_enabled') == '2')
					<li class="header__item pricing">
						<a href="{{ \App\Helpers\UrlGen::pricing() }}" class="nav-link">
							<i class="fas fa-tags"></i> {{ t('pricing_label') }}
						</a>
					</li>
				@endif

				<?php
				$addListingUrl = \App\Helpers\UrlGen::addPost();
				$addListingAttr = '';
				if (!auth()->check()) {
					if (config('settings.single.guests_can_post_listings') != '1') {
						$addListingUrl = '#quickLogin';
						$addListingAttr = ' data-bs-toggle="modal"';
					}
				}
				if (config('settings.single.pricing_page_enabled') == '1') {
					$addListingUrl = \App\Helpers\UrlGen::pricing();
					$addListingAttr = '';
				}
				?>
				<li class="header__item postadd">
					<a class="link link--btn link--dark" href="{{ $addListingUrl }}"{!! $addListingAttr !!} style="color: white">
						Разместить объявление
					</a>
				</li>

{{--				<li class="header__item d-md-none d-sm-block d-block">--}}
{{--					<a href="{{ \App\Helpers\UrlGen::register() }}" class="nav-link"><i class="far fa-user"></i> {{ t('sign_up') }}</a>--}}
{{--				</li>--}}
				@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.menu.select-language', 'layouts.inc.menu.select-language'])
			</ul>
		</div>
	</div>
</header>

