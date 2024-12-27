@extends('layouts.master')

@section('content')
<div class="container">
    <h1 class="mb-4">Добавить счет</h1>

    <form action="{{ route('bill.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="recipient" class="form-label">Получатель</label>
            <input type="text" class="form-control" id="recipient" name="recipient" value="{{ old('recipient') }}" required>
        </div>

        <div class="mb-3">
            <label for="inn" class="form-label">ИНН</label>
            <input type="text" class="form-control" id="inn" name="inn" value="{{ old('inn') }}" required>
        </div>

        <div class="mb-3">
            <label for="bank_name" class="form-label">Название банка</label>
            <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" required>
        </div>

        <div class="mb-3">
            <label for="bik" class="form-label">БИК банка</label>
            <input type="text" class="form-control" id="bik" name="bik" value="{{ old('bik') }}" required>
        </div>

        <div class="mb-3">
            <label for="correspondent_account" class="form-label">Корр. счет</label>
            <input type="text" class="form-control" id="correspondent_account" name="correspondent_account" value="{{ old('correspondent_account') }}" required>
        </div>

        <div class="mb-3">
            <label for="account_number" class="form-label">Номер счета</label>
            <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number') }}" required>
        </div>

        <button type="submit" class="btn btn-success">Сохранить</button>
    </form>
</div>
@endsection
