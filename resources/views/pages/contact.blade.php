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
@extends('layouts.master')

@section('search')
	@parent
	@includeFirst([config('larapen.core.customizedViewPath') . 'pages.inc.contact-intro', 'pages.inc.contact-intro'])
@endsection

@section('content')

	<section class="search">
		<div class="search__container container">
			<a href="{{ \App\Helpers\UrlGen::searchWithoutQuery() }}" class="search__link link link--btn link--accent">Все объявления</a>

			<form id="search" name="search" action="{{ \App\Helpers\UrlGen::searchWithoutQuery() }}" method="GET" class="search__form form-search">
				<input name="q" placeholder="{{ t('what') }}" type="text" value="" class="input-reset input input--search">
<input name="l" value="{{session()->has('l') ? session()->get('l') : ''}}" type="text" hidden>
				<button class="btn-reset form-search__btn">
					<span class="form-search__btn-text">{{ t('find') }}</span>
					<svg class="icon icon--search">
						<use xlink:href="images/sprite.svg#search-white"></use>
					</svg>
				</button>
			</form>

			<a class="search__city link link--flex" href="#browseLocations" data-bs-toggle="modal" data-admin-code="0" data-city-id="0">
				<svg class="icon icon--geo">
					<use xlink:href="/images/sprite.svg#geo"></use>
				</svg>
				<span>{{ session()->has('location') ? session()->get('location') : 'Выберите свой город' }}</span>
			</a>
		</div>
	</section>

	<section class="callback">
		<div class="callback__container container">

			<div class="callback__grid grid grid--coll-2">
				<div class="callback__left">
					<h2 class="callback__title title title--medium">{{ t('Contact Us') }}</h2>

					@if (isset($errors) && $errors->any())
						<div class="col-xl-12">
							<div class="alert alert-danger alert-dismissible">
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ t('Close') }}"></button>
								<h5><strong>{{ t('oops_an_error_has_occurred') }}</strong></h5>
								<ul class="list list-check">
									@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
						</div>
					@endif

					@if (session()->has('flash_notification'))
						<div class="col-xl-12">
							<div class="row">
								<div class="col-xl-12">
									@include('flash::message')
								</div>
							</div>
						</div>
					@endif

					<form class="callback__form form needs-validation" method="post" action="{{ \App\Helpers\UrlGen::contact() }}">
						{!! csrf_field() !!}
						<ul class="list-reset form__list">
							<li class="form__item-flex">
								<?php $firstNameError = (isset($errors) && $errors->has('first_name')) ? ' is-invalid' : ''; ?>
								<div class="form__item">
									<label class="form__label" for="first_name">{{ t('first_name') }}</label>
									<input  id="first_name" name="first_name" type="text" placeholder="{{ t('first_name') }}"
											class="input input--default {{ $firstNameError }}" value="{{ old('first_name') }}">
								</div>
								<?php $lastNameError = (isset($errors) && $errors->has('last_name')) ? ' is-invalid' : ''; ?>
								<div class="form__item">
									<label class="form__label" for="last_name">{{ t('last_name') }}</label>
									<input id="last_name" name="last_name" type="text" placeholder="{{ t('last_name') }}"
										   class="input input--default {{ $lastNameError }}" value="{{ old('last_name') }}">
								</div>
							</li>
							<?php $companyNameError = (isset($errors) && $errors->has('company_name')) ? ' is-invalid' : ''; ?>
							<li class="form__item">
								<label class="form__label" for="company_name">{{ t('company_name') }}</label>
								<input id="company_name" name="company_name" type="text" placeholder="{{ t('company_name') }}"
									   class="input input--default {{ $companyNameError }}" value="{{ old('company_name') }}">
							</li>
							<?php $emailError = (isset($errors) && $errors->has('email')) ? ' is-invalid' : ''; ?>
							<li class="form__item required">
								<label class="form__label" for="email">{{ t('email_address') }}*</label>
								<input id="email" name="email" type="text" placeholder="{{ t('email_address') }}"
									   class="input input--default {{$emailError}}"
									   value="{{ old('email') }}"
								>
							</li>
							<?php $messageError = (isset($errors) && $errors->has('message')) ? ' is-invalid' : ''; ?>
							<li class="form__item required">
								<label class="form__label" for="message">{{ t('Message') }}*</label>
								<textarea class="form__textarea input input--default {{ $messageError }}" id="message" name="message"
										  placeholder="{{ t('Message') }}" rows="7"
								></textarea>
							</li>
							@include('layouts.inc.tools.captcha')
						</ul>

						<div class="callback__bottom">
							<a href="/page/terms" target="_blank" class="callback__link link link--grey">
								Нажимая на кнопку, вы даете согласие на обработку<br>
								<span style="text-decoration: underline;"><b>Персональных данных</b></span>
							</a>
							<button type="submit" class="form__btn btn btn--accent btn-reset" style="border-radius: 0">{{ t('submit') }}</button>
						</div>
					</form>

				</div>

				<div class="callback__right">
					<h2 class="callback__title title title--medium">Контакты</h2>
					<div class="callback__description">
						<a class="title title--xl" href="tel:89172888001">+7 (917) 288 80-01</a>
						<p>Звонок по России бесплатный</p>
					</div>
					<div class="callback__description">
						<a class="title title--xl" href="mailto:info@automost.pro">info@automost.pro</a>
						<p>Техподдержка</p>
					</div>
				</div>
			</div>
		</div>
	</section>

