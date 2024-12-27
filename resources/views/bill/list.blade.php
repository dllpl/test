@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Мои счета</h1>

    <a href="{{ route('bill.add') }}" class="btn btn-primary mb-4">Добавить счет</a>

    @if ($accounts->isEmpty())
        <p>У вас пока нет счетов.</p>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Получатель</th>
                    <th>ИНН</th>
                    <th>Название банка</th>
                    <th>БИК</th>
                    <th>Корр. счет</th>
                    <th>Номер счета</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                    <tr>
                        <td>{{ $account->id }}</td>
                        <td>{{ $account->recipient }}</td>
                        <td>{{ $account->inn }}</td>
                        <td>{{ $account->bank_name }}</td>
                        <td>{{ $account->bik }}</td>
                        <td>{{ $account->correspondent_account }}</td>
                        <td>{{ $account->account_number }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
