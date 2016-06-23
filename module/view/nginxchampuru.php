<?php
/**
 * Amimoto_Dash_Ncc Class file
 *
 * @author hideokamoto <hide.okamoto@digitalcube.jp>
 * @package Amimoto-plugin-dashboard
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Amimoto Plugin Dashboard admin page to set-up Nginx
 *
 * @class Amimoto_Dash_Ncc
 * @since 0.0.1
 */
class Amimoto_Dash_Ncc extends Amimoto_Dash_Component {
	private static $instance;
	private static $text_domain;
	private function __construct() {
		self::$text_domain = Amimoto_Dash_Base::text_domain();
	}

	/**
	 * Get Instance Class
	 *
	 * @return Amimoto_Dash_Cloudfront
	 * @since 0.0.1
	 * @access public
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}

	/**
	 *  Show admin page html
	 *
	 * @access public
	 * @param none
	 * @return none
	 * @since 0.0.1
	 */
	public function init_panel() {
		$plugin_file_path = path_join( ABSPATH , 'wp-content/plugins/nginx-champuru/includes/admin.class.php' );
		if ( ! file_exists( $plugin_file_path ) ) {
			$plugin_file_path = path_join( ABSPATH , 'wp-content/plugins/nginx-cache-controller/includes/admin.class.php' );
		}
		require_once( $plugin_file_path );
		$nginxchampuru_admin = NginxChampuru_Admin::get_instance();
		$nginxchampuru_admin->admin_panel();
	}

}
