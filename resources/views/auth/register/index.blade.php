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

@php
$face_type_list = \DB::table('face_type_list')
->where('active', true)
->get();

@endphp

@extends('layouts.master')
@section('content')
	<section class="search">
		<div class="search__container container">
			<a href="{{ \App\Helpers\UrlGen::searchWithoutQuery() }}" class="search__link link link--btn link--accent">{{ t('all_ads') }}</a>

			<form id="search" name="search" action="{{ \App\Helpers\UrlGen::searchWithoutQuery() }}" method="GET" class="search__form form-search">
				<input name="q" placeholder="{{ t('what') }}" type="text" value="" class="input-reset input input--search">
<input name="l" value="{{session()->has('l') ? session()->get('l') : ''}}" type="text" hidden>
				<button class="btn-reset form-search__btn">
					<span class="form-search__btn-text">{{ t('find') }}</span>
					<svg class="icon icon--search">
						<use xlink:href="/images/sprite.svg#search-white"></use>
					</svg>
				</button>
			</form>

			<a class="search__city link link--flex" href="#browseLocations" data-bs-toggle="modal" data-admin-code="0" data-city-id="0">
				<svg class="icon icon--geo">
					<use xlink:href="/images/sprite.svg#geo"></use>
				</svg>
				<span>{{ session()->has('location') ? session()->get('location') : t('choose_your_city') }}</span>
			</a>
		</div>
	</section>
	<div class="main-container">
		<div class="container">
			<div class="row">

				@if (session()->has('flash_notification'))
					<div class="col-12">
						@include('flash::message')
					</div>
				@endif

				@if (isset($errors) && $errors->any())
					<div class="col-12">
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

					<section class="registration">
						<div class="registration__container">
							<h1 class="registration__title title title--medium">{{ t('register_on_the_site') }} {{ config('settings.app.name') }}</h1>

							<div class="registration__grid grid grid--coll-2">
								<div class="registration__left">
									<div class="form form__container">
										<form method="POST" class="form__registration" id="signupForm" action="{{ url()->current() }}">
											<ul class="list-reset form__list">

												<div class="row mb-3 required">
													<label class="col-md-3 col-form-label" for="auth_field"> {{ t('face_type') }} <sup>*</sup></label>
													<div class="col-md-9">
														@foreach($face_type_list as $item)
															<div class="form-check form-check-inline pt-2">
																<input name="face_type"
																	   value="{{$item->id}}"
																	   class="form-check-input auth-field-input"
																	   type="radio"
																		@checked(old('face_type') == $item->id)
																>
																<label class="form-check-label mb-0">
																	{{$item->name_short}}
																</label>
															</div>
														@endforeach
														<div class="form-text text-muted">
															{{ t('choice_your_variant') }}
													</div>
												</div>

												<li class="form__item entity__block required" style="display: none">
													{{-- inn --}}
													<label class="form__label">ИНН <sup>*</sup></label>
													<?php $nameError = (isset($errors) && $errors->has('inn')) ? ' is-invalid' : ''; ?>
													<input name="inn" placeholder="Введите 10-цифр"  type="number" maxlength="12" class="form-control input input--default {{ $nameError }}" value="{{ old('inn') }}">
												</li>

												@php
													$user_type_list = \DB::table('user_type_list')
                                                    ->where('active', true)
                                                    ->get();
												@endphp
												<div class="row mb-3 required entity__block" style="display: none">
													<label class="col-md-12 col-form-label">
														Кто вы <sup>*</sup>
													</label>
													<div class="col-md-12 col-lg-12">
														<select name="user_type" class="form-control large-data-selecter">
															@foreach ($user_type_list as $key => $item)
																<option value="{{ $key }}">
																	{{ $item->name }}
																</option>
															@endforeach
														</select>
													</div>
												</div>

												<li class="form__item required">
													{{-- name --}}
													<label class="form__label">{{t('full_name')}}</label>
													<?php $nameError = (isset($errors) && $errors->has('name')) ? ' is-invalid' : ''; ?>
													<input name="name" placeholder="{{ t('Name') }}"  type="text" class="form-control input input--default {{ $nameError }}" value="{{ old('name') }}">
												</li>
												@php
													$authFieldError = (isset($errors) && $errors->has('auth_field')) ? ' is-invalid' : '';
												@endphp
												{{-- country_code --}}
												@if (empty(config('country.code')))
													@php
														$countryCodeError = (isset($errors) && $errors->has('country_code')) ? ' is-invalid' : '';
                                                        $countryCodeValue = (!empty(config('ipCountry.code'))) ? config('ipCountry.code') : 0;
													@endphp
													<div class="row mb-3 required">
														<label class="col-md-3 col-form-label{{ $countryCodeError }}" for="country_code">
															{{ t('your_country') }}
														</label>
														<div class="col-md-9 col-lg-6">
															<select id="countryCode" name="country_code" class="form-control large-data-selecter{{ $countryCodeError }}">
																<option value="0" @selected(empty(old('country_code')))>
																	{{ t('Select') }}
																</option>
																@foreach ($countries as $code => $item)
																	<option value="{{ $code }}" @selected(old('country_code', $countryCodeValue) == $code)>
																		{{ $item->get('name') }}
																	</option>
																@endforeach
															</select>
														</div>
													</div>
												@else
													<input id="countryCode" name="country_code" type="hidden" value="{{ config('country.code') }}">
												@endif

												@php
													$forceToDisplay = isBothAuthFieldsCanBeDisplayed() ? ' force-to-display' : '';
												@endphp

												{{-- username --}}
												@php
													$usernameIsEnabled = !config('larapen.core.disable.username');
												@endphp
												@if ($usernameIsEnabled)
														<?php $usernameError = (isset($errors) && $errors->has('username')) ? ' is-invalid' : ''; ?>
													<li class="form__item">
														<label class="form__label" for="username">{{ t('Username') }}</label>
														<input id="username"
															   name="username"
															   type="text"
															   placeholder="{{ t('Username') }}"
															   value="{{ old('username') }}"
															   class="form-control input input--default {{ $usernameError }}"
														>
													</li>
												@endif

												{{-- email --}}
												@php
													$emailError = (isset($errors) && $errors->has('email')) ? ' is-invalid' : '';
												@endphp
												<li class="form__item required{{ $forceToDisplay }}">
													<label class="form__label" for="email">{{ t('email') }}</label>
													<input id="email" name="email"
														   type="email"
														   class="form-control input input--default {{ $emailError }}"
														   placeholder="{{ t('email_address') }}"
														   value="{{ old('email') }}"
													>
												</li>

												{{-- phone --}}
												@php
													$phoneError = (isset($errors) && $errors->has('phone')) ? ' is-invalid' : '';
                                                    $phoneCountryValue = config('country.code');
												@endphp
												<li class="form__item required{{ $forceToDisplay }}">
													<label class="form__label" for="phone">{{ t('phone_number') }}</label>
													<input id="phone" name="phone"
														   class="form-control input input--default{{ $phoneError }}"
														   type="tel"
														   value="{{ phoneE164(old('phone'), old('phone_country', $phoneCountryValue)) }}"
														   autocomplete="off"
														   style="width: 100%"
													>
													<input name="phone_country" type="hidden" value="{{ old('phone_country', $phoneCountryValue) }}">
												</li>

												{{-- auth_field (as notification channel) --}}
												@php
													$authFields = getAuthFields(true);
                                                    $authFieldError = (isset($errors) && $errors->has('auth_field')) ? ' is-invalid' : '';
                                                    $usersCanChooseNotifyChannel = isUsersCanChooseNotifyChannel();
                                                    $authFieldValue = ($usersCanChooseNotifyChannel) ? (old('auth_field', getAuthField())) : getAuthField();
												@endphp
												@if ($usersCanChooseNotifyChannel)
													<div class="row mb-3 required">
														<label class="col-md-3 col-form-label" for="auth_field">{{ t('notifications_channel') }} <sup>*</sup></label>
														<div class="col-md-9">
															@foreach ($authFields as $iAuthField => $notificationType)
																<div class="form-check form-check-inline pt-2">
																	<input name="auth_field"
																		   id="{{ $iAuthField }}AuthField"
																		   value="{{ $iAuthField }}"
																		   class="form-check-input auth-field-input{{ $authFieldError }}"
																		   type="radio" @checked($authFieldValue == $iAuthField)
																	>
																	<label class="form-check-label mb-0" for="{{ $iAuthField }}AuthField">
																		{{ $notificationType }}
																	</label>
																</div>
															@endforeach
															<div class="form-text text-muted">
																{{ t('notifications_channel_hint') }}
															</div>
														</div>
													</div>
												@else
													<input id="{{ $authFieldValue }}AuthField" name="auth_field" type="hidden" value="{{ $authFieldValue }}">
												@endif

												{{-- password --}}
												<?php $passwordError = (isset($errors) && $errors->has('password')) ? ' is-invalid' : ''; ?>
												<li class="form__item required">
													<label class="form__label" for="password">{{ t('password') }} <span class="text-muted">({{ t('at_least_num_characters', ['num' => config('settings.security.password_min_length', 6)]) }})</span> </label>
													<div class="input-group show-pwd-group mb-2">
														<input id="password" name="password"
															   type="password"
															   class="form-control input input--default{{ $passwordError }}"
															   placeholder="{{ t('password') }}"
															   autocomplete="new-password"
															   style="width: 100%; border-top-right-radius: 8px; border-bottom-right-radius: 8px"
														>
														<span class="icon-append show-pwd">
															<button type="button" class="eyeOfPwd">
																<i class="far fa-eye-slash"></i>
															</button>
														</span>
													</div>
												</li>
												<li class="form__item">
													<input id="passwordConfirmation" name="password_confirmation"
														   type="password"
														   class="form-control input input--default{{ $passwordError }}"
														   placeholder="{{ t('Password Confirmation') }}"
														   autocomplete="off"
													>
												</li>
												<li class="form__item">
													<p style="color: #686868">{{ t('click_the_button_agree_obrabotka') }}</p>
												</li>
											</ul>

											<input name="accept_terms" id="acceptTerms"
												   class="form-check-input"
												   value="1"
												   type="hidden"
											>

											@include('layouts.inc.tools.captcha')

											<button id="signupBtn" class="form__btn btn btn--accent btn--center btn-reset"> {{ t('register') }} </button>
										</form>
									</div>
								</div>
								<div class="registration__right">
									<div class="registration__description">
										<svg class="registration__icon icon icon--registration">
											<use xlink:href="/images/sprite.svg#burger"></use>
										</svg>
										<p class="registration__title title title--medium">{{ t('post_free_ads') }}</p>
										<p>{{ t('post_free_ads_desc') }}</p>
									</div>
									<div class="registration__description">
										<svg class="registration__icon icon icon--registration">
											<use xlink:href="/images/sprite.svg#basket"></use>
										</svg>
										<p class="registration__title title title--medium">{{ t('make_your_purchases') }}</p>
										<p>{{ t('make_your_purchases_desc') }}</p>
									</div>
								</div>
							</div>
						</div>
					</section>

