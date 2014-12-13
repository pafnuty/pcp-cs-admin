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
			$block.find('.styler').selectize();
			
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

		console.log('функция в разработке. Нужно передавать данные в ajax скрипт и заменять соответствующую строку таблицы на инпуты с формой.', $data);
	})
	.on('focus', '.onfocus-select', function() {
		$(this).select();
	})
	.on('change', '.status-change', function() {
		var $this = $(this),
			key = $this.data('key'),
			status = $this.find('option:selected').val();

		swal({
			title: 'Изменить статус ключа?',
			// text: "Нобходимо подтвердить действие",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#50bd98',
			confirmButtonText: 'Да',
			closeOnConfirm: false
		}, function (isConfirm) {
			if (isConfirm) {
				confirmButtonColor: '#50bd98';
				if (status == '1') {
					swal({
						title: 'Ошибка!',
						text: 'Активировать лицензию может только клиент.',
						type: 'error'
					}, function () {
						location.reload();
					});
				} else {
					$.ajax({
							url: '/admin/ajax/keystatus.php',
							data: {
								key: key,
								status: status
							}
						})
						.done(function (data) {
							var title = 'Ошибка :(',
								type = 'error';

							if (data == 'ok') {
								title = 'Статус изменён!';
								type = 'success';
							};
							swal({
								title: title,
								// text: 'Статус изменён.',
								type: type
							}, function () {
								location.reload();
							});
						})
						.fail(function () {
							console.log('error');
						});
				};
			} else {
				///
			}
			
		});
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
				this.content.find('.styler').selectize();
			}
		}
	});

});
