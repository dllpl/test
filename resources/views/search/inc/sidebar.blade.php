<!-- this (.mobile-filter-sidebar) part will be position fixed in mobile version -->

{{--<div class="col-md-3 page-sidebar pb-4">--}}
{{--	<aside>--}}
{{--		<div class="sidebar-modern-inner enable-long-words">--}}

            <div class="menu-nav">
                <div class="d-flex justify-content-end"><button class="btn-reset text-decoration-underline font-weight-bold" id="close_menu_nav" style="color: var(--accent);">{{t('Close')}}</button></div>
                <ul class="menu-nav__categories list-reset">

    			    @includeFirst([config('larapen.core.customizedViewPath') . 'search.inc.sidebar.fields', 'search.inc.sidebar.fields'])
                    @includeFirst([config('larapen.core.customizedViewPath') . 'search.inc.sidebar.categories', 'search.inc.sidebar.categories'])
                    @includeFirst([config('larapen.core.customizedViewPath') . 'search.inc.sidebar.cities', 'search.inc.sidebar.cities'])
                    @if (!config('settings.list.hide_dates'))
                        @includeFirst([config('larapen.core.customizedViewPath') . 'search.inc.sidebar.date', 'search.inc.sidebar.date'])
                    @endif
                    @includeFirst([config('larapen.core.customizedViewPath') . 'search.inc.sidebar.price', 'search.inc.sidebar.price'])
                </ul>
            </div>
{{--	</aside>--}}
{{--</div>--}}

@section('after_scripts')
    @parent
    <script>
        var baseUrl = '{{ request()->url() }}';
    </script>
@endsection
