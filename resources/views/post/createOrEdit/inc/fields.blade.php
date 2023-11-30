@php
	$fields ??= [];
	$errors ??= [];
	$oldInput ??= [];

	if (empty($languageCode)) {
		$languageCode = config('app.locale', session('langCode'));
	}
@endphp
@if (!empty($fields))
	@foreach($fields as $field)
		@php
			$modelFieldId = data_get($field, 'id');
			$modelFieldType = data_get($field, 'type');
			$modelDefaultValue = data_get($field, 'default_value');

			// Fields parameters
			$fieldId = 'cf.' . $modelFieldId;
			$fieldName = 'cf[' . $modelFieldId . ']';
			$fieldOld = 'cf.' . $modelFieldId;

			// Errors & Required CSS
			$requiredClass = (data_get($field, 'required') == 1) ? 'required' : '';
			$errorClass = (isset($errors[$fieldOld])) ? ' is-invalid' : '';

			// Get the default value
			$defaultValue = $oldInput[$modelFieldId] ?? $modelDefaultValue;

			// Get field other attributes
			$fieldOptions = data_get($field, 'options') ?? [];
			$fieldOptions = is_array($fieldOptions) ? $fieldOptions : [];
		@endphp

		@if ($modelFieldType == 'checkbox')

			{{-- checkbox --}}
			<div class="row mb-3 {{ $requiredClass }}" style="margin-top: -10px;">
				<label class="col-md-3 col-form-label" for="{{ $fieldId }}"></label>
				<div class="col-md-8">
					<div class="form-check pt-2">
						<input id="{{ $fieldId }}"
							   name="{{ $fieldName }}"
							   value="1"
							   type="checkbox"
							   class="form-check-input{{ $errorClass }}"
								{{ ($defaultValue=='1') ? 'checked="checked"' : '' }}
						>
						<label class="form-check-label" for="{{ $fieldId }}">
							{{ data_get($field, 'name') }}
						</label>
					</div>
					<div class="form-text text-muted">{!! data_get($field, 'help') !!}</div>
				</div>
			</div>

		@elseif ($modelFieldType == 'checkbox_multiple')

			@if (!empty($fieldOptions))
				{{-- checkbox_multiple --}}
				<div class="row mb-3 {{ $requiredClass }}" style="margin-top: -10px;">
					<label class="col-md-3 col-form-label" for="{{ $fieldId }}">
						{{ data_get($field, 'name') }}
						@if (data_get($field, 'required') == 1)
							<sup>*</sup>
						@endif
					</label>
					@php
						$cmFieldStyle = (count($fieldOptions) > 12) ? ' style="height: 250px; overflow-y: scroll;"' : '';
					@endphp
					<div class="col-md-8"{!! $cmFieldStyle !!}>
						@foreach ($fieldOptions as $option)
							@php
								$modelOptionId = data_get($option, 'id');

								// Get the default value
								$defaultValue = (is_array($modelDefaultValue)) ? data_get($modelDefaultValue, $modelOptionId . '.id') : $modelDefaultValue;
								$defaultValue = data_get($oldInput, $modelFieldId . '.' . $modelOptionId, $defaultValue);
							@endphp
							<div class="form-check pt-2">
								<input id="{{ $fieldId . '.' . $modelOptionId }}"
									   name="{{ $fieldName . '[' . $modelOptionId . ']' }}"
									   value="{{ $modelOptionId }}"
									   type="checkbox"
									   class="form-check-input{{ $errorClass }}"
										{{ ($defaultValue == $modelOptionId) ? 'checked="checked"' : '' }}
								>
								<label class="form-check-label" for="{{ $fieldId . '.' . $modelOptionId }}">
									 {{ data_get($option, 'value') }}
								</label>
							</div>
						@endforeach
						<div class="form-text text-muted">{!! data_get($field, 'help') !!}</div>
					</div>
				</div>
			@endif

		@elseif ($modelFieldType == 'file')

			{{-- file --}}
			<div class="row mb-3 {{ $requiredClass }}">
				<label class="col-md-3 col-form-label" for="{{ $fieldId }}">
					{{ data_get($field, 'name') }}
					@if (data_get($field, 'required') == 1)
						<sup>*</sup>
					@endif
				</label>
				<div class="col-md-8">
					<div class="mb10">
						<input id="{{ $fieldId }}" name="{{ $fieldName }}" type="file" class="file{{ $errorClass }}">
					</div>
					<div class="form-text text-muted">
						{!! data_get($field, 'help') !!} {{ t('file_types', ['file_types' => showValidFileTypes('file')], 'global', $languageCode) }}
					</div>
					@if (!empty($modelDefaultValue) && $disk->exists($modelDefaultValue))
						<div>
							<a class="btn btn-default" href="{{ privateFileUrl($modelDefaultValue, null) }}" target="_blank">
								<i class="fas fa-paperclip"></i> {{ t('Download') }}
							</a>
						</div>
					@endif
				</div>
			</div>

		@elseif ($modelFieldType == 'radio')

			@if (!empty($fieldOptions))
				{{-- radio --}}
				<div class="row mb-3 {{ $requiredClass }}">
					<label class="col-md-3 col-form-label" for="{{ $fieldId }}">
						{{ data_get($field, 'name') }}
						@if (data_get($field, 'required') == 1)
							<sup>*</sup>
						@endif
					</label>
					<div class="col-md-8">
						@foreach ($fieldOptions as $option)
							@php
								$modelOptionId = data_get($option, 'id');
							@endphp
							<div class="form-check pt-2">
								<input id="{{ $fieldId }}"
									   name="{{ $fieldName }}"
									   value="{{ $modelOptionId }}"
									   type="radio"
									   class="form-check-input{{ $errorClass }}"
										{{ ($defaultValue == $modelOptionId) ? 'checked="checked"' : '' }}
								>
								<label class="form-check-label" for="{{ $fieldName }}">
									{{ data_get($option, 'value') }}
								</label>
							</div>
						@endforeach
					</div>
					<div class="form-text text-muted">{!! data_get($field, 'help') !!}</div>
				</div>
			@endif

		@elseif ($modelFieldType == 'select')

			{{-- select --}}
			<div class="row mb-3 {{ $requiredClass }}">
				<label class="col-md-3 col-form-label{{ $errorClass }}" for="{{ $fieldId }}">
					{{ data_get($field, 'name') }}
					@if (data_get($field, 'required') == 1)
						<sup>*</sup>
					@endif
				</label>
				<div class="col-md-8">
					@php
