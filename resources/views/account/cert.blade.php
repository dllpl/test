@extends('layouts.master')

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
                        <use xlink:href="/images/sprite.svg#search-white"></use>
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
                    <button class="menu-nav__btn-open btn btn--form btn-reset">Открыть меню</button>
                    <div class="lk-product">
                        <h2 class="lk__title title title--medium">{{ $pageTitles[$pagePath]['title'] ?? 'Сертификация' }}</h2>
                        @if(\App\Helpers\SuperUser::status() === 1)
                            <span class="search__link link link--btn link--accent" style="padding: 10px;"><i class="fa fa-check-circle"></i> Сертифицирован</span>
                        @else
                            @if(\App\Helpers\SuperUser::status() === null)
                                <button data-url="{{route('super-user.send-request')}}" id="super_user_send_request" class="btn-reset search__link link link--btn link--accent" style="padding: 10px;">Пройти сертификацию</button>
                            @elseif(\App\Helpers\SuperUser::status() === 0)
                                <span class="search__link link link--btn link--accent" style="padding: 10px;"><i class="fa fa-clock"></i> Завявка в обработке</span>
                            @elseif(\App\Helpers\SuperUser::status() === 2)
                                <span class="search__link link link--btn link--accent" style="padding: 10px;"><i class="fa fa-times-circle"></i> Завявка отклонена</span>
                            @endif
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
            jsAlert('Отправляем запрос на сертификацию', 'warning');
            $.ajax({
                method: 'POST',
                url: $(this).data('url'),
                success: function (data) {
                    jsAlert(`${data.msg}. С Вами свяжется менеджер, ожидайте звонка`, 'success')
                    $('#super_user_send_request').attr('disabled', true)
                    $('#super_user_send_request').html('<i class="fa fa-clock"></i> Завявка в обработке')
                }
            });
        });
    </script>
@endsection
