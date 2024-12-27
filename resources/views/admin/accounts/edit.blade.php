@extends('admin.layouts.master')

@section('content')

<h1>Редактирование счета</h1>

<form action="{{ route('accounts.update', ['id' => $account->id]) }}" method="POST">
    @csrf
    @method('PUT')
    
    <div class="form-group">
        <label for="recipient">{{ trans('admin.Recipient') }}</label>
        <input type="text" name="recipient" id="recipient" value="{{ old('recipient', $account->recipient) }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="inn">{{ trans('admin.INN') }}</label>
        <input type="text" name="inn" id="inn" value="{{ old('inn', $account->inn) }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="bank_name">{{ trans('admin.Bank Name') }}</label>
        <input type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $account->bank_name) }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="bik">{{ trans('admin.BIK') }}</label>
        <input type="text" name="bik" id="bik" value="{{ old('bik', $account->bik) }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="account_number">{{ trans('admin.Account Number') }}</label>
        <input type="text" name="account_number" id="account_number" value="{{ old('account_number', $account->account_number) }}" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">{{ trans('admin.Save Changes') }}</button>
</form>
@endsection