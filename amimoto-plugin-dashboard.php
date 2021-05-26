<?php
/**
 * Plugin Name: AMIMOTO Plugin Dashboard
 * Version: 1.0.0
 * Description: Control AMIMOTO helper plugins
 * Author: hideokamoto,amimotoami
 * Author URI: https://amimoto-ami.com
 * Plugin URI: https://github.com/amimoto-ami/amimoto-plugin-dashboard
 * Text Domain: amimoto-dashboard
 * Domain Path: /languages
 *
 * @package Amimoto-dashboard
 */

if ( ! is_admin() ) {
	return;
}
define( 'AMI_DASH_PATH', plugin_dir_path( __FILE__ ) );
define( 'AMI_DASH_URL', plugin_dir_url( __FILE__ ) );
define( 'AMI_DASH_ROOT', __FILE__ );

require_once( __DIR__ . '/classes/Class_Loader.php' );
new AMIMOTO_Dashboard\Class_Loader( dirname( __FILE__ ) . '/classes' );
new AMIMOTO_Dashboard\Class_Loader( dirname( __FILE__ ) . '/classes/WP' );
new AMIMOTO_Dashboard\Class_Loader( dirname( __FILE__ ) . '/classes/Views' );

function amimoto_init() {
	new AMIMOTO_Dashboard\Views();
	new AMIMOTO_Dashboard\C3_Service();
	new AMIMOTO_Dashboard\NCC_Service();
	new AMIMOTO_Dashboard\WP\Mail_Fixtures();
}
add_action( 'plugins_loaded', 'amimoto_init' );

register_activation_hook( __FILE__, 'amimoto_initializing_for_managed' );
function amimoto_initializing_for_managed() {
	$env = new \AMIMOTO_Dashboard\WP\Environment();
	if ( ! $env->is_amimoto_managed() ) {
		return;
	}
	$ncc    = new AMIMOTO_Dashboard\NCC_Service();
	$result = $ncc->update_ncc_plugin_settings();
	if ( is_wp_error( $result ) ) {
		error_log( print_r( $result, true ) );
	}
}
