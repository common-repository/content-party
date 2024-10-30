var CP_ALL = CP_ALL || {};

jQuery(document).on('ready', function () {
	CP.getTokenFromSite(function (token) {
		CP.initArticleList(token, CP_ALL.dataTablePrepare);
	});
});

CP_ALL.dataTablePrepare = function (token, d) {
	d.token = token;
	d.type = 'all';
	d.limit = 10;

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