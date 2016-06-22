<?php
/**
 * Amimoto_Dash_Component Class file
 *
 * @author hideokamoto <hide.okamoto@digitalcube.jp>
 * @package Amimoto-plugin-dashboard
 * @since 0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Define AMMIMOTO Dashboard plugin's common comnponents
 *
 * @class Amimoto_Dash_Component
 * @since 0.0.1
 */
class Amimoto_Dash_Component extends Amimoto_Dash_Base {
	private static $instance;
	private static $text_domain;

	private function __construct() {
		self::$text_domain = Amimoto_Dash_Base::text_domain();
	}

	/**
	 * Get Instance Class
	 *
	 * @return Amimoto_Dash_Component
	 * @since 0.0.1
	 * @access public
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}

	/**
	 *  Show AMIMOTO Dashboard Plugin admin page html
	 *
	 * @access public
	 * @param none
	 * @since 0.0.1
	 */
	public function show_panel_html() {
		$content = $this->get_content_html();
		$html = $this->get_layout_html( $content );
		echo $html;
	}

	/**
	 *  Create AMIMOTO Dashboard Plugin's admin page header
	 *
	 * @access private
	 * @param none
	 * @return string(HTML)
	 * @since 0.0.1
	 */
	private function _get_header() {
		$html  = "<header>";
		$html .= '<h1>' . __( 'AMIMOTO Plugin Dashboard', self::$text_domain ) . '</h1>';
		$html .= '<hr/>';
		$html .= '</header>';
		return $html;
	}

	/**
	 *  Create AMIMOTO Dashboard Plugin's admin page html
	 *
	 * @access public
	 * @param none
	 * @return string(HTML)
	 * @since 0.0.1
	 */
	public function get_layout_html( $content ) {
		$html  = "<div class='wrap'>";
		$html .= $this->_get_header();
		$html .= '<h2>'. get_admin_page_title(). '</h2>';
		$html .= $content;
		//$html .= '</div>';
		return $html;
	}

}
