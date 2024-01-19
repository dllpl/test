@extends('admin.layouts.master')

@section('after_styles')
    {{-- Ladda Buttons (loading buttons) --}}
    <link href="{{ asset('assets/plugins/ladda/ladda-themeless.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('header')
    <div class="row page-titles">
        <div class="col-md-6 col-12 align-self-center">
            <h3 class="mb-0">
                Запросы на аккредитацию
            </h3>
        </div>
        <div class="col-md-6 col-12 align-self-center d-none d-md-flex justify-content-end">
            <ol class="breadcrumb mb-0 p-0 bg-transparent">
                <li class="breadcrumb-item"><a href="{{ admin_url() }}">{{ trans('admin.dashboard') }}</a></li>
                <li class="breadcrumb-item active d-flex align-items-center">Аккредитация</li>
            </ol>
        </div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">

            @if (\Session::has('msg'))
                <div class="alert alert-success">
                    {{\Session::get('msg')}}
                </div>
            @endif

            <div class="card rounded">

                <div class="card-body">

                    <div id="loadingData" class="" style="position: relative;"></div>

                    <form id="bulkActionForm" action="http://barsovozprod/admin/users/bulk_actions" method="POST">
                        <input type="hidden" name="_token" value="VZAcbGYy0HeaJplyktx3pUL4EwnaktNOlJSl5FVt"
                               autocomplete="off">

                        <div id="crudTable_wrapper" class="dataTables_wrapper dt-bootstrap5">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="crudTable"
                                           class="dataTable table table-bordered display dt-responsive nowrap dtr-inline"
                                           style="width: 100%;" role="grid" aria-describedby="crudTable_info">
                                        <thead>
                                        <tr role="row">
                                            <th>№</th>
                                            <th>Имя</th>
                                            <th>Телефон</th>
                                            <th>Почта</th>
                                            <th>Статус</th>
                                            <th>Дата</th>
                                            <th>Действие</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($data as $item)
                                            <tr class="@if($item->status == 0) bg-success color-white @endif">
                                                <td>{{$item->s_id}}</td>
                                                <td>{{$item->name}}</td>
                                                <td>{{$item->phone}}</td>
                                                <td>{{$item->email}}</td>
                                                @switch($item->status)
                                                    @case(0)
                                                        <td>Нужно обработать</td>
                                                        @break
                                                    @case(1)
                                                        <td>Обработано</td>
                                                        @break
                                                    @case(2)
                                                        <td>Отказано</td>
                                                        @break
                                                @endswitch
                                                <td>{{date('d.m.Y H:i:s',strtotime($item->s_created_at))}}</td>
                                                <td>
                                                    @switch($item->status)
                                                        @case(0)
                                                            <div class="d-flex justify-content-between">
                                                                <a class="bulk-action btn btn-primary shadow"
                                                                   href="{{route('cert.action', ['action'=>'accept', 'request_id'=>$item->s_id])}}">Принять</a>
                                                                <a class="bulk-action btn btn-danger shadow"
                                                                   href="{{route('cert.action', ['action'=>'decline', 'request_id'=>$item->s_id])}}"
                                                                >Отказать</a>
                                                            </div>
                                                            @break
                                                        @case(1)
                                                            <a class="bulk-action btn btn-danger shadow"
                                                               href="{{route('cert.action', ['action'=>'decline', 'request_id'=>$item->s_id])}}"
                                                            >Отказать</a>
                                                            @break
                                                        @case(2)
                                                            <a class="bulk-action btn btn-warning shadow"
                                                               href="{{route('cert.action', ['action'=>'rollback', 'request_id'=>$item->s_id])}}"
                                                            >В обработку</a>
                                                            @break
                                                        @default
                                                            <span>-</span>
                                                    @endswitch
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
