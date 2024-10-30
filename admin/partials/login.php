<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-tw">
<head>
<title>Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>
	<div class="contentparty-body">
		<input type="hidden" id="hidden_nonce"
			value="<?php echo wp_create_nonce( "cp-nonce" );?>"/>
		<header class="l-header">
			<h1>Content Party</h1>
		</header>

		<div class="l-content">
			<div class="m-login">
				<div class="m-form">
					<h2>登入 Content Party</h2>
					<form id='login-form' method="POST" action="#">
						<div class="m-input">
							<input id='input-email' type="text" required name="email"
								placeholder="E-mail" value="">
						</div>
						<div class="m-input">
							<input id='input-pw' type="password" required name="pw"
								placeholder="Password" value="">
						</div>

						<div class="m-login-submit">
							<input id='submit-btn' class="m-button is-water" type="button"
								value="登入">
						</div>
						<div class="m-login-forget">
							<a target="_blank"
								href="https://contentparty.org/forget_process/forget_pw">忘記密碼</a>
						</div>
					</form>
					還沒有帳號嗎？ <a target="_blank"
						href="https://contentparty.org/user_reg/register">註冊新帳號</a>
				</div>
				<div class="m-copyright">
					<ul>
						<li><a target="_blank" href="https://contentparty.org/go/aboutus/">關於</a></li>
						<li><a target="_blank" href="https://contentparty.org/go/faq/">隱私條款</a></li>
						<li><a target="_blank" href="mailto:service@contentparty.org">聯絡我們</a></li>
					</ul>
					<p>&copy;2015 ContentParty</p>
					<p>
						<i class="m-icon is-mail"></i> Mail: <a
							href="mailto:service@contentparty.org">service@contentparty.org</a>
					</p>
					<p>
						<i class="m-icon is-tel"></i> +886-2-2365-2002#31 癮科技 張小姐
					</p>
				</div>
			</div>
		</div>

		<footer class="l-footer"> </footer>
	</div>
</body>
</html>