//						$select2Type = (count($fieldOptions) <= 10) ? 'selecter' : 'large-data-selecter';
                        if(count($fieldOptions) <= 10) {
                            $select2Type = 'selecter';
                        } else {
                            $select2Type = 'large-data-selecter';
                        }
                        if ($fieldName === 'cf[51]' || $fieldName === 'cf[50]') {
                            $select2Type = 'selecter-custom';

                            if($fieldName === 'cf[51]') {
                                $old_mark_field = $modelDefaultValue;
                            }
                            if($fieldName === 'cf[50]') {
                                $old_model_field = $modelDefaultValue;
                            }
                        }
					@endphp
					<select id="{{ $fieldId }}" name="{{ $fieldName }}" class="form-control {{ $select2Type . $errorClass }}">
						<option value="{{ $modelDefaultValue }}" @selected(empty(old($fieldOld)) || old($fieldOld)==$modelDefaultValue)>
							{{ t('Select', [], 'global', $languageCode) }}
						</option>
						@if (!empty($fieldOptions))
							@foreach ($fieldOptions as $option)
								@php
									$modelOptionId = data_get($option, 'id');
								@endphp
								<option value="{{ $modelOptionId }}" @selected($defaultValue == $modelOptionId)>
									{{ data_get($option, 'value') }}
								</option>
							@endforeach
						@endif
					</select>
					<div class="form-text text-muted">{!! data_get($field, 'help') !!}</div>
				</div>
			</div>

		@elseif ($modelFieldType == 'textarea')

			{{-- textarea --}}
			<div class="row mb-3 {{ $requiredClass }}">
				<label class="col-md-3 col-form-label" for="{{ $fieldId }}">
					{{ data_get($field, 'name') }}
					@if (data_get($field, 'required') == 1)
						<sup>*</sup>
					@endif
				</label>
				<div class="col-md-8">
					@php
						$fieldMax = (int)data_get($field, 'max');
						$fieldMaxAttr = !empty($fieldMax) ? ' maxlength="'. $fieldMax .'"' : '';
					@endphp
					<textarea class="form-control{{ $errorClass }}"
						  id="{{ $fieldId }}"
						  name="{{ $fieldName }}"
						  placeholder="{{ data_get($field, 'name') }}"
						  rows="10"{!! $fieldMaxAttr !!}
					>{{ $defaultValue }}</textarea>
					<div class="form-text text-muted">{!! data_get($field, 'help') !!}</div>
				</div>
			</div>

		@elseif ($modelFieldType == 'url')

			{{-- url --}}
			<div class="row mb-3 {{ $requiredClass }}">
				<label class="col-md-3 col-form-label" for="{{ $fieldId }}">
					{{ data_get($field, 'name') }}
					@if (data_get($field, 'required') == 1)
						<sup>*</sup>
					@endif
				</label>
				<div class="col-md-8">
					<input id="{{ $fieldId }}"
						   name="{{ $fieldName }}"
						   type="text"
						   placeholder="{{ data_get($field, 'name') }}"
						   class="form-control input-md{{ $errorClass }}"
						   value="{{ $defaultValue }}">
					<div class="form-text text-muted">{!! data_get($field, 'help') !!}</div>
				</div>
			</div>

		@elseif ($modelFieldType == 'number')

			{{-- number --}}
			<div class="row mb-3 {{ $requiredClass }}">
				<label class="col-md-3 col-form-label" for="{{ $fieldId }}">
					{{ data_get($field, 'name') }}
					@if (data_get($field, 'required') == 1)
						<sup>*</sup>
					@endif
				</label>
				<div class="col-md-8">
					@php
						$fieldMax = (int)data_get($field, 'max');
						$fieldMaxAttr = !empty($fieldMax) ? ' max="'. $fieldMax .'"' : '';
					@endphp
					<input id="{{ $fieldId }}"
						   name="{{ $fieldName }}"
						   type="number"
						   placeholder="{{ data_get($field, 'name') }}"
						   class="form-control input-md{{ $errorClass }}"
						   value="{{ $defaultValue }}"{!! $fieldMaxAttr !!}>
					<div class="form-text text-muted">{!! data_get($field, 'help') !!}</div>
				</div>
			</div>

		@elseif ($modelFieldType == 'date')

			{{-- date --}}
			<div class="row mb-3 {{ $requiredClass }}">
				<label class="col-md-3 col-form-label" for="{{ $fieldId }}">
					{{ data_get($field, 'name') }}
					@if (data_get($field, 'required') == 1)
						<sup>*</sup>
					@endif
				</label>
				<div class="col-md-8">
					<input id="{{ $fieldId }}"
						   name="{{ $fieldName }}"
						   type="text"
						   placeholder="{{ data_get($field, 'name') }}"
						   class="form-control input-md{{ $errorClass }} cf-date"
						   value="{{ $defaultValue }}"
						   autocomplete="off"
					>
					<div class="form-text text-muted">{!! data_get($field, 'help') !!}</div>
				</div>
			</div>

		@elseif ($modelFieldType == 'date_time')

			{{-- date_time --}}
			<div class="row mb-3 {{ $requiredClass }}">
				<label class="col-md-3 col-form-label" for="{{ $fieldId }}">
					{{ data_get($field, 'name') }}
					@if (data_get($field, 'required') == 1)
						<sup>*</sup>
					@endif
				</label>
				<div class="col-md-8">
					<input id="{{ $fieldId }}"
						   name="{{ $fieldName }}"
						   type="text"
						   placeholder="{{ data_get($field, 'name') }}"
						   class="form-control input-md{{ $errorClass }} cf-date_time"
						   value="{{ $defaultValue }}"
						   autocomplete="off"
					>
					<div class="form-text text-muted">{!! data_get($field, 'help') !!}</div>
				</div>
			</div>

		@elseif ($modelFieldType == 'date_range')

			{{-- date_range --}}
			<div class="row mb-3 {{ $requiredClass }}">
				<label class="col-md-3 col-form-label" for="{{ $fieldId }}">
					{{ data_get($field, 'name') }}
					@if (data_get($field, 'required') == 1)
						<sup>*</sup>
					@endif
				</label>
				<div class="col-md-8">
					<input id="{{ $fieldId }}"
						   name="{{ $fieldName }}"
						   type="text"
						   placeholder="{{ data_get($field, 'name') }}"
						   class="form-control input-md{{ $errorClass }} cf-date_range"
						   value="{{ $defaultValue }}"
						   autocomplete="off"
					>
					<div class="form-text text-muted">{!! data_get($field, 'help') !!}</div>
				</div>
			</div>

		@else

			{{-- text --}}
			<div class="row mb-3 {{ $requiredClass }}">
				<label class="col-md-3 col-form-label" for="{{ $fieldId }}">
					{{ data_get($field, 'name') }}
					@if (data_get($field, 'required') == 1)
						<sup>*</sup>
					@endif
				</label>
				<div class="col-md-8">
					@php
						$fieldMax = (int)data_get($field, 'max');
						$fieldMaxAttr = !empty($fieldMax) ? ' maxlength="'. $fieldMax .'"' : '';
					@endphp
					<input id="{{ $fieldId }}"
						   name="{{ $fieldName }}"
						   type="text"
						   placeholder="{{ data_get($field, 'name') }}"
						   class="form-control input-md{{ $errorClass }}"
						   value="{{ $defaultValue }}"{!! $fieldMaxAttr !!}>
					<div class="form-text text-muted">{!! data_get($field, 'help') !!}</div>
				</div>
			</div>

		@endif
	@endforeach
