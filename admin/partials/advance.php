<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-tw">
<head>
    <title>Login</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

</head>
<body>
<div class="contentparty-body" id="cp-advance-box">
    <input type="hidden" id="hidden_nonce"
           value="<?php echo wp_create_nonce("cp-nonce"); ?>"/>
    <input type="hidden" id="hidden_dataid"
           value="<?php echo htmlspecialchars($_GET["data_id"]); ?>"/>
    <header class="l-header">
        <h1>進階設定</h1>
    </header>
    <div class="l-content">
        <div class="m-advance">
            <div class="m-advance-left">
                <div class="m-section">
                    <label>標題</label>
                    <div class="m-input">
                        <input id='input-title' type="text" value="">
                    </div>
                    <span class="b-info">可編輯</span>
                </div>

                <div class="m-section">
                    <label>建議標籤</label>
                    <div class="m-tag-import-area">
                        <div class="m-tag-import-box">
                            <div class="m-tag-labels"></div>
                        </div>
                    </div>
                    <span class="b-info">點擊選用</span>
                </div>

                <div class="m-section">
                    <label>文章內容</label>
                    <div class="m-input is-readonly" id='content-area'>
                    </div>
                    <span class="b-info">原文 JavaScript 預讀顯示</span>
                </div>

                <div class="m-section">
                    <label>內容摘要</label>
                    <div class="m-input">
                        <textarea id='excerpt-area'></textarea>
                    </div>
                    <span class="b-info">可編輯</span>
                </div>
            </div>

            <div class="m-advance-right">
                <div class="m-category">
                    <h3>分類</h3>
                    <div class="m-select" id="category-m-select">
                        <select>

                        </select>
                    </div>
                </div>

                <div class="m-tag-area">
                    <h3>標籤</h3>
                    <div class="m-tag-box">
                        <div class="m-tag-button"></div>
                        <div class="m-tag-labels"></div>
                        <input class="m-tag-add" type="text" placeholder="add a tag"
                               value="">
                    </div>
                </div>

                <div class="m-thumb-box">
                    <h3>特色首圖</h3>
                    <div class="m-thumb" id="article-img-container">
                    </div>
                </div>

                <div class="m-date-box">
                    <h3>時間排程</h3>
                    <div class="m-input">
                        <input id='datetimepicker' type="text"> <a
                            id='DatetimepickerIcon' class="m-button"> <i
                                class="m-icon is-calendar"></i>
                        </a>
                    </div>
                </div>

                <div class="m-import">
                    <a id="draft-button" class="m-button"> <i class="m-icon is-upload"></i>
                        匯入草稿
                    </a>
                </div>
            </div>

            <div class="m-advance-bottom">
                <div class="m-submit">
                    <div id="preview-button" class="m-button">預覽</div>
                    <div id="publish-button" class="m-button">發表</div>
                </div>
                <div class="m-cancel">
                    <div id="cancel-button" class="m-button">取消</div>
                </div>
            </div>
        </div>
    </div>

    <footer class="l-footer"></footer>
</div>
</body>


</html>
