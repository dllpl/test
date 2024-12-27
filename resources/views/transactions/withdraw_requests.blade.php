@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Запросы на вывод</h1>

    @if($withdrawRequests->isEmpty())
        <p>У вас пока нет запросов на вывод средств.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Сумма</th>
                    <th>Дата создания</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                @foreach($withdrawRequests as $request)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ number_format($request->amount, 2) }} руб.</td>
                        <td>{{ $request->created_at->format('d.m.Y H:i') }}</td>
                        <td>
                            @if ($request->status === 'pending')
                                <span class="badge bg-warning">В ожидании</span>
                            @elseif ($request->status === 'approved')
                                <span class="badge bg-success">Одобрено</span>
                            @elseif ($request->status === 'rejected')
                                <span class="badge bg-danger">Отклонено</span>
                            @else
                                <span class="badge bg-secondary">Неизвестно</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
