<?php
namespace AMIMOTO_Dashboard\Tests\Mocks\WP;
use AMIMOTO_Dashboard\WP;

class Plugins extends WP\Plugins {
    private $c3 = array(
        'activated' => false,
        'exists' => false,
    );
    private $ncc = array(
        'activated' => false,
        'exists' => false,
    );
    function __construct( $options ) {
        if ( isset( $options[ 'c3' ] ) ) {
            if ( isset( $options[ 'c3' ][ 'activated'] ) ) {
                $this->c3[ 'activated' ] = $options[ 'c3' ][ 'activated'];
            }
            if ( isset( $options[ 'c3' ][ 'exists'] ) ) {
                $this->c3[ 'exists' ] = $options[ 'c3' ][ 'exists'];
            }
        }
        if ( isset( $options[ 'ncc' ] ) ) {
            if ( isset( $options[ 'ncc' ][ 'activated'] ) ) {
                $this->ncc[ 'activated' ] = $options[ 'ncc' ][ 'activated' ];
            }
            if ( isset( $options[ 'ncc' ][ 'exists'] ) ) {
                $this->ncc[ 'exists' ] = $options[ 'ncc' ][ 'exists'];
            }
        }
    }
    
	public function is_activated_c3() {
		return $this->c3[ 'activated' ];
	}

	public function is_exists_c3() {
		return $this->c3[ 'exists' ];
	}
    
	public function is_activated_ncc() {
		return $this->ncc[ 'activated' ];
	}

	public function is_exists_ncc() {
		return $this->ncc[ 'exists' ];
	}
}
