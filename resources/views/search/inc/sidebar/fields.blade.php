@if (isset($customFields) && !empty($customFields))
	<form id="cfForm" role="form" class="form menu-nav__categories-item" action="{{ request()->url() }}" method="GET">
		@php
			$disabledFieldsTypes = ['file', 'video'];
			$clearFilterBtn = '';
			$firstFieldFound = false;
		@endphp
		@foreach($customFields as $field)
			@continue(in_array(data_get($field, 'type'), $disabledFieldsTypes) || data_get($field, 'use_as_filter') != 1)
			@php
				// Fields parameters
				$fieldId = 'cf.' . data_get($field, 'id');
				$fieldName = 'cf[' . data_get($field, 'id') . ']';
				$fieldOld = 'cf.' . data_get($field, 'id');

				// Get the default value
				$defaultValue = (request()->filled($fieldOld)) ? request()->input($fieldOld) : data_get($field, 'default_value');

				// Field Query String
				$fieldQueryStringId = 'cf' . data_get($field, 'id') . 'QueryString';
				$fieldQueryStringValue = \App\Helpers\Arr::query(request()->except(['page', $fieldId]));
				$fieldQueryString = '<input type="hidden" id="' . $fieldQueryStringId . '" value="' . $fieldQueryStringValue . '">';

				// Clear Filter Button
				$clearFilterBtn = \App\Helpers\UrlGen::getCustomFieldFilterClearLink($fieldOld, $cat ?? null, $city ?? null);
			@endphp

			@if (in_array(data_get($field, 'type'), ['text', 'textarea', 'url', 'number']))

				{{-- text --}}
				<div class="block-title has-arrow sidebar-header">
					<h5>
						<span class="fw-bold">
							{{ data_get($field, 'name') }}
						</span> {!! $clearFilterBtn !!}
					</h5>
				</div>
				<div class="block-content list-filter">
					<div class="filter-content row gx-1 gy-1">
						<div class="col-lg-9 col-md-12 col-sm-12">
							<input id="{{ $fieldId }}"
								   name="{{ $fieldName }}"
								   type="{{ (data_get($field, 'type') == 'number') ? 'number' : 'text' }}"
								   placeholder="{{ data_get($field, 'name') }}"
								   class="form-control input-md"
								   value="{{ strip_tags($defaultValue) }}"{!! (data_get($field, 'type') == 'number') ? ' autocomplete="off"' : '' !!}
							>
						</div>
						<div class="col-lg-3 col-md-12 col-sm-12">
							<button class="btn btn-default btn-block" type="submit"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
				{!! $fieldQueryString !!}
				<div style="clear:both"></div>

			@endif
			@if (data_get($field, 'type') == 'checkbox')

				{{-- checkbox --}}
				<div class="block-title has-arrow sidebar-header">
					<h5>
						<span class="fw-bold"><a href="#">{{ data_get($field, 'name') }}</a></span> {!! $clearFilterBtn !!}
					</h5>
				</div>
				<div class="block-content list-filter">
					<div class="filter-content">
						<div class="form-check form-switch">
							<input id="{{ $fieldId }}"
								   name="{{ $fieldName }}"
								   value="1"
								   type="checkbox"
								   class="form-check-input"
									{{ ($defaultValue == '1') ? 'checked="checked"' : '' }}
							>
							<label class="form-check-label" for="{{ $fieldId }}">
								{{ data_get($field, 'name') }}
							</label>
						</div>
					</div>
				</div>
				{!! $fieldQueryString !!}
				<div style="clear:both"></div>

			@endif
			@if (data_get($field, 'type') == 'checkbox_multiple')

				@if (!empty(data_get($field, 'options')))
					{{-- checkbox_multiple --}}
					<div class="block-title has-arrow sidebar-header">
						<h5>
							<span class="fw-bold">
								{{ data_get($field, 'name') }}
							</span> {!! $clearFilterBtn !!}
						</h5>
					</div>
					<div class="block-content list-filter">
						@php
							$cmFieldStyle = (is_array(data_get($field, 'options')) && count(data_get($field, 'options')) > 12)
								? ' style="height: 250px; overflow-y: scroll;"'
								: '';
						@endphp
						<div class="filter-content"{!! $cmFieldStyle !!}>
							@foreach (data_get($field, 'options') as $option)
								@php
									$optionId = data_get($option, 'id');

									// Get the default value
									$defaultValue = (request()->filled($fieldOld . '.' . $optionId))
										? request()->input($fieldOld . '.' . $optionId)
										: (
											(
												is_array(data_get($field, 'default_value'))
												&& !empty($optionId)
												&& !empty(data_get($field, 'default_value.' . $optionId . '.value'))
											)
												? data_get($field, 'default_value.' . $optionId . '.value')
												: data_get($field, 'default_value')
										);

									// Field Query String
									$fieldQueryStringId = 'cf' . data_get($field, 'id') . $optionId . 'QueryString';
									$fieldQueryStringValue = \App\Helpers\Arr::query(request()->except(['page', $fieldId . '.' . $optionId]));
									$fieldQueryString = '<input type="hidden" id="' . $fieldQueryStringId . '" value="' . $fieldQueryStringValue . '">';
								@endphp
								<div class="form-check form-switch">
									<input id="{{ $fieldId . '.' . $optionId }}"
										   name="{{ $fieldName . '[' . $optionId . ']' }}"
										   value="{{ $optionId }}"
										   type="checkbox"
										   class="form-check-input"
											{{ ($defaultValue == $optionId) ? 'checked="checked"' : '' }}
									>
									<label class="form-check-label" for="{{ $fieldId . '.' . $optionId }}">
										{{ data_get($option, 'value') }}
									</label>
								</div>
								{!! $fieldQueryString !!}
							@endforeach
						</div>
					</div>
					<div style="clear:both"></div>
				@endif

			@endif
			@if (data_get($field, 'type') == 'radio')

				@if (!empty(data_get($field, 'options')))
					{{-- radio --}}
					<div class="block-title has-arrow sidebar-header">
						<h5>
							<span class="fw-bold">
								{{ data_get($field, 'name') }}
							</span> {!! $clearFilterBtn !!}
						</h5>
					</div>
					<div class="block-content list-filter">
						@php
							$rFieldStyle = (is_array(data_get($field, 'options')) && count(data_get($field, 'options')) > 12)
								? ' style="height: 250px; overflow-y: scroll;"'
								: '';
						@endphp
						<div class="filter-content"{!! $rFieldStyle !!}>
							@foreach (data_get($field, 'options') as $option)
								@php
									$optionId = data_get($option, 'id');
								@endphp
								<div class="form-check">
									<input id="{{ $fieldId }}"
										   name="{{ $fieldName }}"
										   value="{{ $optionId }}"
										   type="radio"
										   class="form-check-input"
											{{ ($defaultValue == $optionId) ? 'checked="checked"' : '' }}
									>
									<label class="form-check-label" for="{{ $fieldId }}">
										{{ data_get($option, 'value') }}
									</label>
								</div>
							@endforeach
						</div>
					</div>
					{!! $fieldQueryString !!}
					<div style="clear:both"></div>
				@endif

			@endif
			@if (data_get($field, 'type') == 'select')

				{{-- select --}}
				<div class="block-title has-arrow sidebar-header">
					<h5>
						<span class="fw-bold">
							{{ data_get($field, 'name') }}
						</span> {!! $clearFilterBtn !!}
					</h5>
				</div>
				<div class="block-content list-filter">
					<div class="filter-content">
						@php
							$fieldOptions = is_array(data_get($field, 'options')) ? data_get($field, 'options') : [];
							$select2Type = (count($fieldOptions) <= 10) ? 'selecter' : 'large-data-selecter';
						@endphp
						<select id="{{ $fieldId }}" name="{{ $fieldName }}" class="form-control {{ $select2Type }}">
							<option value="" @selected(empty(old($fieldOld)))>
								{{ t('Select') }}
							</option>
							@if (!empty($fieldOptions))
								@foreach ($fieldOptions as $option)
									@php
										$optionId = data_get($option, 'id');
									@endphp
									<option value="{{ $optionId }}" @selected($defaultValue == $optionId)>
										{{ data_get($option, 'value') }}
									</option>
								@endforeach
							@endif
						</select>
					</div>
				</div>
				{!! $fieldQueryString !!}
				<div style="clear:both"></div>

			@endif
			@if (in_array(data_get($field, 'type'), ['date', 'date_time', 'date_range']))

				{{-- date --}}
				<div class="block-title has-arrow sidebar-header">
					<h5>
						<span class="fw-bold">
							{{ data_get($field, 'name') }}
						</span> {!! $clearFilterBtn !!}
					</h5>
				</div>
				@php
					$datePickerClass = '';
					if (in_array(data_get($field, 'type'), ['date', 'date_time'])) {
						$datePickerClass = ' cf-date';
					}
					if (data_get($field, 'type') == 'date_range') {
						$datePickerClass = ' cf-date_range';
					}
				@endphp
				<div class="block-content list-filter">
					<div class="filter-content row gx-1 gy-1">
						<div class="col-lg-9 col-md-12 col-sm-12">
							<input id="{{ $fieldId }}"
								   name="{{ $fieldName }}"
								   type="text"
								   placeholder="{{ data_get($field, 'name') }}"
								   class="form-control input-md{{ $datePickerClass }}"
								   value="{{ strip_tags($defaultValue) }}"
								   autocomplete="off"
							>
						</div>
						<div class="col-lg-3 col-md-12 col-sm-12">
							<button class="btn btn-default btn-block" type="submit"><i class="fa fa-search"></i></button>
						</div>
					</div>
				</div>
				{!! $fieldQueryString !!}
				<div style="clear:both"></div>

			@endif

		@endforeach
	</form>
	<div style="clear:both"></div>
