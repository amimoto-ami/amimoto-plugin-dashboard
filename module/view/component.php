<?php
class Amimoto_Dash_Component extends Amimoto_Dash_Base {
	private static $instance;
	private static $text_domain;

	private function __construct() {
		self::$text_domain = Amimoto_Dash_Base::text_domain();
	}

	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			$c = __CLASS__;
			self::$instance = new $c();
		}
		return self::$instance;
	}

	public function show_panel_html() {
		$content = $this->get_content_html();
		$html = $this->get_layout_html( $content );
		echo $html;
	}

	public function get_header() {
		$html  = "<header>";
		$html .= '<h1>' . __( 'AMIMOTO Plugin Dashboard', self::$text_domain ) . '</h1>';
		$html .= '<hr/>';
		$html .= '</header>';
		return $html;
	}

	public function get_layout_html( $content ) {
		$html  = "<div class='wrap'>";
		$html .= $this->get_header();
		$html .= '<h2>'. get_admin_page_title(). '</h2>';
		$html .= $content;
		//$html .= '</div>';
		return $html;
	}

}
