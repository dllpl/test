@php
    $apiResult = $apiResult ?? [];
	$isPagingable = (!empty(data_get($apiResult, 'links.prev')) || !empty(data_get($apiResult, 'links.next')));
	$paginator = (array)data_get($apiResult, 'links');
	$totalEntries = (int)data_get($apiResult, 'meta.total');
	$currentPage = (int)data_get($apiResult, 'meta.current_page');
	$elements = data_get($apiResult, 'meta.links');
@endphp
@if ($totalEntries > 0 && $isPagingable)
    <style>
        .pagination {
            display: -ms-flexbox;
            flex-wrap: wrap;
            display: flex;
            padding-left: 0;
            list-style: none;
            border-radius: 0.25rem;
        }
    </style>
    <ul class="list-reset mt-5" style="display: flex; justify-content: space-between; color: #3F3F3E; font-weight: 500; font-size: 12px;">
{{--        <li>Вперед</li>--}}
        {{-- Previous Page Link --}}
        @if (!data_get($paginator, 'prev'))
            <li aria-disabled="true" aria-label="@lang('pagination.previous')">
                <span aria-hidden="true" class="text-decoration-underline">Назад</span>
            </li>
        @else
            <li>
                <a class="page-link text-decoration-underline" href="{{ data_get($paginator, 'prev') }}" rel="prev" aria-label="@lang('pagination.previous')" >Назад</a>
            </li>
        @endif
        <div class="d-flex w-100 justify-content-around">
            {{-- Pagination Elements --}}
            @if (is_array($elements) && count($elements) > 0)
                @foreach ($elements as $element)
                    @continue($loop->first || $loop->last)
                    {{-- "Three Dots" Separator --}}
                    @if (!data_get($element, 'url'))
                        <li class="disabled" aria-disabled="true">{{ data_get($element, 'label') }}</li>
                    @else
                        {{-- Array Of Links --}}
                        @if ((int)data_get($element, 'label') == $currentPage)
                            <li class="active" aria-current="page">{{ data_get($element, 'label') }}</li>
                        @else
                            <li><a href="{{ data_get($element, 'url') }}">{{ data_get($element, 'label') }}</a></li>
                        @endif
                    @endif
                @endforeach
            @endif
        </div>
        {{-- Next Page Link --}}
        @if (data_get($paginator, 'next'))
            <li>
                <a href="{{ data_get($paginator, 'next') }}" rel="next" aria-label="@lang('pagination.next')" class="text-decoration-underline">Вперед</a>
            </li>
        @else
            <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                <span aria-hidden="true" class="text-decoration-underline">Вперед</span>
            </li>
        @endif
{{--        <li>Назад</li>--}}
    </ul>
{{--    <ul class="pagination justify-content-center" role="navigation">--}}
{{--        --}}{{-- Previous Page Link --}}
{{--        @if (!data_get($paginator, 'prev'))--}}
{{--            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">--}}
{{--                <span class="page-link" aria-hidden="true">&lsaquo;</span>--}}
{{--            </li>--}}
{{--        @else--}}
{{--            <li class="page-item">--}}
{{--                <a class="page-link" href="{{ data_get($paginator, 'prev') }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>--}}
{{--            </li>--}}
{{--        @endif--}}

{{--        --}}{{-- Pagination Elements --}}
{{--        @if (is_array($elements) && count($elements) > 0)--}}
{{--            @foreach ($elements as $element)--}}
{{--                @continue($loop->first || $loop->last)--}}

{{--                --}}{{-- "Three Dots" Separator --}}
{{--                @if (!data_get($element, 'url'))--}}
{{--                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ data_get($element, 'label') }}</span></li>--}}
{{--                @else--}}
{{--                    --}}{{-- Array Of Links --}}
{{--                    @if ((int)data_get($element, 'label') == $currentPage)--}}
{{--                        <li class="page-item active" aria-current="page"><span class="page-link">{{ data_get($element, 'label') }}</span></li>--}}
{{--                    @else--}}
{{--                        <li class="page-item"><a class="page-link" href="{{ data_get($element, 'url') }}">{{ data_get($element, 'label') }}</a></li>--}}
{{--                    @endif--}}
{{--                @endif--}}
{{--            @endforeach--}}
{{--        @endif--}}

{{--        --}}{{-- Next Page Link --}}
{{--        @if (data_get($paginator, 'next'))--}}
{{--            <li class="page-item">--}}
{{--                <a class="page-link" href="{{ data_get($paginator, 'next') }}" rel="next" aria-label="@lang('pagination.next')">&rsaquo;</a>--}}
{{--            </li>--}}
{{--        @else--}}
{{--            <li class="page-item disabled" aria-disabled="true" aria-label="@lang('pagination.next')">--}}
{{--                <span class="page-link" aria-hidden="true">&rsaquo;</span>--}}
{{--            </li>--}}
{{--        @endif--}}
{{--    </ul>--}}
@endif