@endif

@section('after_styles')
	<link href="{{ url('assets/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
@endsection
@section('after_scripts')
	@parent
	<script src="{{ url('assets/plugins/momentjs/moment.min.js') }}" type="text/javascript"></script>
	<script src="{{ url('assets/plugins/bootstrap-daterangepicker/daterangepicker.js') }}" type="text/javascript"></script>

	<script>
		$(document).ready(function ()
		{
			/* Select */
			$('#cfForm').find('select').change(function() {
				/* Get full field's ID */
				var fullFieldId = $(this).attr('id');

				/* Get full field's ID without dots */
				var jsFullFieldId = fullFieldId.split('.').join('');

				/* Get real field's ID */
				var tmp = fullFieldId.split('.');
				if (typeof tmp[1] !== 'undefined') {
					var fieldId = tmp[1];
				} else {
					return false;
				}

				/* Get saved QueryString */
				var fieldQueryString = $('#' + jsFullFieldId + 'QueryString').val();

				/* Add the field's value to the QueryString */
				if (fieldQueryString !== '') {
					fieldQueryString = fieldQueryString + '&';
				}
				fieldQueryString = fieldQueryString + 'cf['+fieldId+']=' + $(this).val();

				/* Redirect to the new search URL */
				var searchUrl = baseUrl + '?' + fieldQueryString;
				redirect(searchUrl);
			});

			/* Radio & Checkbox */
			$('#cfForm').find('input[type=radio], input[type=checkbox]').click(function() {
				/* Get full field's ID */
				var fullFieldId = $(this).attr('id');

				/* Get full field's ID without dots */
				var jsFullFieldId = fullFieldId.split('.').join('');

				/* Get real field's ID */
				var tmp = fullFieldId.split('.');
				if (typeof tmp[1] !== 'undefined') {
					var fieldId = tmp[1];
					if (typeof tmp[2] !== 'undefined') {
						var fieldOptionId = tmp[2];
					}
				} else {
					return false;
				}

				/* Get saved QueryString */
				var fieldQueryString = $('#' + jsFullFieldId + 'QueryString').val();

				/* Check if field is checked */
				if ($(this).prop('checked') == true) {
					/* Add the field's value to the QueryString */
					if (fieldQueryString != '') {
						fieldQueryString = fieldQueryString + '&';
					}
					if (typeof fieldOptionId !== 'undefined') {
						fieldQueryString = fieldQueryString + 'cf[' + fieldId + '][' + fieldOptionId + ']=' + rawurlencode($(this).val());
					} else {
						fieldQueryString = fieldQueryString + 'cf[' + fieldId + ']=' + $(this).val();
					}
				}

				/* Redirect to the new search URL */
				var searchUrl = baseUrl + '?' + fieldQueryString;
				redirect(searchUrl);
			});

			/*
			 * Custom Fields Date Picker
			 * https://www.daterangepicker.com/#options
			 */
			{{-- Single Date --}}
			$('#cfForm .cf-date').daterangepicker({
				autoUpdateInput: false,
				autoApply: true,
				showDropdowns: true,
				minYear: parseInt(moment().format('YYYY')) - 100,
				maxYear: parseInt(moment().format('YYYY')) + 20,
				locale: {
					format: '{{ t('datepicker_format') }}',
					applyLabel: "{{ t('datepicker_applyLabel') }}",
					cancelLabel: "{{ t('datepicker_cancelLabel') }}",
					fromLabel: "{{ t('datepicker_fromLabel') }}",
					toLabel: "{{ t('datepicker_toLabel') }}",
					customRangeLabel: "{{ t('datepicker_customRangeLabel') }}",
					weekLabel: "{{ t('datepicker_weekLabel') }}",
					daysOfWeek: [
						"{{ t('datepicker_sunday') }}",
						"{{ t('datepicker_monday') }}",
						"{{ t('datepicker_tuesday') }}",
						"{{ t('datepicker_wednesday') }}",
						"{{ t('datepicker_thursday') }}",
						"{{ t('datepicker_friday') }}",
						"{{ t('datepicker_saturday') }}"
					],
					monthNames: [
						"{{ t('January') }}",
						"{{ t('February') }}",
						"{{ t('March') }}",
						"{{ t('April') }}",
						"{{ t('May') }}",
						"{{ t('June') }}",
						"{{ t('July') }}",
						"{{ t('August') }}",
						"{{ t('September') }}",
						"{{ t('October') }}",
						"{{ t('November') }}",
						"{{ t('December') }}"
					],
					firstDay: 1
				},
				singleDatePicker: true,
				startDate: moment().format('{{ t('datepicker_format') }}')
			});
			$('#cfForm .cf-date').on('apply.daterangepicker', function(ev, picker) {
				$(this).val(picker.startDate.format('{{ t('datepicker_format') }}'));
			});

			{{-- Date Range --}}
			$('#cfForm .cf-date_range').daterangepicker({
				autoUpdateInput: false,
				autoApply: true,
				showDropdowns: false,
				minYear: parseInt(moment().format('YYYY')) - 100,
				maxYear: parseInt(moment().format('YYYY')) + 20,
				locale: {
					format: '{{ t('datepicker_format') }}',
					applyLabel: "{{ t('datepicker_applyLabel') }}",
					cancelLabel: "{{ t('datepicker_cancelLabel') }}",
					fromLabel: "{{ t('datepicker_fromLabel') }}",
					toLabel: "{{ t('datepicker_toLabel') }}",
					customRangeLabel: "{{ t('datepicker_customRangeLabel') }}",
					weekLabel: "{{ t('datepicker_weekLabel') }}",
					daysOfWeek: [
						"{{ t('datepicker_sunday') }}",
						"{{ t('datepicker_monday') }}",
						"{{ t('datepicker_tuesday') }}",
						"{{ t('datepicker_wednesday') }}",
						"{{ t('datepicker_thursday') }}",
						"{{ t('datepicker_friday') }}",
						"{{ t('datepicker_saturday') }}"
					],
					monthNames: [
						"{{ t('January') }}",
						"{{ t('February') }}",
						"{{ t('March') }}",
						"{{ t('April') }}",
						"{{ t('May') }}",
						"{{ t('June') }}",
						"{{ t('July') }}",
						"{{ t('August') }}",
						"{{ t('September') }}",
						"{{ t('October') }}",
						"{{ t('November') }}",
						"{{ t('December') }}"
					],
					firstDay: 1
				},
				startDate: moment().format('{{ t('datepicker_format') }}'),
				endDate: moment().add(1, 'days').format('{{ t('datepicker_format') }}')
			});
			$('#cfForm .cf-date_range').on('apply.daterangepicker', function(ev, picker) {
				$(this).val(picker.startDate.format('{{ t('datepicker_format') }}') + ' - ' + picker.endDate.format('{{ t('datepicker_format') }}'));
			});

			$("[id='cf.41']").suggestions({
				token: "{{env('DADATA_API_TOKEN', '8122273c27d35ba75910a900bfc2e4a9b3925e1a')}}",
				type: "ADDRESS"
			})
			$("[id='cf.42']").suggestions({
				token: "{{env('DADATA_API_TOKEN', '8122273c27d35ba75910a900bfc2e4a9b3925e1a')}}",
				type: "ADDRESS"
			})

			let mark_field = $('select[id="cf.50"]')
			let model_field = $('select[id="cf.51"]')

			console.log(mark_field,model_field )

			if(mark_field && model_field) {
				mark_field.select2({
					ajax: {
						url: '{{route('base.marks')}}',
						dataType: 'json',
						delay: 250,
						data: (params) => {
							return {
								search: params.term,
								page: params.page || 1
							}
						},
						processResults: ({data}, params) => {
							params.page = params.page || 1
							return {
								results: data.data,
								pagination: {
									more: (params.page * 30) < data.total
								}
							};
						},
						cache: true,
						minimumInputLength: 1,
					},
					placeholder: 'Поиск марки',
				});

				model_field.select2({
					ajax: {
						url: '{{route('base.models')}}',
						dataType: 'json',
						delay: 250,
						data: (params) => {
							return {
								mark_id: sessionStorage.getItem('mark_id'),
								search: params.term,
								page: params.page || 1
							}
						},
						processResults: ({data}, params) => {
							params.page = params.page || 1
							return {
								results: data.data,
								pagination: {
									more: (params.page * 30) < data.total
								}
							};
						},
						cache: true,
						minimumInputLength: 1,
					},
					placeholder: 'Поиск модели',
				});


				mark_field.on("select2:selecting", function(e) {
					model_field.val(null).trigger('change')
					sessionStorage.setItem('mark_id', e.params.args.data.mark_id)
					model_field.prop('disabled', false)
				});

				if(!sessionStorage.getItem('mark_id')) {
					model_field.prop('disabled', true)
				}

				const urlParams = new URLSearchParams(window.location.search);

				let old_mark_field = urlParams.get('cf[50]') ?? '';

				let old_model_field = urlParams.get('cf[51]') ?? '';

				if(old_mark_field?.length) {
					let markFieldOption = new Option(old_mark_field, old_mark_field, true, true)
					mark_field.append(markFieldOption)
				}
				if(old_model_field?.length) {
					let modelFieldOption = new Option(old_model_field, old_model_field, true, true)
					model_field.append(modelFieldOption)
				}
			}
		});
	</script>
@endsection
