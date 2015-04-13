<?php
/*
Plugin Name: Helloify Contact Form and Live Chat
Description: Adds the Helloify code to your WordPress website.
Version: 1.0.1
Author: Helloify
Author URI: http://helloify.com/
*/

if (!defined('WP_CONTENT_URL')) {
	define('WP_CONTENT_URL', get_option('siteurl') . '/wp-content');
}
if (!defined('WP_CONTENT_DIR')) {
	define('WP_CONTENT_DIR', ABSPATH . 'wp-content');
}
if (!defined('WP_PLUGIN_URL')) {
	define('WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins');
}
if (!defined('WP_PLUGIN_DIR')) {
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins');
}

function activate_helloify() {
	add_option('web_customer_id', '');
}

function deactive_helloify() {
	delete_option('web_customer_id');
}

function admin_init_helloify() {
	register_setting('helloify', 'web_customer_id');
	register_setting('helloify', 'web_customer_email');
	register_setting('helloify', 'web_customer_email_valid');
}

function admin_menu_helloify() {
	add_options_page('Helloify Chat', 'Helloify Chat', 'manage_options', 'helloify', 'options_page_helloify');
}

function options_page_helloify() {
	include(WP_PLUGIN_DIR . '/helloify-contact-form-and-live-chat/options.php');  
}

function first_install_message() {
?>
	<div class="update-nag">
		<p><?php _e( 'Helloify is not yet active, visit the <a href="options-general.php?page=helloify">settings page</a> to finish the setup.', 'helloify' ); ?></p>
	</div>
<?php
}

register_activation_hook(__FILE__, 'my_plugin_activate');
add_action('admin_init', 'my_plugin_notif');

function my_plugin_activate() {
	add_option('first_install_msg', true);
}

function my_plugin_notif() {
	if (get_option('first_install_msg', false)) {
		delete_option('first_install_msg');
		add_action('admin_notices', 'first_install_message');	
	}
}

function helloify() {
	$web_customer_id = get_option('web_customer_id');
?>
<!-- START Helloify code -->
<script src="//cdn.helloify.com/js" data-helloify-account="<?php echo $web_customer_id ?>" async></script>
<!-- END Helloify code -->
<?php
}

register_activation_hook(__FILE__, 'activate_helloify');
register_deactivation_hook(__FILE__, 'deactive_helloify');

if (is_admin()) {
	add_action('admin_init', 'admin_init_helloify');
	add_action('admin_menu', 'admin_menu_helloify');
}

if (!is_admin()) {
	add_action('wp_head', 'helloify');
}

?>
