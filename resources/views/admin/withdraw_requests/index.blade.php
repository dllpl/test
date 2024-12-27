@extends('admin.layouts.master')

@section('after_styles')
    {{-- Ladda Buttons (loading buttons) --}}
    <link href="{{ asset('assets/plugins/ladda/ladda-themeless.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('header')
    <div class="row page-titles">
        <div class="col-md-6 col-12 align-self-center">
            <h3 class="mb-0">
                Запросы на вывод
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
<div class="container">
    <h1>{{ trans('admin.withdraw_requests') }}</h1>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ trans('admin.user') }}</th>
                <th>{{ trans('admin.amount') }}</th>
                <th>{{ trans('admin.status') }}</th>
                <th>{{ trans('admin.account_details') }}</th>
                <th>{{ trans('admin.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($withdrawRequests as $request)
                <tr>
                    <td>{{ $request->id }}</td>
                    <td>{{ $request->user->name }}</td>
                    <td>{{ $request->amount }}</td>
                    <td>{{ $request->status }}</td>
                    <td>
                        @if ($request->account)
                            <strong>{{ trans('admin.recipient') }}:</strong> {{ $request->account->recipient }}<br>
                            <strong>{{ trans('admin.inn') }}:</strong> {{ $request->account->inn }}<br>
                            <strong>{{ trans('admin.bank_name') }}:</strong> {{ $request->account->bank_name }}<br>
                            <strong>{{ trans('admin.bik') }}:</strong> {{ $request->account->bik }}<br>
                            <strong>{{ trans('admin.correspondent_account') }}:</strong> {{ $request->account->correspondent_account }}<br>
                            <strong>{{ trans('admin.account_number') }}:</strong> {{ $request->account->account_number }}
                        @else
                            {{ trans('admin.no_account_details') }}
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('withdraw.requests.update', $request->id) }}" method="POST">
                            @csrf
                            <select name="status" onchange="this.form.submit()">
                                <option value="pending" {{ $request->status == 'pending' ? 'selected' : '' }}>Ожидает</option>
                                <option value="approved" {{ $request->status == 'approved' ? 'selected' : '' }}>Выплачено</option>
                                <option value="rejected" {{ $request->status == 'rejected' ? 'selected' : '' }}>Отказано</option>
                            </select>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $withdrawRequests->links() }}
</div>
@endsection