{{--					--}}
{{--				<div class="col-md-8 page-content">--}}
{{--					<div class="inner-box">--}}
{{--						<h2 class="title-2">--}}
{{--							<strong>--}}
{{--								{{ t('create_your_account_it_is_quick') }}--}}
{{--							</strong>--}}
{{--						</h2>--}}

{{--						@includeFirst([config('larapen.core.customizedViewPath') . 'auth.login.inc.social', 'auth.login.inc.social'])--}}

{{--						@php--}}
{{--							$mtAuth = !socialLoginIsEnabled() ? ' mt-5' : ' mt-4';--}}
{{--						@endphp--}}
{{--						<div class="row{{ $mtAuth }}">--}}
{{--							<div class="col-12">--}}
{{--								<form id="signupForm" class="form-horizontal" method="POST" action="{{ url()->current() }}">--}}
{{--									{!! csrf_field() !!}--}}
{{--									<fieldset>--}}

{{--										--}}{{-- name --}}
{{--										<?php $nameError = (isset($errors) && $errors->has('name')) ? ' is-invalid' : ''; ?>--}}
{{--										<div class="row mb-3 required">--}}
{{--											<label class="col-md-3 col-form-label">{{ t('Name') }} <sup>*</sup></label>--}}
{{--											<div class="col-md-9 col-lg-6">--}}
{{--												<input name="name" placeholder="{{ t('Name') }}" class="form-control input-md{{ $nameError }}" type="text" value="{{ old('name') }}">--}}
{{--											</div>--}}
{{--										</div>--}}

