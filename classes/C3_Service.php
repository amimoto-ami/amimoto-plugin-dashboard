<?php
namespace AMIMOTO_Dashboard;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly
}
use AMIMOTO_Dashboard\WP\Plugins;
use AMIMOTO_Dashboard\Constants;
use AMIMOTO_Dashboard\WP\Admin_Notice;

class C3_Service {
	private $plugin;
	private $notice;
	public function __construct( ...$args ) {
		if ( $args && ! empty( $args ) ) {
			foreach ( $args as $key => $value ) {
				if ( $value instanceof Plugins ) {
					$this->plugin = $value;
				} elseif ( $value instanceof Admin_Notice ) {
					$this->notice = $value;
				}
			}
		}

		if ( ! $this->plugin || ! isset( $this->plugin ) ) {
			$this->plugin = new Plugins();
		}
		if ( ! $this->notice || ! isset( $this->notice ) ) {
			$this->notice = new Admin_Notice();
		}
		add_action(
			'admin_init',
			array(
				$this,
				'invalidate_manually',
			)
		);
		add_action( 'admin_init', array( $this, 'control_plugin' ) );
		add_action( 'admin_init', array( $this, 'update_setting' ) );
	}
	public function update_setting() {
		if ( empty( $_POST ) ) {
			return;
		}
		$key = Constants::CLOUDFRONT_SETTINGS;
		if ( ! isset( $_POST[ $key ] ) || ! $_POST[ $key ] ) {
			return;
		}

		if ( ! check_admin_referer( $key, $key ) ) {
			return;
		}
		try {
			$updated_setting = array();
			foreach ( $_POST['c3_settings'] as $key => $value ) {
				$updated_setting[ $key ] = esc_attr( $value );
			}
			update_option( 'c3_settings', $updated_setting );
			$this->notice->show_admin_success( 'Update CloudFront cache settings', 'Success' );
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
				if ( isset( $_POST['plugin_type'] ) && 'c3-cloudfront-clear-cache' === $_POST['plugin_type'] ) {
					$result = $this->activate_c3_plugin();
				}
			}
		}
		if ( isset( $_POST[ Constants::PLUGIN_DEACTIVATION ] ) && $_POST[ Constants::PLUGIN_DEACTIVATION ] ) {
			if ( check_admin_referer( Constants::PLUGIN_DEACTIVATION, Constants::PLUGIN_DEACTIVATION ) ) {
				if ( isset( $_POST['plugin_type'] ) && 'c3-cloudfront-clear-cache' === $_POST['plugin_type'] ) {
					$result = $this->deactivate_c3_plugin();
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

	public function invalidate_manually() {
		if ( empty( $_POST ) ) {
			return;
		}
		$result = null;
		$key    = Constants::CLOUDFRONT_INVALIDATION;
		if ( ! isset( $_POST[ $key ] ) || ! $_POST[ $key ] ) {
			return;
		}

		if ( ! check_admin_referer( $key, $key ) ) {
			return;
		}
		$target = 'all';
		if ( isset( $_POST['invalidation_target'] ) && $_POST['invalidation_target'] ) {
			$target = $_POST['invalidation_target'];
		}
		$result = $this->invalidation( $target );
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
	public function activate_c3_plugin() {
		$result = $this->plugin->activate( 'C3 Cloudfront Cache Controller' );

		if ( is_wp_error( $result ) ) {
			return new \WP_Error( 'AMIMOTO Dashboard Error', 'C3 Cloudfront Cache Controller Plugin does not exists' );
		}
		return;
	}

	/**
	 * Deactivate Nginx Cache Controller plugin
	 *
	 * @return void | WP_Error
	 * @access public
	 */
	public function deactivate_c3_plugin() {
		$result = $this->plugin->deactivate( 'C3 Cloudfront Cache Controller' );

		if ( is_wp_error( $result ) ) {
			return new \WP_Error( 'AMIMOTO Dashboard Error', 'C3 Cloudfront Cache Controller Plugin does not exists' );
		}
		return;
	}

	/**
	 *  Invalidation
	 *
	 * @access public
	 * @param (string) $target
	 * @return boolean | WP_Error
	 */
	public function invalidation( $target = 'all' ) {
		$plugin_file_path = Plugins::get_plugin_file_path_by_name( 'C3 Cloudfront Cache Controller' );
		require_once( $plugin_file_path );
		$c3     = \CloudFront_Clear_Cache::get_instance();
		$result = $c3->c3_invalidation();
		if ( is_wp_error( $result ) ) {
			return $result;
		}
		return array(
			'type'    => 'Success',
			'message' => 'Invalidation has been succeeded, please wait a few minutes to remove the cache.',
		);
	}

	public function get_plugin_options() {
		return get_option( 'c3_settings' );
	}
}
