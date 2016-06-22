<?php
class Amimoto_Dash_Base {
	private static $instance;
	private static $text_domain;
	private static $version;

	//Panel key
	const PANEL_ROOT = 'amimoto_dash_root';

	private function __construct() {
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}

	public static function version() {
		static $version;

		if ( ! $version ) {
			$data = get_file_data( AMI_DASH_ROOT , array( 'version' => 'Version' ) );
			$version = $data['version'];
		}
		return $version;
	}

	public static function text_domain() {
		static $text_domain;

		if ( ! $text_domain ) {
			$data = get_file_data( AMI_DASH_ROOT , array( 'text_domain' => 'Text Domain' ) );
			$text_domain = $data['text_domain'];
		}
		return $text_domain;
	}

}
