{{--
 * LaraClassifier - Classified Ads Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com
 * Author: BeDigit | https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - https://codecanyon.net/licenses/standard
--}}
<?php
	$plugins = array_keys((array)config('plugins'));
	$publicDisk = \Storage::disk(config('filesystems.default'));
?>
<!DOCTYPE html>
<html lang="{{ ietfLangTag(config('app.locale')) }}"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>
<head>
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@includeFirst([config('larapen.core.customizedViewPath') . 'common.meta-robots', 'common.meta-robots'])
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="apple-mobile-web-app-title" content="{{ config('settings.app.name') }}">
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ $publicDisk->url('app/default/ico/apple-touch-icon-144-precomposed.png') . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ $publicDisk->url('app/default/ico/apple-touch-icon-114-precomposed.png') . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ $publicDisk->url('app/default/ico/apple-touch-icon-72-precomposed.png') . getPictureVersion() }}">
	<link rel="apple-touch-icon-precomposed" href="{{ $publicDisk->url('app/default/ico/apple-touch-icon-57-precomposed.png') . getPictureVersion() }}">
	<link rel="shortcut icon" href="{{ config('settings.app.favicon_url') }}">
	<title>{!! MetaTag::get('title') !!}</title>
	{!! MetaTag::tag('description') !!}{!! MetaTag::tag('keywords') !!}
	<link rel="canonical" href="{{ request()->fullUrl() }}"/>
	{{-- Specify a default target for all hyperlinks and forms on the page --}}
	<base target="_top"/>
	@if (isset($post))
		@if (isVerifiedPost($post))
			@if (config('services.facebook.client_id'))
				<meta property="fb:app_id" content="{{ config('services.facebook.client_id') }}" />
			@endif
			{!! $og->renderTags() !!}
			{!! MetaTag::twitterCard() !!}
		@endif
	@else
		@if (config('services.facebook.client_id'))
			<meta property="fb:app_id" content="{{ config('services.facebook.client_id') }}" />
		@endif
		{!! $og->renderTags() !!}
		{!! MetaTag::twitterCard() !!}
	@endif
	@include('feed::links')
	{!! seoSiteVerification() !!}

	@if (file_exists(public_path('manifest.json')))
		<link rel="manifest" href="/manifest.json">
	@endif

	@stack('before_styles_stack')
    @yield('before_styles')

	@if (config('lang.direction') == 'rtl')
		<link href="https://fonts.googleapis.com/css?family=Cairo|Changa" rel="stylesheet">
		<link href="{{ url(mix('css/app.rtl.css')) }}" rel="stylesheet">
	@else
		<link href="{{ url(mix('css/app.css')) }}" rel="stylesheet">
	@endif
	@if (config('plugins.detectadsblocker.installed'))
		<link href="{{ url('assets/detectadsblocker/css/style.css') . getPictureVersion() }}" rel="stylesheet">
	@endif

	<?php
	$skinQs = (request()->filled('skin')) ? '?skin=' . request()->get('skin') : null;
	if (request()->filled('display')) {
		$skinQs .= (!empty($skinQs)) ? '&' : '?';
		$skinQs .= 'display=' . request()->get('display');
	}
	?>
	<link href="{{ url('common/css/style.css') . $skinQs . getPictureVersion(!empty($skinQs)) }}" rel="stylesheet">
	<?php
		if (isset($getSearchFormOp) && is_array($getSearchFormOp)) {
			$homeStyle = view('common.css.homepage', ['getSearchFormOp', $getSearchFormOp])->render();
			echo $homeStyle;
		}
	?>
	<link href="{{ url()->asset('css/custom.css') . getPictureVersion() }}" rel="stylesheet">

	@stack('after_styles_stack')
    @yield('after_styles')

	@if (isset($plugins) && !empty($plugins))
		@foreach($plugins as $plugin)
			@yield($plugin . '_styles')
		@endforeach
	@endif

    @if (config('settings.style.custom_css'))
		{!! printCss(config('settings.style.custom_css')) . "\n" !!}
    @endif

	@if (config('settings.other.js_code'))
		{!! printJs(config('settings.other.js_code')) . "\n" !!}
	@endif

	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
	<![endif]-->

	<script>
		paceOptions = {
			elements: true
		};
	</script>
	<script src="{{ url()->asset('assets/plugins/pace/0.4.17/pace.min.js') }}"></script>
	<script src="{{ url()->asset('assets/plugins/modernizr/modernizr-custom.js') }}"></script>

	@yield('captcha_head')
	@section('recaptcha_head')
		@if (
			config('settings.security.captcha') == 'recaptcha'
			&& config('recaptcha.site_key')
			&& config('recaptcha.secret_key')
		)
			<style>
				.is-invalid .g-recaptcha iframe,
				.has-error .g-recaptcha iframe {
					border: 1px solid #f85359;
				}
			</style>
			@if (config('recaptcha.version') == 'v3')
				<script type="text/javascript">
					function myCustomValidation(token){
						/* read HTTP status */
						/* console.log(token); */

						if ($('#gRecaptchaResponse').length) {
							$('#gRecaptchaResponse').val(token);
						}
					}
				</script>
				{!! recaptchaApiV3JsScriptTag([
					'action' 		    => request()->path(),
					'custom_validation' => 'myCustomValidation'
				]) !!}
			@else
				{!! recaptchaApiJsScriptTag() !!}
			@endif
		@endif
	@show
