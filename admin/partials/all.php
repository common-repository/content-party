<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-tw">
<head>
<title>Login</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
	<div class="contentparty-body">
		<input type="hidden" id="hidden_nonce"
			value="<?php echo wp_create_nonce( "cp-nonce" );?>" />
		<div class="m-help">
			<div class="m-ins is-list-1">
				<div class="m-sidenav">
					<ul>
						<li class="is-list-1"><a href="#" data-toggle="is-list-1"
							data-toggle-remove="is-list-1 is-list-2 is-list-3 is-list-4 is-list-5"
							data-toggle-target=".m-help .m-ins">總覽</a></li>
						<li class="is-list-2"><a href="#" data-toggle="is-list-2"
							data-toggle-remove="is-list-1 is-list-2 is-list-3 is-list-4 is-list-5"
							data-toggle-target=".m-help .m-ins">話題</a></li>
						<li class="is-list-3"><a href="#" data-toggle="is-list-3"
							data-toggle-remove="is-list-1 is-list-2 is-list-3 is-list-4 is-list-5"
							data-toggle-target=".m-help .m-ins">媒體來源</a></li>
						<li class="is-list-4"><a href="#" data-toggle="is-list-4"
							data-toggle-remove="is-list-1 is-list-2 is-list-3 is-list-4 is-list-5"
							data-toggle-target=".m-help .m-ins">取用文章</a></li>
						<li class="is-list-5"><a href="#" data-toggle="is-list-5"
							data-toggle-remove="is-list-1 is-list-2 is-list-3 is-list-4 is-list-5"
							data-toggle-target=".m-help .m-ins">關於我們</a></li>
					</ul>
				</div>
				<div class="m-block is-block-1">
					<p>在總覽裡，有幾種功能可以幫助你找到想要的文章，包含：</p>
					<ul>
						<li>最新：文章列表按照時間新至舊排序</li>
						<li>熱門：文章列表按照前 3 天瀏覽量高至低排序</li>
						<li>搜尋：輸入想搜尋的內容，系統會自動核對標題、作者、媒體來源、內文，幫你找出相關文章。</li>
					</ul>
				</div>
				<div class="m-block is-block-2">
					<p>在話題裡，羅列 Content Party 前 3 天的「二十大熱門瀏覽」關鍵字排行，幫助你更快找到熱門內容。</p>
				</div>
				<div class="m-block is-block-3">
					<p>在媒體來源中，整理出熱門與新進兩大媒體來源，</p>
					<ul>
						<li>熱門點閱：為 Content Party 前 30 天內擁有高瀏覽量的熱門媒體。</li>
						<li>新進媒體：為 Content Party 前 7 天新加入的供稿媒體夥伴。</li>
					</ul>
				</div>
				<div class="m-block is-block-4">
					<p>在外掛裡取用文章時，你可以針對單篇文章進行設定，也可以批次匯至草稿。</p>
					<ul>
						<li>單篇文章設定：
							<div>
								<i class='m-icon is-search'></i>預覽原文
							</div>
							<div>
								<i class='m-icon is-link'></i>觀看原文網址
							</div>
							<div>
								<i class='m-icon is-gear'></i>進階設定
							</div>
							<div>
								<i class='m-icon is-floppy'></i>單篇直接存入草稿
							</div>
						</li>
						<li>批次匯至草稿：勾選多篇文章，點按即批次匯至你的 WordPress 草稿。</li>
					</ul>
				</div>
				<div class="m-block is-block-5">
					<p>Content Party
						是一個媒合作者與媒體的內容交換服務，我們志在將優質的好內容，合法的取得並提供給媒體使用，簡單且容易的使用。</p>
					<p>這個外掛也在這樣的使命下誕生，希望能讓使用 WordPress 的媒體們，能享受到更便利、更快速的取用服務。</p>
				</div>
			</div>
			<div class="m-topnav">
				<a href="#" data-toggle data-toggle-target=".m-help .m-ins">使用說明</a>
				<a href="admin.php?page=content_party_logout_handle">登出</a>
			</div>
		</div>

		<header class="l-header">
			<h1>Content Party</h1>
			<div class="m-navbar">
				<ul>
					<li class="is-curr"><a
						href="admin.php?page=content_party_allpage_handle">總覽</a></li>
					<li><a href="admin.php?page=content_party_topicpage_handle">話題</a></li>
					<li><a href="admin.php?page=content_party_mediapage_handle">媒體來源</a></li>
				</ul>
			</div>
		</header>

		<hr class="m-hr">

		<div class="l-content">
			<div class="m-searchbar">
				<a class="m-button is-black" href="#"><i
					class="m-icon is-search-white"></i></a>
				<div class="m-input">
					<input id='search_input' type="text"
						placeholder="標題、關鍵字、媒體來源、作者..." value="">
				</div>
				<input id='search_button' class="m-button is-black" type="button"
					value="Search">
			</div>

			<div class="m-tab">
				<ul>
					<li id="sort_latest" class="is-curr"><a href="#">最新</a></li>
					<li id="sort_hot"><a href="#">熱門</a></li>
				</ul>
			</div>

			<div class="m-table">
				<table id="article_list">
				</table>
				<input type="button" id="batch_button"
					class="m-button is-black is-batch" value="批次存入草稿">
			</div>
		</div>
		<footer class="l-footer"> </footer>
	</div>
</body>
</html>
