<?php
/**
 * @version   4.1.2 May 18, 2014
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
defined( 'GANTRY_VERSION' ) or die();

gantry_import( 'core.gantrylayout' );

/**
 *
 * @package gantry
 * @subpackage html.layouts
 */
class GantryLayoutComment_Standard extends GantryLayout {
	var $render_params = array(
		'comment' => null,
		'depth' => 0,
		'args' => array()
	);

	function render( $params = array() ) {
		global $gantry;
		$fparams = $this->_getParams( $params );
	}

	static function render_comment( $comment, $args, $depth ) {
		global $post;

		ob_start();

		$GLOBALS['comment'] = $comment;
		
		$avatar = get_avatar( $comment, $size = 40 );
		$avatar = str_replace( "class='", "class='rt-image ", $avatar );

		?>
		
		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
			<div id="comment-<?php comment_ID(); ?>" class="comment-body comment">

				<div class="author-avatar">
					<?php echo $avatar; ?>
				</div>

				<div class="comment-meta comment-author vcard">
					<?php
						printf(
							'<span class="author lead">%s</span>', 
							get_comment_author_link()
						);
					?>
				</div>

				<?php if ( $comment->comment_approved == '0' ) : ?>
				<p class="info">
					<span class="awaiting-moderation"><?php _re( 'Your comment is awaiting moderation.' ); ?></span>
				</p>
				<?php endif; ?>

				<div class="comment-content">
					<blockquote>
						<?php comment_text(); ?>
						<small>
							<?php 
								// translators %1$s date, %2$s time
								echo sprintf(
									'<a href="%1$s" class="comment-time"><cite>%2$s</cite></a>',
									esc_url( get_comment_link( $comment->comment_ID ) ),
									sprintf( _r( '%1$s at %2$s' ), 
									get_comment_date(),
									get_comment_time() )
								);
							?>
						</small>
					</blockquote>
					<?php edit_comment_link( _r( 'Edit' ), '<p class="edit-link">', '</p>' ); ?>
				</div>

				<div class="reply">
					<?php comment_reply_link( array_merge( $args, array( 'reply_text' => _r( 'Reply' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
				</div>

			</div>
		 
		<?php

		unset($comment_classes, $get_comment_classes);

		echo ob_get_clean();

		return;
	}
}