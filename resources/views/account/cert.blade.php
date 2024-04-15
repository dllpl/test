@extends('layouts.master')

@section('content')
    <section class="search">
        <div class="search__container container">
            <a href="{{ \App\Helpers\UrlGen::searchWithoutQuery() }}" class="search__link link link--btn link--accent">{{ t('all_ads') }}</a>

            <form id="search" name="search" action="{{ \App\Helpers\UrlGen::searchWithoutQuery() }}" method="GET"
                  class="search__form form-search">
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

    <section class="lk">
        <div class="lk__container container">

            @if (session()->has('flash_notification'))
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-12">
                            @include('flash::message')
                        </div>
                    </div>
                </div>
            @endif

            <div class="lk__wrapp">
                @includeFirst([config('larapen.core.customizedViewPath') . 'account.inc.sidebar', 'account.inc.sidebar'])

                <div class="lk__content">
                    <button class="menu-nav__btn-open btn btn--form btn-reset">{{t('open_mobile_menu')}}</button>
                    <div class="lk-product">
                    @if(\App\Helpers\SuperUser::status() === null)
                            <h2 class="lk__title title title--medium">{{t('accreditation')}}</h2>
                        @elseif(\App\Helpers\SuperUser::status() === 0)
                            <h2 class="lk__title title title--medium">{{t('accred_on_proccess')}}</h2>
                        @elseif(\App\Helpers\SuperUser::status() === 2)
                            <h2 class="lk__title title title--medium">{{t('accred_close')}}</h2>
                        @elseif(\App\Helpers\SuperUser::status() === 1)
                            <h2 class="lk__title title title--medium"><img src="/images/logomost.png" width="48px">{{t('accred_user')}}</h2>
                        @endif
                        <h3 class="mb-3 title">{{t('accred_user_yes')}}</h3>
                        <p class="mb-3">{{t('how_to_get_accred')}}</p>
                        @if(\App\Helpers\SuperUser::status() !== 1)
                            <h3 class="mb-3 title">{{t('how_to_get_accred?')}}</h3>
                            <p class="mb-3">{{t('how_to_get_accred_info')}}</p>
                        @endif
                        <h3 class="mb-3 title">{{t('avail_accred_user?')}}</h3>
                        <ul class="mb-3">
                            <li style="list-style-type: disc">{{t('accred_avail_1')}}
                            </li>
                            <li style="list-style-type: disc">{{t('accred_avail_2')}}
                            </li>
                        </ul>
                        @if(\App\Helpers\SuperUser::status() === null)
                            <button data-url="{{route('super-user.send-request')}}" id="super_user_send_request"
                                    class="btn-reset search__link link link--btn link--accent" style="padding: 10px;">{{t('make_accred')}}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('after_scripts')
    <script>
        $('#super_user_send_request').on('click', function () {
            jsAlert({{t('make_accred')}}, 'warning');
            $.ajax({
                method: 'POST',
                url: $(this).data('url'),
                success: function (data) {
                    jsAlert(`${data.msg}. {{t('manager_contact_you')}}`, 'success')
                    $('#super_user_send_request').attr('disabled', true)
                    $('#super_user_send_request').html('<i class="fa fa-clock"></i> {{t('accred_on_proccess')}}')
                }
            });
        });
    </script>
@endsection