{{--										--}}{{-- country_code --}}
{{--										@if (empty(config('country.code')))--}}
{{--											@php--}}
{{--												$countryCodeError = (isset($errors) && $errors->has('country_code')) ? ' is-invalid' : '';--}}
{{--												$countryCodeValue = (!empty(config('ipCountry.code'))) ? config('ipCountry.code') : 0;--}}
{{--											@endphp--}}
{{--											<div class="row mb-3 required">--}}
{{--												<label class="col-md-3 col-form-label{{ $countryCodeError }}" for="country_code">--}}
{{--													{{ t('your_country') }} <sup>*</sup>--}}
{{--												</label>--}}
{{--												<div class="col-md-9 col-lg-6">--}}
{{--													<select id="countryCode" name="country_code" class="form-control large-data-selecter{{ $countryCodeError }}">--}}
{{--														<option value="0" @selected(empty(old('country_code')))>--}}
{{--															{{ t('Select') }}--}}
{{--														</option>--}}
{{--														@foreach ($countries as $code => $item)--}}
{{--															<option value="{{ $code }}" @selected(old('country_code', $countryCodeValue) == $code)>--}}
{{--																{{ $item->get('name') }}--}}
{{--															</option>--}}
{{--														@endforeach--}}
{{--													</select>--}}
{{--												</div>--}}
{{--											</div>--}}
{{--										@else--}}
{{--											<input id="countryCode" name="country_code" type="hidden" value="{{ config('country.code') }}">--}}
{{--										@endif--}}

