<?php
namespace AMIMOTO_Dashboard\Templates\Layouts;
use AMIMOTO_Dashboard\Constants;
$text_domain = Constants::text_domain();

if ( ! apply_filters( 'amimoto_show_header', true ) ) {
	return;
}
?>

<header>
	<h1><?php _e( 'AMIMOTO Plugin Dashboard', $text_domain ); ?></h1>
	<hr/>
</header>
