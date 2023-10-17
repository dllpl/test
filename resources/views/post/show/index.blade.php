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

@php
	$post ??= [];
	$catBreadcrumb ??= [];
	$topAdvertising ??= [];
	$bottomAdvertising ??= [];
@endphp

@section('content')
	{!! csrf_field() !!}
	<input type="hidden" id="postId" name="post_id" value="{{ data_get($post, 'id') }}">

	<section class="search">
		<div class="search__container container">
			<a href="{{ \App\Helpers\UrlGen::searchWithoutQuery() }}" class="search__link link link--btn link--accent">Все объявления</a>

			<form id="search" name="search" action="{{ \App\Helpers\UrlGen::searchWithoutQuery() }}" method="GET" class="search__form form-search">
				<input name="q" placeholder="{{ t('what') }}" type="text" value="" class="input-reset input input--search">
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
				<span>Выберите свой город</span>
			</a>
		</div>
	</section>

	@if (isset($siteCountryInfo))
		<section class="message-avtorization">
			<div class="container">
				<p class="message-avtorization__content">
					<a href="#" class="message-avtorization__link">{!! $siteCountryInfo !!}</a>
				</p>
			</div>
		</section>
	@endif

	@if (session()->has('flash_notification'))
		@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
		@php
			$paddingTopExists = true;
		@endphp
		<div class="container">
			<div class="row">
				<div class="col-12">
					@include('flash::message')
				</div>
			</div>
		</div>
		@php
			session()->forget('flash_notification.message');
		@endphp
	@endif

	{{-- Archived listings message --}}
	@if (!empty(data_get($post, 'archived_at')))
		@includeFirst([config('larapen.core.customizedViewPath') . 'common.spacer', 'common.spacer'])
		@php
			$paddingTopExists = true;
		@endphp
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="alert alert-warning" role="alert">
						{!! t('This listing has been archived') !!}
					</div>
				</div>
			</div>
		</div>
	@endif

	<div class="main-container">

		@if (!empty($topAdvertising))
			@includeFirst(
				[config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.top', 'layouts.inc.advertising.top'],
				['paddingTopExists' => $paddingTopExists ?? false]
			)
			@php
				$paddingTopExists = false;
			@endphp
		@endif
			<section class="path {{ !empty($topAdvertising) ? 'mt-3' : 'mt-2' }}">
				<div class="path__container container">
					<ul class="list-reset path__list">
						<li class="path__item">
							<a href="{{ url('/') }}" class="path__link"><i class="fas fa-home"></i></a>
						</li>
						<li class="path__item">
							<a href="{{ url('/') }}" class="path__link">{{ config('country.name') }}</a>
						</li>
						@if (is_array($catBreadcrumb) && count($catBreadcrumb) > 0)
							@foreach($catBreadcrumb as $key => $value)
								<li class="path__item">
									<a href="{{ $value->get('url') }}" class="path__link">
										{!! $value->get('name') !!}
									</a>
								</li>
							@endforeach
						@endif
						<li class="path__item" aria-current="page">
							{{ str(data_get($post, 'title'))->limit(70) }}
						</li>
					</ul>
				</div>
			</section>

{{--		<div class="container {{ !empty($topAdvertising) ? 'mt-3' : 'mt-2' }}">--}}
{{--			<div class="row">--}}
{{--				<div class="col-md-12">--}}

{{--					<nav aria-label="breadcrumb" role="navigation" class="float-start">--}}
{{--						<ol class="breadcrumb">--}}
{{--							<li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="fas fa-home"></i></a></li>--}}
{{--							<li class="breadcrumb-item"><a href="{{ url('/') }}">{{ config('country.name') }}</a></li>--}}
{{--							@if (is_array($catBreadcrumb) && count($catBreadcrumb) > 0)--}}
{{--								@foreach($catBreadcrumb as $key => $value)--}}
{{--									<li class="breadcrumb-item">--}}
{{--										<a href="{{ $value->get('url') }}">--}}
{{--											{!! $value->get('name') !!}--}}
{{--										</a>--}}
{{--									</li>--}}
{{--								@endforeach--}}
{{--							@endif--}}
{{--							<li class="breadcrumb-item active" aria-current="page">{{ str(data_get($post, 'title'))->limit(70) }}</li>--}}
{{--						</ol>--}}
{{--					</nav>--}}

{{--					<div class="float-end backtolist">--}}
{{--						<a href="{{ rawurldecode(url()->previous()) }}"><i class="fa fa-angle-double-left"></i> {{ t('back_to_results') }}</a>--}}
{{--					</div>--}}

{{--				</div>--}}
{{--			</div>--}}
{{--		</div>--}}

		<section class="product">
			<div class="product__container container">
				<div class="product__header">
					<div class="product__header-top">
						<h1 class="title title--xl">
							<a href="{{ \App\Helpers\UrlGen::post($post) }}" title="{{ data_get($post, 'title') }}">
								{{ data_get($post, 'title') }}
							</a>
						</h1>
						@if (config('settings.single.show_listing_types'))
							<div class="product__verification">
								<span>{{ data_get($post, 'postType.name') }}</span>
								<svg class="icon icon--check">
									<use xlink:href="/images/sprite.svg#check"></use>
								</svg>
							</div>
						@endif
					</div>
					<div class="product__header-bottom">
						@if (!config('settings.single.hide_dates'))
							<div class="product__header-bottom-desc">
								<svg class="icon icon--clock">
									<use xlink:href="/images/sprite.svg#clock"></use>
								</svg>
								<span>{!! data_get($post, 'created_at_formatted') !!}</span>
							</div>
						@endif
{{--						<div class="product__header-bottom-desc"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>--}}
{{--							<i class="bi bi-folder"></i> {{ data_get($post, 'category.parent.name', data_get($post, 'category.name')) }}--}}
{{--						</div>&nbsp;--}}
{{--						<div class="product__header-bottom-desc"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>--}}
{{--							<i class="bi bi-geo-alt"></i> {{ data_get($post, 'city.name') }}--}}
{{--						</div>&nbsp;--}}
						<div class="product__header-bottom-desc">
							<svg class="icon icon--eye">
								<use xlink:href="/images/sprite.svg#eye"></use>
							</svg>
							<span> {{
									\App\Helpers\Number::short(data_get($post, 'visits'))
									. ' '
									. trans_choice('global.count_views', getPlural(data_get($post, 'visits')), [], config('app.locale'))
									}}
							</span>
						</div>
					</div>
				</div>
				<div class="product__grid grid grid--coll-4">
					<div class="product__img">
						@include('post.show.inc.pictures-slider')
					</div>
					<div class="product__info">
						<div class="product__info-wrapp">
							<div class="product__price-wrapp">
								<span class="product__price">{!! data_get($post, 'price_formatted') !!}</span>
								@if (auth()->check())
									<a href="#contactUser" class="link link--underline" data-bs-toggle="modal">
										Предложить свою цену
									</a>
								@else
									<a href="#quickLogin" class="link link--underline" data-bs-toggle="modal">
										Предложить свою цену
									</a>
								@endif
							</div>
							<div class="product__like">
								<p class="product__like-content">Добавить объявление в избранное</p>
								@if (auth()->check())
									@if (!empty(data_get($post, 'savedByLoggedUser')))
										<a class="product__like-content make-favorite" id="{{ data_get($post, 'id') }}" title="{{ t('Remove favorite') }}">
											<svg class="preview__like">
												<path
														d="M13.1961 24.5476L13.1946 24.5462C9.40345 21.1084 6.33722 18.3222 4.20709 15.7161C2.08795 13.1235 1.00003 10.8316 1.00003 8.3995C1.00003 4.42718 4.0956 1.34387 8.05566 1.34387C10.3019 1.34387 12.4743 2.39471 13.8878 4.04165L14.6466 4.9258L15.4055 4.04165C16.819 2.39471 18.9914 1.34387 21.2376 1.34387C25.1977 1.34387 28.2932 4.42718 28.2932 8.3995C28.2932 10.8316 27.2053 13.1235 25.0862 15.7161C22.956 18.3222 19.8898 21.1084 16.0986 24.5462L16.0972 24.5476L14.6466 25.8681L13.1961 24.5476Z"
														fill="#FF4848" stroke="#FF4848" stroke-width="2" />
											</svg>
										</a>
									@else
										<a class="product__like-content make-favorite" id="{{ data_get($post, 'id') }}" title="{{ t('Save') }}">
											<svg class="preview__like">
												<path
														d="M13.1961 24.5476L13.1946 24.5462C9.40345 21.1084 6.33722 18.3222 4.20709 15.7161C2.08795 13.1235 1.00003 10.8316 1.00003 8.3995C1.00003 4.42718 4.0956 1.34387 8.05566 1.34387C10.3019 1.34387 12.4743 2.39471 13.8878 4.04165L14.6466 4.9258L15.4055 4.04165C16.819 2.39471 18.9914 1.34387 21.2376 1.34387C25.1977 1.34387 28.2932 4.42718 28.2932 8.3995C28.2932 10.8316 27.2053 13.1235 25.0862 15.7161C22.956 18.3222 19.8898 21.1084 16.0986 24.5462L16.0972 24.5476L14.6466 25.8681L13.1961 24.5476Z"
														stroke="#FF4848" stroke-width="2" />
											</svg>
										</a>
									@endif
								@else
									<a class="preview__btn btn-reset make-favorite" id="{{ data_get($post, 'id') }}" title="{{ t('Save') }}">
										<svg class="preview__like">
											<path
													d="M13.1961 24.5476L13.1946 24.5462C9.40345 21.1084 6.33722 18.3222 4.20709 15.7161C2.08795 13.1235 1.00003 10.8316 1.00003 8.3995C1.00003 4.42718 4.0956 1.34387 8.05566 1.34387C10.3019 1.34387 12.4743 2.39471 13.8878 4.04165L14.6466 4.9258L15.4055 4.04165C16.819 2.39471 18.9914 1.34387 21.2376 1.34387C25.1977 1.34387 28.2932 4.42718 28.2932 8.3995C28.2932 10.8316 27.2053 13.1235 25.0862 15.7161C22.956 18.3222 19.8898 21.1084 16.0986 24.5462L16.0972 24.5476L14.6466 25.8681L13.1961 24.5476Z"
													stroke="#FF4848" stroke-width="2" />
										</svg>
									</a>
								@endif
							</div>
						</div>
						<div class="product__saler saler">
							<div class="saler__top">
								<div class="saler__left">
									<div class="saler__photo">
										<img class="saler__img" src="{{ data_get($post, 'user_photo_url') }}" alt="{{ data_get($post, 'contact_name') }}">
									</div>
									<ul class="saler__list list-reset">
										<li class="saler__item">
											<span class="saler__desc">Объявление размещено от</span>
										</li>
										@if (!empty($user))
											<li class="saler__item">
												<a class="saler__name" href="{{ \App\Helpers\UrlGen::user($user) }}">
													{{ data_get($post, 'contact_name') }}
												</a>
											</li>
										@else
											{{ data_get($post, 'contact_name') }}
										@endif
										<li class="saler__item">
											@if (config('plugins.reviews.installed'))
												@if (view()->exists('reviews::ratings-single'))
													@include('reviews::ratings-single')
												@endif
											@endif
										</li>
									</ul>
								</div>
								<div class="saler__right">
									@if (!empty($user))
										<a href="{{ \App\Helpers\UrlGen::user($user) }}" class="saler__link link link--underline">
											Все объявления
										</a>
									@else
										{{ data_get($post, 'contact_name') }}
									@endif
								</div>
							</div>
							<div class="saler__bottom">
								@if (auth()->check())
									@if (auth()->user()->id == data_get($post, 'user_id'))
										<a href="{{ \App\Helpers\UrlGen::editPost($post) }}" class="btn btn-default btn-block">
											<i class="far fa-edit"></i> {{ t('Update the details') }}
										</a>
										@if (config('settings.single.publication_form_type') == '1')
											<a href="{{ url('posts/' . data_get($post, 'id') . '/photos') }}" class="btn btn-default btn-block">
												<i class="fas fa-camera"></i> {{ t('Update Photos') }}
											</a>
											@if ($countPackages > 0 && $countPaymentMethods > 0)
												<a href="{{ url('posts/' . data_get($post, 'id') . '/payment') }}" class="btn btn-success btn-block">
													<i class="far fa-check-circle"></i> {{ t('Make It Premium') }}
												</a>
											@endif
										@endif
										@if (empty(data_get($post, 'archived_at')) && isVerifiedPost($post))
											<a href="{{ url('account/posts/list/' . data_get($post, 'id') . '/offline') }}" class="btn btn-warning btn-block confirm-simple-action">
												<i class="fas fa-eye-slash"></i> {{ t('put_it_offline') }}
											</a>
										@endif
										@if (!empty(data_get($post, 'archived_at')))
											<a href="{{ url('account/posts/archived/' . data_get($post, 'id') . '/repost') }}" class="btn btn-info btn-block confirm-simple-action">
												<i class="fa fa-recycle"></i> {{ t('re_post_it') }}
											</a>
										@endif
									@else
										{!! genPhoneNumberBtn($post, true) !!}
										{!! genEmailContactBtn($post, true) !!}
									@endif
									@php
										try {
                                            if (auth()->user()->can(\App\Models\Permission::getStaffPermissions())) {
                                                $btnUrl = admin_url('blacklists/add') . '?';
                                                $btnQs = (!empty(data_get($post, 'email'))) ? 'email=' . data_get($post, 'email') : '';
                                                $btnQs = (!empty($btnQs)) ? $btnQs . '&' : $btnQs;
                                                $btnQs = (!empty(data_get($post, 'phone'))) ? $btnQs . 'phone=' . data_get($post, 'phone') : $btnQs;
                                                $btnUrl = $btnUrl . $btnQs;

                                                if (!isDemoDomain($btnUrl)) {
                                                    $btnText = trans('admin.ban_the_user');
                                                    $btnHint = $btnText;
                                                    if (!empty(data_get($post, 'email')) && !empty(data_get($post, 'phone'))) {
                                                        $btnHint = trans('admin.ban_the_user_email_and_phone', [
                                                            'email' => data_get($post, 'email'),
                                                            'phone' => data_get($post, 'phone'),
                                                        ]);
                                                    } else {
                                                        if (!empty(data_get($post, 'email'))) {
                                                            $btnHint = trans('admin.ban_the_user_email', ['email' => data_get($post, 'email')]);
                                                        }
                                                        if (!empty(data_get($post, 'phone'))) {
                                                            $btnHint = trans('admin.ban_the_user_phone', ['phone' => data_get($post, 'phone')]);
                                                        }
                                                    }
                                                    $tooltip = ' data-bs-toggle="tooltip" data-bs-placement="bottom" title="' . $btnHint . '"';

                                                    $btnOut = '<a href="'. $btnUrl .'" class="btn btn-outline-danger btn-block confirm-simple-action"'. $tooltip .'>';
                                                    $btnOut .= $btnText;
                                                    $btnOut .= '</a>';

                                                    echo $btnOut;
                                                }
                                            }
                                        } catch (\Throwable $e) {}
									@endphp
								@else
									{!! genPhoneNumberBtn($post, true) !!}
									{!! genEmailContactBtn($post, true) !!}
								@endif
							</div>
						</div>
					</div>
					<div class="product__specific">
						<h2 class="product__title title title--xl">
							Характеристики
						</h2>
						<ul class="product__list list-reset">
							{{-- Custom Fields --}}
							@includeFirst([config('larapen.core.customizedViewPath') . 'post.show.inc.details.fields-values', 'post.show.inc.details.fields-values'])
						</ul>
					</div>
					<div class="product__options">
						<h2 class="product__title title title--xl">
							Дополнительные опции
						</h2>
						@foreach($customFields as $field)
						@php $fieldValue = data_get($field, 'value'); @endphp
							@if (is_array($fieldValue) && count($fieldValue) > 0)
									@foreach($fieldValue as $valueItem)
										<li class="product__item">
											<p class="product__value">
													{{ $valueItem }}
											</p>
										</li>
									@endforeach
							@endif
						@endforeach
					</div>
					<div class="product__description">
						<h2 class="product__title title title--xl">Описание</h2>
						<div class="title">
							{!! data_get($post, 'description') !!}
						</div>
					</div>

					<div class="product__map">
						@if (config('settings.single.show_listing_on_googlemap') && isset($post['lat'], $post['lon']))
							<div id="map" style="height: 350px"></div>
						@endif
					</div>

					<div class="product__bottom">
						@foreach(data_get($post, 'tags') as $iTag)
							<a class="product__bottom-link link link--dark" href="{{ \App\Helpers\UrlGen::tag($iTag) }}">
								{{ $iTag }}
							</a>
						@endforeach
					</div>


				</div>
			</div>
		</section>
{{--		<div class="container">--}}
{{--			<div class="row">--}}
{{--				<div class="col-lg-9 page-content col-thin-right">--}}
{{--					@php--}}
{{--						$innerBoxStyle = (!auth()->check() && plugin_exists('reviews')) ? 'overflow: visible;' : '';--}}
{{--					@endphp--}}
{{--					<div class="inner inner-box items-details-wrapper pb-0" style="{{ $innerBoxStyle }}">--}}
{{--						<h1 class="h4 fw-bold enable-long-words">--}}
{{--							<strong>--}}
{{--								<a href="{{ \App\Helpers\UrlGen::post($post) }}" title="{{ data_get($post, 'title') }}">--}}
{{--									{{ data_get($post, 'title') }}--}}
{{--                                </a>--}}
{{--                            </strong>--}}
{{--							@if (config('settings.single.show_listing_types'))--}}
{{--								@if (!empty(data_get($post, 'postType')))--}}
{{--									<small class="label label-default adlistingtype">{{ data_get($post, 'postType.name') }}</small>--}}
{{--								@endif--}}
{{--							@endif--}}
{{--							@if (data_get($post, 'featured') == 1 && !empty(data_get($post, 'latestPayment.package')))--}}
{{--								<i class="fas fa-check-circle"--}}
{{--								   style="color: {{ data_get($post, 'latestPayment.package.ribbon') }};"--}}
{{--								   data-bs-placement="bottom"--}}
{{--								   data-bs-toggle="tooltip"--}}
{{--								   title="{{ data_get($post, 'latestPayment.package.short_name') }}"--}}
{{--								></i>--}}
{{--                            @endif--}}
{{--						</h1>--}}
{{--						<span class="info-row">--}}
{{--							@if (!config('settings.single.hide_dates'))--}}
{{--							<span class="date"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>--}}
{{--								<i class="far fa-clock"></i> {!! data_get($post, 'created_at_formatted') !!}--}}
{{--							</span>&nbsp;--}}
{{--							@endif--}}
{{--							<span class="category"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>--}}
{{--								<i class="bi bi-folder"></i> {{ data_get($post, 'category.parent.name', data_get($post, 'category.name')) }}--}}
{{--							</span>&nbsp;--}}
{{--							<span class="item-location"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>--}}
{{--								<i class="bi bi-geo-alt"></i> {{ data_get($post, 'city.name') }}--}}
{{--							</span>&nbsp;--}}
{{--							<span class="category"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>--}}
{{--								<i class="bi bi-eye"></i> {{--}}
{{--									\App\Helpers\Number::short(data_get($post, 'visits'))--}}
{{--									. ' '--}}
{{--									. trans_choice('global.count_views', getPlural(data_get($post, 'visits')), [], config('app.locale'))--}}
{{--									}}--}}
{{--							</span>--}}
{{--							<span class="category float-md-end"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>--}}
{{--								{{ t('reference') }}: {{ hashId(data_get($post, 'id'), false, false) }}--}}
{{--							</span>--}}
{{--						</span>--}}

{{--						@include('post.show.inc.pictures-slider')--}}

{{--						@if (config('plugins.reviews.installed'))--}}
{{--							@if (view()->exists('reviews::ratings-single'))--}}
{{--								@include('reviews::ratings-single')--}}
{{--							@endif--}}
{{--						@endif--}}

{{--						@includeFirst([config('larapen.core.customizedViewPath') . 'post.show.inc.details', 'post.show.inc.details'])--}}
{{--					</div>--}}
{{--				</div>--}}

{{--				<div class="col-lg-3 page-sidebar-right">--}}
{{--					@includeFirst([config('larapen.core.customizedViewPath') . 'post.show.inc.sidebar', 'post.show.inc.sidebar'])--}}
{{--				</div>--}}
{{--			</div>--}}

{{--		</div>--}}

		@if (config('settings.single.similar_listings') == '1' || config('settings.single.similar_listings') == '2')
			@php
				$widgetType = (config('settings.single.similar_listings_in_carousel') ? 'carousel' : 'normal');
			@endphp
			@includeFirst([
					config('larapen.core.customizedViewPath') . 'search.inc.posts.widget.' . $widgetType,
					'search.inc.posts.widget.' . $widgetType
				],
				['widget' => ($widgetSimilarPosts ?? null), 'firstSection' => false]
			)
		@endif

		@includeFirst(
			[config('larapen.core.customizedViewPath') . 'layouts.inc.advertising.bottom', 'layouts.inc.advertising.bottom'],
			['firstSection' => false]
		)

		@if (isVerifiedPost($post))
			@includeFirst(
				[config('larapen.core.customizedViewPath') . 'layouts.inc.tools.facebook-comments', 'layouts.inc.tools.facebook-comments'],
				['firstSection' => false]
			)
		@endif

		{{-- Promo Listing Button --}}
		<section class="ads-add">
			<div class="ads-add__wrapp container">
				<div class="ads-add__content">
					<h3 class="title title--light title--xl">
						{{--						{{ t('do_you_have_anything') }}--}}
						Разместите объявление
					</h3>
					<p class="ads-add__subtitle">
						{{--						{{ t('sell_products_and_services_online_for_free') }}--}}
						И начните продавать или продвигать свои услуги вместе с Барсовоз
					</p>
				</div>
				@if (!auth()->check() && config('settings.single.guests_can_post_listings') != '1')
					<a href="#quickLogin" class="ads-add__link link link--btn-big link--dark" data-bs-toggle="modal">
						{{--						{{ t('start_now') }}--}}
						Разместить объявление
					</a>
				@else
					<a href="{{ \App\Helpers\UrlGen::addPost() }}" class="ads-add__link link link--btn-big link--dark">
						{{--						{{ t('start_now') }}--}}
						Разместить объявление
					</a>
				@endif
			</div>
		</section>
	</div>
@endsection
@php
	if (!session()->has('emailVerificationSent') && !session()->has('phoneVerificationSent')) {
		if (session()->has('message')) {
			session()->forget('message');
		}
	}
@endphp

@section('modal_message')
	@if (config('settings.single.show_security_tips') == '1')
		@includeFirst([config('larapen.core.customizedViewPath') . 'post.show.inc.security-tips', 'post.show.inc.security-tips'])
	@endif
	@if (auth()->check() || config('settings.single.guests_can_contact_authors')=='1')
		@includeFirst([config('larapen.core.customizedViewPath') . 'account.messenger.modal.create', 'account.messenger.modal.create'])
	@endif
@endsection

@section('after_styles')
@endsection

@section('before_scripts')
	<script>
		var showSecurityTips = '{{ config('settings.single.show_security_tips', '0') }}';
	</script>
@endsection

@section('after_scripts')
    @if (config('services.googlemaps.key'))
    @endif
	@if (config('settings.single.show_listing_on_googlemap') && isset($post['lat'], $post['lon']))
		<script src="https://api-maps.yandex.ru/2.1/?apikey=837a7aab-3ca6-4d11-bb98-089f871ee337&lang=ru_RU"></script>
		<script>
			document.addEventListener("DOMContentLoaded", function () {
				ymaps.ready(init);
				let windowInnerWidth = window.innerWidth;
				let zoom;

				if (windowInnerWidth >= 576) {
					zoom = 14;
				} else {
					zoom = 15;
				}

				function init() {
					let myMap = new ymaps.Map("map", {
						center: [{{$post['lat']}},{{$post['lon']}}],
						zoom: zoom,
						controls: []
					}, {
						suppressMapOpenBlock: true
					});


					let myPlacemark = new ymaps.Placemark([{{$post['lat']}},{{$post['lon']}}], {
						balloonContent: '{{ data_get($post, 'city.name') }}',
						hintContent: '{{ data_get($post, 'city.name') }}',
					}, {
						iconImageSize: [30, 42],
						iconImageOffset: [-15, -42],
					});
					myMap.behaviors.disable('scrollZoom');
					myMap.geoObjects.add(myPlacemark);
				}
			});
		</script>
	@endif

	<script>
		{{-- Favorites Translation --}}
        var lang = {
            labelSavePostSave: "{!! t('Save listing') !!}",
            labelSavePostRemove: "{!! t('Remove favorite') !!}",
            loginToSavePost: "{!! t('Please log in to save the Listings') !!}",
            loginToSaveSearch: "{!! t('Please log in to save your search') !!}"
        };

		$(document).ready(function () {
			{{-- Tooltip --}}
			var tooltipTriggerList = [].slice.call(document.querySelectorAll('[rel="tooltip"]'));
			var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
				return new bootstrap.Tooltip(tooltipTriggerEl)
			});

			@if (config('settings.single.show_listing_on_googlemap'))
				{{--
				let mapUrl = '{{ addslashes($mapUrl) }}';
				let iframe = document.getElementById('googleMaps');
				iframe.setAttribute('src', mapUrl);
				--}}
			@endif

			{{-- Keep the current tab active with Twitter Bootstrap after a page reload --}}
            $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
                /* save the latest tab; use cookies if you like 'em better: */
                /* localStorage.setItem('lastTab', $(this).attr('href')); */
				localStorage.setItem('lastTab', $(this).attr('data-bs-target'));
            });
			{{-- Go to the latest tab, if it exists: --}}
            let lastTab = localStorage.getItem('lastTab');
            if (lastTab) {
				{{-- let triggerEl = document.querySelector('a[href="' + lastTab + '"]'); --}}
				let triggerEl = document.querySelector('button[data-bs-target="' + lastTab + '"]');
				if (typeof triggerEl !== 'undefined' && triggerEl !== null) {
					let tabObj = new bootstrap.Tab(triggerEl);
					if (tabObj !== null) {
						tabObj.show();
					}
				}
            }
		});
	</script>
@endsection
