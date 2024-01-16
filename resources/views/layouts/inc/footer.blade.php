<?php
$socialLinksAreEnabled = (
	config('settings.social_link.facebook_page_url')
	|| config('settings.social_link.twitter_url')
	|| config('settings.social_link.tiktok_url')
	|| config('settings.social_link.linkedin_url')
	|| config('settings.social_link.pinterest_url')
	|| config('settings.social_link.instagram_url')
);
$appsLinksAreEnabled = (
	config('settings.other.ios_app_url')
	|| config('settings.other.android_app_url')
);
$socialAndAppsLinksAreEnabled = ($socialLinksAreEnabled || $appsLinksAreEnabled);
?>

<footer class="footer">
	@if (!config('settings.footer.hide_links'))
		<div class="footer__container container">
			<div class="footer__content d-none d-sm-grid">
				<div class="footer__menu">
					<h2 class="footer__title title ">
						О системе
					</h2>
					<ul class="list-reset">
						@if (isset($pages) && $pages->count() > 0)
							@foreach($pages as $page)
								<li class="footer__item">
										<?php
										$linkTarget = '';
										if ($page->target_blank == 1) {
											$linkTarget = 'target="_blank"';
										}
										?>
									@if (!empty($page->external_link))
										<a href="{!! $page->external_link !!}" rel="nofollow" {!! $linkTarget !!}> {{ $page->name }} </a>
									@else
										<a href="{{ \App\Helpers\UrlGen::page($page) }}" {!! $linkTarget !!}> {{ $page->name }} </a>
									@endif
								</li>
							@endforeach
						@endif
					</ul>
				</div>
				<div class="footer__menu">
					<h2 class="footer__title title ">
						Информация
					</h2>
					<ul class="list-reset">
						<li class="footer__item"><a href="{{ \App\Helpers\UrlGen::contact() }}"> {{ t('Contact') }} </a></li>
						<li class="footer__item"><a href="{{ \App\Helpers\UrlGen::sitemap() }}"> {{ t('sitemap') }} </a></li>
						@if (isset($countries) && $countries->count() > 1)
							<li class="footer__item"><a href="{{ \App\Helpers\UrlGen::countries() }}"> {{ t('countries') }} </a></li>
						@endif
					</ul>
				</div>
				<div class="footer__menu">
					<h2 class="footer__title title">
						Аккаунт
					</h2>
					<ul class="list-reset">
						@if (!auth()->user())
							<li class="footer__item">
								@if (config('settings.security.login_open_in_modal'))
									<a href="#quickLogin" data-bs-toggle="modal"> {{ t('log_in') }} </a>
								@else
									<a href="{{ \App\Helpers\UrlGen::login() }}"> {{ t('log_in') }} </a>
								@endif
							</li>
							<li class="footer__item"><a href="{{ \App\Helpers\UrlGen::register() }}"> {{ t('register') }} </a></li>
						@else
							<li class="footer__item"><a href="{{ url('account') }}"> {{ t('My Account') }} </a></li>
							<li class="footer__item"><a href="{{ url('account/posts/list') }}"> {{ t('my_listings') }} </a></li>
							<li class="footer__item"><a href="{{ url('account/posts/favourite') }}"> {{ t('favourite_listings') }} </a></li>
						@endif
					</ul>
				</div>
				<div class="footer__menu">
					<h2 class="footer__title title">
						Контакты
					</h2>
					<ul class="list-reset">
						<li class="footer__item">
							<a href="tel:89172888001" class="link link--flex">
								<svg class="footer__svg">
									<use xlink:href="/images/sprite.svg#phone"></use>
								</svg>
								<div class="footer__info-wrapp">
									<span class="footer__info">+7 (917) 288-80-01 </span>
									<span class="footer__info">Бесплатно по России</span>
								</div>
							</a>
						</li>
						<li class="footer__item">
							<a href="#" class="link link--flex">
								<svg class="footer__svg">
									<use xlink:href="/images/sprite.svg#mail"></use>
								</svg>
								<div class="footer__info-wrapp">
									<span class="footer__info">info@barsovoz.ru </span>
									<span class="footer__info">Служба поддержки</span>
								</div>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="d-sm-none">

				<ul class="list-reset header__list">
					<li class="header__item header__accordion">
						<div class="accordion accordion__footer">О системе Automost</div>
						<div class="accordion__panel" style="background-color: transparent; border-bottom-color: #686868">
							<ul class="py-2">
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
									<a href="/page/privacy"> Политика конфиденциальности </a>
								</li>
							</ul>
						</div>

						<div class="accordion accordion__footer">Информация</div>
						<div class="accordion__panel" style="background-color: transparent; border-bottom-color: #686868">
							<ul class="py-2">
								<li><a href="/contact"> Связаться </a></li>
								<li><a href="/sitemap"> Карта сайта </a></li>
							</ul>
						</div>

						<div class="accordion accordion__footer">Аккаунт</div>
						<div class="accordion__panel" style="background-color: transparent; border-bottom-color: #686868">
							<ul class="py-2">
								<li><a href="#quickLogin" class="link" data-bs-toggle="modal"><i class="fas fa-user"></i> Войти</a></li>
								<li><a href="/register" class="link"><i class="far fa-user"></i> Зарегистрироваться</a></li>
							</ul>
						</div>
					</li>
					<li class="dropdown no-arrow open-on-hover hide__mobile">
						<a href="#" class="link link--flex dropdown-toggle" data-bs-toggle="dropdown">
							<svg class="header__svg">
								<use xlink:href="/images/sprite.svg#user"></use>
							</svg>
							<span class="header__content-adaptive">Вход и регистрация</span>
						</a>
						<ul id="authDropdownMenu" class="dropdown-menu user-menu shadow-sm">
							<li class="dropdown-item">
								<a href="#quickLogin" class="link" data-bs-toggle="modal"><i class="fas fa-user"></i> Войти</a>
							</li>
							<li class="dropdown-item">
								<a href="/register" class="link"><i class="far fa-user"></i> Зарегистрироваться</a>
							</li>
						</ul>
					</li>



					<li>
						<span style="font-size: 16px; font-weight: 500; padding: 10px 0">Контакты</span>
						<ul class="py-2">
							<li class="mb-2">
								<a href="tel:89172888001" class="link link--flex">
									<i class="fa fa-phone-alt fa-2x"></i>
									<div class="footer__info-wrapp">
										<span class="footer__info">+7 (917) 288-80-01 </span>
										<span class="footer__info">Бесплатно по России</span>
									</div>
								</a>
							</li>
							<li class="mb-2">
								<a href="#" class="link link--flex">
									<i class="fa fa-envelope fa-2x"></i>
									<div class="footer__info-wrapp">
										<span class="footer__info">info@barsovoz.ru </span>
										<span class="footer__info">Служба поддержки</span>
									</div>
								</a>
							</li>
							<li class="mb-2">
								<a href="https://vk.com/automost_pro" target="_blank" class="link link--flex">
									<img src="/images/icon/VK.svg" alt="VK" width="24px">
									<div class="footer__info-wrapp">
										<span class="footer__info">vk.com/automost_pro</span>
										<span class="footer__info">Сообщество в VK</span>
									</div>
								</a>
							</li>
						</ul>
					</li>

					<li class="header__item header__accordion">
						<div class="accordion accordion__footer">Документы</div>
						<div class="accordion__panel" style="background-color: transparent; border-bottom-color: #686868">
							<ul class="py-2">
								<li>
									<a href="/page/faq">Политика конфиденциальности</a>
								</li>
								<li>
									<a href="/page/anti-scam"> Анти-мошенничество </a>
								</li>
								<li>
									<a href="/page/terms"> Правила использования </a>
								</li>
								<li>
									<a href="/page/privacy"> Политика конфиденциальности </a>
								</li>
							</ul>
						</div>
					</li>
				</ul>
			</div>
		</div>
	@endif
	<div class="footer__copyright copyright">
		<div class="copyright__container container">
			<ul class="list-reset copyright__list d-none d-sm-flex">
				<li class="copyright__item">
					<a href="#" class="">
						Политика конфиденциальности
					</a>
				</li>
				<li class="copyright__item">
					<a href="#" class="">
						Политика обработки данных
					</a>
				</li>
				<li class="copyright__item">
					<a href="#" class="">
						Cookie
					</a>
				</li>
			</ul>
			<ul class="list-reset copyright__list">
				<li class="copyright__item text-end">
					{{ config('settings.app.name') }}, {{ date('Y') }}
				</li>
			</ul>
		</div>
	</div>
