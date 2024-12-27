<!-- resources/views/deal/create.blade.php -->

@extends('layouts.master')

@section('content')
<div class="container deal">
    <h1>Создание сделки</h1>

    <!-- Вывод ошибок -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Вывод ошибки, если недостаточно средств -->
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Вывод информации о посте -->
    <div class="mb-4">
        <h3>{{ $deal_post->title }}</h3>
        <p><strong>Цена:</strong> {{ $deal_post->price }} {{ config('settings.currency') }}</p>
        <p><strong>Продавец:</strong> {{ $deal_post->user->name }} ({{ $deal_post->user->email }})</p>
    </div>

    <!-- Форма для создания сделки -->
    <form action="{{ route('deal.store') }}" method="post">
        @csrf

        <input type="number" name="deal_amount" id="deal_amount" class="form-control" value="{{ $deal_post->price }}" hidden required>

        <input type="text" name="seller_id" id="seller_id" value="{{ $deal_post->user->id }}" hidden>

        <input type="text" name="post_id" id="post_id" value="{{ $deal_post->id }}" hidden>

        <input type="number" name="commission" id="commission" class="form-control" value="10" hidden required>


        <input type="number" name="timer" id="timer" class="form-control" value="5" hidden required>

        <div class="form-group">
            <label for="desired_datetime">Желаемая дата и время</label>
            <input type="datetime-local" name="desired_datetime" id="desired_datetime" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="vin_number">VIN номер</label>
            <input type="text" name="vin_number" id="vin_number" class="form-control" required>
        </div>

        <input type="hidden" name="deal_post_id" value="{{ $deal_post->id }}">

        <button type="submit" class="btn btn-primary">Создать сделку</button>
    </form>
</div>

<style>
    p {
        margin-top: 10px;
    }

    .deal {
        margin-top: 60px;
    }

    h1 {
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    button {
        margin-bottom: 50px;
    }
</style>
@endsection
