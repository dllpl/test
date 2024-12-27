

<h5>Счета клиента</h5>
<table class="table">
    <thead>
        <tr>
            <th>{{ trans('admin.Recipient') }}</th>
            <th>{{ trans('admin.INN') }}</th>
            <th>{{ trans('admin.Bank Name') }}</th>
            <th>{{ trans('admin.BIK') }}</th>
            <th>{{ trans('admin.Account Number') }}</th>
            <th>{{ trans('admin.actions') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($accounts as $account)
            <tr>
                <td>{{ $account->recipient }}</td>
                <td>{{ $account->inn }}</td>
                <td>{{ $account->bank_name }}</td>
                <td>{{ $account->bik }}</td>
                <td>{{ $account->account_number }}</td>
                <td>
                    <a href="{{ route('accounts.edit', ['id' => $account->id]) }}" class="btn btn-warning">{{ trans('admin.Edit') }}</a>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>


