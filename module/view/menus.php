<?php
class Amimoto_Dash_Menus extends Amimoto_Dash_Base {
	private static $instance;
	private static $text_domain;
	private function __construct() {
		self::$text_domain = Amimoto_Dash_Base::text_domain();
	}
	private $amimoto_plugin_menu = array(
		'c3-admin-menu',
		'nginx-champuru',
	);
	private $amimoto_plugin_submenu = array(
		'nephila-clavata',
	);

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}
	public function init() {
		add_action( 'admin_menu',    array( $this, 'define_menus' ) );
		add_action( 'admin_bar_init', array( $this, 'remove_menus' ) );
	}

	public function remove_menus() {
		$this->_remove_top_menu();
		$this->_remove_submenu();
	}

	private function _remove_submenu() {
		global $submenu;
		foreach ( $submenu['options-general.php'] as $key => $array ) {
			foreach ( $this->amimoto_plugin_submenu as $plugin ) {
				if( array_search( $plugin, $array ) ) {
					unset($submenu['options-general.php'][ $key ] );
					break;
				}
			}
		}
	}

	private function _remove_top_menu() {
		global $menu;
		foreach ( $menu as $key => $array ) {
			foreach ( $this->amimoto_plugin_menu as $plugin ) {
				if( array_search( $plugin, $array ) ) {
					unset($menu[ $key ] );
					break;
				}
			}
		}
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