</footer>


{{--<footer class="main-footer">--}}
{{--	<?php--}}
{{--	$rowColsLg = $socialAndAppsLinksAreEnabled ? 'row-cols-lg-4' : 'row-cols-lg-3';--}}
{{--	$rowColsMd = 'row-cols-md-3';--}}

{{--	$ptFooterContent = '';--}}
{{--	$mbCopy = ' mb-3';--}}
{{--	if (config('settings.footer.hide_links')) {--}}
{{--		$ptFooterContent = ' pt-sm-5 pt-5';--}}
{{--		$mbCopy = ' mb-4';--}}
{{--	}--}}
{{--	?>--}}
{{--	<div class="footer-content{{ $ptFooterContent }}">--}}
{{--		<div class="container">--}}
{{--			<div class="row {{ $rowColsLg }} {{ $rowColsMd }} row-cols-sm-2 row-cols-2 g-3">--}}

{{--				@if (!config('settings.footer.hide_links'))--}}
{{--					<div class="col">--}}
{{--						<div class="footer-col">--}}
{{--							<h4 class="footer-title">{{ t('about_us') }}</h4>--}}
{{--							<ul class="list-unstyled footer-nav">--}}
{{--								@if (isset($pages) && $pages->count() > 0)--}}
{{--									@foreach($pages as $page)--}}
{{--										<li>--}}
{{--											<?php--}}
{{--												$linkTarget = '';--}}
{{--												if ($page->target_blank == 1) {--}}
{{--													$linkTarget = 'target="_blank"';--}}
{{--												}--}}
{{--											?>--}}
{{--											@if (!empty($page->external_link))--}}
{{--												<a href="{!! $page->external_link !!}" rel="nofollow" {!! $linkTarget !!}> {{ $page->name }} </a>--}}
{{--											@else--}}
{{--												<a href="{{ \App\Helpers\UrlGen::page($page) }}" {!! $linkTarget !!}> {{ $page->name }} </a>--}}
{{--											@endif--}}
{{--										</li>--}}
{{--									@endforeach--}}
{{--								@endif--}}
{{--							</ul>--}}
{{--						</div>--}}
{{--					</div>--}}