{{--	@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])--}}
{{--	<div class="main-container">--}}
{{--		<div class="container">--}}
{{--			<div class="row clearfix">--}}

{{--				@if (isset($errors) && $errors->any())--}}
{{--					<div class="col-xl-12">--}}
{{--						<div class="alert alert-danger alert-dismissible">--}}
{{--							<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ t('Close') }}"></button>--}}
{{--							<h5><strong>{{ t('oops_an_error_has_occurred') }}</strong></h5>--}}
{{--							<ul class="list list-check">--}}
{{--								@foreach ($errors->all() as $error)--}}
{{--									<li>{{ $error }}</li>--}}
{{--								@endforeach--}}
{{--							</ul>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				@endif--}}

{{--				@if (session()->has('flash_notification'))--}}
{{--					<div class="col-xl-12">--}}
{{--						<div class="row">--}}
{{--							<div class="col-xl-12">--}}
{{--								@include('flash::message')--}}
{{--							</div>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				@endif--}}

{{--				<div class="col-md-12">--}}
{{--					<div class="contact-form">--}}
{{--						<h5 class="list-title gray mt-0">--}}
{{--							<strong>{{ t('Contact Us') }}</strong>--}}
{{--						</h5>--}}

{{--						<form class="form-horizontal needs-validation" method="post" action="{{ \App\Helpers\UrlGen::contact() }}">--}}
{{--							{!! csrf_field() !!}--}}
{{--							<fieldset>--}}
{{--								<div class="row">--}}
{{--									<div class="col-md-6 mb-3">--}}
{{--										<?php $firstNameError = (isset($errors) && $errors->has('first_name')) ? ' is-invalid' : ''; ?>--}}
{{--										<div class="form-floating required">--}}
{{--											<input id="first_name" name="first_name" type="text" placeholder="{{ t('first_name') }}"--}}
{{--												   class="form-control{{ $firstNameError }}" value="{{ old('first_name') }}">--}}
{{--											<label for="first_name">{{ t('first_name') }}</label>--}}
{{--										</div>--}}
{{--									</div>--}}

{{--									<div class="col-md-6 mb-3">--}}
{{--										<?php $lastNameError = (isset($errors) && $errors->has('last_name')) ? ' is-invalid' : ''; ?>--}}
{{--										<div class="form-floating required">--}}
{{--											<input id="last_name" name="last_name" type="text" placeholder="{{ t('last_name') }}"--}}
{{--												   class="form-control{{ $lastNameError }}" value="{{ old('last_name') }}">--}}
{{--											<label for="last_name">{{ t('last_name') }}</label>--}}
{{--										</div>--}}
{{--									</div>--}}

{{--									<div class="col-md-6 mb-3">--}}
{{--										<?php $companyNameError = (isset($errors) && $errors->has('company_name')) ? ' is-invalid' : ''; ?>--}}
{{--										<div class="form-floating required">--}}
{{--											<input id="company_name" name="company_name" type="text" placeholder="{{ t('company_name') }}"--}}
{{--												   class="form-control{{ $companyNameError }}" value="{{ old('company_name') }}">--}}
{{--											<label for="company_name">{{ t('company_name') }}</label>--}}
{{--										</div>--}}
{{--									</div>--}}

{{--									<div class="col-md-6 mb-3">--}}
{{--										<?php $emailError = (isset($errors) && $errors->has('email')) ? ' is-invalid' : ''; ?>--}}
{{--										<div class="form-floating required">--}}
{{--											<input id="email" name="email" type="text" placeholder="{{ t('email_address') }}" class="form-control{{ $emailError }}"--}}
{{--												   value="{{ old('email') }}">--}}
{{--											<label for="email">{{ t('email_address') }}</label>--}}
{{--										</div>--}}
{{--									</div>--}}

{{--									<div class="col-md-12 mb-3">--}}
{{--										<?php $messageError = (isset($errors) && $errors->has('message')) ? ' is-invalid' : ''; ?>--}}
{{--										<div class="form-floating required">--}}
{{--											<textarea class="form-control{{ $messageError }}" id="message" name="message" placeholder="{{ t('Message') }}"--}}
{{--													  rows="7" style="height: 150px">{{ old('message') }}</textarea>--}}
{{--											<label for="message">{{ t('Message') }}</label>--}}
{{--										</div>--}}
{{--									</div>--}}

{{--									<div class="col-md-12">--}}
{{--										@include('layouts.inc.tools.captcha')--}}
{{--									</div>--}}

{{--									<div class="col-md-12 mb-3">--}}
{{--										<button type="submit" class="btn btn-primary btn-lg">{{ t('submit') }}</button>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--							</fieldset>--}}
{{--						</form>--}}
{{--					</div>--}}
{{--				</div>--}}
{{--			</div>--}}
{{--		</div>--}}
{{--	</div>--}}
@endsection

@section('after_scripts')
	<script src="{{ url('assets/js/form-validation.js') }}"></script>
@endsection