</head>
<body class="skin">
<div id="wrapper">

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
	<div class="bottom__mobile__menu">
		<a class="bottom__mobile__menu__plus" href="{{ $addListingUrl }}" {!! $addListingAttr !!}>
			<img src="/images/icon/plus-circle.svg">
		</a>

		<div class="bottom__mobile__menu__container">
			<a class="bottom__mobile__menu__item" href="/search">
				<img src="/images/icon/search.svg">
				<span>Поиск</span>
			</a>
			<a class="bottom__mobile__menu__item" @if(auth()->check()) href="/account/posts/favourite" @else href="#quickLogin" data-bs-toggle="modal" @endif>
				<img src="/images/icon/heart.svg">
				<span>Избранное</span>
			</a>
			<a class="bottom__mobile__menu__item position-relative" @if(auth()->check()) href="/account/messages" @else href="#quickLogin" data-bs-toggle="modal" @endif>
				@if(auth()->check())
					<div class="count-new-message position-absolute" style="display: none"></div>
				@endif
				<img src="/images/icon/message.svg">
				<span>Сообщения</span>
			</a>
			<a class="bottom__mobile__menu__item" @if(auth()->check()) href="/account" @else href="#quickLogin" data-bs-toggle="modal" @endif>
				<img src="/images/icon/profile.svg">
				<span>{{ t('profile') }}</span>
			</a>
		</div>
	</div>

	@section('header')
		@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.header', 'layouts.inc.header'])
	@show

	@section('search')
	@show

	@section('wizard')
	@show

	@if (isset($siteCountryInfo))
{{--		<section class="message-avtorization">--}}
{{--			<div class="container">--}}
{{--				<p class="message-avtorization__content">--}}
{{--					<a href="#" class="message-avtorization__link">--}}
{{--						{!! $siteCountryInfo !!}--}}
{{--					</a>.--}}
{{--				</p>--}}
{{--			</div>--}}
{{--		</section>--}}
{{--		<div class="p-0 mt-lg-4 mt-md-3 mt-3"></div>--}}
{{--		<div class="container">--}}
{{--			<div class="row">--}}
{{--				<div class="col-12">--}}
{{--					<div class="alert alert-warning alert-dismissible mb-3">--}}
{{--						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ t('Close') }}"></button>--}}
{{--						{!! $siteCountryInfo !!}--}}
{{--					</div>--}}
{{--				</div>--}}
{{--			</div>--}}
{{--		</div>--}}
	@endif

	@yield('content')

	@section('info')
	@show

	@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.auto', 'layouts.inc.advertising.auto'])

	@section('footer')
		@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.footer', 'layouts.inc.footer'])
	@show

</div>
@section('modal_location')
@show
@section('modal_abuse')
@show
@section('modal_message')
@show

@includeWhen(!auth()->check(), 'auth.login.inc.modal')
@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.modal.change-country', 'layouts.inc.modal.change-country'])
@includeFirst([config('larapen.core.customizedViewPath') . 'layouts.inc.modal.error', 'layouts.inc.modal.error'])
@include('cookie-consent::index')

@if (config('plugins.detectadsblocker.installed'))
	@if (view()->exists('detectadsblocker::modal'))
		@include('detectadsblocker::modal')
	@endif
@endif

@include('common.js.init')