@endif

<script>
	$(function() {
		/*
		 * Custom Fields Date Picker
		 * https://www.daterangepicker.com/#options
		 */
		{{-- Single Date --}}
		let dateEl = $('#cfContainer .cf-date');
		dateEl.daterangepicker({
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
		dateEl.on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('{{ t('datepicker_format') }}'));
		});

		{{-- Single Date (with Time) --}}
		let dateTimeEl = $('#cfContainer .cf-date_time');
		dateTimeEl.daterangepicker({
			autoUpdateInput: false,
			autoApply: true,
			showDropdowns: false,
			minYear: parseInt(moment().format('YYYY')) - 100,
			maxYear: parseInt(moment().format('YYYY')) + 20,
			locale: {
				format: '{{ t('datepicker_format_datetime') }}',
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
			timePicker: true,
			timePicker24Hour: true,
			startDate: moment().format('{{ t('datepicker_format_datetime') }}')
		});
		dateTimeEl.on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('{{ t('datepicker_format_datetime') }}'));
		});

		{{-- Date Range --}}
		let dateRangeEl = $('#cfContainer .cf-date_range');
		dateRangeEl.daterangepicker({
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
		dateRangeEl.on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('{{ t('datepicker_format') }}') + ' - ' + picker.endDate.format('{{ t('datepicker_format') }}'));
		});


	});


	let mark_field = $('select[id="cf.51"]')
	let model_field = $('select[id="cf.50"]')

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
						mark_id: window.mark_id,
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
			window.mark_id = e.params.args.data.mark_id
			model_field.prop('disabled', false)
		});

		model_field.prop('disabled', true)

		let old_mark_field = "{{ $old_model_field ?? '' }}"
		let old_model_field = "{{ $old_model_field ?? '' }}"

		if(old_mark_field?.length) {
			let markFieldOption = new Option(old_mark_field, old_mark_field, true, true)
			mark_field.append(markFieldOption).trigger('change');
		}
		if(old_model_field?.length) {
			let modelFieldOption = new Option(old_model_field, old_model_field, true, true)
			model_field.append(modelFieldOption).trigger('change');
			model_field.prop('disabled', false)
		}
	}

	const url = 'https://partsapi.ru/api.php?method=VINdecode'
	const key = '{{env('VIN_API', '5cdb1bdd38ea05fb81466bafb1d86efb')}}'

	/** Обработка поля VIN-номера */
	let vin_field = document.getElementById("cf.27") ?? null;

	if(vin_field) {

		document.getElementById("cf.27").addEventListener("input", function(event) {
			const allowedChars = /^[0-9A-HJ-NPR-Z]+$/i
			const inputValue = event.target.value

			if (!allowedChars.test(inputValue)) {
				event.target.value = inputValue.replace(/[^0-9A-HJ-NPR-Z]+/ig, "").toUpperCase()
			} else {
				event.target.value = inputValue.toUpperCase()
			}
		})

		document.getElementById("cf.27").addEventListener("change", function () {
			makeRequest()
		})

		function toggleDisabled(disabled) {
			document.querySelectorAll('#cfContainer input,select').forEach(element => element.disabled = disabled);
		}

		function makeRequest() {
			jsAlert('Ищем данные по VIN. Пожалуйста, подождите.', 'warning');
			toggleDisabled(true)
			fetch(`https://partsapi.ru/api.php?method=VINdecode&key=${key}&vin=${event.target.value}&lang=ru`)
					.then((response) => response.json())
					.then((data) => {
						console.log(data)
						if(!data.result[0].manuName && !data.result[0].modelName) {
							jsAlert('Не смогли найти Ваш VIN номер. Укажите данные вручную.', 'warning');
						} else {
							jsAlert('VIN номер валидный. Некоторые поля подставлены автоматически.', 'success');

							let powerHpFrom = data.result[0]?.powerHpFrom ?? null
							let cylinderCapacityLiter = data.result[0]?.cylinderCapacityLiter ?? null
							let impulsionType = data.result[0]?.impulsionType ?? null
							let fuelType = data.result[0]?.fuelType ?? null
							let marka = data.result[0]?.manuName ?? null
							let model = data.result[0]?.modelName ?? null

							if(cylinderCapacityLiter) {
								$('select[id="cf.34"] > option').each(function(e) {
									if(this.text == cylinderCapacityLiter) {
										$('select[id="cf.34"]').find(`option[value=${this.value}]`).attr("selected",true)
										$('#select2-cf34-container').text(this.text)
									}
								});
							}
							if(powerHpFrom) {
								$('input[id="cf.49"]').val(powerHpFrom)
							}
							if(impulsionType) {
								let privod = '';

								if(impulsionType.toLowerCase().includes('front')) {
									privod = 'Передний'
								} else if (impulsionType.toLowerCase().includes('back')) {
									privod = 'Задний'
								} else {
									privod = 'Полный'
								}

								$('select[id="cf.36"] > option').each(function(e) {
									if(this.text === privod) {
										$('select[id="cf.36"]').find(`option[value=${this.value}]`).attr("selected",true)
										$('#select2-cf36-container').text(privod)
									}
								});
							}
							if(fuelType) {
								let fuel = '';
								//TODO дописать если добавим поле тип топлива
							}
							if(marka) {
								$('#select2-cf51-container').val(marka)
							}
							if(model) {
								$('#select2-cf50-container').val(model)
							}
						}
					})
					.finally(()=> {
						toggleDisabled(false)
					})
		}

		// makeRequest()
	}
</script>

