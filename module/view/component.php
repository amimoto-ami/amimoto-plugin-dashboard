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
		$html  = '<header>';
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
		$html  = "<div class='wrap' id='amimoto-dashboard'>";
		$html .= $this->_get_header();
		$html .= "<div class='amimoto-dash-main'>";
		$html .= $content;
		$html .= '</div>';
		$html .= $this->_get_subcontent_html();
		return $html;
	}

	/**
	 *  Create AMIMOTO Dashboard's side content html
	 *
	 * @access private
	 * @param none
	 * @return string(HTML)
	 * @since 0.0.1
	 */
	private function _get_subcontent_html() {
		$html  = "<div class='amimoto-dash-side'>";
		$html .= $this->_get_amimoto_logo();
		$html .= '</div>';
		return $html;
	}

	/**
	 *  Create AMIMOTO LOGO Widget html
	 *
	 * @access private
	 * @param none
	 * @return string(HTML)
	 * @since 0.0.1
	 */
	private function _get_amimoto_logo() {
		$html  = '';
		$logo_url = path_join( AMI_DASH_URL, 'assets/amimoto.png' );
		$html .= "<div class='postbox'>";
		$html .= "<div class='hndle'><h3 class='amimoto-logo-title'>". __( 'High Performance WordPress Cloud', self::$text_domain ). '</h3></div>';
		$html .= "<div class='inside'>";
		$html .= "<a href='https://amimoto-ami.com/' class='amimoto-logo-image'><img src={$logo_url} alt='Amimoto' ></a>";
		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}
}
