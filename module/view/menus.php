<?php
class Amimoto_Dash_Menus extends Amimoto_Dash_Base {
	private static $instance;
	private static $text_domain;
	private function __construct() {
		self::$text_domain = Amimoto_Dash_Base::text_domain();
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}
	public function init() {
		add_action( 'admin_menu',    array( $this, 'define_menus' ) );
	}

	public function define_menus() {
		$Base = Amimoto_Dash_Admin::get_instance();
		add_menu_page(
			__( 'Welcome to AMIMOTO Plugin Dashboard', self::$text_domain ),
			__( 'AMIMOTO', self::$text_domain ),
			'administrator',
			self::PANEL_ROOT,
			array( $Base, 'init_panel' ),
			'dashicons-admin-settings',
			3
		);
	}

}