<script>
	var countryCode = '{{ config('country.code', 0)  }}';
	var timerNewMessagesChecking = {{ (int)config('settings.other.timer_new_messages_checking', 0)  }};

	/* Complete langLayout translations */
	langLayout.hideMaxListItems = {
		'moreText': "{{ t('View More') }}",
		'lessText': "{{ t('View Less') }}"
	};
	langLayout.select2 = {
		errorLoading: function(){
			return "{!! t('The results could not be loaded') !!}"
		},
		inputTooLong: function(e){
			var t = e.input.length - e.maximum, n = {!! t('Please delete X character') !!};
			return t != 1 && (n += 's'),n
		},
		inputTooShort: function(e){
			var t = e.minimum - e.input.length, n = {!! t('Please enter X or more characters') !!};
			return n
		},
		loadingMore: function(){
			return "{!! t('Loading more results') !!}"
		},
		maximumSelected: function(e){
			var t = {!! t('You can only select N item') !!};
			return e.maximum != 1 && (t += 's'),t
		},
		noResults: function(){
			return "{!! t('No results found') !!}"
		},
		searching: function(){
			return "{!! t('Searching') !!}"
		}
	};
	var loadingWd = '{{ t('loading_wd') }}';

	{{-- The app's default auth field --}}
	var defaultAuthField = '{{ old('auth_field', getAuthField()) }}';
	var phoneCountry = '{{ config('country.code') }}';

	{{-- Others global variables --}}
	var fakeLocationsResults = "{{ config('settings.list.fake_locations_results', 0) }}";
	var stateOrRegionKeyword = "{{ t('area') }}";
	var errorText = {
		errorFound: "{{ t('error_found') }}"
	};
	var refreshBtnText = "{{ t('refresh') }}";
</script>

@stack('before_scripts_stack')
@yield('before_scripts')

<script src="{{ url('common/js/intl-tel-input/countries.js') . getPictureVersion() }}"></script>
<script src="{{ url(mix('js/app.js')) }}"></script>

@if (config('settings.optimization.lazy_loading_activation') == 1)
	<script src="{{ url()->asset('assets/plugins/lazysizes/lazysizes.min.js') }}" async=""></script>
@endif
@if (file_exists(public_path() . '/assets/plugins/select2/js/i18n/'.config('app.locale').'.js'))
	<script src="{{ url()->asset('assets/plugins/select2/js/i18n/'.config('app.locale').'.js') }}"></script>
@endif
@if (config('plugins.detectadsblocker.installed'))
	<script src="{{ url('assets/detectadsblocker/js/script.js') . getPictureVersion() }}"></script>
@endif
<script>
	$(document).ready(function () {
		{{-- Searchable Select Boxes --}}
		let largeDataSelect2Params = {
			width: '100%',
			dropdownAutoWidth: 'true'
		};
		{{-- Simple Select Boxes --}}
		let select2Params = {...largeDataSelect2Params};
		{{-- Hiding the search box --}}
		select2Params.minimumResultsForSearch = Infinity;

		if (typeof langLayout !== 'undefined' && typeof langLayout.select2 !== 'undefined') {
			select2Params.language = langLayout.select2;
			largeDataSelect2Params.language = langLayout.select2;
		}

		$('.selecter').select2(select2Params);
		$('.large-data-selecter').select2(largeDataSelect2Params);

		{{-- Social Share --}}
		$('.share').ShareLink({
			title: '{{ addslashes(MetaTag::get('title')) }}',
			text: '{!! addslashes(MetaTag::get('title')) !!}',
			url: '{!! request()->fullUrl() !!}',
			width: 640,
			height: 480
		});

		{{-- Modal Login --}}
		@if (isset($errors) && $errors->any())
			@if ($errors->any() && old('quickLoginForm')=='1')
				{{-- Re-open the modal if error occured --}}
				openLoginModal();
			@endif
		@endif
	});

</script>
<script src="{{ url('js/custom.js') }}"></script>
<script src="{{ url('js/plugins/jquery.mask.min.js') }}"></script>

<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@21.12.0/dist/js/jquery.suggestions.min.js"></script>

<script>

	/** Смена лого при скролле */
	$(function() {
		let mobile_icons_logo = $('.mobile-icons-logo img')
		$(window).scroll(function() {
			if($(this).scrollTop() > 50) {
				mobile_icons_logo.attr('src','/images/logo-2.svg');
			} else {
				mobile_icons_logo.attr('src','/images/logo-full.svg');
			}
		});
	});

	/** Аккордеон для хедера */
	var acc = document.getElementsByClassName("accordion");
	var i;
	for (i = 0; i < acc.length; i++) {
		acc[i].addEventListener("click", function() {
			this.classList.toggle("active");
			var panel = this.nextElementSibling;
			if (panel.style.maxHeight) {
				panel.style.maxHeight = null;
			} else {
				panel.style.maxHeight = panel.scrollHeight + "px";
			}
		});
	}

	$("[name='inn']").mask('0000000000')

	$("[id='cf.41']").suggestions({
		token: "{{env('DADATA_API_TOKEN', '8122273c27d35ba75910a900bfc2e4a9b3925e1a')}}",
		type: "ADDRESS",
		hint: false,
		bounds: "city-settlement"
	})
	$("[id='cf.42']").suggestions({
		token: "{{env('DADATA_API_TOKEN', '8122273c27d35ba75910a900bfc2e4a9b3925e1a')}}",
		type: "ADDRESS",
		hint: false,
		bounds: "city-settlement"
	})

	$(document).ready(function(){
		$('[type="tel"]').mask('(000) 000-00-00')
	})

</script>

@stack('after_scripts_stack')
@yield('after_scripts')
@yield('captcha_footer')


@if (isset($plugins) && !empty($plugins))
	@foreach($plugins as $plugin)
		@yield($plugin . '_scripts')
	@endforeach
@endif

@if (config('settings.footer.tracking_code'))
	{!! printJs(config('settings.footer.tracking_code')) . "\n" !!}
@endif
</body>
</html>
