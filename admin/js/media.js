var CP_MEDIA = CP_MEDIA || {};

jQuery(document).on('ready', function () {
	CP.getTokenFromSite(function (token) {
		CP_MEDIA.getMediaList();
		CP.initArticleList(token, CP_MEDIA.dataTablePrepare);
	});
});

CP_MEDIA.hotDone = false;
CP_MEDIA.latestDone = false;

CP_MEDIA.getMediaList = function () {
	jQuery.ajax({
		type: "post",
		url: 'https://contentparty.org/api/get_rank_cache',
		dataType: "json",
		data: {
			"type": 'new_source',
			'limit': 20,
		},
		success: function (data) {
			CP_MEDIA.addLatestMediaElements(data);
			CP_MEDIA.latestDone = true;
			if (CP_MEDIA.hotDone && CP_MEDIA.latestDone) {
				CP.sourceMedia.init();
			}
		},
		error: function (request, status, error) {
			alert("getLatestMediaList error!");
		},
	});
	jQuery.ajax({
		type: "post",
		url: 'https://contentparty.org/api/get_hot_site',
		dataType: "json",
		data: {
			"day": 7
		},
		success: function (data) {
			CP_MEDIA.addHotMediaElements(data);
			CP_MEDIA.hotDone = true;
			if (CP_MEDIA.hotDone && CP_MEDIA.latestDone) {
				CP.sourceMedia.init();
			}
		},
		error: function (request, status, error) {
			alert("getHotMediaList error!");
		},
	});
}

CP_MEDIA.addLatestMediaElements = function (latestSrcList) {
	jQuery("#latest-media-list>.m-label").remove();
	jQuery(".m-souce-new-more>label.m-label").remove();
	var index = 0;
	jQuery.each(latestSrcList, function (nameIndex) {
		var resource = latestSrcList[nameIndex].res_name;
		if (index < 10) {
			jQuery("<label class='m-label'>" + resource + "<i class='m-icon is-close'></i></label>").insertBefore('.m-souce-new-more').attr('data-name', resource);
		} else if (index >= 10 && index < 20) {
			jQuery("<label class='m-label'>" + resource + "<i class='m-icon is-close'></i></label>").insertBefore('.m-souce-new-more>span.m-label-more').attr('data-name', resource);
		} else {
			return false; // means break.
		}
		index++;
	});
}

CP_MEDIA.addHotMediaElements = function (hotSrcList) {
	jQuery("#hot-media-list>.m-label").remove();
	jQuery(".m-souce-hot-more>label.m-label").remove();
	var index = 0;
	jQuery.each(hotSrcList, function (nameIndex) {
		if (index < 10) {
			jQuery("<label class='m-label'>" + nameIndex + "<i class='m-icon is-close'></i></label>").insertBefore('.m-souce-hot-more').attr('data-name', nameIndex);
		} else if (index >= 10 && index < 20) {
			jQuery("<label class='m-label'>" + nameIndex + "<i class='m-icon is-close'></i></label>").insertBefore('.m-souce-hot-more>span.m-label-more').attr('data-name', nameIndex);
		} else {
			return false; // means break.
		}
		index++;
	});
}

CP_MEDIA.dataTablePrepare = function (token, d) {
	d.token = token;
	d.type = 'all';
	d.limit = 10;

	d.site = CP.selectedMedia.join(',;');

	if (jQuery("#sort_latest").attr('class') == 'is-curr') {
		d.sort = 'time';
	} else if (jQuery("#sort_hot").attr('class') == 'is-curr') {
		var starttime = new Date();
		starttime.setDate(starttime.getDate() - 3);
		d.start_time = starttime.getFullYear() + '-' + (starttime.getMonth() + 1) + '-' + starttime.getDate();

		d.sort = 'clicks';
	} else {
		alert('sort type error');
	}

	d.keyword = jQuery("#search_input").val();

	var info = jQuery('#article_list').DataTable().page.info();
	if (typeof info != 'undefined') {
		d.page = info.page + 1;
	} else {
		d.page = 1;
	}
}