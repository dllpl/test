<?php
$sectionOptions = $getCategoriesOp ?? [];
$sectionData ??= [];
$categories = (array)data_get($sectionData, 'categories');
$subCategories = (array)data_get($sectionData, 'subCategories');
$countPostsPerCat = (array)data_get($sectionData, 'countPostsPerCat');
$countPostsPerCat = collect($countPostsPerCat)->keyBy('id')->toArray();

$hideOnMobile = (data_get($sectionOptions, 'hide_on_mobile') == '1') ? ' hidden-sm' : '';

$catDisplayType = data_get($sectionOptions, 'cat_display_type');
$maxSubCats = (int)data_get($sectionOptions, 'max_sub_cats');

$counter = 0;
?>
@includeFirst([config('larapen.core.customizedViewPath') . 'home.inc.spacer', 'home.inc.spacer'], ['hideOnMobile' => $hideOnMobile])

{{--Новое--}}

@if ($catDisplayType == 'c_picture_list')
    <section class="d-block d-md-none">
        <div class="container">
            <div class="swiper catalog-promo__slider">
                <div class="swiper-wrapper">
                    @if (!empty($categories))
                        @foreach($categories as $key => $cat)
                            <div class="swiper-slide">
                                <a href="{{ \App\Helpers\UrlGen::category($cat) }}" class="catalog-promo__link link">
                                    <div class="catalog-promo__img-wrapp">
                                        <img class="catalog-promo__img img" src="{{ data_get($cat, 'picture_url') }}"
                                             alt="{{ data_get($cat, 'name') }}">
                                    </div>
                                    <div class="catalog-promo__content-wrapp">
                                        <div class="catalog-promo__content-desc">
                                            <h4 class="catalog-promo__title title">{{ data_get($cat, 'name') }}</h4>
                                            <p class="catalog-promo__subtitle">{{ strip_tags(data_get($cat, 'description')) }}</p>
                                        </div>
                                        <svg class="catalog-promo__icon">
                                            <use xlink:href="images/sprite.svg#arrow-green"></use>
                                        </svg>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </section>

    @if (!empty($categories))
        @php
            if(count($categories) >= 4) {
                $last_cat = last($categories);
                array_pop($categories);
            }
        @endphp
        <section class="catalog-promo">
            <div class="catalog-promo__container container">
                <ul class="catalog-promo__list list-reset">
                    @foreach($categories as $key => $cat)
                        <li class="catalog-promo__item">
                            <a href="{{ \App\Helpers\UrlGen::category($cat) }}" class="catalog-promo__link link">
                                <div class="catalog-promo__img-wrapp">
                                    <img class="catalog-promo__img img" src="{{ data_get($cat, 'picture_url') }}"
                                         alt="{{ data_get($cat, 'name') }}">
                                </div>
                                <div class="catalog-promo__content-wrapp">
                                    <div class="catalog-promo__content-desc">
                                        <h4 class="catalog-promo__title title">{{ data_get($cat, 'name') }}</h4>
                                        <p class="catalog-promo__subtitle">{{ strip_tags(data_get($cat, 'description')) }}</p>
                                    </div>
                                    <svg class="catalog-promo__icon">
                                        <use xlink:href="images/sprite.svg#arrow-green"></use>
                                    </svg>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
                @if(isset($last_cat))
                    @php
                        foreach ($subCategories['items'] as $key => $value) {
                            if ($value["parent_id"] == $last_cat['id']) {
                                $subCategory[] = $value;
                            }
                        }
                    @endphp
                    <div class="d-flex mt-3 bg-white" style="box-shadow: 0px 0px 20px 5px rgba(146, 159, 169, 0.15); gap: 15px">

                        <div class="catalog-promo__img-wrapp" style="width: 24%">
                            <img class="catalog-promo__img img" src="{{ data_get($last_cat, 'picture_url') }}"
                                 alt="{{ data_get($last_cat, 'name') }}">
                        </div>
                        <div class="col-9 p-0 ml-4">
                            <div class="d-flex flex-column justify-content-around p-2 h-100">
                                <a href="{{ \App\Helpers\UrlGen::category($last_cat) }}">
                                    <h4 class="catalog-promo__title title">{{ data_get($last_cat, 'name') }}</h4>
                                </a>
                                <ul class="d-flex">
                                    @foreach($subCategory as $sub)
                                        <li style="margin-right: 0.5rem">
                                            <a href="{{ \App\Helpers\UrlGen::category($sub) }}">
                                                {{$sub['name']}}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                    </div>
                @endif
            </div>
        </section>
    @endif

