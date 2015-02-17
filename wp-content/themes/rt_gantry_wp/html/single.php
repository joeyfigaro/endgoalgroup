<?php
/**
 * @version   4.1.2 May 18, 2014
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined( 'ABSPATH' ) or die( 'Restricted access' );
?>

<?php global $post, $posts, $query_string; ?>

	<div class="item-page">
		
		<?php if ( have_posts() ) : ?>

			<?php /** Begin Page Heading **/ ?>

			<?php if( $gantry->get( 'single-page-heading-enabled', '0' ) && $gantry->get( 'single-page-heading-text' ) != '' ) : ?>
			
				<h1>
					<?php echo $gantry->get( 'single-page-heading-text' ); ?>
				</h1>
			
			<?php endif; ?>
			
			<?php /** End Page Heading **/ ?>

			<?php while ( have_posts() ) : the_post(); ?>

				<?php $this->get_content_template( 'content/content', get_post_format() ); ?>
			
			<?php endwhile; ?>
		
		<?php else : ?>
																	
			<h1>
				<?php _re( 'Sorry, no posts matched your criteria.' ); ?>
			</h1>
			
		<?php endif; ?>

	</div>