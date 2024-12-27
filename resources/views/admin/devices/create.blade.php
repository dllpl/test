@extends('admin.layouts.master')

@section('content')
<div class="container">
    <h1>Добавить устройство для: {{ $user->name }}</h1>
    <form action="{{ route('devices.store') }}" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{ $user->id }}">

        <div class="form-group">
            <label for="serial_number">Серийный номер</label>
            <input type="text" name="serial_number" id="serial_number" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="contract_number">Номер договора</label>
            <input type="text" name="contract_number" id="contract_number" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Добавить</button>
    </form>
</div>
@endsection
