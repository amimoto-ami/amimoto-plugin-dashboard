<?php
/**
 * Plugin Name: Amimoto-plugin-dashboard
 * Version: 0.1-alpha
 * Description: PLUGIN DESCRIPTION HERE
 * Author: YOUR NAME HERE
 * Author URI: YOUR SITE HERE
 * Plugin URI: PLUGIN SITE HERE
 * Text Domain: amimoto-plugin-dashboard
 * Domain Path: /languages
 * @package Amimoto-plugin-dashboard
 */

require_once( 'module/includes.php' );
 define( 'AMI_DASH_PATH', plugin_dir_path( __FILE__ ) );
 define( 'AMI_DASH_URL', plugin_dir_url( __FILE__ ) );
 define( 'AMI_DASH_ROOT', __FILE__ );

 $Amimoto_Dash = Amimoto_Dash::get_instance();
 $Amimoto_Dash->init();

class Amimoto_Dash {
	private $Base;
	private static $instance;
	private static $text_domain;

	private function __construct() {
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}

	public function init() {
		$this->Base = Amimoto_Dash_Base::get_instance();
		$Menu = Amimoto_Dash_Menus::get_instance();
		$Menu->init();
	}
}