{{--										--}}{{-- auth_field (as notification channel) --}}
{{--										@php--}}
{{--											$authFields = getAuthFields(true);--}}
{{--											$authFieldError = (isset($errors) && $errors->has('auth_field')) ? ' is-invalid' : '';--}}
{{--											$usersCanChooseNotifyChannel = isUsersCanChooseNotifyChannel();--}}
{{--											$authFieldValue = ($usersCanChooseNotifyChannel) ? (old('auth_field', getAuthField())) : getAuthField();--}}
{{--										@endphp--}}
{{--										@if ($usersCanChooseNotifyChannel)--}}
{{--											<div class="row mb-3 required">--}}
{{--												<label class="col-md-3 col-form-label" for="auth_field">{{ t('notifications_channel') }} <sup>*</sup></label>--}}
{{--												<div class="col-md-9">--}}
{{--													@foreach ($authFields as $iAuthField => $notificationType)--}}
{{--														<div class="form-check form-check-inline pt-2">--}}
{{--															<input name="auth_field"--}}
{{--																   id="{{ $iAuthField }}AuthField"--}}
{{--																   value="{{ $iAuthField }}"--}}
{{--																   class="form-check-input auth-field-input{{ $authFieldError }}"--}}
{{--																   type="radio" @checked($authFieldValue == $iAuthField)--}}
{{--															>--}}
{{--															<label class="form-check-label mb-0" for="{{ $iAuthField }}AuthField">--}}
{{--																{{ $notificationType }}--}}
{{--															</label>--}}
{{--														</div>--}}
{{--													@endforeach--}}
{{--													<div class="form-text text-muted">--}}
{{--														{{ t('notifications_channel_hint') }}--}}
{{--													</div>--}}
{{--												</div>--}}
{{--											</div>--}}
{{--										@else--}}
{{--											<input id="{{ $authFieldValue }}AuthField" name="auth_field" type="hidden" value="{{ $authFieldValue }}">--}}
{{--										@endif--}}

{{--										@php--}}
{{--											$forceToDisplay = isBothAuthFieldsCanBeDisplayed() ? ' force-to-display' : '';--}}
{{--										@endphp--}}

{{--										--}}{{-- email --}}
{{--										@php--}}
{{--											$emailError = (isset($errors) && $errors->has('email')) ? ' is-invalid' : '';--}}
{{--										@endphp--}}
{{--										<div class="row mb-3 auth-field-item required{{ $forceToDisplay }}">--}}
{{--											<label class="col-md-3 col-form-label pt-0" for="email">{{ t('email') }}--}}
{{--												@if (getAuthField() == 'email')--}}
{{--													<sup>*</sup>--}}
{{--												@endif--}}
{{--											</label>--}}
{{--											<div class="col-md-9 col-lg-6">--}}
{{--												<div class="input-group">--}}
{{--													<span class="input-group-text"><i class="far fa-envelope"></i></span>--}}
{{--													<input id="email" name="email"--}}
{{--														   type="email"--}}
{{--														   class="form-control{{ $emailError }}"--}}
{{--														   placeholder="{{ t('email_address') }}"--}}
{{--														   value="{{ old('email') }}"--}}
{{--													>--}}
{{--												</div>--}}
{{--											</div>--}}
{{--										</div>--}}

