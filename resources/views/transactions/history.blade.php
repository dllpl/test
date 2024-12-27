@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="mb-4">История операций</h1>

    @if ($transactions->isEmpty())
        <p>У вас пока нет операций.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Описание</th>
                    <th>Способ оплаты</th>
                    <th>Сумма</th>
                    <th>Дата</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->description }}</td>
                        <td>{{ $transaction->payment_method }}</td>
                        <td>{{ $transaction->amount }}</td>
                        <td>{{ $transaction->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            @if ($transaction->status === 'completed')
                                <span class="text-success">Завершено</span>
                            @elseif ($transaction->status === 'pending')
                                <span class="text-warning">В обработке</span>
                            @else
                                <span class="text-danger">Неудачно</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
