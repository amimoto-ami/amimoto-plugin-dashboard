<?php
/**
 * Amimoto_Dash_S3 Class file
 *
 * @author hideokamoto <hide.okamoto@digitalcube.jp>
 * @package Amimoto-plugin-dashboard
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Amimoto Plugin Dashboard admin page to set-up S3
 *
 * @class Amimoto_Dash_S3
 * @since 0.0.1
 */
class Amimoto_Dash_S3 extends Amimoto_Dash_Component {
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
		$this->show_panel_html();
	}

	/**
	 *  Get admin page html content
	 *
	 * @access public
	 * @param none
	 * @return string(HTML)
	 * @since 0.0.1
	 */
	public function get_content_html() {
		$html = '';
		return $html;
	}
}
