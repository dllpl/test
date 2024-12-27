<div>
    <h5>Устройства пользователя</h5>
    @if($devices->isEmpty())
        <p>Устройств нет</p>
    @else
        <ul class="list-group mb-3">
            @foreach($devices as $device)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $device->serial_number }} (Номер договора: {{ $device->contract_number }})
                    <form action="{{ route('devices.destroy', $device->id) }}" method="POST" onsubmit="return confirm('{{ trans('admin.Are you sure?') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
    <a href="{{ route('devices.create', ['user_id' => $userId]) }}" class="btn btn-primary">
        Добавить устройство
    </a>
</div>
