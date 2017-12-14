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
		$html .= $this->_get_support_search_form();
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
		$html .= $this->_get_amimoto_api_widget( 16 );
		$html .= $this->_get_amimoto_api_widget( 17 );
		$html .= '</div>';
		return $html;
	}

	/**
	 * Search AMIMOTO FAQ (ZenDesk)
	 *
	 * @access private
	 * @return string
	 * @since 0.2.0
	 **/
	private function _get_support_search_form() {
		$html  = '';
		$html .= "<form role='search' class='' action='https://support.amimoto-ami.com/' method='get'>";
		$html .= '<p class="search-box">';
		$html .= '<label class="screen-reader-text" for="amimoto-support-input">AMIMOTO Support Search:</label>';
		$html .= '<input type="search" id="amimoto-support-input" name="q" value="">';
		$html .= '<input type="submit" id="search-submit" class="button" value="Search AMIMOTO Support">';
		$html .= '</p>';
		$html .= "</form>";
		return $html;
	}
	/**
	 *  Create AMIMOTO News Widget html
	 *
	 * @access private
	 * @param none
	 * @return string(HTML)
	 * @since 0.1.0
	 */
	private function _get_amimoto_api_widget( $category_id = false ) {
		$wp = Amimoto_WPAPI::get_instance();
		$result = $wp->get_post( 5, $category_id );
		if ( is_wp_error( $result ) ) {
			return '';
		}
		switch ( $category_id ) {
			case 16:
				$title = __( 'Road to Becoming the AMIMOTO Master', self::$text_domain );
				break;

			case 17:
				$title = __( 'AMIMOTO News', self::$text_domain );
				break;

			default:
				$title = false;
				break;
		}
		$html  = '';
		$html .= "<div class='postbox'>";
		if ( $title ) {
			$html .= "<div class='hndle'><h3 class='amimoto-logo-title'>{$title}</h3></div>";
		}
		$html .= "<div class='inside'>";
		foreach ( $result as $post ) {
			$date = date( 'Y/m/d', strtotime( $post['date'] ) );
			$html .= '<section>';
			$html .= "<a href={$post['link']} target='_blank'>";
			$html .= '<h4>'. esc_attr( $post['title']['rendered'] ). "<br/><small>{$date}</small></h4>";
			$html .= '</a></section>';
		}
		$html .= '</div>';
		$html .= '</div>';
		return $html;
	}

}
