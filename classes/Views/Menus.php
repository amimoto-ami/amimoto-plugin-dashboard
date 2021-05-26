<?php
namespace AMIMOTO_Dashboard\Views;
use AMIMOTO_Dashboard\Constants;
use AMIMOTO_Dashboard\WP\Environment;
use AMIMOTO_Dashboard\WP\Plugins;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly
}

class Menus {
	private $amimoto_plugin_menu    = array(
		'c3-admin-menu',
		'nginx-champuru',
	);
	private $amimoto_plugin_submenu = array(
		'c3-admin-menu',
	);
	private $env;
	private $plugins;

	public function __construct( ...$args ) {
		if ( $args && ! empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				if ( $value instanceof Environment ) {
					$this->env = $value;
				} elseif ( $value instanceof Plugins ) {
					$this->plugins = $value;
				}
			}
		}
		if ( ! isset( $this->env ) ) {
			$this->env = new Environment();
		}
		if ( ! isset( $this->plugins ) ) {
			$this->plugins = new Plugins();
		}

		add_action( 'admin_menu', array( $this, 'define_menus' ) );
		add_action( 'admin_bar_init', array( $this, 'remove_top_menus' ) );
		add_action( 'admin_bar_init', array( $this, 'remove_submenus' ) );
	}

	public function define_menus() {
		$text_domain = Constants::text_domain();
		add_menu_page(
			__( 'Welcome to AMIMOTO Plugin Dashboard', $text_domain ),
			__( 'AMIMOTO', $text_domain ),
			'administrator',
			Constants::PANEL_ROOT,
			array( $this, 'render_html' ),
			'dashicons-admin-settings',
			3
		);
	}

	/**
	 * Remove AMIMOTO's plugin default submenu
	 *
	 * @access public
	 * @param none
	 * @since 0.0.1
	 */
	public function remove_submenus() {
		global $submenu;
		if ( ! isset( $submenu['options-general.php'] ) ) {
			return;
		}
		foreach ( (array) $submenu['options-general.php'] as $key => $array ) {
			foreach ( $this->amimoto_plugin_submenu as $plugin ) {
				if ( array_search( $plugin, $array ) ) {
					unset( $submenu['options-general.php'][ $key ] );
					break;
				}
			}
		}
	}

	/**
	 * Remove AMIMOTO's plugin default menu
	 *
	 * @access public
	 * @param none
	 * @since 0.0.1
	 */
	public function remove_top_menus() {
		global $menu;
		$menu = $this->drop_amimoto_plugin_menus( $menu );
	}

	public function drop_amimoto_plugin_menus( $menu ) {
		if ( ! isset( $menu ) ) {
			return $menu;
		}
		foreach ( (array) $menu as $key => $array ) {
			foreach ( $this->amimoto_plugin_menu as $plugin ) {
				if ( array_search( $plugin, $array ) ) {
					unset( $menu[ $key ] );
					$menu = array_values( $menu );
					break;
				}
			}
		}
		return $menu;
	}

	public function render_html() {
		$is_amimoto_managed = $this->env->is_amimoto_managed();
		echo "<div class='wrap' id='amimoto-dashboard'>";
		require_once( AMI_DASH_PATH . 'templates/Plugin_Header.php' );
		echo '<div class="amimoto-dash-main">';
		if ( $is_amimoto_managed ) {
			require_once( AMI_DASH_PATH . 'templates/WP/AMIMOTO_Managed.php' );
		} else {
			require_once( AMI_DASH_PATH . 'templates/WP/Plugins.php' );
		}
		echo '</div>';
		require_once( AMI_DASH_PATH . 'templates/Plugin_Sidebar.php' );
		echo '</div>';
	}
}
