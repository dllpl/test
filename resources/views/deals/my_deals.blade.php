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

<div class="container mt-4">
    <h1 class="mb-4">Мои сделки</h1>

    @if($deals->isEmpty())
        <p>У вас пока нет сделок.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>№ сделки</th>
                    <th>Дата сделки</th>
                    <th>Исполнитель</th>
                    <th>Назначенное время</th>
                    <th>Сумма сделки</th>
                    <th>Статус сделки</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deals as $deal)
                <tr id="deal-{{ $deal->id }}">
                    <td>{{ $deal->id }}</td>
                    <td>{{ $deal->created_at->format('d.m.Y H:i') }}</td>
                    <td>
                        @if($deal->seller_id == auth()->id())
                            Вы
                        @else
                            {{ $deal->seller->name ?? 'N/A' }}
                        @endif
                    </td>
                    <td>{{ $deal->desired_time }}</td>
                    <td>{{ $deal->deal_amount }} руб.</td>
                    <td>{{ ucfirst($deal->status) }}</td>
                    <td>
                        <!-- Если статус сделки "created", показываем таймер и кнопку принятия -->
                        @if($deal->status == 'created')
                            <div id="timer-{{ $deal->id }}" class="timer">
                                <p>Оставшееся время: <span id="time-{{ $deal->id }}"></span></p>
                                @if (now() > \Carbon\Carbon::parse($deal->created_at)->addMinutes($deal->timer))
                                    
                                @else
                                    <a href="{{ route('deals.view', $deal->id) }}" class="btn btn-sm btn-primary">Просмотр</a>
                                @endif
                            </div>
                        <!-- Если статус не "created", показываем только кнопку "Просмотр" -->
                        @else
                            <a href="{{ route('deals.view', $deal->id) }}" class="btn btn-sm btn-primary">Просмотр</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection

@section('after_styles')
    <style>
        .action-td p {
            margin-bottom: 5px;
        }

        .timer {
            margin-top: 10px;
        }

        .timer p {
            font-weight: bold;
        }

        .btn {
            margin-top: 10px;
        }

        .expired-message {
            color: red;
            font-weight: bold;
        }
    </style>
@endsection

@section('after_scripts')
    <script src="{{ url('assets/js/footable.js?v=2-0-1') }}" type="text/javascript"></script>
    <script src="{{ url('assets/js/footable.filter.js?v=2-0-1') }}" type="text/javascript"></script>

    <script>
        // Массив для хранения информации о сделках
        var dealsData = [];

        @foreach($deals as $deal)
            var dealCreatedAt = new Date("{{ \Carbon\Carbon::parse($deal->created_at)->format('Y-m-d H:i:s') }} UTC");

            var timerMinutes = {{ $deal->timer }}; // Количество минут таймера
            var dealTime = new Date(dealCreatedAt.getTime() + timerMinutes * 60000); // Считаем конечное время

            // Добавляем данные о сделке в массив
            dealsData.push({
                id: {{ $deal->id }},
                dealTime: dealTime,
                timerElement: document.getElementById("time-{{ $deal->id }}"),
                acceptButton: document.getElementById("accept-btn-{{ $deal->id }}"),
                dealRow: document.getElementById("deal-{{ $deal->id }}")
            });
        @endforeach

        // Функция для обновления всех таймеров
        function updateTimers() {
            var now = new Date();

            dealsData.forEach(function(deal) {
                var timeDiff = deal.dealTime - now;

                if (timeDiff <= 0) {
                    deal.timerElement.innerHTML = "Время истекло.";
                    if (deal.acceptButton) {
                        deal.acceptButton.style.display = "none";
                    }
                    deal.dealRow.querySelector(".timer p").innerHTML = "Время принятия сделки истекло.";
                } else {
                    var minutes = Math.floor(timeDiff / 60000);
                    var seconds = Math.floor((timeDiff % 60000) / 1000);
                    deal.timerElement.innerHTML = minutes + " минут " + seconds + " секунд";
                }
            });
        }

        // Обновляем таймеры каждую секунду
        setInterval(updateTimers, 1000);
        updateTimers(); // Начальная настройка таймеров
    </script>
@endsection