@endif

{{--<div class="container{{ $hideOnMobile }}">--}}
{{--    <div class="col-xl-12 content-box layout-section">--}}
{{--        <div class="row row-featured row-featured-category">--}}
{{--            <div class="col-xl-12 box-title no-border">--}}
{{--                <div class="inner">--}}
{{--                    <h2>--}}
{{--                        <span class="title-3">{{ t('Browse by') }} <span--}}
{{--                                    style="font-weight: bold;">{{ t('category') }}</span></span>--}}
{{--                        <a href="{{ \App\Helpers\UrlGen::sitemap() }}" class="sell-your-item">--}}
{{--                            {{ t('View more') }} <i class="fas fa-bars"></i>--}}
{{--                        </a>--}}
{{--                    </h2>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            @if ($catDisplayType == 'c_picture_list')--}}

{{--                @if (!empty($categories))--}}
{{--                    @foreach($categories as $key => $cat)--}}
{{--                        <div class="col-lg-2 col-md-3 col-sm-4 col-6 f-category">--}}
{{--                            <a href="{{ \App\Helpers\UrlGen::category($cat) }}">--}}
{{--                                <img src="{{ data_get($cat, 'picture_url') }}" class="lazyload img-fluid"--}}
{{--                                     alt="{{ data_get($cat, 'name') }}">--}}
{{--                                <h6>--}}
{{--                                    {{ data_get($cat, 'name') }}--}}
{{--                                    @if (config('settings.list.count_categories_listings'))--}}
{{--                                        &nbsp;({{ $countPostsPerCat[data_get($cat, 'id')]['total'] ?? 0 }})--}}
{{--                                    @endif--}}
{{--                                </h6>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                @endif--}}

{{--            @elseif ($catDisplayType == 'c_bigIcon_list')--}}

{{--                @if (!empty($categories))--}}
{{--                    @foreach($categories as $key => $cat)--}}
{{--                        <div class="col-lg-2 col-md-3 col-sm-4 col-6 f-category">--}}
{{--                            <a href="{{ \App\Helpers\UrlGen::category($cat) }}">--}}
{{--                                @if (in_array(config('settings.list.show_category_icon'), [2, 6, 7, 8]))--}}
{{--                                    <i class="{{ data_get($cat, 'icon_class') ?? 'fas fa-folder' }}"></i>--}}
{{--                                @endif--}}
{{--                                <h6>--}}
{{--                                    {{ data_get($cat, 'name') }}--}}
{{--                                    @if (config('settings.list.count_categories_listings'))--}}
{{--                                        &nbsp;({{ $countPostsPerCat[data_get($cat, 'id')]['total'] ?? 0 }})--}}
{{--                                    @endif--}}
{{--                                </h6>--}}
{{--                            </a>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                @endif--}}

{{--            @elseif (in_array($catDisplayType, ['cc_normal_list', 'cc_normal_list_s']))--}}

{{--                <div style="clear: both;"></div>--}}
{{--                    <?php $styled = ($catDisplayType == 'cc_normal_list_s') ? ' styled' : ''; ?>--}}

{{--                @if (!empty($categories))--}}
{{--                    <div class="col-xl-12">--}}
{{--                        <div class="list-categories-children{{ $styled }}">--}}
{{--                            <div class="row px-3">--}}
{{--                                @foreach ($categories as $key => $cols)--}}
{{--                                    <div class="col-md-4 col-sm-4 {{ (count($categories) == $key+1) ? 'last-column' : '' }}">--}}
{{--                                        @foreach ($cols as $iCat)--}}

{{--                                                <?php--}}
{{--                                                $randomId = '-' . substr(uniqid(rand(), true), 5, 5);--}}
{{--                                                ?>--}}

