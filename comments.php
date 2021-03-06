<?php
/**
 * Comments
 *
 * @package   Theme_Package
 * @author    Theme_Author <Theme_Author_Email>
 * @copyright Copyright (c) 2018, Theme_Author
 * @license   GNU General Public License v2 or later
 * @version   1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}
?>
<div id="comments" class="comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments__title">
			<?php wp_dl_post_comments_title(); ?>
		</h2><!-- /.comments__title -->
		<?php
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
			get_template_part( 'template-parts/content-comments', 'navigation' );
		endif;
		?>
		<ol class="comment__list">
			<?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true ) ); ?>
		</ol><!-- .comment__list -->
		<?php
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
			get_template_part( 'template-parts/content-comments', 'navigation' );
		endif;
		?>
		<?php if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) : ?>
			<p class="comments__none"><?php esc_html_e( 'Comments are closed.', '_s' ); ?></p>
		<?php endif; ?>
		<?php comment_form(); ?>
	<?php endif; ?>
</div>
