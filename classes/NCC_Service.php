<?php
namespace AMIMOTO_Dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly
}
use AMIMOTO_Dashboard\WP\Plugins;
use AMIMOTO_Dashboard\Constants;
use AMIMOTO_Dashboard\WP\Environment;
use AMIMOTO_Dashboard\WP\Admin_Notice;

class NCC_Service {
	private $env;
	private $plugin;
	private $notice;
	public function __construct( ...$args ) {
		$this->plugin = new Plugins();
		$this->notice = new Admin_Notice();
        $this->env = new Environment();
		if ( $args && ! empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				if ( $value instanceof Environment ) {
					$this->env = $value;
				} elseif ( $value instanceof Plugins ) {
					$this->plugin = $value;
				} elseif ( $value instanceof Admin_Notice ) {
					$this->notice = $value;
				}
			}
		}
		add_action( 'admin_init', array( $this, 'update_ncc_settings' ) );
		add_action( 'admin_init', array( $this, 'control_plugin' ) );
	}

	public function update_ncc_settings() {
		if ( empty( $_POST ) ) {
			return;
		}
		$result = null;
		$key    = Constants::CLOUDFRONT_UPDATE_NCC;
		if ( ! isset( $_POST[ $key ] ) || ! $_POST[ $key ] ) {
			return;
		}

		if ( ! check_admin_referer( $key, $key ) ) {
			return;
		}
		try {
			$this->update_ncc_cache_expires();
			if ( $this->env->is_amimoto_managed() ) {
				$this->update_ncc_cache_settings();
			}
			$this->notice->show_admin_success( 'Update Nginx Settings', 'Success' );
		} catch ( \Exception $e ) {
			$this->notice->show_admin_error( new \WP_Error( 'AMIMOTO Dashboard Error', $e->getMessage() ) );
		}
	}

	public function control_plugin() {
		if ( empty( $_POST ) ) {
			return;
		}
		$result;
		if ( isset( $_POST[ Constants::PLUGIN_ACTIVATION ] ) && $_POST[ Constants::PLUGIN_ACTIVATION ] ) {
			if ( check_admin_referer( Constants::PLUGIN_ACTIVATION, Constants::PLUGIN_ACTIVATION ) ) {
				if ( isset( $_POST['plugin_type'] ) && 'nginxchampuru' === $_POST['plugin_type'] ) {
					$result = $this->activate_ncc_plugin();
				}
			}
		}
		if ( isset( $_POST[ Constants::PLUGIN_DEACTIVATION ] ) && $_POST[ Constants::PLUGIN_DEACTIVATION ] ) {
			if ( check_admin_referer( Constants::PLUGIN_DEACTIVATION, Constants::PLUGIN_DEACTIVATION ) ) {
				if ( isset( $_POST['plugin_type'] ) && 'nginxchampuru' === $_POST['plugin_type'] ) {
					$result = $this->deactivate_ncc_plugin();
				}
			}
		}
		if ( ! isset( $result ) ) {
			return;
		}
		if ( is_wp_error( $result ) ) {
			$this->notice->show_admin_error( $result );
		} else {
			$this->notice->show_admin_success( $result['message'], $result['type'] );
		}
	}

	/**
	 * Activate Nginx Cache Controller plugin
	 *
	 * @return void | WP_Error
	 * @access public
	 */
	public function activate_ncc_plugin() {
		$result = $this->plugin->activate( 'Nginx Cache Controller on WP.org' );
		if ( is_wp_error( $result ) ) {
			$result = $this->plugin->activate( 'Nginx Cache Controller on GitHub' );
		}

		if ( is_wp_error( $result ) ) {
			return new \WP_Error( 'AMIMOTO Dashboard Error', 'Nginx Cache Contoroller Plugin does not exists' );
		}
		return;
	}

	/**
	 * Deactivate Nginx Cache Controller plugin
	 *
	 * @return void | WP_Error
	 * @access public
	 */
	public function deactivate_ncc_plugin() {
		$result = $this->plugin->deactivate( 'Nginx Cache Controller on WP.org' );
		if ( is_wp_error( $result ) ) {
			$result = $this->plugin->deactivate( 'Nginx Cache Controller on GitHub' );
		}

		if ( is_wp_error( $result ) ) {
			return new \WP_Error( 'AMIMOTO Dashboard Error', 'Nginx Cache Contoroller Plugin does not exists' );
		}
		return;
	}

	/**
	 * Update Nginx Cache Controller plugin setting to use Amazon CloudFront
	 */
	public function update_ncc_plugin_settings() {
		if ( ! $this->plugin->is_activated_ncc() ) {
			return new \WP_Error( 'AMIMOTO Dashboard Error', 'Nginx Cache Controller Plugin is not activated.' );
		}
		$this->update_ncc_cache_expires();
		$this->update_ncc_cache_settings();
	}

	public function update_ncc_cache_expires() {
		$expires = get_option( 'nginxchampuru-cache_expires' );
		if ( ! is_array( $expires ) ) {
			return;
		}
		$updated_expires = array();
		foreach ( $expires as $key => $value ) {
			$updated_expires[ $key ] = 30;
		}
		update_option( 'nginxchampuru-cache_expires', $updated_expires );
	}

	public function update_ncc_cache_settings() {
		update_option( 'nginxchampuru-cache_levels', '1:2' );
		update_option( 'nginxchampuru-cache_dir', '/var/cache/nginx/proxy_cache' );
		update_option( 'nginxchampuru-comment', 'single' );
		update_option( 'nginxchampuru-publish', 'almost' );
		update_option( 'nginxchampuru-enable_flush', 1 );
	}
}
