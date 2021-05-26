<?php
namespace AMIMOTO_Dashboard\WP;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly
}
use AMIMOTO_Dashboard\Constants;
use AMIMOTO_Dashboard\WP\Environment;

class Plugins {
	public $amimoto_uninstalled_plugins = array();

	private function _get_active_plugins() {
		return get_option( 'active_plugins' );
	}

	public static function get_plugin_file_path( string $plugin_slug ) {
		return path_join( ABSPATH . 'wp-content/plugins', $plugin_slug );
	}

	public static function get_plugin_slug_by_name( string $plugin_name ) {
		if ( ! isset( Constants::AMIMOTO_PLUGINS[ $plugin_name ] ) ) {
			return null;
		}
		return Constants::AMIMOTO_PLUGINS[ $plugin_name ];
	}

	/**
	 * Get the plugin path to control it
	 *
	 * @return string | null
	 * @access public
	 */
	public static function get_plugin_file_path_by_name( string $plugin_name ) {
		$plugin_slug = self::get_plugin_slug_by_name( $plugin_name );
		if ( ! $plugin_slug || ! isset( $plugin_slug ) ) {
			return null;
		}
		$plugin_file_path = self::get_plugin_file_path( $plugin_slug );
		return $plugin_file_path;
	}
	/**
	 * Check is C3 Cloudfront Cache Controller Activated
	 *
	 * @return boolean
	 * @access public
	 */
	public function is_activated_c3() {
		$amimoto_plugins  = Constants::AMIMOTO_PLUGINS;
		$activate_plugins = self::_get_active_plugins();
		if (
			array_search( $amimoto_plugins['C3 Cloudfront Cache Controller'], $activate_plugins, true ) > -1
		) {
			return true;
		}
		return false;
	}

