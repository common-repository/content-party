var CP_TOPIC = CP_TOPIC || {};

jQuery(document).on('ready', function () {
	CP.getTokenFromSite(function (token) {
		CP_TOPIC.getTopics();
	});
});

CP_TOPIC.getTopics = function () {
	jQuery.ajax({
		type: "post",
		url: 'https://contentparty.org/api/get_rank_cache',
		dataType: "json",
		data: {
			"type": 'top_tag',
			"limit": 20,
		},
		success: function (data) {
			CP_TOPIC.addTopicElements(data);
		},
		error: function (request, status, error) {
			alert("getTopics error!");
		},
	});
}

CP_TOPIC.addTopicElements = function (topics) {
	jQuery("#list1").empty();
	jQuery("#list2").empty();

	for (i = 0; i < topics.length; i++) {
		var topic = topics[i].category;
		if (i < 10) {
			jQuery("#list1").append("<li><span>#" + (i + 1) + "</span><a href='admin.php?page=content_party_topicadvpage_handle&category=" + topic + "' class='m-label topiclink'>" + topic + "</a></li>");
		} else {
			jQuery("#list2").append("<li><span>#" + (i + 1) + "</span><a href='admin.php?page=content_party_topicadvpage_handle&category=" + topic + "' class='m-label topiclink'>" + topic + "</a></li>");
		}
	}
}