{{--                                            <div class="cat-list">--}}
{{--                                                <h3 class="cat-title rounded">--}}
{{--                                                    @if (in_array(config('settings.list.show_category_icon'), [2, 6, 7, 8]))--}}
{{--                                                        <i class="{{ data_get($iCat, 'icon_class') ?? 'fas fa-check' }}"></i>--}}
{{--                                                        &nbsp;--}}
{{--                                                    @endif--}}
{{--                                                    <a href="{{ \App\Helpers\UrlGen::category($iCat) }}">--}}
{{--                                                        {{ data_get($iCat, 'name') }}--}}
{{--                                                        @if (config('settings.list.count_categories_listings'))--}}
{{--                                                            &nbsp;--}}
{{--                                                            ({{ $countPostsPerCat[data_get($iCat, 'id')]['total'] ?? 0 }}--}}
{{--                                                            )--}}
{{--                                                        @endif--}}
{{--                                                    </a>--}}
{{--                                                    <span class="btn-cat-collapsed collapsed"--}}
{{--                                                          data-bs-toggle="collapse"--}}
{{--                                                          data-bs-target=".cat-id-{{ data_get($iCat, 'id') . $randomId }}"--}}
{{--                                                          aria-expanded="false"--}}
{{--                                                    >--}}
{{--														<span class="icon-down-open-big"></span>--}}
{{--													</span>--}}
{{--                                                </h3>--}}
{{--                                                <ul class="cat-collapse collapse show cat-id-{{ data_get($iCat, 'id') . $randomId }} long-list-home">--}}
{{--                                                    @if (isset($subCategories[data_get($iCat, 'id')]))--}}
{{--                                                            <?php $catSubCats = $subCategories[data_get($iCat, 'id')]; ?>--}}
{{--                                                        @foreach ($catSubCats as $iSubCat)--}}
{{--                                                            <li>--}}
{{--                                                                <a href="{{ \App\Helpers\UrlGen::category($iSubCat) }}">--}}
{{--                                                                    {{ data_get($iSubCat, 'name') }}--}}
{{--                                                                </a>--}}
{{--                                                                @if (config('settings.list.count_categories_listings'))--}}
{{--                                                                    &nbsp;--}}
{{--                                                                    ({{ $countPostsPerCat[data_get($iSubCat, 'id')]['total'] ?? 0 }}--}}
{{--                                                                    )--}}
{{--                                                                @endif--}}
{{--                                                            </li>--}}
{{--                                                        @endforeach--}}
{{--                                                    @endif--}}
{{--                                                </ul>--}}
{{--                                            </div>--}}
{{--                                        @endforeach--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div style="clear: both;"></div>--}}
{{--                    </div>--}}
{{--                @endif--}}

{{--            @else--}}

{{--                    <?php--}}
{{--                    $listTab = [--}}
{{--                        'c_border_list' => 'list-border',--}}
{{--                    ];--}}
{{--                    $catListClass = (isset($listTab[$catDisplayType])) ? 'list ' . $listTab[$catDisplayType] : 'list';--}}
{{--                    ?>--}}
{{--                @if (!empty($categories))--}}
{{--                    <div class="col-xl-12">--}}
{{--                        <div class="list-categories">--}}
{{--                            <div class="row">--}}
{{--                                @foreach ($categories as $key => $items)--}}
{{--                                    <ul class="cat-list {{ $catListClass }} col-md-4 {{ (count($categories) == $key+1) ? 'cat-list-border' : '' }}">--}}
{{--                                        @foreach ($items as $k => $cat)--}}
{{--                                            <li>--}}
{{--                                                @if (in_array(config('settings.list.show_category_icon'), [2, 6, 7, 8]))--}}
{{--                                                    <i class="{{ data_get($cat, 'icon_class') ?? 'fas fa-check' }}"></i>--}}
{{--                                                    &nbsp;--}}
{{--                                                @endif--}}
{{--                                                <a href="{{ \App\Helpers\UrlGen::category($cat) }}">--}}
{{--                                                    {{ data_get($cat, 'name') }}--}}
{{--                                                </a>--}}
{{--                                                @if (config('settings.list.count_categories_listings'))--}}
{{--                                                    &nbsp;({{ $countPostsPerCat[data_get($cat, 'id')]['total'] ?? 0 }})--}}
{{--                                                @endif--}}
{{--                                            </li>--}}
{{--                                        @endforeach--}}
{{--                                    </ul>--}}
{{--                                @endforeach--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}

{{--            @endif--}}

{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

@section('before_scripts')
    @parent
    @if ($maxSubCats >= 0)
        <script>
            var maxSubCats = {{ $maxSubCats }};
        </script>
    @endif
@endsection

@section('after_styles')
    @parent
    <link href="{{ url('assets/plugins/swiper/7.4.1/swiper-bundle.min.css') }}" rel="stylesheet"/>
@endsection

@section('after_scripts')
    @parent
    <script src="{{ url('assets/plugins/swiper/7.4.1/swiper-bundle.min.js') }}"></script>
    <script>
        const swiper_catalog = new Swiper(".catalog-promo__slider", {
            slidesPerView: 1.5,
            spaceBetween: 10,
        });
    </script>
@endsection