{{--					<div class="col">--}}
{{--						<div class="footer-col">--}}
{{--							<h4 class="footer-title">{{ t('Contact and Sitemap') }}</h4>--}}
{{--							<ul class="list-unstyled footer-nav">--}}
{{--								<li><a href="{{ \App\Helpers\UrlGen::contact() }}"> {{ t('Contact') }} </a></li>--}}
{{--								<li><a href="{{ \App\Helpers\UrlGen::sitemap() }}"> {{ t('sitemap') }} </a></li>--}}
{{--								@if (isset($countries) && $countries->count() > 1)--}}
{{--									<li><a href="{{ \App\Helpers\UrlGen::countries() }}"> {{ t('countries') }} </a></li>--}}
{{--								@endif--}}
{{--							</ul>--}}
{{--						</div>--}}
{{--					</div>--}}

{{--					<div class="col">--}}
{{--						<div class="footer-col">--}}
{{--							<h4 class="footer-title">{{ t('My Account') }}</h4>--}}
{{--							<ul class="list-unstyled footer-nav">--}}
{{--								@if (!auth()->user())--}}
{{--									<li>--}}
{{--										@if (config('settings.security.login_open_in_modal'))--}}
{{--											<a href="#quickLogin" data-bs-toggle="modal"> {{ t('log_in') }} </a>--}}
{{--										@else--}}
{{--											<a href="{{ \App\Helpers\UrlGen::login() }}"> {{ t('log_in') }} </a>--}}
{{--										@endif--}}
{{--									</li>--}}
{{--									<li><a href="{{ \App\Helpers\UrlGen::register() }}"> {{ t('register') }} </a></li>--}}
{{--								@else--}}
{{--									<li><a href="{{ url('account') }}"> {{ t('My Account') }} </a></li>--}}
{{--									<li><a href="{{ url('account/posts/list') }}"> {{ t('my_listings') }} </a></li>--}}
{{--									<li><a href="{{ url('account/posts/favourite') }}"> {{ t('favourite_listings') }} </a></li>--}}
{{--								@endif--}}
{{--							</ul>--}}
{{--						</div>--}}
{{--					</div>--}}

