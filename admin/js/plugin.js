var CP = CP || {};

// toggle Class
CP.toggleClass = (function ($) {
	var $document = jQuery(document),

		toggleClass = function () {
			var $this = jQuery(this), $target, cls = $this.attr('data-toggle') || 'is-active', remove = $this.attr('data-toggle-remove') || null;
			target = $this.attr('data-toggle-target') || null;

			$target = (target) ? (jQuery(target)) : $this;
			if (remove) {
				$target.removeClass(remove);
			}

			$target.toggleClass(cls);

			return false;
		},

		init = function () {
			$document.on('click', '[data-toggle]', toggleClass);
		};

	return {
		init: init
	};
})(jQuery);

// list of selected media
CP.selectedMedia = [];

// 來源媒體
CP.sourceMedia = (function ($) {

	clickLabel = function () {
		$source = jQuery('.m-source');
		$label = $source.find('.m-label');
		$text = $source.find('.m-source-text');

		var $this = jQuery(this), $label = $source.find('.m-label'), $list, text = $this.text(), tmp = [];

		$label.filter('[data-name="' + text + '"]').toggleClass('is-active');
		$list = $label.filter('.is-active').each(function () {
			var $this = jQuery(this);
			tmp.push($this.attr('data-name'));
		});
		tmp = tmp.filter(function (x, i, self) {
			return self.indexOf(x) === i;
		});

		CP.selectedMedia = tmp;
		$text.text(tmp.join('、'));

		jQuery('#article_list').DataTable().ajax.reload();
	},

		clearLabel = function () {
			$source = jQuery('.m-source');
			$label = $source.find('.m-label');
			$text = $source.find('.m-source-text');
			var $label = $source.find('.m-label');
			$label.removeClass('is-active');
			$text.text('');
			jQuery('#article_list').DataTable().ajax.reload();
		},

		init = function () {
			$source = jQuery('.m-source');
			if ($source.length) {
				$source.on('click', '.m-label', clickLabel);
				$source.find('.m-source-clear').on('click', clearLabel);
			}
		};

	return {
		init: init
	};
})(jQuery);

CP.openPreviewBox = function (title, link) {
	jQuery.fancybox({
		'href': link,
		'title': title,
		'type': 'iframe',
		titleShow: false,
		margin: 50,
		width: 1024,
		height: 1900,
		onComplete: function (current, previous) {
			// make it highter than wordpress menu Zzz
			jQuery('#fancybox-wrap').css('z-index', 99999 + 1);
		}
	});
}

CP.getTokenFromSite = function (success) {
	var nonce = jQuery("#hidden_nonce").val();
	jQuery.post(ajaxurl, {
		'action': 'content_party_get_usercode',
		'security': nonce
	}, function (data) {
		jQuery.post("https://contentparty.org:443/api/get_token", {
			user_code: data,
		}, function (data) {
			success(data.result.token);
		}).fail(function (data) {
			alert('Get token failed: ' + data.responseText);
		});
	}).fail(function (data) {
		if (data.status == 400) {
			alert('Please log in first.');
			window.location.replace("admin.php?page=content_party_menu_handle");
		} else {
			alert('Get user code failed: ' + data.responseText);
		}
	});

}

