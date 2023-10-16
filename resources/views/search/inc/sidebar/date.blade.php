<?php
// Clear Filter Button
$clearFilterBtn = \App\Helpers\UrlGen::getDateFilterClearLink($cat ?? null, $city ?? null);
?>
{{-- Date --}}
<li class="menu-nav__categories-item">
	<h3 class="menu-nav__categories-title title title--medium title--underline">
		{{ t('Date Posted') }} {!! $clearFilterBtn !!}
	</h3>
	<ul class="menu-nav__list list-reset">
		@if (isset($periodsList) && !empty($periodsList))
			@foreach($periodsList as $key => $value)
				@if (request()->input('postedDate') == $key)
					<li class="menu-nav__item">
						<input type="radio"
							   name="postedDate"
							   value="{{ $key }}"
							   id="postedDate_{{ $key }}" {{ (request()->get('postedDate')==$key) ? 'checked="checked"' : '' }}
							   hidden
						>
						<label for="postedDate_{{ $key }}" class="link" style="cursor: pointer;">{{ $value }}</label>
					</li>
				@else
					<li class="menu-nav__item">
						<input type="radio"
							   name="postedDate"
							   value="{{ $key }}"
							   id="postedDate_{{ $key }}" {{ (request()->get('postedDate')==$key) ? 'checked="checked"' : '' }}
							   hidden
						>
						<label for="postedDate_{{ $key }}" class="link" style="cursor: pointer; font-weight: normal">{{ $value }}</label>
					</li>
				@endif
			@endforeach
		@endif
		<input type="hidden" id="postedQueryString" value="{{ \App\Helpers\Arr::query(request()->except(['page', 'postedDate'])) }}">
	</ul>
</li>
{{--<div class="block-title has-arrow sidebar-header">--}}
{{--	<h5>--}}
{{--		<span class="fw-bold">--}}
{{--			{{ t('Date Posted') }}--}}
{{--		</span> {!! $clearFilterBtn !!}--}}
{{--	</h5>--}}
{{--</div>--}}
{{--<div class="block-content list-filter">--}}
{{--	<div class="filter-date filter-content">--}}
{{--		<ul>--}}
{{--			@if (isset($periodsList) && !empty($periodsList))--}}
{{--				@foreach($periodsList as $key => $value)--}}
{{--					<li>--}}
{{--						<input type="radio"--}}
{{--							   name="postedDate"--}}
{{--							   value="{{ $key }}"--}}
{{--							   id="postedDate_{{ $key }}" {{ (request()->get('postedDate')==$key) ? 'checked="checked"' : '' }}--}}
{{--						>--}}
{{--						<label for="postedDate_{{ $key }}">{{ $value }}</label>--}}
{{--					</li>--}}
{{--				@endforeach--}}
{{--			@endif--}}
{{--			<input type="hidden" id="postedQueryString" value="{{ \App\Helpers\Arr::query(request()->except(['page', 'postedDate'])) }}">--}}
{{--		</ul>--}}
{{--	</div>--}}
{{--</div>--}}
<div style="clear:both"></div>

@section('after_scripts')
	@parent

	<script>
		$(document).ready(function ()
		{
			$('input[type=radio][name=postedDate]').click(function() {
				let postedQueryString = $('#postedQueryString').val();

				if (postedQueryString !== '') {
					postedQueryString = postedQueryString + '&';
				}
				postedQueryString = postedQueryString + 'postedDate=' + $(this).val();

				let searchUrl = baseUrl + '?' + postedQueryString;
				redirect(searchUrl);
			});
		});
	</script>
@endsection
