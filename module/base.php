<?php
/**
 * Amimoto_Dash_Base Class file
 *
 * @author hideokamoto <hide.okamoto@digitalcube.jp>
 * @package Amimoto-plugin-dashboard
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Define AMMIMOTO Dashboard plugin's basic function and parameters
 *
 * @class Amimoto_Dash_Base
 * @since 0.0.1
 */
class Amimoto_Dash_Base {
	private static $instance;
	private static $text_domain;
	private static $version;

	//Panel key
	const PANEL_ROOT = 'amimoto_dash_root';

	// Action key
	const PLUGIN_SETTING = 'amimoto_setting';
	const PLUGIN_ACTIVATION = 'amimoto_activation';

	private function __construct() {
	}

	/**
	 * Get Instance Class
	 *
	 * @return Amimoto_Dash_Base
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
	 * Get Plugin version
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public static function version() {
		static $version;

		if ( ! $version ) {
			$data = get_file_data( AMI_DASH_ROOT , array( 'version' => 'Version' ) );
			$version = $data['version'];
		}
		return $version;
	}

	/**
	 * Get Plugin text_domain
	 *
	 * @return string
	 * @since 0.1.0
	 */
	public static function text_domain() {
		static $text_domain;

		if ( ! $text_domain ) {
			$data = get_file_data( AMI_DASH_ROOT , array( 'text_domain' => 'Text Domain' ) );
			$text_domain = $data['text_domain'];
		}
		return $text_domain;
	}

}
