var CP_ADV = CP_ADV || {};

CP_ADV.fulldata = null;
CP_ADV.postId = null;
CP_ADV.datetimepicker = null;


jQuery(document).on('ready', function () {
	//hide wp menu/header/blahblahblah
	var $body = jQuery('#cp-advance-box').parents("body.wp-admin");
	var $content = jQuery('#cp-advance-box');
	$body.children().hide();
	$content.appendTo($body);

	// remove wp toolbar class to reset css
	var $doc = jQuery('#cp-advance-box').parents('html.wp-toolbar');
	$doc.removeClass('wp-toolbar');

	//show tripped content
	$content.show();

	jQuery("#content-area").hide();
	CP.getTokenFromSite(function (token) {
		CP_ADV.fillareas(token);
		CP_ADV.selectBox.init();
	});

	CP_ADV.datetimepicker = jQuery('#datetimepicker').datetimepicker({
		format: 'Y-m-d H:i:s',
	});

	jQuery('#DatetimepickerIcon').click(function () {
		jQuery('#datetimepicker').datetimepicker('show');
	});

	jQuery('#draft-button').click(function () {
		CP_ADV.addDraft();
	});

	jQuery('#preview-button').click(function () {
		if (CP_ADV.postId !== null) {
			var nonce = jQuery("#hidden_nonce").val();
			jQuery.post(ajaxurl, {
				"action": 'content_party_get_preview_url',
				"post_id": CP_ADV.postId,
				'security': nonce
			}, function (url, status) {
				window.open(url, '_blank');
			}).fail(function () {
				alert('get preview URL error!');
			});
		} else {
			alert("請先存入草稿");
		}
	});

	jQuery('#cancel-button').click(function () {
		parent.jQuery.fancybox.close();
	});

	jQuery('#publish-button').click(CP_ADV.addPost);

});

CP_ADV.addPost = function () {
	CP_ADV.fulldata.title = jQuery('#input-title').val();
	CP_ADV.fulldata.excerpt = jQuery('#excerpt-area').val();
	CP_ADV.fulldata.postStatus = 'publish';
	CP_ADV.fulldata.postDate = CP_ADV.datetimepicker.val();
	var tags = [];
	jQuery('.m-tag-box > .m-tag-labels > span.m-tag').each(function () {
		tags.push(jQuery(this).text())
	});
	CP_ADV.fulldata.tags = tags;

	var categories = [];
	jQuery('#category-m-select>select').find(":selected").each(function () {
		categories.push(parseInt(jQuery(this).attr('cateId'), 10));
	});
	CP_ADV.fulldata.categories = categories;

	var nonce = jQuery("#hidden_nonce").val();
	jQuery.post(ajaxurl, {
		"action": 'content_party_add_draft',
		"data": CP_ADV.fulldata,
		'security': nonce
	}, function (report, status) {
		alert("成功發佈!");
		CP_ADV.postId = report;
	}).fail(function (data) {
		alert('addPost error: ' + data.responseText);
	});
}

CP_ADV.fillareas = function (token) {
	var dataId = jQuery('#hidden_dataid').val();
	CP.getFullArticleData(dataId, token, function (data, status) {
		if (status !== 'success') {
			alert("fillareas error!");
			return;
		}
		CP_ADV.fulldata = data.result;
		jQuery('#input-title').val(CP_ADV.fulldata.title);

		$thumbs = jQuery(CP_ADV.fulldata.content).find('img[alt=首圖]');
		if ($thumbs.length !== 0) {
			jQuery('#article-img-container').append($thumbs[0].outerHTML);
		}

		if (CP_ADV.fulldata.tag === false) {
			CP_ADV.tagBox.init([]);
		} else {
			tags = CP_ADV.fulldata.tag.split(",");
			CP_ADV.tagBox.init(tags);
		}

		// expand js content
		jQuery('#content-area').append(CP_ADV.fulldata.content);
		jQuery("#content-area").fadeIn(500);
	});
}

CP_ADV.addDraft = function () {
	CP_ADV.fulldata.title = jQuery('#input-title').val();
	CP_ADV.fulldata.excerpt = jQuery('#excerpt-area').val();
	CP_ADV.fulldata.postStatus = 'draft';
	CP_ADV.fulldata.postDate = CP_ADV.datetimepicker.val();
	var tags = [];
	jQuery('.m-tag-box > .m-tag-labels > span.m-tag').each(function () {
		tags.push(jQuery(this).text())
	});
	CP_ADV.fulldata.tags = tags;

	var categories = [];
	jQuery('#category-m-select>select').find(":selected").each(function () {
		categories.push(parseInt(jQuery(this).attr('cateId'), 10));
	});
	CP_ADV.fulldata.categories = categories;

	var nonce = jQuery("#hidden_nonce").val();
	jQuery.post(ajaxurl, {
		"action": 'content_party_add_draft',
		"data": CP_ADV.fulldata,
		'security': nonce
	}, function (report, status) {
		alert("存入草稿成功!");
		CP_ADV.postId = report;
	}).fail(function (data) {
		alert('addDraft error: ' + data.responseText);
	});
}

