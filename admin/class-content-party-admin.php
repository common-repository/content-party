<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package Plugin_Name
 * @subpackage Plugin_Name/admin
 * @author Your Name <email@example.com>
 */
class Content_Party_Admin
{
    private $main_page_handle;
    private $login_page_hook;
    private $all_page_hook;
    private $import_page_hook;
    private $media_page_hook;
    private $topic_page_hook;
    private $topic_advance_page_hook;

    /**
     * The ID of this plugin.
     *
     * @since 1.0.0
     * @access private
     * @var string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since 1.0.0
     * @access private
     * @var string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since 1.0.0
     *
     * @param string $plugin_name
     *            The name of this plugin.
     * @param string $version
     *            The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * add all pages for redirect(notice that some pages have null menu slug)
     */
    public function add_menu_pages()
    {
        $this->login_page_hook = add_menu_page('Content Party for WordPress', 'Content Party', 'publish_posts', 'content_party_menu_handle', array(
            $this,
            'show_login_page'
        ), plugin_dir_url(__FILE__) . 'img/CP50-50small.png', 90.5);

        $this->all_page_hook = add_submenu_page(null, 'All Page', '總覽', 'publish_posts', 'content_party_allpage_handle', array(
            $this,
            'show_all_page'
        ));

        $this->topic_page_hook = add_submenu_page(null, 'Topic Page', '話題', 'publish_posts', 'content_party_topicpage_handle', array(
            $this,
            'show_topic_page'
        ));

        $this->media_page_hook = add_submenu_page(null, 'Media Page', '媒體來源', 'publish_posts', 'content_party_mediapage_handle', array(
            $this,
            'show_media_page'
        ));

        $this->topic_advance_page_hook = add_submenu_page(null, 'Topic Advance Page', 'Topic Advance Page', 'publish_posts', 'content_party_topicadvpage_handle', array(
            $this,
            'show_topic_advance_page'
        ));

        $this->import_page_hook = add_submenu_page(null, 'Import Page', 'Import Page', 'publish_posts', 'content_party_importpage_handle', array(
            $this,
            'show_import_page'
        ));

        add_submenu_page(null, null, null, 'publish_posts', 'content_party_logout_handle', array(
            $this,
            'show_logout_page'
        ));
    }

    public function show_logout_page()
    {
        global $wpdb;
        $wpdb->query("TRUNCATE TABLE `content_party_token`");
        echo '<script type="text/javascript"> window.location = "admin.php?page=content_party_menu_handle" </script>';
    }

    public function show_media_page()
    {
        include("partials/media.php");
    }

    public function show_topic_advance_page()
    {
        include("partials/topic-adv.php");
    }

    public function show_topic_page()
    {
        include("partials/topic.php");
    }

    public function show_import_page()
    {
        include("partials/advance.php");
        //$content = file_get_contents(plugin_dir_url( __FILE__ ).'partials/advance.php');
        //echo $content;
    }

    public function show_login_page()
    {
        // check if logged in
        global $wpdb;
        $sql = "SELECT `user_key` FROM `content_party_token` limit 1";
        $userKey = $wpdb->get_var($sql, 0, 0);

        if ($userKey != '') {
            echo '<script type="text/javascript"> window.location = "admin.php?page=content_party_allpage_handle" </script>';
        } else {
            include("partials/login.php");
        }
    }

