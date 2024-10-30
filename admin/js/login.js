var CP_LOGIN = CP_LOGIN || {};

jQuery(document).on('ready', function () {
	jQuery('#submit-btn').click(function () {
		CP_LOGIN.login();
	});
});

CP_LOGIN.login = function () {
	var email = jQuery("#input-email").val();
	var pw = jQuery("#input-pw").val();
	var nonce = jQuery("#hidden_nonce").val();
	jQuery.post("https://contentparty.org/api/get_user_code", {
		"email": email,
		"pw": pw,
	}, function (data) {
		if (data.message === "success") {
			jQuery.post(ajaxurl, {
				'action': 'content_party_add_usercode',
				'contentPartyUserCode': data.result.user_code,
				'security': nonce
			}, function (data) {
				if (data === "success") {
					window.location.replace("admin.php?page=content_party_allpage_handle");
				} else {
					// double check(...)
					alert('Insert userId failed: ' + data);
				}
			}).fail(function (data) {
				alert('Insert userId failed: ' + data.responseText);
			});
		} else {
			alert("錯誤: 無此帳號或密碼不符");
		}
	}).fail(function (data) {
		alert('Api get_user_code error: ' + data.responseText);
	});
}