// select Box
CP_ADV.selectBox = (function ($) {
	var $select = jQuery('.m-select'),

		activeSelect = function () {
			var $this = jQuery(this);
			$this.toggleClass('is-active');
		},

		selectTemplate = function (data) {
			var tmp = [], i, l;

			data = data || {};
			data.arr = data.arr || [];
			i = 0;
			l = data.arr.length;
			for (i; i < l; i++) {
				tmp.push('<li data-value="', data.arr[i].value, '">', data.arr[i].text, '</li>');
			}
			return ['<label>', data.def, '</label>', '<ul>', tmp.join(''), '</ul>'].join('');
		},

		getSelect = function () {
			var $this = jQuery(this), $par = $this.parents('.m-select'), $sel = $par.find('>select'), $lab = $par.find('>label'), val = $this.attr('data-value'), text = $this.text();
			$sel.val(val);
			$lab.text(text);
		},

		preSetting = function () {
			var $mSelect = jQuery('#category-m-select'), $obj = $mSelect.find('select'), $opt = $obj.find('>option'), data = {};
			if (!$obj.length) {
				return;
			}
			data.arr = [];
			$opt.each(function () {
				var $this = jQuery(this);
				data.arr.push({
					value: $this.val(),
					text: $this.text()
				})
			});
			data.def = $opt.filter(':selected').text();
			$mSelect.append(selectTemplate(data));
		},

		getCategories = function () {
			var nonce = jQuery("#hidden_nonce").val();
			jQuery.post(ajaxurl, {
				'action': 'content_party_get_categories',
				'security': nonce
			}, function (catlist, status) {
				for (var catkey in catlist) {
					jQuery('#category-m-select>select').append(jQuery("<option></option>").attr("value", catlist[catkey].name).attr("cateId", catlist[catkey].cat_ID).text(catlist[catkey].name));
				}
				jQuery('#category-m-select').each(preSetting).on('click', activeSelect).on('click', 'li[data-value]', getSelect);
			}).fail(function (data) {
				alert('getCategories failed: ' + data.responseText);
			});
		},

		init = function () {
			getCategories();
		};

	return {
		init: init
	};
})(jQuery);

CP_ADV.tagBox = (function ($) {

	labelTag = function (val) {
		return ['<span class="m-tag">', '<span class="m-icon is-close"></span>', val, '</span>'].join('');
	},

		importTag = function () {
			var $this = jQuery(this);
			if ($this.hasClass('imported')) {
				$found = jQuery('.m-tag-area').find(".m-tag-labels .m-tag:contains('" + $this.text() + "')");
				$found.remove();
				$this.removeClass('imported');
			} else {
				jQuery('.m-tag-area').find('.m-tag-labels').append(jQuery("<span class='m-tag m-import-tag'><span class='m-icon is-close'></span>" + $this.text() + "</span>"));
				$this.addClass('imported');
			}
		},

		addTag = function () {
			var $this = jQuery(this), $par = $this.parents('.m-tag-area'), $lab = $par.find('.m-tag-labels'), $add = $par.find('.m-tag-add');

			if ($add.val()) {
				$lab.append(labelTag($add.val()));
				$add.val('');
			} else {
				$add.trigger('focus');
			}
		},

		removeTag = function () {
			$tag = jQuery(this).parents('.m-tag');
			jQuery('.m-tag-import-area').find(".m-tag-labels .m-tag:contains('" + $tag.text() + "')").removeClass('imported');
			$tag.remove();
		},

		pressEnter = function (e) {
			if (e.keyCode !== 13) {
				return;
			}
			jQuery(this).parents('.m-tag-area').find('.m-tag-button').trigger('click');
		},

		getTags = function (originalTags) {
			var $tagbox = jQuery('.m-tag-area');
			var $importTagbox = jQuery('.m-tag-import-area');
			var nonce = jQuery("#hidden_nonce").val();
			jQuery.post(ajaxurl, {
				'action': 'content_party_get_tags',
				'security': nonce
			}, function (wptags, status) {
				// show tags, import if exist in wordpress tag list
				$importTagbox.find('.m-tag-labels').addClass('noselect');
				$tagbox.find('.m-tag-labels').addClass('noselect');
				for (var index in originalTags) {
					$originalTag = jQuery("<span class='m-tag m-import-tag'>" + originalTags[index] + "</span>");
					$importTagbox.find('.m-tag-labels').append($originalTag);

					if (wptags.indexOf(originalTags[index]) !== -1) {
						$tagbox.find('.m-tag-labels').append(jQuery("<span class='m-tag'><span class='m-icon is-close'></span>" + originalTags[index] + "</span>"));
						$originalTag.addClass('imported');
					}
				}

				$importTagbox.on('click', '.m-import-tag', importTag);

				$tagbox.on('click', '.m-tag-button', addTag);
				$tagbox.on('click', '.m-tag .m-icon.is-close', removeTag);
				$tagbox.on('keypress', '.m-tag-add', pressEnter);
			}).fail(function (data) {
				alert("Get tags error: " + request.responseText);
			});
		},

		init = function (originalTags) {
			getTags(originalTags);
		};

	return {
		init: init
	};
})(jQuery);
