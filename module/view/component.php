<?php
/**
 * Amimoto_Dash_Component Class file
 *
 * @author hideokamoto <hide.okamoto@digitalcube.jp>
 * @package Amimoto-dashboard
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
		$html .= $this->_get_support_search_form();
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
		$html .= "<a href='https://amimoto-ami.com/' class='amimoto-logo-image'><img src={$logo_url} alt='Amimoto' style='max-width:100%;height:auto;'></a>";
		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}

	/**
	 * Search AMIMOTO FAQ (Intercom)
	 *
	 * @access private
	 * @return string
	 * @since 0.5.0
	 **/
	private function _get_support_search_form() {
		$html  = '';
		$html .= "<div class='postbox'>";
		$html .= "<div class='hndle'><h3 class='amimoto-logo-title'>". __( 'Search AMIMOTO FAQ', self::$text_domain ). '</h3></div>';
		$html .= "<div class='inside'>";
		$html .= "<form role='search' class='' action='https://support.amimoto-ami.com/' method='get'>";
		$html .= '<p class="">';
		$html .= '<label class="screen-reader-text" for="amimoto-support-input">AMIMOTO Support Search:</label>';
		$html .= '<input type="search" id="amimoto-support-input" name="q" value="" placeholder="Search">';
		$html .= '<input type="submit" id="search-submit" class="button" value="Search">';
		$html .= '</p>';
		$html .= "</form>";
		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}

	/**
	 *  Get AMIMOTO Managed cache control HTML
	 *
	 * @access protected
	 * @param none
	 * @return string HTML tag to show cache control form
	 * @since 0.5.0
	 */
	protected function _get_amimoto_managed_cache_control_form() {
		$html = '';
		if ( ! $this->is_amimoto_managed() ) {
			return $html;
		}
		$html .= "<table class='wp-list-table widefat plugins'>";
		$html .= '<thead>';
		$html .= "<tr><th colspan='2'><h2>" . __( 'AMIMOTO Cache Control', self::$text_domain ). '</h2></th></tr>';
		$html .= '</thead>';
		$html .= '<tbody>';
		$html .= '<tr><th><b>'. __( 'Flush All CDN Cache', self::$text_domain ). '</b>';
		$html .= '<p></p></th>';
		$html .= '<td>';
		$html .= "<form method='post' action=''>";
		$html .= "<input type='hidden' name='invalidation_target' value='all' />";
		$html .= wp_nonce_field( self::CLOUDFRONT_INVALIDATION , self::CLOUDFRONT_INVALIDATION , true , false );
		$html .= get_submit_button( __( 'Flush All CDN Cache', self::$text_domain ) );
		$html .= '</form>';
		$html .= '</td>';
		$html .= '</tr>';
		$html .= '<tr><th><b>'. __( 'Reset Nginx Cache Setting', self::$text_domain ). '</b>';
		$html .= '<p>' . __( 'All Nginx Cache Expires change 30sec.', self::$text_domain ) . '</p></th>';
		$html .= '<td>';
		$html .= "<form method='post' action=''>";
		$html .= "<input type='hidden' name='invalidation_target' value='all' />";
		$html .= wp_nonce_field( self::CLOUDFRONT_UPDATE_NCC , self::CLOUDFRONT_UPDATE_NCC , true , false );
		$html .= get_submit_button( __( 'Reset Nginx Cache Setting', self::$text_domain ) );
		$html .= '</form>';
		$html .= '</td>';
		$html .= '</tr>';
		$html .= '</tbody></table>';
		return $html;
	}
}
