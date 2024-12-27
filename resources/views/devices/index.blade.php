@extends('layouts.master')

@section('content')
<div class="container">
    <h2>Мои устройства</h2>

    @if ($devices->isEmpty())
        <p>У вас пока нет устройств.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Серийный номер</th>
                    <th>Номер договора</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($devices as $index => $device)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $device->serial_number }}</td>
                        <td>{{ $device->contract_number }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
