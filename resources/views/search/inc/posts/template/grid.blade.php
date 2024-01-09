@php
    $posts ??= [];
    $totalPosts ??= 0;

    /** Проверяем серт ли пользователь */
    if(auth()->user()) {
        $user = auth()->user();
        $user_cert = (bool)\DB::table('request_to_super')
            ->where('user_id', $user->id)
            ->where('status', 1)
            ->latest()
            ->first();
    } else {
        $user_cert = false;
    }

    /** Тайминг для показа объяв 24 часа */
    $hour_to_public = env('CERT_HOUR', 24);

    foreach ($posts as $key => $value) {
        /** Берем поле наличия для каждого **/
        $posts[$key]['available_field'] = \DB::table('post_values')->where('post_id', $posts[$key]['id'])->where('field_id', 28)->first();
    }

@endphp
@if (!empty($posts) && $totalPosts > 0)
    <ul class="grid grid--coll-3 list-reset">
        @foreach($posts as $key => $post)
            @php
                $time = (time() - strtotime($post['created_at']) >= $hour_to_public * 60 * 60);
            @endphp
            @if((time() - strtotime($post['created_at']) >= $hour_to_public * 60 * 60) || $user_cert)

                @if (data_get($post, 'featured') == 1)
                    @if (!empty(data_get($post, 'latestPayment.package')))
                        @if (data_get($post, 'latestPayment.package.ribbon') != '')
                            <div class="ribbon-horizontal {{ data_get($post, 'latestPayment.package.ribbon') }}">
                                <span>{{ data_get($post, 'latestPayment.package.short_name') }}</span>
                            </div>
                        @endif
                    @endif
                @endif
                @php
                    if((time() - strtotime($post['created_at']) <= $hour_to_public * 60 * 60)) {
                        $date_to_public = date('d.m.Y H:i:s', strtotime($post['created_at'] . " + $hour_to_public hours"));
                        $d1 = new DateTime($date_to_public);
                        $d2 = new DateTime();
                        $interval= $d1->diff($d2);
                        $time_lost = $interval->h . ' ч.' . $interval->i . ' мин.';
                    }

                @endphp
                <li class="preview">
                    <div class="preview__img-wrapp">
                        <a href="{{ \App\Helpers\UrlGen::post($post) }}" class="position-relative">
                            {!! imgTag(data_get($post, 'picture.filename'), 'medium', ['class' => 'lazyload thumbnail preview__img img img--preview', 'alt' => data_get($post, 'title')]) !!}
                            {{-- Плашка в наличии --}}
                            @if($post['available_field'] && $post['available_field']->value == 177)
                                <div class="position-absolute badge__available--accent" style=""><p>в наличии</p></div>
                            @endif
                            @if((time() - strtotime($post['created_at']) <= $hour_to_public * 60 * 60))
                                <span class="position-absolute badge__available--heart" style="bottom:0%">Осталось {{$time_lost}}</span>
                            @endif
                        </a>
                    </div>
                    <h4 class="preview__title title title--large title--accent">
                        <a href="{{ \App\Helpers\UrlGen::post($post) }}">{{ str(data_get($post, 'title'))->limit(70) }}</a>
                    </h4>
                    <span class="preview__price price">{!! data_get($post, 'price_formatted') !!}</span>
                    <div class="preview__wrapp">
                        <div class="preview__geo">
							<span class="preview__city">
								<a href="{!! \App\Helpers\UrlGen::city(data_get($post, 'city'), null, $cat ?? null) !!}"
                                   class="info-link">
									{{ data_get($post, 'city.name') }}
								</a>
							</span>
                            @if (!config('settings.list.hide_dates'))
                                <span class="preview__time">{!! data_get($post, 'created_at_formatted') !!}</span>
                            @endif
                        </div>
                        @if (!empty(data_get($post, 'savedByLoggedUser')))
                            <a class="preview__btn btn-reset make-favorite" id="{{ data_get($post, 'id') }}"
                               title="{{ t('Saved') }}">
                                <svg class="preview__like">
                                    <use xlink:href="/images/sprite.svg#heart"></use>
                                </svg>
                            </a>
                        @else
                            <a class="preview__btn btn-reset make-favorite" id="{{ data_get($post, 'id') }}"
                               title="{{ t('Save') }}">
                                <svg class="preview__like">
                                    <use xlink:href="/images/sprite.svg#heart-stroke"></use>
                                </svg>
                            </a>
                        @endif
                    </div>
                </li>
                {{--		<div class="item-list">--}}
                {{--			@if (data_get($post, 'featured') == 1)--}}
                {{--				@if (!empty(data_get($post, 'latestPayment.package')))--}}
                {{--					@if (data_get($post, 'latestPayment.package.ribbon') != '')--}}
                {{--						<div class="ribbon-horizontal {{ data_get($post, 'latestPayment.package.ribbon') }}">--}}
                {{--							<span>{{ data_get($post, 'latestPayment.package.short_name') }}</span>--}}
                {{--						</div>--}}
                {{--					@endif--}}
                {{--				@endif--}}
                {{--			@endif--}}

                {{--			<div class="row">--}}
                {{--				<div class="col-sm-2 col-12 no-padding photobox">--}}
                {{--					<div class="add-image">--}}
                {{--						<span class="photo-count">--}}
                {{--							<i class="fa fa-camera"></i> {{ data_get($post, 'count_pictures') }}--}}
                {{--						</span>--}}
                {{--						<a href="{{ \App\Helpers\UrlGen::post($post) }}">--}}
                {{--							{!! imgTag(data_get($post, 'picture.filename'), 'medium', ['class' => 'lazyload thumbnail no-margin', 'alt' => data_get($post, 'title')]) !!}--}}
                {{--						</a>--}}
                {{--					</div>--}}
                {{--				</div>--}}

                {{--				<div class="col-sm-7 col-12 add-desc-box">--}}
                {{--					<div class="items-details">--}}
                {{--						<h5 class="add-title">--}}
                {{--							<a href="{{ \App\Helpers\UrlGen::post($post) }}">{{ str(data_get($post, 'title'))->limit(70) }}</a>--}}
                {{--						</h5>--}}

                {{--						<span class="info-row">--}}
                {{--							@if (config('settings.single.show_listing_types'))--}}
                {{--								@if (!empty(data_get($post, 'postType')))--}}
                {{--									<span class="add-type business-posts"--}}
                {{--										  data-bs-toggle="tooltip"--}}
                {{--										  data-bs-placement="bottom"--}}
                {{--										  title="{{ data_get($post, 'postType.name') }}"--}}
                {{--									>--}}
                {{--										{{ strtoupper(mb_substr(data_get($post, 'postType.name'), 0, 1)) }}--}}
                {{--									</span>&nbsp;--}}
                {{--								@endif--}}
                {{--							@endif--}}
                {{--							@if (!config('settings.list.hide_dates'))--}}
                {{--								<span class="date"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>--}}
                {{--									<i class="far fa-clock"></i> {!! data_get($post, 'created_at_formatted') !!}--}}
                {{--								</span>--}}
                {{--							@endif--}}
                {{--							<span class="category"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>--}}
                {{--								<i class="bi bi-folder"></i>&nbsp;--}}
                {{--								@if (!empty(data_get($post, 'category.parent')))--}}
                {{--									<a href="{!! \App\Helpers\UrlGen::category(data_get($post, 'category.parent'), null, $city ?? null) !!}" class="info-link">--}}
                {{--										{{ data_get($post, 'category.parent.name') }}--}}
                {{--									</a>&nbsp;&raquo;&nbsp;--}}
                {{--								@endif--}}
                {{--								<a href="{!! \App\Helpers\UrlGen::category(data_get($post, 'category'), null, $city ?? null) !!}" class="info-link">--}}
                {{--									{{ data_get($post, 'category.name') }}--}}
                {{--								</a>--}}
                {{--							</span>--}}
                {{--							<span class="item-location"{!! (config('lang.direction')=='rtl') ? ' dir="rtl"' : '' !!}>--}}
                {{--								<i class="bi bi-geo-alt"></i>&nbsp;--}}
                {{--								<a href="{!! \App\Helpers\UrlGen::city(data_get($post, 'city'), null, $cat ?? null) !!}" class="info-link">--}}
                {{--									{{ data_get($post, 'city.name') }}--}}
                {{--								</a> {{ (!empty(data_get($post, 'distance'))) ? '- ' . round(data_get($post, 'distance'), 2) . getDistanceUnit() : '' }}--}}
                {{--							</span>--}}
                {{--						</span>--}}

                {{--						@if (config('plugins.reviews.installed'))--}}
                {{--							@if (view()->exists('reviews::ratings-list'))--}}
                {{--								@include('reviews::ratings-list')--}}
                {{--							@endif--}}
                {{--						@endif--}}
                {{--					</div>--}}
                {{--				</div>--}}

                {{--				<div class="col-sm-3 col-12 text-end price-box" style="white-space: nowrap;">--}}
                {{--					<div class="row w-100">--}}
                {{--						<div class="col-12 m-0 p-0 d-flex justify-content-end">--}}
                {{--							<h2 class="item-price">--}}
                {{--								{!! data_get($post, 'price_formatted') !!}--}}
                {{--							</h2>--}}
                {{--						</div>--}}
                {{--						<div class="col-12 m-0 p-0 d-flex justify-content-end">--}}
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
                {{--					</div>--}}
                {{--				</div>--}}
                {{--			</div>--}}
                {{--		</div>--}}
            @endif
        @endforeach
    </ul>
@else
    <div class="p-4 w-100">
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