CP.initArticleList = function (token, dataPrepareFunc) {
	jQuery('#article_list').addClass('display');
	var table = jQuery('#article_list').DataTable({
		"searching": false,
		"ordering": true,
		"lengthChange": false,
		"processing": true,
		"serverSide": true,
		"bInfo": false,
		"autoWidth": false,
		"ajax": {
			'url': "https://contentparty.org:443/api/search_article_id",
			'type': "POST",
			"data": function (d) {
				d = dataPrepareFunc(token, d);
			},
			"dataSrc": function (json) {
				var info = jQuery('#article_list').DataTable().page.info();
				json.recordsTotal = json.total;

				if (json.total > 100) {
					json.recordsFiltered = 100;
				} else {
					json.recordsFiltered = json.total;
				}

				if (json.total === 0) {
					return [];
				} else {
					return json.result;
				}
			},
			"error": function (data, textStatus) {
				alert('list article api error(status:' + textStatus + '): ' + data.responseText);
			}
		},
		"deferRender": true,
		"order": [],
		"columnDefs": [{
			"title": '<input id="check_all" value="1" type="checkbox">',
			"width": "5%",
			'targets': 0,
			'searchable': false,
			'orderable': false,
			'className': 'dt-body-center',
			'render': function (data, type, full, meta) {
				return '<input type="checkbox">';
			}
		}, {
			"data": "data_id",
			"visible": false,
			"searchable": false
		}, {
			"data": "url",
			"visible": false,
			"searchable": false
		}, {
			"data": "title",
			"orderable": false,
			"width": "30%",
			"targets": 1,
			"title": '標題'
		}, {
			"data": "author",
			"orderable": false,
			"width": "13%",
			'className': 'dt-center',
			"targets": 2,
			"title": '作者'
		}, {
			"data": "sitename",
			"orderable": false,
			"width": "12%",
			'className': 'dt-center',
			"targets": 3,
			"title": '來源'
		}, {
			"data": "time",
			"orderable": false,
			"sorting": ["desc", "asc"],
			"width": "13%",
			'className': 'dt-center',
			"targets": 4,
			"title": '時間'
		}, {
			"data": null,
			"defaultContent": "\
          <a class='m-button is-gray datatable-action-btn' id='content_preview' value='' href='#' title='預覽'><i class='m-icon is-search'></i></a>\
          <a class='m-button is-gray datatable-action-btn' id='open_url' href='#' title='看原始網址'><i class='m-icon is-link'></i></a>\
          <a class='m-button is-gray datatable-action-btn' id='open_setting' href='#' title='進階設定' ><i class='m-icon is-gear'></i></a>\
          <a class='m-button is-gray datatable-action-btn' id='save_draft' href='#' title='存入草稿' ><i class='m-icon is-floppy'></i></a>",
			"orderable": false,
			"width": "12%",
			'className': 'dt-center',
			"targets": 5,
			"title": '動作'
		}],
		"jQueryUI": false,
		"paging": true,
		"pagingType": "input",
		"iDisplayLength ": 10
	});

	jQuery('#sort_latest').click(function () {
		jQuery(this).attr('class', 'is-curr');
		jQuery("#sort_hot").attr('class', 'not-curr');
		table.ajax.reload();
	});

	jQuery('#sort_hot').click(function () {
		jQuery(this).attr('class', 'is-curr');
		jQuery("#sort_latest").attr('class', 'not-curr');
		table.ajax.reload();
	});

	jQuery('#search_input').keypress(function (e) {
		var key = e.which;
		if (key == 13) {
			// enter pressed
			table.ajax.reload();
		}
	});

	jQuery('#search_button').click(function () {
		table.ajax.reload();
	});

	jQuery('#check_all').change(function (o) {
		jQuery('#article_list tbody').find('input[type=checkbox]').prop('checked', jQuery(this).prop('checked'));
	});

	// batch import
	jQuery('#batch_button').click(function () {
		var successCnt = 0;
		$checkboxes = jQuery('#article_list tbody').find('input[type=checkbox]:checked');
		if ($checkboxes.size() == 0) {
			alert('請選擇文章!');
			return;
		}
		jQuery("#wpcontent").mask();
		$checkboxes.each(function () {
			var data = table.row(jQuery(this).closest('tr')).data();
			CP.getFullArticleData(data.data_id, token, function (fulldata, status) {
				var nonce = jQuery("#hidden_nonce").val();
				jQuery.post(ajaxurl, {
					"action": 'content_party_add_draft',
					"data": fulldata.result,
					'security': nonce
				}, function () {
					successCnt++;
					if (successCnt == $checkboxes.size()) {
						alert('成功存入' + successCnt + '份草稿!');
						jQuery("#wpcontent").unmask();
					}
				}).fail(function (data) {
					alert('addDraft error: ' + data.responseText);
					jQuery("#wpcontent").unmask();
				});
			});
		});
	});

	// original link
	jQuery('#article_list tbody').on('click', 'a[id="open_url"]', function () {
		var data = table.row(jQuery(this).closest('tr')).data();
		console.log(data);
		window.open(data.url, '_blank');
	});

	// preview
	jQuery('#article_list tbody').on('click', 'a[id="content_preview"]', function () {
		var data = table.row(jQuery(this).closest('tr')).data();
		var link = 'https://contentparty.org/pr/' + data.data_id;
		CP.openPreviewBox(data.title, link);
	});

	// advance view
	jQuery('#article_list tbody').on('click', 'a[id="open_setting"]', function () {
		var data = table.row(jQuery(this).closest('tr')).data();
		var link = 'admin.php?page=content_party_importpage_handle&data_id=' + data.data_id;
		CP.openAdvanceBox(data.title, link);
	});

	// one click add draft
	jQuery('#article_list tbody').on('click', 'a[id="save_draft"]', function () {
		jQuery("#wpcontent").mask();
		var data = table.row(jQuery(this).closest('tr')).data();
		CP.getFullArticleData(data.data_id, token, function (fulldata, status) {
			var nonce = jQuery("#hidden_nonce").val();
			jQuery.post(ajaxurl, {
				"action": 'content_party_add_draft',
				"data": fulldata.result,
				'security': nonce
			}, function () {
				alert("存入草稿成功!");
				jQuery("#wpcontent").unmask();
			}).fail(function (data) {
				alert('addDraft error: ' + data.responseText);
				jQuery("#wpcontent").unmask();
			});
		});
	});
}

CP.openAdvanceBox = function (title, link) {

	jQuery.fancybox({
		'href': link,
		'type': 'iframe',
		fitToView: true,
		margin: 50,
		width: 1024,
		height: 1900,
		onComplete: function (current, previous) {
			// make it highter than wordpress menu ZZzz
			jQuery('#fancybox-wrap').css('z-index', 99999 + 1);
		}
	});
}

CP.getFullArticleData = function (data_id, token, callback) {
	jQuery.post("https://contentparty.org:443/api/get_article", {
		"data_id": data_id,
		"token": token,
		"lang": "zh-tw",
		"tag": "disable",
	}, function (data, status) {
		if (status === 'success') {
			// fetch thumbnail img url
			$thumbs = jQuery(data.result.content).find('img[alt=首圖]');
			data.result.thumbnail_url = $thumbs[0].src;
		} else {
			alert('getFullArticleData error!');
		}
		callback(data, status);
	}).fail(function (data) {
		alert('Api get_article error: ' + data.responseText);
	});
}

jQuery(document).on('ready', function () {
	CP.toggleClass.init();
});