	/**
	 * Check is C3 Cloudfront Cache Controller file exists
	 *
	 * @return boolean
	 * @access public
	 */
	public function is_exists_c3() {
		$amimoto_plugins = Constants::AMIMOTO_PLUGINS;
		if ( file_exists( path_join( ABSPATH . 'wp-content/plugins', $amimoto_plugins['C3 Cloudfront Cache Controller'] ) ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Check is Nginx Cache Controller Activated
	 *
	 * @return boolean
	 * @since 0.0.1
	 * @access public
	 */
	public function is_activated_ncc() {
		$amimoto_plugins  = Constants::AMIMOTO_PLUGINS;
		$activate_plugins = self::_get_active_plugins();
		if (
			array_search( $amimoto_plugins['Nginx Cache Controller on GitHub'], $activate_plugins, true ) > -1 ||
			array_search( $amimoto_plugins['Nginx Cache Controller on WP.org'], $activate_plugins, true ) > -1
		) {
			return true;
		}
		return false;
	}

	/**
	 * Check is Nginx Cache Controller file exists
	 *
	 * @return boolean
	 * @since 0.0.1
	 * @access public
	 */
	public function is_exists_ncc() {
		$amimoto_plugins = Constants::AMIMOTO_PLUGINS;
		if ( file_exists( path_join( ABSPATH . 'wp-content/plugins', $amimoto_plugins['Nginx Cache Controller on GitHub'] ) ) ||
			 file_exists( path_join( ABSPATH . 'wp-content/plugins', $amimoto_plugins['Nginx Cache Controller on WP.org'] ) ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Lists activated AMIMOTO plugin
	 *
	 * @return Array<string>
	 * @access public
	 */
	public function list_amimoto_activated_plugins() {
		$active_plugins = self::_get_active_plugins();
		$plugins        = array();
		if ( ! isset( $active_plugins ) || ! is_array( $active_plugins ) ) {
			return $plugins;
		}
		foreach ( $active_plugins as $plugin_url ) {
			if ( ! array_search( $plugin_url, Constants::AMIMOTO_PLUGINS ) ) {
				continue;
			}
			$plugins[] = $plugin_url;
		}
		return $plugins;
	}

	/**
	 * Activate plugin by name.
	 * Only allowed AMIMOTO plugins
	 *
	 * @return void | WP_Error
	 * @access public
	 */
	public static function activate( string $plugin_name ) {
		$plugin_file_path = self::get_plugin_file_path_by_name( $plugin_name );
		if ( ! file_exists( $plugin_file_path ) ) {
			return new \WP_Error( 'AMIMOTO Dashboard Error', $plugin_name . ' Plugin does not exists' );
		}
		\activate_plugins( $plugin_file_path, '', Environment::is_multisite() );
	}

	/**
	 * Deactivate plugin by name
	 * Only allowed AMIMOTO plugins
	 *
	 * @return void | WP_Error
	 * @access public
	 */
	public static function deactivate( string $plugin_name ) {
		$plugin_file_path = self::get_plugin_file_path_by_name( $plugin_name );
		if ( ! file_exists( $plugin_file_path ) ) {
			return new \WP_Error( 'AMIMOTO Dashboard Error', $plugin_name . ' Plugin does not exists' );
		}
		\deactivate_plugins( $plugin_file_path, '', Environment::is_multisite() );
	}

	public function list_amimoto_plugins() {
		$amimoto_plugins    = Constants::AMIMOTO_PLUGINS;
		$env                = new Environment();
		$is_amimoto_managed = $env->is_amimoto_managed();
		$plugins            = array();
		foreach ( $amimoto_plugins as $plugin_name => $plugin_url ) {
			if ( $is_amimoto_managed ) {
				if (
					$plugin_name === 'C3 Cloudfront Cache Controller' ||
					$plugin_name === 'Nginx Cache Controller on GitHub' ||
					$plugin_name === 'Nginx Cache Controller on WP.org'
				) {
					continue;
				}
			}
			$plugin_file_path = path_join( ABSPATH . 'wp-content/plugins', $plugin_url );
			if ( ! file_exists( $plugin_file_path ) ) {
				if ( 'Nginx Cache Controller on GitHub' != $plugin_name ) {
					$this->amimoto_uninstalled_plugins[ $plugin_name ] = $plugin_url;
				}
				unset( $amimoto_plugins[ $plugin_name ] );
				continue;
			}
			$plugins[ $plugin_url ] = get_plugin_data( $plugin_file_path, false );
		}
		return $plugins;
	}

	public function list_uninstalled_plugins() {
		return $this->amimoto_uninstalled_plugins;
	}

	public function get_activated_plugin_list() {
		$active_plugin_urls = $this->_get_active_plugins();
		$plugins            = array();
		foreach ( $active_plugin_urls as $plugin_url ) {
			if ( ! array_search( $plugin_url, Constants::AMIMOTO_PLUGINS ) ) {
				continue;
			}
			$plugins[] = $plugin_url;
		}
		return $plugins;
	}

	public function get_amimoto_plugin_for_use( string $plugin_name ) {
		$text_domain = Constants::text_domain();
		switch ( $plugin_name ) {
			case 'nginxchampuru':
			case 'Nginx Cache Controller':
				$description = __( 'Nginx Reverse Proxy Cache', $text_domain );
				break;

			case 'c3-cloudfront-clear-cache':
			case 'C3 Cloudfront Cache Controller':
				$description = __( 'Amazon CloudFront', $text_domain );
				break;

			default:
				$description = '';
				break;
		}
		return $description;
	}
	public function get_action_type( string $plugin_type ) {
		switch ( $plugin_type ) {
			case 'c3-cloudfront-clear-cache':
				$action = Constants::PANEL_C3;
				break;

			case 'nginxchampuru':
				$action = Constants::PANEL_NCC;
				break;

			default:
				$action = '';
				break;
		}
		return $action;
	}

	/**
	 *  Get amimoto plugin description
	 *
	 * @access public
	 * @param (string) $plugin_name
	 * @return string
	 * @since 0.0.1
	 */
	public function get_amimoto_plugin_description( string $plugin_name ) {
		$text_domain = Constants::text_domain();
		switch ( $plugin_name ) {
			case 'Nginx Cache Controller':
				$description = __( 'Provides some functions of controlling Nginx proxy server cache.', $text_domain );
				break;

			case 'C3 Cloudfront Cache Controller':
				$description = __( "Controlle CloudFront's CDN server cache.", $text_domain );
				break;

			default:
				$description = '';
				break;
		}
		return $description;
	}
}
