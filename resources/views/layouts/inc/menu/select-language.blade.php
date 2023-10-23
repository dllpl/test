<?php $supportedLanguages = getSupportedLanguages(); ?>
@if (is_array($supportedLanguages) && count($supportedLanguages) > 1)
	{{-- Language Selector --}}
	<li class="header__item dropdown lang-menu no-arrow open-on-hover">
		<a href="#" class="dropdown-toggle link" data-bs-toggle="dropdown" id="langDropdown">
			<span><svg class="header__svg">
              <use xlink:href="/images/sprite.svg#world"></use>
            </svg></span>
		</a>
		<ul id="langDropdownItems"
			class="dropdown-menu dropdown-menu-end user-menu shadow-sm"
			role="menu"
			aria-labelledby="langDropdown"
		>
			@foreach($supportedLanguages as $langCode => $lang)
				<li class="dropdown-item{{ (strtolower($langCode) == strtolower(config('app.locale'))) ? ' active' : '' }}">
					<a href="{{ url('locale/' . $langCode) }}" tabindex="-1" rel="alternate" hreflang="{{ $langCode }}" title="{{ $lang['name'] }}">
						<?php
							$langFlag = (
								config('settings.app.show_languages_flags')
								&& isset($lang, $lang['flag'])
								&& is_string($lang['flag'])
								&& !empty(trim($lang['flag']))
							)
								? '<i class="flag-icon ' . $lang['flag'] . '"></i>&nbsp;'
								: '';
						?>
						{!! $langFlag. $lang['native'] !!}
					</a>
				</li>
			@endforeach
		</ul>
	</li>
@endif
