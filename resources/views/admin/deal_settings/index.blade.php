@extends('admin.layouts.master')

@section('after_styles')
    {{-- Ladda Buttons (loading buttons) --}}
    <link href="{{ asset('assets/plugins/ladda/ladda-themeless.min.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('header')
    <div class="row page-titles">
        <div class="col-md-6 col-12 align-self-center">
            <h3 class="mb-0">
                Настройки сделок
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
    <h1>Настройки сделок</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.deal-settings.update') }}" method="POST">
        @csrf

        <!-- Комиссия по умолчанию -->
        <div class="mb-3">
            <label for="default_commission" class="form-label">Комиссия по умолчанию (%)</label>
            <input type="number" id="default_commission" name="default_commission" class="form-control"
                   value="{{ $commissionSettings->firstWhere('name', 'Default Commission')->value ?? '' }}" 
                   min="0" max="100" required>
        </div>

        <div class="mb-3">
            <label for="timer" class="form-label">Таймер (в минутах)</label>
            <input type="number" id="timer" name="timer" class="form-control" 
                   value="{{ $timerSettings->firstWhere('name', 'Default Timer')->value ?? '' }}" 
                   min="0" required>
        </div>

        <!-- Комиссия для каждой категории -->
        <h3>Комиссия для категорий</h3>
        @foreach ($categories as $category)
            <div class="mb-3">
                <label for="category_{{ $category->id }}" class="form-label">
                    {{ $category->name }}
                </label>
                <input type="number" id="category_{{ $category->id }}" name="categories[{{ $category->id }}]" 
                       class="form-control" value="{{ $category->commission }}" 
                       min="0" max="100" required>
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
</div>
@endsection