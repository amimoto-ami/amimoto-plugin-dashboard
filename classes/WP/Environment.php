<?php
namespace AMIMOTO_Dashboard\WP;
use AMIMOTO_Dashboard\Constants;
if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly
}

class Environment {

	/**
	 * Check is AMIMOTO Managed mode
	 *
	 * @return bool
	 * @access public
	 */
	public function is_amimoto_managed( $server = null ) {
		if ( ! isset( $server ) ) {
			$server = $_SERVER;
		}
		if ( isset( $server['HTTP_X_AMIMOTO_MANAGED'] ) && $server['HTTP_X_AMIMOTO_MANAGED'] ) {
			return true;
		}
		return false;
	}

	/**
	 * Check is multisite
	 *
	 * @return boolean
	 * @access public
	 */
	public function is_multisite() {
		return function_exists( 'is_multisite' ) && is_multisite();
	}

}