    public function show_all_page()
    {
        include("partials/all.php");
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since 1.0.0
     */
    public function enqueue_styles($hook)
    {
        wp_register_style('content_party_login_style', plugin_dir_url(__FILE__) . 'css/login.css', array(), null, 'all');
        wp_register_style('content_party_reset_style', plugin_dir_url(__FILE__) . 'css/reset.css', array(), null, 'all');
        wp_register_style('content_party_plugin_style', plugin_dir_url(__FILE__) . 'css/style.css', array(), null, 'all');
        wp_register_style('content_party_advance_style', plugin_dir_url(__FILE__) . 'css/advance.css', array(), null, 'all');
        wp_register_style('content_party_datatable_style', 'https://cdn.datatables.net/1.10.10/css/jquery.dataTables.css', array(), null, 'all');
        wp_register_style('content_party_fancybox_style', plugin_dir_url(__FILE__) . 'css/fancybox/jquery.fancybox-1.3.4.css', array(), null, 'all');
        wp_register_style('content_party_datetimepicker_style', plugin_dir_url(__FILE__) . 'css/jquery.datetimepicker.css', array(), null, 'all');
        wp_register_style('content_party_mask_style', plugin_dir_url(__FILE__) . 'css/jquery.mask.min.css', array(), null, 'all');

        // add inline styles for icon url
        $login_custom_css = ".m-icon {background-image:url('" . plugin_dir_url(__FILE__) . "img/login-icons.png?1443357576');}";
        wp_add_inline_style('content_party_login_style', $login_custom_css);
        $style_custom_css = ".m-icon {	position:relative;
				background:url('" . plugin_dir_url(__FILE__) . "img/icons.png?1443354273') no-repeat;
				background-size:auto 100%;
				display:inline-block;
				vertical-align:middle;
				width:1em;
				height:1em;}";
        wp_add_inline_style('content_party_plugin_style', $style_custom_css);

        switch ($hook) {
            case $this->login_page_hook :
                wp_enqueue_style('content_party_reset_style');
                wp_enqueue_style('content_party_plugin_style');
                wp_enqueue_style('content_party_login_style');
                break;
            case $this->media_page_hook :
            case $this->all_page_hook :
            case $this->topic_page_hook :
            case $this->topic_advance_page_hook :
                wp_enqueue_style('content_party_reset_style');
                wp_enqueue_style('content_party_datatable_style');
                wp_enqueue_style('content_party_plugin_style');
                wp_enqueue_style('content_party_fancybox_style');
                wp_enqueue_style('content_party_mask_style');
                break;
            case $this->import_page_hook :
                wp_enqueue_style('content_party_reset_style');
                wp_enqueue_style('content_party_advance_style');
                wp_enqueue_style('content_party_datetimepicker_style');
                break;
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since 1.0.0
     */
    public function enqueue_scripts($hook)
    {
        wp_register_script("content_party_datatable_js", plugin_dir_url(__FILE__) . 'js/jquery.dataTables.min.js', array(
            'jquery'
        ), null, false);
        wp_register_script("content_party_datatable_input_js", plugin_dir_url(__FILE__) . 'js/input.js', array(
            'jquery',
            'content_party_datatable_js'
        ), null, false);
        wp_register_script("content_party_plugin_js", plugin_dir_url(__FILE__) . 'js/plugin.js', array(
            'jquery',
            'content_party_datatable_js',
            'content_party_datatable_input_js',
            'content_party_fancybox_js'
        ), $this->version, false);
        wp_register_script("content_party_login_js", plugin_dir_url(__FILE__) . 'js/login.js', array(
            'jquery'
        ), $this->version, false);
        wp_register_script("content_party_all_js", plugin_dir_url(__FILE__) . 'js/all.js', array(
            'jquery',
            'content_party_plugin_js'
        ), $this->version, false);
        wp_register_script("content_party_topic_js", plugin_dir_url(__FILE__) . 'js/topic.js', array(
            'jquery',
            'content_party_plugin_js'
        ), $this->version, false);
        wp_register_script("content_party_topic_adv_js", plugin_dir_url(__FILE__) . 'js/topic-advanced.js', array(
            'jquery',
            'content_party_plugin_js'
        ), $this->version, false);
        wp_register_script("content_party_media_js", plugin_dir_url(__FILE__) . 'js/media.js', array(
            'jquery',
            'content_party_plugin_js'
        ), $this->version, false);
        wp_register_script("content_party_mask_js", plugin_dir_url(__FILE__) . 'js/jquery.mask.js', array(
            'jquery'
        ), null, false);
        wp_register_script("content_party_fancybox_js", plugin_dir_url(__FILE__) . 'js/fancybox/jquery.fancybox-1.3.4.pack.js', array(
            'jquery'
        ), null, false);
        wp_register_script("content_party_datepicker_js", plugin_dir_url(__FILE__) . 'js/jquery.datetimepicker.full.min.js', array(
            'jquery'
        ), null, false);
        wp_register_script("content_party_advance_js", plugin_dir_url(__FILE__) . 'js/advance.js', array(
            'jquery',
            'content_party_datepicker_js'
        ), $this->version, false);

        wp_enqueue_script("jquery");

        switch ($hook) {
            case $this->login_page_hook :
                wp_enqueue_script("content_party_login_js");
                break;
            case $this->all_page_hook :
                wp_enqueue_script("content_party_fancybox_js");
                wp_enqueue_script("content_party_plugin_js");
                wp_enqueue_script("content_party_all_js");
                wp_enqueue_script("content_party_mask_js");
                break;
            case $this->topic_page_hook :
                wp_enqueue_script("content_party_plugin_js");
                wp_enqueue_script("content_party_topic_js");
                break;
            case $this->topic_advance_page_hook :
                wp_enqueue_script("content_party_fancybox_js");
                wp_enqueue_script("content_party_plugin_js");
                wp_enqueue_script("content_party_topic_adv_js");
                wp_enqueue_script("content_party_mask_js");
                break;
            case $this->media_page_hook :
                wp_enqueue_script("content_party_fancybox_js");
                wp_enqueue_script("content_party_plugin_js");
                wp_enqueue_script("content_party_media_js");
                wp_enqueue_script("content_party_mask_js");
                break;
            case $this->import_page_hook :
                wp_enqueue_script("content_party_plugin_js");
                wp_enqueue_script("content_party_fancybox_js");
                wp_enqueue_script("content_party_datepicker_js");
                wp_enqueue_script("content_party_advance_js");
                break;
        }
    }

    /**
     * import draft from content party to wp
     *
     * @throws Exception
     */
    public function addDraft()
    {
        $check = check_ajax_referer('cp-nonce', 'security', false);
        if ($check == false) {
            wp_die("nonce check failed.", '', array('response' => 400));
        }

        $data = $_POST ["data"];
        if (isset ($data) == false) {
            wp_die('empty data', '', array('response' => 400));
        }
        if (isset ($data ['content']) == false) {
            wp_die('empty content', '', array('response' => 400));
        }
        if (isset ($data ['title']) == false) {
            wp_die('empty title', '', array('response' => 400));
        }
        if (isset ($data ['postStatus']) == false) {
            $data ['postStatus'] = 'draft';
        }

        $post = array(
            'post_content' => balanceTags($data ['content']),
            'post_title' => sanitize_text_field($data ['title']),
            'post_status' => sanitize_text_field($data ['postStatus']),
            'post_type' => 'post',
            'post_excerpt' => isset ($data ['excerpt']) ? sanitize_text_field($data ['excerpt']) : '',
            'post_date' => isset ($data ['postDate']) ? sanitize_text_field($data ['postDate']) : '',
            'post_category' => array_map('absint', $data ['categories']),
            'tags_input' => array_map('sanitize_text_field', $data ['tags'])
        );

        // insert post
        $postId = wp_insert_post($post, true);
        if (is_int($postId) == false) {
            // error occurred
            //http_response_code(500);
            var_dump($postId->get_error_codes());
            foreach ($postId->get_error_codes() as $errCode) {
                var_dump($postId->get_error_messages($errCode));
            }
            wp_die('', '', array('response' => 500));
        }

        // check if thumbnail exist
        if (isset ($data ['thumbnail_url']) == false) {
            wp_die('empty thumbnail URL.', '', array('response' => 400));
        }
        try {
            // generateFeaturedImage:
            // http://wordpress.stackexchange.com/questions/40301/how-do-i-set-a-featured-image-thumbnail-by-image-url-when-using-wp-insert-post
            $image_url = esc_url($data ['thumbnail_url']);

            $upload_dir = wp_upload_dir();
            $image_data = file_get_contents($image_url);
            $filename = basename($image_url);
            if (wp_mkdir_p($upload_dir ['path'])) {
                $file = $upload_dir ['path'] . '/' . $filename;
            } else {
                $file = $upload_dir ['basedir'] . '/' . $filename;
            }
            file_put_contents($file, $image_data);

            $wp_filetype = wp_check_filetype($filename, null);
            $attachment = array(
                'post_mime_type' => $wp_filetype ['type'],
                'post_title' => sanitize_file_name($filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attachId = wp_insert_attachment($attachment, $file, $postId);
            if ($attachId == 0) {
                throw new Exception ("wp_insert_attachment error.");
            }
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            $attach_data = wp_generate_attachment_metadata($attachId, $file);
            $res1 = wp_update_attachment_metadata($attachId, $attach_data);
            if ($res1 == false) {
                throw new Exception ("wp_update_attachment_metadata error.");
            }
            $res2 = set_post_thumbnail($postId, $attachId);
            if ($res2 == false) {
                echo '$post_id: ' . $postId;
                echo '$attach_id: ' . $attachId;
                throw new Exception ("set_post_thumbnail error: " . $res2);
            }
        } catch (Exception $e) {
            //http_response_code(500);
            //echo 'Caught exception: ' . $e->getMessage() . "\n";
            wp_die('Caught exception: ' . $e->getMessage(), '', array('response' => 500));
        }

        //http_response_code(200);
        //echo esc_textarea($postId);
        wp_die(esc_textarea($postId), '', array('response' => 200));
    }

    /**
     * get usercode in wpdb
     */
    public function getUserCode()
    {

        $check = check_ajax_referer('cp-nonce', 'security', false);
        if ($check == false) {
            wp_die("nonce check failed.", '', array('response' => 400));
        }

        global $wpdb;
        $wpdb->suppress_errors();
        $countSql = 'SELECT count(*) FROM `content_party_token`';
        $codeCount = $wpdb->get_var($countSql, 0, 0);

        if ($codeCount == null) {
            wp_die("codeCount=null:" . $wpdb->last_query . $wpdb->last_error, '', array('response' => 500));
        } else if ($codeCount == 0) {
            //logged out
            wp_die("logged out", '', array('response' => 400));
        }

        $sql = "SELECT `user_key` FROM `content_party_token` limit 1";
        $userCode = $wpdb->get_var($sql, 0, 0);
        if ($userCode == null) {
            wp_die("userCode=null:" . $wpdb->last_query . $wpdb->last_error, '', array('response' => 500));
        } else if ($userCode != '') {
            //echo esc_textarea($userCode);
            wp_die(esc_textarea($userCode), '', array('response' => 200));
        }
    }

    /**
     * insert usercode into wpdb
     */
    public function addUserCode()
    {
        $check = check_ajax_referer('cp-nonce', 'security', false);
        if ($check == false) {
            wp_die("nonce check failed.", '', array('response' => 400));
        }

        global $wpdb;
        $wpdb->suppress_errors();
        $userCode = sanitize_text_field($_POST ['contentPartyUserCode']);
        if ($userCode == "") {
            wp_die("empty userKey", '', array('response' => 400));
        }

        $result = $wpdb->insert("content_party_token", array(
            'user_key' => $userCode
        ), array(
            '%s'
        ));

        if ($result == false) {
            //http_response_code(500);
            wp_die($wpdb->last_error, '', array('response' => 500));
        } else {
            //echo "success";
            wp_die("success", '', array('response' => 200));
        }
    }

    /**
     * get exist wp categories
     */
    public function getCategories()
    {
        $check = check_ajax_referer('cp-nonce', 'security', false);
        if ($check == false) {
            //http_response_code(400);
            wp_die("nonce check failed.", '', array('response' => 400));
        }

        $args = array(
            'type' => 'post',
            'child_of' => 0,
            'parent' => '',
            'orderby' => 'count',
            'order' => 'desc',
            'hide_empty' => 0,
            'hierarchical' => 1,
            'exclude' => '',
            'include' => '',
            'number' => '5',
            'taxonomy' => 'category',
            'pad_counts' => false
        );
        wp_send_json(get_categories($args));
        //wp_die('', '', array('response' => 200));
    }

    /**
     * get wp tags
     */
    public function getTags()
    {
        $check = check_ajax_referer('cp-nonce', 'security', false);
        if ($check == false) {
            wp_die("nonce check failed.", '', array('response' => 400));
        }

        $args = array(
            'orderby' => 'count',
            'order' => 'desc',
            'hide_empty' => 0
        );

        // fetch tag names only
        $tags = get_tags($args);
        $tagnames = array();
        foreach ($tags as $tag) {
            $tagnames [] = $tag->name;
        }

        wp_send_json($tagnames);
        //wp_die('', '', array('response' => 200));
    }

    /**
     * get wp preview url by post_id
     */
    public function getPreviewUrl()
    {
        $check = check_ajax_referer('cp-nonce', 'security', false);
        if ($check == false) {
            wp_die("nonce check failed.", '', array('response' => 400));
        }

        $postId = $_POST ['post_id'];
        if (absint($postId)) {
            echo esc_url(site_url() . '/?p=' . $postId . '&preview=true');
            wp_die('', '', array('response' => 200));
        } else {
            wp_die('invalid post_id', '', array('response' => 400));
        }
    }
}
