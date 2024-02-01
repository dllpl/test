@php
	$posts ??= [];
	$totalPosts ??= 0;

	$colDescBox = (config('settings.list.display_mode') == 'make-compact')
		? 'col-sm-9 col-12'
		: 'col-sm-7 col-12';
	$colPriceBox = 'col-sm-3 col-12';



@endphp
@if (!empty($posts) && $totalPosts > 0)
	<div class="table-responsive">
	<table class="table table-borderless striped-rows">
		<thead>
		<tr>
			<th scope="col">Дата выезда</th>
			<th scope="col">Тип</th>
			<th scope="col">Отправка</th>
			<th scope="col">Прибытие</th>
			<th scope="col">Мест</th>
			<th scope="col">Цена</th>
			<th scope="col">Пользователь</th>
			<th scope="col"></th>
		</tr>
		</thead>
		<tbody>
			@foreach($posts as $key => $post)
				@php
					$post['fields'] = \DB::table('post_values')->where('post_id', $post['id'])->orderBy('field_id', 'DESC')->get()->toArray();
                    if(isset($post['fields'][4])) {
                        $post['fields'][4]->value =  json_decode(\DB::table('fields_options')->where('id', $post['fields'][4]->value)
						->select('value')->first()->value)->ru;
                    }
                    dump($post['fields']);
				@endphp
				<tr onclick="document.location = '{{ \App\Helpers\UrlGen::post($post) }}';" style="cursor: pointer">
					<th scope="row">{{ \App\Helpers\Date::format($post['fields'][2]->value, 'datetime') }}</th>
					<td>{{ isset($post['fields'][4]) ? $post['fields'][4]->value : '-'}}</td>
					<td>{{$post['fields'][0]->value}}</td>
					<td>{{$post['fields'][1]->value}}</td>
					<td>{{$post['fields'][3]->value}}</td>
					<td>{{$post['price']}}</td>
					<td>{{$post['user']['name']}}</td>
					<td>
						@if (!empty(data_get($post, 'savedByLoggedUser')))
							<a class="preview__btn btn-reset make-favorite" id="{{ data_get($post, 'id') }}"
							   title="{{ t('Saved') }}">
								<svg class="preview__like">
									<path
											d="M13.1961 24.5476L13.1946 24.5462C9.40345 21.1084 6.33722 18.3222 4.20709 15.7161C2.08795 13.1235 1.00003 10.8316 1.00003 8.3995C1.00003 4.42718 4.0956 1.34387 8.05566 1.34387C10.3019 1.34387 12.4743 2.39471 13.8878 4.04165L14.6466 4.9258L15.4055 4.04165C16.819 2.39471 18.9914 1.34387 21.2376 1.34387C25.1977 1.34387 28.2932 4.42718 28.2932 8.3995C28.2932 10.8316 27.2053 13.1235 25.0862 15.7161C22.956 18.3222 19.8898 21.1084 16.0986 24.5462L16.0972 24.5476L14.6466 25.8681L13.1961 24.5476Z"
											fill="#FF4848" stroke="#FF4848" stroke-width="2"/>
								</svg>
							</a>
						@else
							<a class="preview__btn btn-reset make-favorite" id="{{ data_get($post, 'id') }}"
							   title="{{ t('Save') }}">
								<svg class="preview__like">
									<path
											d="M13.1961 24.5476L13.1946 24.5462C9.40345 21.1084 6.33722 18.3222 4.20709 15.7161C2.08795 13.1235 1.00003 10.8316 1.00003 8.3995C1.00003 4.42718 4.0956 1.34387 8.05566 1.34387C10.3019 1.34387 12.4743 2.39471 13.8878 4.04165L14.6466 4.9258L15.4055 4.04165C16.819 2.39471 18.9914 1.34387 21.2376 1.34387C25.1977 1.34387 28.2932 4.42718 28.2932 8.3995C28.2932 10.8316 27.2053 13.1235 25.0862 15.7161C22.956 18.3222 19.8898 21.1084 16.0986 24.5462L16.0972 24.5476L14.6466 25.8681L13.1961 24.5476Z"
											stroke="#FF4848" stroke-width="2"/>
								</svg>
							</a>
						@endif
					</td>
				</tr>

{{--				<div class="item-list">--}}
{{--					@if (data_get($post, 'featured') == 1)--}}
{{--						@if (!empty(data_get($post, 'latestPayment.package')))--}}
{{--							@if (data_get($post, 'latestPayment.package.ribbon') != '')--}}
{{--								<div class="ribbon-horizontal {{ data_get($post, 'latestPayment.package.ribbon') }}">--}}
{{--									<span>{{ data_get($post, 'latestPayment.package.short_name') }}</span>--}}
{{--								</div>--}}
{{--							@endif--}}
{{--						@endif--}}
{{--					@endif--}}

