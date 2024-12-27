@extends('admin.layouts.master')

@section('header')
    <div class="row page-titles">
        <div class="col-md-6 col-12 align-self-center">
            <h3 class="mb-0">
                Запросы на отмену сделок
            </h3>
        </div>
        <div class="col-md-6 col-12 align-self-center d-none d-md-flex justify-content-end">
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="{{ admin_url() }}">{{ trans('admin.dashboard') }}</a></li>
                <li class="breadcrumb-item active d-flex align-items-center">Запросы на отмену</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
        <h4 class="page-title">Список запросов на отмену сделок</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Пост</th>
                    <th>Продавец</th>
                    <th>Покупатель</th>
                    <th>Сумма сделки</th>
                    <th>Причина отмены</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deals as $deal)
                    <tr>
                        <td>{{ $deal->id }}</td>
                        <td>{{ $deal->post->title }}</td>
                        <td>{{ $deal->seller->name }}</td>
                        <td>{{ $deal->buyer->name }}</td>
                        <td>{{ $deal->deal_amount }} руб.</td>
                        <td>{{ $deal->cancellation_reason ?? 'Не указана' }}</td>
                        <td>{{ $deal->status }}</td>
                        <td>
                            <form action="{{ route('admin.deal.accept-cancellation', $deal->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">Принять отмену</button>
                            </form>
                            <form action="{{ route('admin.deal.reject-cancellation', $deal->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Отказать</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