{{--					@if ($socialAndAppsLinksAreEnabled)--}}
{{--						<div class="col">--}}
{{--							<div class="footer-col row">--}}
{{--								<?php--}}
{{--									$footerSocialClass = '';--}}
{{--									$footerSocialTitleClass = '';--}}
{{--								?>--}}
{{--								@if ($appsLinksAreEnabled)--}}
{{--									<div class="col-sm-12 col-12 p-lg-0">--}}
{{--										<div class="mobile-app-content">--}}
{{--											<h4 class="footer-title">{{ t('Mobile Apps') }}</h4>--}}
{{--											<div class="row">--}}
{{--												@if (config('settings.other.ios_app_url'))--}}
{{--												<div class="col-12 col-sm-6">--}}
{{--													<a class="app-icon" target="_blank" href="{{ config('settings.other.ios_app_url') }}">--}}
{{--														<span class="hide-visually">{{ t('iOS app') }}</span>--}}
{{--														<img src="{{ url('images/site/app-store-badge.svg') }}" alt="{{ t('Available on the App Store') }}">--}}
{{--													</a>--}}
{{--												</div>--}}
{{--												@endif--}}
{{--												@if (config('settings.other.android_app_url'))--}}
{{--												<div class="col-12 col-sm-6">--}}
{{--													<a class="app-icon" target="_blank" href="{{ config('settings.other.android_app_url') }}">--}}
{{--														<span class="hide-visually">{{ t('Android App') }}</span>--}}
{{--														<img src="{{ url('images/site/google-play-badge.svg') }}" alt="{{ t('Available on Google Play') }}">--}}
{{--													</a>--}}
{{--												</div>--}}
{{--												@endif--}}
{{--											</div>--}}
{{--										</div>--}}
{{--									</div>--}}
{{--									<?php--}}
{{--										$footerSocialClass = 'hero-subscribe';--}}
{{--										$footerSocialTitleClass = 'm-0';--}}
{{--									?>--}}
{{--								@endif--}}

{{--								@if ($socialLinksAreEnabled)--}}
{{--									<div class="col-sm-12 col-12 p-lg-0">--}}
{{--										<div class="{!! $footerSocialClass !!}">--}}
{{--											<h4 class="footer-title {!! $footerSocialTitleClass !!}">{{ t('Follow us on') }}</h4>--}}
{{--											<ul class="list-unstyled list-inline mx-0 footer-nav social-list-footer social-list-color footer-nav-inline">--}}
{{--												@if (config('settings.social_link.facebook_page_url'))--}}
{{--												<li>--}}
{{--													<a class="icon-color fb"--}}
{{--													   data-bs-placement="top"--}}
{{--													   data-bs-toggle="tooltip"--}}
{{--													   href="{{ config('settings.social_link.facebook_page_url') }}"--}}
{{--													   title="Facebook"--}}
{{--													>--}}
{{--														<i class="fab fa-facebook"></i>--}}
{{--													</a>--}}
{{--												</li>--}}
{{--												@endif--}}
{{--												@if (config('settings.social_link.twitter_url'))--}}
{{--												<li>--}}
{{--													<a class="icon-color tw"--}}
{{--													   data-bs-placement="top"--}}
{{--													   data-bs-toggle="tooltip"--}}
{{--													   href="{{ config('settings.social_link.twitter_url') }}"--}}
{{--													   title="Twitter"--}}
{{--													>--}}
{{--														<i class="fab fa-twitter"></i>--}}
{{--													</a>--}}
{{--												</li>--}}
{{--												@endif--}}
{{--												@if (config('settings.social_link.instagram_url'))--}}
{{--													<li>--}}
{{--														<a class="icon-color pin"--}}
{{--														   data-bs-placement="top"--}}
{{--														   data-bs-toggle="tooltip"--}}
{{--														   href="{{ config('settings.social_link.instagram_url') }}"--}}
{{--														   title="Instagram"--}}
{{--														>--}}
{{--															<i class="fab fa-instagram"></i>--}}
{{--														</a>--}}
{{--													</li>--}}
{{--												@endif--}}
{{--												@if (config('settings.social_link.linkedin_url'))--}}
{{--												<li>--}}
{{--													<a class="icon-color lin"--}}
{{--													   data-bs-placement="top"--}}
{{--													   data-bs-toggle="tooltip"--}}
{{--													   href="{{ config('settings.social_link.linkedin_url') }}"--}}
{{--													   title="LinkedIn"--}}
{{--													>--}}
{{--														<i class="fab fa-linkedin"></i>--}}
{{--													</a>--}}
{{--												</li>--}}
{{--												@endif--}}
{{--												@if (config('settings.social_link.pinterest_url'))--}}
{{--												<li>--}}
{{--													<a class="icon-color pin"--}}
{{--													   data-bs-placement="top"--}}
{{--													   data-bs-toggle="tooltip"--}}
{{--													   href="{{ config('settings.social_link.pinterest_url') }}"--}}
{{--													   title="Pinterest"--}}
{{--													>--}}
{{--														<i class="fab fa-pinterest-p"></i>--}}
{{--													</a>--}}
{{--												</li>--}}
{{--												@endif--}}
{{--												@if (config('settings.social_link.tiktok_url'))--}}
{{--													<li>--}}
{{--														<a class="icon-color tt"--}}
{{--														   data-bs-placement="top"--}}
{{--														   data-bs-toggle="tooltip"--}}
{{--														   href="{{ config('settings.social_link.tiktok_url') }}"--}}
{{--														   title="Tiktok"--}}
{{--														>--}}
{{--															<i class="fab fa-tiktok"></i>--}}
{{--														</a>--}}
{{--													</li>--}}
{{--												@endif--}}
{{--											</ul>--}}
{{--										</div>--}}
{{--									</div>--}}
{{--								@endif--}}
{{--							</div>--}}
{{--						</div>--}}
{{--					@endif--}}

