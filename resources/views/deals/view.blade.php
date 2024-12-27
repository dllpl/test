@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h2>Просмотр сделки</h2>

    <!-- Информация о сделке -->
    <div class="card mb-4">
        <div class="card-header">
            Детали сделки
        </div>
        <div class="card-body">
            <p><strong>ID сделки:</strong> {{ $deal->id }}</p>
            <p><strong>Пост:</strong> {{ $deal->post->title }}</p>
            <p><strong>Исполнитель:</strong> {{ $deal->seller->name }}</p>
            <p><strong>Покупатель:</strong> {{ $deal->buyer->name }}</p>
            <p><strong>Сумма сделки:</strong> {{ $deal->deal_amount }} {{ config('settings.currency') }}</p>
            <p><strong>Статус:</strong> {{ $deal->status }}</p>
            <p><strong>Назначенное время:</strong> {{ $deal->desired_time }}</p>
            <p><strong>VIN номер:</strong> {{ $deal->vin_number }}</p>
        </div>
    </div>

    <!-- Кнопки действий -->
    <div>
        @if(auth()->id() == $deal->seller_id)
            <!-- Действия для покупателя -->
            @if($deal->status == 'created')
                <form action="{{ route('deal.accept', $deal->id) }}" method="post" class="d-inline-block">
                    @csrf
                    <button type="submit" class="btn btn-success">Принять сделку</button>
                </form>
                <form action="{{ route('deal.reject', $deal->id) }}" method="post" class="d-inline-block">
                    @csrf
                    <button type="submit" class="btn btn-danger">Отклонить сделку</button>
                </form>
            @elseif($deal->status == 'выполняется')
                <form action="{{ route('deal.request_cancel', $deal->id) }}" method="post" class="d-inline-block">
                    @csrf
                    <button type="button" class="btn btn-warning" onclick="openCancelPopup()">Запросить отмену</button>
                </form>
            @endif
        @elseif(auth()->id() == $deal->buyer_id)
            <!-- Действия для продавца -->
            @if($deal->status == 'выполняется')
                <form action="{{ route('deal.request_cancel', $deal->id) }}" method="post" class="d-inline-block">
                    @csrf
                    <button type="button" class="btn btn-warning" onclick="openCancelPopup()">Запросить отмену</button>
                </form>

                <form action="{{ route('deal.complete', $deal->id) }}" method="post" class="d-inline-block">
                    @csrf
                    <button type="submit" class="btn btn-primary">Принять выполнение</button>
                </form>
            @endif
        @endif
    </div>

    <!-- POPUP для отмены сделки -->
    <div id="cancelPopup" style="display:none;">
        <form action="{{ route('deal.request_cancel', $deal->id) }}" method="post">
            @csrf
            <label for="cancel_reason">Введите причину отмены:</label>
            <textarea name="cancel_reason" id="cancel_reason" class="form-control" rows="3" required></textarea>
            <button type="submit" class="btn btn-danger mt-2">Отправить запрос отмены</button>
            <button type="button" class="btn btn-secondary mt-2" onclick="closeCancelPopup()">Закрыть</button>
        </form>
    </div>
</div>

<script>
    function openCancelPopup() {
        document.getElementById('cancelPopup').style.display = 'block';
    }
    function closeCancelPopup() {
        document.getElementById('cancelPopup').style.display = 'none';
    }
</script>
@endsection
