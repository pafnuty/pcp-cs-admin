/*!
=============================================================================
Независимая админка для php-cs
=============================================================================
Автор:   Павел Белоусов 
URL:     https://github.com/pafnuty/pcp-cs-admin
email:   pafnuty10@gmail.com
=============================================================================
*/

var doc = $(document);


doc
	.on('click', '.modal-close', function () {
		$.magnificPopup.close();
	})
	.on('click browserNavigation', '[data-page-num]', function (event) {
		var $this = $(this),
			$block = $this.closest('[data-list-id]'),
			listId = $block.data('listId'),
			pageNum = $this.data('pageNum'),
			laddaBtn = $this.ladda(),
			u = new Url,
			url;

		u.query.p = pageNum;
		url = u;

		laddaBtn.ladda('start');

		$.ajax({
			url: url,
			dataType: 'html',
			data: {
				ajax: 'Y',
				p: pageNum
			},
		})
		.done(function (data) {
			$block.html($(data).find('[data-list-id="' + listId + '"]').html());
			
			if (history.pushState && event.type != 'browserNavigation') {
				window.history.pushState(null, null, url);
			}
		})
		.fail(function () {
			console.log("error");
		});

	})
	.on('click', '.btn-ajax-edit', function(event) {
		var $this = $(this),
			$data = $this.data(),
			$row = $('[data-method-id="' + $data.id + '"]');

		console.log('функция в разработке. Нужно передавать данные в ajax скрипт и звменять соответствующую строку таблицы на инпуты с формой.', $data);
	});

	$(window).on('popstate', function (e) {
		var u = new Url(location.href);

		if (u.query.p) {
			$('[data-page-num="' + u.query.p + '"]').trigger('browserNavigation');
		} else {
			if ($('[data-page-num="1"]').length) {
				$('[data-page-num="1"]').trigger('browserNavigation');
			};
		}
	});




jQuery(document).ready(function ($) {
	// Селекты
	var $select = $('.styler').selectize();

	// Дефолтные настройки magnificpopup
	$.extend(true, $.magnificPopup.defaults, {
		tClose: 'Закрыть (Esc)', // Alt text on close button
		tLoading: 'Загрузка...', // Text that is displayed during loading. Can contain %curr% and %total% keys
		ajax: {
			tError: '<a href="%url%">Контент</a> не загружен.' // Error message when ajax request failed
		}
	});

	$('.mfp-open-ajax').magnificPopup({
		type: 'ajax',
		callbacks : {
			ajaxContentAdded: function () {
				$('.styler').selectize();
			}
		}
	});

});


/**
 * Функция для реализации эффекта загрузки блока
 * Добавляет/удаляет заданный класс для заданного блока
 * вся работа по оформлению ложится на css
 *
 * @author ПафНутиЙ <pafnuty10@gmail.com>
 *
 * @param  {str} id        ID блока
 * @param  {str} method    start/stop
 * @param  {str} className Имя класса, добавляемого блоку
 */
function base_loader (id, method, className) {
	var $block = $('#' + id),
		cname = (className) ? className : 'base-loader';
	if (method == 'start') {
		$block.addClass(cname);
	};

	if (method == 'stop') {
		$block.removeClass(cname);
	};
}