{{--					<div style="clear: both"></div>--}}
{{--				@endif--}}

{{--			</div>--}}
{{--			<div class="row">--}}
{{--				<?php--}}
{{--				$mtPay = '';--}}
{{--				$mtCopy = ' mt-md-4 mt-3 pt-2';--}}
{{--				?>--}}
{{--				<div class="col-12">--}}
{{--					@if (!config('settings.footer.hide_payment_plugins_logos') && isset($paymentMethods) && $paymentMethods->count() > 0)--}}
{{--						@if (config('settings.footer.hide_links'))--}}
{{--							<?php $mtPay = ' mt-0'; ?>--}}
{{--						@endif--}}
{{--						<div class="text-center payment-method-logo{{ $mtPay }}">--}}
{{--							--}}{{-- Payment Plugins --}}
{{--							@foreach($paymentMethods as $paymentMethod)--}}
{{--								@if (file_exists(plugin_path($paymentMethod->name, 'public/images/payment.png')))--}}
{{--									<img src="{{ url('images/' . $paymentMethod->name . '/payment.png') }}" alt="{{ $paymentMethod->display_name }}" title="{{ $paymentMethod->display_name }}">--}}
{{--								@endif--}}
{{--							@endforeach--}}
{{--						</div>--}}
{{--					@else--}}
{{--						<?php $mtCopy = ' mt-0'; ?>--}}
{{--						@if (!config('settings.footer.hide_links'))--}}
{{--							<?php $mtCopy = ' mt-md-4 mt-3 pt-2'; ?>--}}
{{--							<hr class="bg-secondary border-0">--}}
{{--						@endif--}}
{{--					@endif--}}

{{--					<div class="copy-info text-center mb-md-0{{ $mbCopy }}{{ $mtCopy }}">--}}
{{--						© {{ date('Y') }} {{ config('settings.app.name') }}. {{ t('all_rights_reserved') }}.--}}
{{--						@if (!config('settings.footer.hide_powered_by'))--}}
{{--							@if (config('settings.footer.powered_by_info'))--}}
{{--								{{ t('Powered by') }} {!! config('settings.footer.powered_by_info') !!}--}}
{{--							@else--}}
{{--								{{ t('Powered by') }} <a href="https://laraclassifier.com" title="LaraClassifier">LaraClassifier</a>.--}}
{{--							@endif--}}
{{--						@endif--}}
{{--					</div>--}}
{{--				</div>--}}
{{--			</div>--}}
{{--		</div>--}}
{{--	</div>--}}
{{--</footer>--}}