{{--										--}}{{-- phone --}}
{{--										@php--}}
{{--											$phoneError = (isset($errors) && $errors->has('phone')) ? ' is-invalid' : '';--}}
{{--											$phoneCountryValue = config('country.code');--}}
{{--										@endphp--}}
{{--										<div class="row mb-3 auth-field-item required{{ $forceToDisplay }}">--}}
{{--											<label class="col-md-3 col-form-label pt-0" for="phone">{{ t('phone_number') }}--}}
{{--												@if (getAuthField() == 'phone')--}}
{{--													<sup>*</sup>--}}
{{--												@endif--}}
{{--											</label>--}}
{{--											<div class="col-md-9 col-lg-6">--}}
{{--												<input id="phone" name="phone"--}}
{{--													   class="form-control input-md{{ $phoneError }}"--}}
{{--													   type="tel"--}}
{{--													   value="{{ phoneE164(old('phone'), old('phone_country', $phoneCountryValue)) }}"--}}
{{--													   autocomplete="off"--}}
{{--												>--}}
{{--												<input name="phone_country" type="hidden" value="{{ old('phone_country', $phoneCountryValue) }}">--}}
{{--											</div>--}}
{{--										</div>--}}

{{--										--}}{{-- username --}}
{{--										@php--}}
{{--											$usernameIsEnabled = !config('larapen.core.disable.username');--}}
{{--										@endphp--}}
{{--										@if ($usernameIsEnabled)--}}
{{--											<?php $usernameError = (isset($errors) && $errors->has('username')) ? ' is-invalid' : ''; ?>--}}
{{--											<div class="row mb-3 required">--}}
{{--												<label class="col-md-3 col-form-label" for="username">{{ t('Username') }}</label>--}}
{{--												<div class="col-md-9 col-lg-6">--}}
{{--													<div class="input-group">--}}
{{--														<span class="input-group-text"><i class="far fa-user"></i></span>--}}
{{--														<input id="username"--}}
{{--															   name="username"--}}
{{--															   type="text"--}}
{{--															   class="form-control{{ $usernameError }}"--}}
{{--															   placeholder="{{ t('Username') }}"--}}
{{--															   value="{{ old('username') }}"--}}
{{--														>--}}
{{--													</div>--}}
{{--												</div>--}}
{{--											</div>--}}
{{--										@endif--}}

{{--										--}}{{-- password --}}
{{--										<?php $passwordError = (isset($errors) && $errors->has('password')) ? ' is-invalid' : ''; ?>--}}
{{--										<div class="row mb-3 required">--}}
{{--											<label class="col-md-3 col-form-label" for="password">{{ t('password') }} <sup>*</sup></label>--}}
{{--											<div class="col-md-9 col-lg-6">--}}
{{--												<div class="input-group show-pwd-group mb-2">--}}
{{--													<input id="password" name="password"--}}
{{--														   type="password"--}}
{{--														   class="form-control{{ $passwordError }}"--}}
{{--														   placeholder="{{ t('password') }}"--}}
{{--														   autocomplete="new-password"--}}
{{--													>--}}
{{--													<span class="icon-append show-pwd">--}}
{{--														<button type="button" class="eyeOfPwd">--}}
{{--															<i class="far fa-eye-slash"></i>--}}
{{--														</button>--}}
{{--													</span>--}}
{{--												</div>--}}
{{--												<input id="passwordConfirmation" name="password_confirmation"--}}
{{--													   type="password"--}}
{{--													   class="form-control{{ $passwordError }}"--}}
{{--													   placeholder="{{ t('Password Confirmation') }}"--}}
{{--													   autocomplete="off"--}}
{{--												>--}}
{{--												<div class="form-text text-muted">--}}
{{--													{{ t('at_least_num_characters', ['num' => config('settings.security.password_min_length', 6)]) }}--}}
{{--												</div>--}}
{{--											</div>--}}
{{--										</div>--}}

{{--										@include('layouts.inc.tools.captcha', ['colLeft' => 'col-md-3', 'colRight' => 'col-md-6'])--}}

