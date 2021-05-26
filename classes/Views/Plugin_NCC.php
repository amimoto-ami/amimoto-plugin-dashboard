<?php
namespace AMIMOTO_Dashboard\Views;
use AMIMOTO_Dashboard\Constants;
use AMIMOTO_Dashboard\WP\Environment;
use AMIMOTO_Dashboard\WP\Plugins;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly
}

class Plugin_NCC {
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
		add_action( 'admin_menu', array( $this, 'add_ncc_menu' ) );
	}

	public function add_ncc_menu() {
		$is_amimoto_managed = $this->env->is_amimoto_managed();
		$text_domain        = Constants::text_domain();

		if ( $is_amimoto_managed ) {
			return;
		}

		if ( ! $this->plugins->is_activated_ncc() ) {
			return;
		}

		$plugin_file_path = path_join( ABSPATH, 'wp-content/plugins/nginx-champuru/includes/admin.class.php' );
		if ( ! file_exists( $plugin_file_path ) ) {
			$plugin_file_path = path_join( ABSPATH, 'wp-content/plugins/nginx-cache-controller/includes/admin.class.php' );
		}
		if ( ! file_exists( $plugin_file_path ) ) {
			return;
		}
		require_once( $plugin_file_path );
		$nginxchampuru_admin = \NginxChampuru_Admin::get_instance();
		add_submenu_page(
			Constants::PANEL_ROOT,
			__( 'Nginx Cache Controller', $text_domain ),
			__( 'Nginx Reverse Proxy', $text_domain ),
			'administrator',
			Constants::PANEL_NCC,
			array( $nginxchampuru_admin, 'admin_panel' )
		);
	}
}
