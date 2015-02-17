<?php
/**
 * @version   $Id: comment_basic.php 60343 2014-01-03 18:16:44Z jakub $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 */
defined('GANTRY_VERSION') or die();

gantry_import('core.gantrylayout');

/**
 *
 * @package    gantry
 * @subpackage html.layouts
 */
class GantryLayoutComment_Basic extends GantryLayout
{
	var $render_params = array(
		'comment' => null,
		'depth'   => 0,
		'args'    => array()
	);

	function render($params = array())
	{
		/** @global $gantry Gantry */
		global $gantry;
		$fparams = $this->_getParams($params);
	}

	static function render_comment($comment, $args, $depth)
	{
		ob_start();
		$GLOBALS['comment'] = $comment;
		?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment-div-wrapper">
			<div class="comment-author vcard" style="line-height: 50px;">
				<div class="comment_gravatar_wrapper">
					<?php echo get_avatar($comment, $size = 50); ?>
				</div>
				<div class="comment-meta commentmetadata">
					<?php printf(_g('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>
					<div class="comment-meta-time">
						<a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>"><?php printf(_g('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?></a>
						<?php edit_comment_link(_g('(Edit)'), '  ', '') ?>
					</div>
				</div>
				<div class="clear"></div>
			</div>
			<?php if ($comment->comment_approved == '0') : ?>
				<span class="attention"><?php _ge('Your comment is awaiting moderation.') ?></span>
			<?php endif; ?>
			<?php comment_text() ?>
			<div class="reply">
				<?php comment_reply_link(array_merge($args, array(
				                                                 'depth'     => $depth,
				                                                 'max_depth' => $args['max_depth']
				                                            ))) ?>
			</div>
			<div class="clear"></div>
		</div>
		<?php
		echo ob_get_clean();
		return;
	}
}