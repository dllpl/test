/*
 * LaraClassifier - Classified Ads Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com
 * Author: BeDigit | https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - https://codecanyon.net/licenses/standard
 */

if (typeof isLogged === 'undefined') {
	var isLogged = false;
}

$(document).ready(function () {

	/* Save the Post */
	$("a[id].make-favorite").click(function () {
		if (isLogged !== true) {
			openLoginModal();

			return false;
		}

		savePost(this);
	});

	/* Save the Search */
	$('#saveSearch').click(function () {
		if (isLogged !== true) {
			openLoginModal();

			return false;
		}

		saveSearch(this);
	});

});

/**
 * Save Ad
 * @param el
 * @returns {boolean}
 */
function savePost(el) {
	/* Get element's icon */
	var iconEl = null;
	if ($(el).is('a')) {
		iconEl = $(el).find('i');
	}

	let postId = $(el).attr('id');
	if (!isDefined(postId)) {
		return false;
	}

	let url = siteUrl + '/ajax/save/post';

	let ajax = $.ajax({
		method: 'POST',
		url: url,
		data: {
			'post_id': postId,
			'_token': $('input[name=_token]').val()
		},
		beforeSend: function() {
			/* Change the button indicator */
			if (iconEl) {
				iconEl.removeClass('fas fa-bookmark');
				iconEl.addClass('spinner-border spinner-border-sm').css({'vertical-align': 'middle'});
				iconEl.attr({'role': 'status', 'aria-hidden': 'true'});
			}
		}
	});
	ajax.done(function (xhr) {
		/* console.log(xhr); */
		if (typeof xhr.isLogged === 'undefined') {
			/* Reset the button indicator */
			if (iconEl) {
				iconEl.removeClass('spinner-border spinner-border-sm').css({'vertical-align': ''});
				iconEl.addClass('fas fa-bookmark').removeAttr('role aria-hidden');
			}

			return false;
		}

		if (xhr.isLogged !== true) {
			openLoginModal();

			/* Reset the button indicator */
			if (iconEl) {
				iconEl.removeClass('spinner-border spinner-border-sm').css({'vertical-align': ''});
				iconEl.addClass('fas fa-bookmark').removeAttr('role aria-hidden');
			}

			return false;
		}

		/* Logged Users - Notification */
		if (xhr.isSaved === true) {
			$(el).html('<svg class="preview__like"><path d="M13.1961 24.5476L13.1946 24.5462C9.40345 21.1084 6.33722 18.3222 4.20709 15.7161C2.08795 13.1235 1.00003 10.8316 1.00003 8.3995C1.00003 4.42718 4.0956 1.34387 8.05566 1.34387C10.3019 1.34387 12.4743 2.39471 13.8878 4.04165L14.6466 4.9258L15.4055 4.04165C16.819 2.39471 18.9914 1.34387 21.2376 1.34387C25.1977 1.34387 28.2932 4.42718 28.2932 8.3995C28.2932 10.8316 27.2053 13.1235 25.0862 15.7161C22.956 18.3222 19.8898 21.1084 16.0986 24.5462L16.0972 24.5476L14.6466 25.8681L13.1961 24.5476Z" fill="#FF4848" stroke="#FF4848" stroke-width="2" /></svg>');
			jsAlert(xhr.message, 'success');
		} else {
			$(el).html('<svg class="preview__like"><path d="M13.1961 24.5476L13.1946 24.5462C9.40345 21.1084 6.33722 18.3222 4.20709 15.7161C2.08795 13.1235 1.00003 10.8316 1.00003 8.3995C1.00003 4.42718 4.0956 1.34387 8.05566 1.34387C10.3019 1.34387 12.4743 2.39471 13.8878 4.04165L14.6466 4.9258L15.4055 4.04165C16.819 2.39471 18.9914 1.34387 21.2376 1.34387C25.1977 1.34387 28.2932 4.42718 28.2932 8.3995C28.2932 10.8316 27.2053 13.1235 25.0862 15.7161C22.956 18.3222 19.8898 21.1084 16.0986 24.5462L16.0972 24.5476L14.6466 25.8681L13.1961 24.5476Z" fill="#FFFFFF" stroke="#FF4848" stroke-width="2" /></svg>');
			jsAlert(xhr.message, 'success');
		}

		/* Reset the button indicator */
		if (iconEl) {
			iconEl.removeClass('spinner-border spinner-border-sm').css({'vertical-align': ''});
			iconEl.addClass('fas fa-bookmark').removeAttr('role aria-hidden');
		}

		return false;
	});
	ajax.fail(function (xhr, textStatus, errorThrown) {
		/* Reset the button indicator */
		if (iconEl) {
			iconEl.removeClass('spinner-border spinner-border-sm').css({'vertical-align': ''});
			iconEl.addClass('fas fa-bookmark').removeAttr('role aria-hidden');
		}

		if (typeof xhr.status !== 'undefined') {
			if (xhr.status === 401) {
				openLoginModal();

				if (isLogged !== true) {
					return false;
				}
			}
		}

		let message = getJqueryAjaxError(xhr);
		if (message !== null) {
			jsAlert(message, 'error', false);
		}
	});

	return false;
}

/**
 * Save Search
 * @param el
 * @returns {boolean}
 */
function saveSearch(el) {
	let searchUrl = $(el).data('name');
	let countPosts = $(el).data('count');

	let url = siteUrl + '/ajax/save/search';

	let ajax = $.ajax({
		method: 'POST',
		url: url,
		data: {
			'url': searchUrl,
			'count_posts': countPosts,
			'_token': $('input[name=_token]').val()
		}
	});
	ajax.done(function (xhr) {
		/* console.log(xhr); */
		if (typeof xhr.isLogged === 'undefined') {
			return false;
		}

		if (xhr.isLogged !== true) {
			openLoginModal();

			return false;
		}

		/* Logged Users - Notification */
		jsAlert(xhr.message, 'success');

		return false;
	});
	ajax.fail(function (xhr, textStatus, errorThrown) {
		if (typeof xhr.status !== 'undefined') {
			if (xhr.status === 401) {
				openLoginModal();

				return false;
			}
		}

		let message = getJqueryAjaxError(xhr);
		if (message !== null) {
			jsAlert(message, 'error', false);
		}
	});

	return false;
}
