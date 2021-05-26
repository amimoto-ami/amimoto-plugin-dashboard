<?php
namespace AMIMOTO_Dashboard\Templates;
use AMIMOTO_Dashboard\Constants;
$logo_url    = path_join( AMI_DASH_URL, 'assets/amimoto.png' );
$text_domain = Constants::text_domain();
?>
<div class='amimoto-dash-side'>
	<div class='postbox'>
		<div class='hndle'>
			<h3 class='amimoto-logo-title'>
				<?php _e( 'High Performance WordPress Cloud', $text_domain ); ?>
			</h3>
		</div>
		<div class='inside'>
			<a
				href='https://amimoto-ami.com/'
				class='amimoto-logo-image'
			>
				<img src="<?php echo esc_attr( $logo_url ); ?>" alt='Amimoto' style='max-width:100%;height:auto;'>
			</a>
		</div>
	</div>

	<div class='postbox'>
		<div class='hndle'>
			<h3 class='amimoto-logo-title'><?php _e( 'Search AMIMOTO FAQ', $text_domain ); ?></h3>
		</div>
		<div class='inside'>
			<form role='search' class='' action='https://support.amimoto-ami.com/' method='get'>
				<p class="">
					<label class="screen-reader-text" for="amimoto-support-input">AMIMOTO Support Search:</label>
					<input type="search" id="amimoto-support-input" name="q" value="" placeholder="Search">
					<input type="submit" id="search-submit" class="button" value="Search">
				</p>
			</form>
		</div>
	</div>
</div>
