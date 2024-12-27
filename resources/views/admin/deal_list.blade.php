@extends('admin.layouts.master')

@section('after_styles')
    {{-- Ladda Buttons (loading buttons) --}}
    <link href="{{ asset('assets/plugins/ladda/ladda-themeless.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('header')
    <div class="row page-titles">
        <div class="col-md-6 col-12 align-self-center">
            <h3 class="mb-0">
                Список сделок
            </h3>
        </div>
        <div class="col-md-6 col-12 align-self-center d-none d-md-flex justify-content-end">
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="{{ admin_url() }}">{{ trans('admin.dashboard') }}</a></li>
                <li class="breadcrumb-item active d-flex align-items-center">{{t('accreditation')}}</li>
            </ol>
        </div>
    </div>
@endsection

@section('content')
    <div class="container-fluid">
    <h4 class="page-title">Статистика системы</h4>

<!-- Статистика -->
<div class="row mb-4">
    <!-- Количество завершённых сделок -->
    <div class="col-lg-3 col-3">
        <div class="card bg-success rounded shadow">
            <div class="card-body">
                <div class="row py-1">
                    <div class="col-8 d-flex align-items-center">
                        <div>
                            <h2 class="fw-light">
                                <span class="text-white" style="font-weight: bold;">
                                    {{ $completedDealsCount }}
                                </span>
                            </h2>
                            <h6 class="text-white">
                                Завершённые сделки
                            </h6>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center justify-content-end">
                        <span class="text-white display-6">
                            <i class="fa fa-check-circle"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Количество отменённых сделок -->
    <div class="col-lg-3 col-3">
        <div class="card bg-danger rounded shadow">
            <div class="card-body">
                <div class="row py-1">
                    <div class="col-8 d-flex align-items-center">
                        <div>
                            <h2 class="fw-light">
                                <span class="text-white" style="font-weight: bold;">
                                    {{ $cancelledDealsCount }}
                                </span>
                            </h2>
                            <h6 class="text-white">
                                Отменённые сделки
                            </h6>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center justify-content-end">
                        <span class="text-white display-6">
                            <i class="fa fa-times-circle"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Сумма всех сделок -->
    <div class="col-lg-3 col-3">
        <div class="card bg-info rounded shadow">
            <div class="card-body">
                <div class="row py-1">
                    <div class="col-8 d-flex align-items-center">
                        <div>
                            <h2 class="fw-light">
                                <span class="text-white" style="font-weight: bold;">
                                    {{ number_format($totalAmount, 2) }} ₽
                                </span>
                            </h2>
                            <h6 class="text-white">
                                Сумма всех сделок
                            </h6>
                        </div>
                    </div>
                    <div class="col-4 d-flex align-items-center justify-content-end">
                        <span class="text-white display-6">
                            <i class="fa fa-dollar-sign"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Заработанная комиссия -->
    <div class="col-lg-3 col-3">
            <div class="card bg-warning rounded shadow">
                <div class="card-body">
                    <div class="row py-1">
                        <div class="col-8 d-flex align-items-center">
                            <div>
                                <h2 class="fw-light">
                                    <span class="text-white" style="font-weight: bold;">
                                        {{ number_format($totalCommissions, 2) }} ₽
                                    </span>
                                </h2>
                                <h6 class="text-white">
                                    Заработано комиссий
                                </h6>
                            </div>
                        </div>
                        <div class="col-4 d-flex align-items-center justify-content-end">
                            <span class="text-white display-6">
                                <i class="fa fa-money-bill-alt"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <h4 class="page-title">Список сделок</h4>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Пост</th>
                    <th>Продавец</th>
                    <th>Покупатель</th>
                    <th>Сумма сделки</th>
                    <th>Комиссия</th>
                    <th>Таймер</th>
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
                        <td>{{ $deal->commission }}%</td>
                        <td>{{ $deal->timer }} мин.</td>
                        <td>{{ $deal->status }}</td>
                        <td>
                            <form action="{{ route('admin.deal.update-status', $deal->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-control" onchange="this.form.submit()">
                                    <option value="выполняется" {{ $deal->status == 'выполняется' ? 'selected' : '' }}>Выполняется</option>
                                    <option value="завершена" {{ $deal->status == 'завершена' ? 'selected' : '' }}>Завершена</option>
                                    <option value="отклонена" {{ $deal->status == 'отклонена' ? 'selected' : '' }}>Отклонена</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