{{--										--}}{{-- accept_terms --}}
{{--										<?php $acceptTermsError = (isset($errors) && $errors->has('accept_terms')) ? ' is-invalid' : ''; ?>--}}
{{--										<div class="row mb-1 required">--}}
{{--											<label class="col-md-3 col-form-label"></label>--}}
{{--											<div class="col-md-9">--}}
{{--												<div class="form-check">--}}
{{--													<input name="accept_terms" id="acceptTerms"--}}
{{--														   class="form-check-input{{ $acceptTermsError }}"--}}
{{--														   value="1"--}}
{{--														   type="checkbox" @checked(old('accept_terms') == '1')--}}
{{--													>--}}
{{--													<label class="form-check-label" for="acceptTerms" style="font-weight: normal;">--}}
{{--														{!! t('accept_terms_label', ['attributes' => getUrlPageByType('terms')]) !!}--}}
{{--													</label>--}}
{{--												</div>--}}
{{--												<div style="clear:both"></div>--}}
{{--											</div>--}}
{{--										</div>--}}

{{--										--}}{{-- accept_marketing_offers --}}
{{--										<?php $acceptMarketingOffersError = (isset($errors) && $errors->has('accept_marketing_offers')) ? ' is-invalid' : ''; ?>--}}
{{--										<div class="row mb-3 required">--}}
{{--											<label class="col-md-3 col-form-label"></label>--}}
{{--											<div class="col-md-9">--}}
{{--												<div class="form-check">--}}
{{--													<input name="accept_marketing_offers" id="acceptMarketingOffers"--}}
{{--														   class="form-check-input{{ $acceptMarketingOffersError }}"--}}
{{--														   value="1"--}}
{{--														   type="checkbox" @checked(old('accept_marketing_offers') == '1')--}}
{{--													>--}}
{{--													<label class="form-check-label" for="acceptMarketingOffers" style="font-weight: normal;">--}}
{{--														{!! t('accept_marketing_offers_label') !!}--}}
{{--													</label>--}}
{{--												</div>--}}
{{--												<div style="clear:both"></div>--}}
{{--											</div>--}}
{{--										</div>--}}

{{--										--}}{{-- Button --}}
{{--										<div class="row mb-3">--}}
{{--											<label class="col-md-3 col-form-label"></label>--}}
{{--											<div class="col-md-7">--}}
{{--												<button id="signupBtn" class="btn btn-primary btn-lg"> {{ t('register') }} </button>--}}
{{--											</div>--}}
{{--										</div>--}}

{{--										<div class="mb-4"></div>--}}

{{--									</fieldset>--}}
{{--								</form>--}}
{{--							</div>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				</div>--}}

{{--				<div class="col-md-4 reg-sidebar">--}}
{{--					<div class="reg-sidebar-inner text-center">--}}
{{--						<div class="promo-text-box">--}}
{{--							<i class="far fa-image fa-4x icon-color-1"></i>--}}
{{--							<h3><strong>{{ t('create_new_listing') }}</strong></h3>--}}
{{--							<p>--}}
{{--								{{ t('do_you_have_something_text', ['appName' => config('app.name')]) }}--}}
{{--							</p>--}}
{{--						</div>--}}
{{--						<div class="promo-text-box">--}}
{{--							<i class="fas fa-pen-square fa-4x icon-color-2"></i>--}}
{{--							<h3><strong>{{ t('create_and_manage_items') }}</strong></h3>--}}
{{--							<p>{{ t('become_a_best_seller_or_buyer_text') }}</p>--}}
{{--						</div>--}}
{{--						<div class="promo-text-box"><i class="fas fa-heart fa-4x icon-color-3"></i>--}}
{{--							<h3><strong>{{ t('create_your_favorite_listings_list') }}</strong></h3>--}}
{{--							<p>{{ t('create_your_favorite_listings_list_text') }}</p>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				</div>--}}
			</div>
		</div>
	</div>
@endsection

@section('after_scripts')
	<script>
		$(document).ready(function () {
			{{-- Submit Form --}}
			$(document).on('click', '#signupBtn', function(e) {
				e.preventDefault();
				$("#signupForm").submit();

				return false;
			});

			let face_type_checked = $("input[type=radio][name='face_type']:checked")
			if(face_type_checked.val() === '2') {
				$('.entity__block').show()
			} else {
				$('.entity__block').hide()
			}

			$("input[type=radio][name='face_type']").on('change', function (){
				if($(this).val() === '2') {
					$('.entity__block').show()
				} else {
					$('.entity__block').hide()
				}
			})

		});
	</script>
@endsection
