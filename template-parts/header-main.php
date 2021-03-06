<?php
/**
 * Header Main
 *
 * @package    Theme_Package
 * @subpackage Theme_Package/Template_Parts
 * @author     Theme_Author <Theme_Author_Email>
 * @copyright  Copyright (c) 2018, Theme_Author
 * @license    GNU General Public License v2 or later
 * @version    1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

?>
<header class="site-header row">
	<section class="col-2 site-header__branding">
		<div class="branding__wrap">
			<h1 class="branding__title">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
					<?php echo esc_html( get_bloginfo( 'name', 'display' ) ); ?>
				</a>
			</h1>
			<?php if ( get_bloginfo( 'description' ) ) : ?>
				<p class="branding__description">
					<?php echo esc_html( get_bloginfo( 'description', 'display' ) ); ?>
				</p>
			<?php endif; ?>
		</div>
	</section><!-- /.site-header__branding -->
	<?php get_template_part( 'template-parts/_partials/nav-menu/main' ); ?>
</header><!-- /.site-header -->
