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
		<div class="d-md-flex d-sm-none d-none">
			<a href="{{ url('/') }}" class="main-logo">
				<img src="{{ config('settings.app.logo_url') }}"
					 alt="{{ strtolower(config('settings.app.name')) }}" class="main-logo" data-bs-placement="bottom"
					 data-bs-toggle="tooltip"
					 title="{!! $logoLabel !!}" style="height: 22px"/>
			</a>
            <div class="d-flex align-items-center gap-4">
                <a href="/category/automobiles">Автомобили</a>
                <a href="/category/uslugi">Услуги</a>
                <a href="/category/parts">Запчасти</a>
                <a href="/category/logistika">Логистика</a>
            </div>
			<!--@if (config('settings.geo_location.show_country_flag'))
				@if (!empty(config('country.icode')))
					@if (file_exists(public_path() . '/images/flags/32/' . config('country.icode') . '.png'))
						<li class="flag-menu country-flag d-md-block d-sm-none d-none nav-item"
							data-bs-toggle="tooltip"
							data-bs-placement="{{ (config('lang.direction') == 'rtl') ? 'bottom' : 'right' }}" {!! $multiCountriesLabel !!}
						>
							@if ($multiCountriesIsEnabled)
								<a class="nav-link p-0" data-bs-toggle="modal" data-bs-target="#selectCountry">
									<img class="flag-icon mt-1"
										 src="{{ url('images/flags/32/' . config('country.icode') . '.png') . getPictureVersion() }}"
										 alt="{{ config('country.name') }}"
									>
									<span class="caret d-lg-block d-md-none d-sm-none d-none float-end mt-3 mx-1"></span>
								</a>
							@else
								<a class="p-0" style="cursor: default;">
									<img class="flag-icon"
										 src="{{ url('images/flags/32/' . config('country.icode') . '.png') . getPictureVersion() }}"
										 alt="{{ config('country.name') }}"
									>
								</a>
							@endif
						</li>
					@endif
				@endif
			@endif -->
		</div>

		<div class="d-flex">
		<button class="burger btn-reset" aria-label="{{t('open_mobile_menu')}}" aria-expanded="false" data-burger>
		  <span class="burger__icon"></span>
		</button>
		<!--	{{-- Country Flag (Mobile) --}}
					@if ($multiCountriesIsEnabled)
						@if (!empty(config('country.icode')))
							@if (file_exists(public_path() . '/images/flags/24/' . config('country.icode') . '.png'))
								<button class="flag-menu country-flag d-md-none d-sm-block  btn btn-default float-end" href="#selectCountry" data-bs-toggle="modal" style="border: none; padding: 0">
									<img src="{{ url('images/flags/24/' . config('country.icode') . '.png') . getPictureVersion() }}"
										 alt="{{ config('country.name') }}"
										 style="float: left;"
									>
									<span class="caret d-none"></span>
								</button>
							@endif
						@endif
					@endif
		-->
		</div>



		<a href="{{ url('/') }}" class="mobile-icons-logo">
			<img src="/images/logo-full.svg"
				 alt="logo-2" data-bs-placement="bottom"
				 data-bs-toggle="tooltip" style="height: 18px"/>
		</a>

		<div class="mobile-icons-block align-items-baseline">
			@if(!auth()->check())
				<a href="#quickLogin" data-bs-toggle="modal">
					<svg class="header__svg">
						<use xlink:href="/images/sprite.svg#user"></use>
					</svg>
				</a>
			@else
				<a href="/account">
					<svg class="header__svg">
						<use xlink:href="/images/sprite.svg#user"></use>
					</svg>
				</a>
			@endif

			<a href="#browseLocations" data-bs-toggle="modal" data-admin-code="0" data-city-id="0">
				<svg class="icon icon--geo">
					<use xlink:href="/images/sprite.svg#geo--old"></use>
				</svg>
			</a>
		</div>

		<div class="header__menu menu" data-menu>
			<ul class="list-reset header__list">
				<li class="header__item header__accordion">
					<div class="accordion">{{ t('about_system') }} {{ config('settings.app.name') }}</div>
					<div class="accordion__panel">
						<ul>
							<li>
								<a href="/page/faq"> Часто задаваемые вопросы </a>
							</li>
							<li>
								<a href="/page/anti-scam"> Анти-мошенничество </a>
							</li>
							<li>
								<a href="/page/terms"> Правила использования </a>
							</li>
							<li>
								<a href="/page/privacy"> {{ t('privacy') }} </a>
							</li>
						</ul>
					</div>

					<div class="accordion">{{ t('information') }}</div>
					<div class="accordion__panel">
						<ul>
							<li><a href="{{ \App\Helpers\UrlGen::contact() }}"> {{ t('Contact') }} </a></li>
							<li><a href="{{ \App\Helpers\UrlGen::sitemap() }}"> {{ t('sitemap') }} </a></li>
							@if (isset($countries) && $countries->count() > 1)
								<li><a href="{{ \App\Helpers\UrlGen::countries() }}"> {{ t('countries') }} </a></li>
							@endif
						</ul>
					</div>

					<div class="accordion">{{ t('my_account') }}</div>
					<div class="accordion__panel">
						@if (!auth()->check())
							<ul>
								@if (config('settings.security.login_open_in_modal'))
									<li><a href="#quickLogin" class="link" data-bs-toggle="modal"><i class="fas fa-user"></i> {{ t('log_in') }}</a></li>
								@else
									<li><a href="{{ \App\Helpers\UrlGen::login() }}" class="link"><i class="fas fa-user"></i> {{ t('log_in') }}</a></li>
								@endif
									<li><a href="{{ \App\Helpers\UrlGen::register() }}" class="link"><i class="far fa-user"></i> {{ t('sign_up') }}</a></li>
							</ul>
						@else
							<ul>
								<li>
									<a href="#">
										<svg class="header__svg">
											<use xlink:href="/images/sprite.svg#user"></use>
										</svg>
										<span>{{ auth()->user()->name }}</span>
									</a>
								</li>
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
										<li class="{{ (isset($value['isActive']) && $value['isActive']) ? ' active' : '' }}">
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
					@endif
					</div>
				</li>
				@if (!auth()->check())
					<li class="dropdown no-arrow open-on-hover hide__mobile">
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
				@else
					<li class="header__item hide__mobile">
						<a href="/account/posts/favourite" class="link">
							<svg class="header__svg">
								<use xlink:href="/images/sprite.svg#heart"></use>
							</svg>
							<span class="header__content-adaptive">Избранное</span>
						</a>
					</li>
					<li class="header__item hide__mobile">
						<a href="/account/saved-searches" class=" link">
                            <i class="fa fa-search" style="font-size: 20px" aria-hidden="true"></i>
							<span class="header__content-adaptive">Уведомления</span>
						</a>
					</li>
					<li class="header__item hide__mobile">
						<a href="/account/messages" class="link">
							<svg class="header__svg">
								<use xlink:href="/images/sprite.svg#chat"></use>
							</svg>
							<span class="header__content-adaptive">Чат</span>
						</a>
						<span class="badge badge-pill link--accent count-threads-with-new-messages d-lg-inline-block d-md-none">0</span>
					</li>

					<li class="header__item dropdown no-arrow open-on-hover hide__mobile">
						<a href="#" class="dropdown-toggle link" data-bs-toggle="dropdown">
							<svg class="header__svg">
								<use xlink:href="/images/sprite.svg#user"></use>
							</svg>
							<span>{{ auth()->user()->name }}</span>
						</a>
						<ul id="userMenuDropdown" class="dropdown-menu user-menu shadow-sm">
							@if (isset($userMenu) && !empty($userMenu))
							    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
                                    <link
                                      rel="stylesheet"
                                      href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"
                                    />

                                    <!-- Скрытая форма для пополнения баланса -->
                                    <div style="display: none;" id="balanceFormPopup "  >
                                        <h3 class="headers_balance">Пополнить баланс</h3>
                                        <form action="{{ route('tinkoff.payment') }}" method="POST" style="width: 30rem;">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                                            <label calss="col-form-label" for="amount">Введите сумму пополнения:</label>
                                            <input class="form-control input input--default" type="number" name="amount" required min="1">

                                            <button type="submit" class="topup_balance_btn_form">Пополнить</button>
                                        </form>
                                    </div>

                                    {{-- Вывод баланса и ссылки на пополнение --}}
                                    @auth
                                        <li class="dropdown-item{{ (isset($value['isActive']) && $value['isActive']) ? ' active__lk' : '' }}">
                                            <a href="#balanceFormPopup " data-fancybox class="menu-nav__link link link--flex">
                                                <h4 class="title title--small">Баланс - {{ auth()->user()->balance }} руб.</h4>
                                            </a>
                                        </li>

                                        <script>
                                          // Инициализация Fancybox
                                          Fancybox.bind("[data-fancybox]", {
                                            // Дополнительные параметры, если нужны
                                          });
                                        </script>
                                    @endauth
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
									<li class="dropdown-item{{ (isset($value['isActive']) && $value['isActive']) ? ' active__lk' : '' }}">
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

				<li class="header__item header__accordion">
					<span style="font-size: 16px; font-weight: 500; padding: 10px 0">{{ t('Contact') }}</span>
					<ul>
						<li>
							<a href="tel:89172888001" class="link link--flex">
								<svg class="header__svg">
									<use xlink:href="/images/sprite.svg#phone"></use>
								</svg>
								<div class="footer__info-wrapp">
									<span class="footer__info">+7 (917) 288-80-01 </span>
									<span class="footer__info">{{ t('free_in_russia') }}</span>
								</div>
							</a>
						</li>
						<li>
							<a href="#" class="link link--flex">
								<svg class="header__svg">
									<use xlink:href="/images/sprite.svg#mail"></use>
								</svg>
								<div class="footer__info-wrapp">
									<span class="footer__info">info@automost.pro </span>
									<span class="footer__info">{{ t('support_service') }}</span>
								</div>
							</a>
						</li>
					</ul>
				</li>

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
						{{ t('Create Listing') }}
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

