@extends('layouts.master')

@section('content')
    <section class="search">
        <div class="search__container container">
            <a href="{{ \App\Helpers\UrlGen::searchWithoutQuery() }}" class="search__link link link--btn link--accent">Все объявления</a>

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
                    @if(\App\Helpers\SuperUser::status() === null)
                            <h2 class="lk__title title title--medium">Аккредитация</h2>
                        @elseif(\App\Helpers\SuperUser::status() === 0)
                            <h2 class="lk__title title title--medium">Ваша заявка в обработке. Пожалуйста, ожидайте решения</h2>
                        @elseif(\App\Helpers\SuperUser::status() === 2)
                            <h2 class="lk__title title title--medium">Завявка отклонена</h2>
                        @elseif(\App\Helpers\SuperUser::status() === 1)
                            <h2 class="lk__title title title--medium"><img src="/images/logomost.png" width="48px"> Вы аккредитованный пользователь AUTOMOST</h2>
                        @endif
                        <h3 class="mb-3 title">Аккредитованный пользователь</h3>
                        <p class="mb-3">Отметку «Аккредитованный пользователь» могут получить только профессиональные пользователи AUTOMOST, которые подтвердят свою профессиональную деятельность в автомобильной сфере. Для подтверждения вашего аккаунта менеджер сервиса может попросить предоставить дополнительные сведения, подтверждающие вашу профессиональную деятельность.</p>
                        @if(\App\Helpers\SuperUser::status() !== 1)
                            <h3 class="mb-3 title">Как получить аккредитацию?</h3>
                            <p class="mb-3">Для получения отметки «Аккредитованный пользователь» нажмите на кнопку ниже, для автоматического формирования заявки на прохождение проверки. Позже менеджер сервиса свяжется с вами для уточнения деталей вашей деятельности и подтверждения аккаунта.
</p>
                        @endif
                        <h3 class="mb-3 title">Что доступно аккредитованному пользователю?</h3>
                        <ul class="mb-3">
                            <li style="list-style-type: disc">Аккредитованные пользователи получают уникальную возможность увидеть объявления о продаже или поиске автомобилей первыми в течение 12 часов. Обычные пользователи увидят объявления только через 12 часов после его публикации.
                            </li>
                            <li style="list-style-type: disc">Аккредитованные пользователи отмечаются специальной пометкой “Проверенный пользователь”
                            </li>
                        </ul>
                        @if(\App\Helpers\SuperUser::status() === null)
                            <button data-url="{{route('super-user.send-request')}}" id="super_user_send_request"
                                    class="btn-reset search__link link link--btn link--accent" style="padding: 10px;">Подать заявку на аккредитацию
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
            jsAlert('Отправляем запрос на аккредитацию', 'warning');
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