{{--					<div class="row">--}}
{{--						<div class="{{ $colDescBox }} add-desc-box">--}}
{{--							<div class="items-details">--}}
{{--								<h5 class="add-title">--}}
{{--									<a href="{{ \App\Helpers\UrlGen::post($post) }}">{{ str(data_get($post, 'title'))->limit(70) }}</a>--}}
{{--								</h5>--}}

{{--								<span class="info-row">--}}
{{--									@if (config('settings.single.show_listing_types'))--}}
{{--										@if (!empty(data_get($post, 'postType')))--}}
{{--											<span class="add-type business-posts"--}}
{{--												  data-bs-toggle="tooltip"--}}
{{--												  data-bs-placement="bottom"--}}
{{--												  title="{{ data_get($post, 'postType.name') }}"--}}
{{--											>--}}
{{--												{{ strtoupper(mb_substr(data_get($post, 'postType.name'), 0, 1)) }}--}}
{{--											</span>&nbsp;--}}
{{--										@endif--}}
{{--									@endif--}}
{{--									@if (!config('settings.list.hide_dates'))--}}
{{--										<span class="date"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>--}}
{{--											<i class="far fa-clock"></i> {!! data_get($post, 'created_at_formatted') !!}--}}
{{--										</span>--}}
{{--									@endif--}}
{{--									<span class="category"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>--}}
{{--										<i class="bi bi-folder"></i>&nbsp;--}}
{{--										@if (!empty(data_get($post, 'category.parent')))--}}
{{--											<a href="{!! \App\Helpers\UrlGen::category(data_get($post, 'category.parent'), null, $city ?? null) !!}" class="info-link">--}}
{{--												{{ data_get($post, 'category.parent.name') }}--}}
{{--											</a>&nbsp;&raquo;&nbsp;--}}
{{--										@endif--}}
{{--										<a href="{!! \App\Helpers\UrlGen::category(data_get($post, 'category'), null, $city ?? null) !!}" class="info-link">--}}
{{--											{{ data_get($post, 'category.name') }}--}}
{{--										</a>--}}
{{--									</span>--}}
{{--									<span class="item-location"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>--}}
{{--										<i class="bi bi-geo-alt"></i>&nbsp;--}}
{{--										<a href="{!! \App\Helpers\UrlGen::city(data_get($post, 'city'), null, $cat ?? null) !!}" class="info-link">--}}
{{--											{{ data_get($post, 'city.name') }}--}}
{{--										</a> {{ (!empty(data_get($post, 'distance'))) ? '- ' . round(data_get($post, 'distance'), 2) . getDistanceUnit() : '' }}--}}
{{--									</span>--}}
{{--								</span>--}}

{{--								@if (config('plugins.reviews.installed'))--}}
{{--									@if (view()->exists('reviews::ratings-list'))--}}
{{--										@include('reviews::ratings-list')--}}
{{--									@endif--}}
{{--								@endif--}}
{{--							</div>--}}
{{--						</div>--}}

{{--						<div class="{{ $colPriceBox }} text-end price-box" style="white-space: nowrap;">--}}
{{--							<h2 class="item-price">--}}
{{--								{!! data_get($post, 'price_formatted') !!}--}}
{{--							</h2>--}}
{{--							@if (!empty(data_get($post, 'latestPayment.package')))--}}
{{--								@if (data_get($post, 'latestPayment.package.has_badge') == 1)--}}
{{--									<a class="btn btn-danger btn-sm make-favorite">--}}
{{--										<i class="fa fa-certificate"></i> <span>{{ data_get($post, 'latestPayment.package.short_name') }}</span>--}}
{{--									</a>&nbsp;--}}
{{--								@endif--}}
{{--							@endif--}}
{{--							@if (!empty(data_get($post, 'savedByLoggedUser')))--}}
{{--								<a class="btn btn-success btn-sm make-favorite" id="{{ data_get($post, 'id') }}">--}}
{{--									<i class="fas fa-bookmark"></i> <span>{{ t('Saved') }}</span>--}}
{{--								</a>--}}
{{--							@else--}}
{{--								<a class="btn btn-default btn-sm make-favorite" id="{{ data_get($post, 'id') }}">--}}
{{--									<i class="fas fa-bookmark"></i> <span>{{ t('Save') }}</span>--}}
{{--								</a>--}}
{{--							@endif--}}
{{--						</div>--}}
{{--			</div>--}}
{{--		</div>--}}
	@endforeach
		</tbody>
	</table>
	</div>
@else
	<div class="p-4" style="width: 100%;">
		{{ t('no_result_refine_your_search') }}
	</div>
@endif

@section('after_scripts')
	@parent
	<script>
		{{-- Favorites Translation --}}
		var lang = {
			labelSavePostSave: "{!! t('Save listing') !!}",
			labelSavePostRemove: "{!! t('Remove favorite') !!}",
			loginToSavePost: "{!! t('Please log in to save the Listings') !!}",
			loginToSaveSearch: "{!! t('Please log in to save your search') !!}"
		};
	</script>
@endsection
