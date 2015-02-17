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

<?php global $post, $posts, $query_string, $wp_query; ?>

	<?php /** Begin Query Setup **/ ?>
	
	<?php

	$page_context = $this->getContext();

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	
	$query = $wp_query->query;
	if ( !is_array( $query ) ) parse_str( $query, $query );
	
	$custom_query = new WP_Query( array_merge( $query, array( 'posts_per_page' => $gantry->get( $page_context . '-count', '5' ), 'paged' => $paged ) ) ); ?>

	<?php /** End Query Setup **/ ?>

	<?php if( $custom_query->have_posts() ) : ?>

		<?php
			/* Queue the first post, that way we know
			 * what author we're dealing with (if that is the case).
			 *
			 * We reset this later so we can run the loop
			 * properly with a call to rewind_posts().
			 */
			the_post();
		?>
	
		<?php /** Begin Page Heading **/ ?>
		
		<?php if( $gantry->get( $page_context . '-page-heading-enabled', '1' ) ) : ?>
		
			<?php if( $gantry->get( $page_context . '-page-heading-text' ) != '' ) : ?>
			
				<h1>
					<?php echo $gantry->get( $page_context . '-page-heading-text' ); ?>
				</h1>
			
			<?php else : ?>
																												
				<h1>
					<?php printf( _r( 'Author Archives: %s' ), '<span class="vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( "ID" ) ) ) . '" title="' . esc_attr( get_the_author() ) . '" rel="me">' . get_the_author() . '</a></span>' ); ?>
				</h1>

			<?php endif; ?>

		<?php endif; ?>
		
		<?php /** End Page Heading **/ ?>

		<?php
			/* Since we called the_post() above, we need to
			 * rewind the loop back to the beginning that way
			 * we can run the loop properly, in full.
			 */
			rewind_posts();
		?>

		<?php /** Begin Author Info **/ ?>

		<?php if( get_the_author_meta( 'description' ) ) : ?>

			<div class="author-info">
				<div class="author-name">
					<h2>
						<?php printf( _r( 'About %s' ), get_the_author() ); ?>
					</h2>
				</div>
				<div class="author-description">
					<div class="author-avatar">
						<?php echo get_avatar( get_the_author_meta( 'user_email' ), $size = 60 ); ?>
					</div>
					<blockquote>
						<p>
							<?php the_author_meta( 'description' ); ?>
						</p>
						<small>
							<?php the_author(); ?>
						</small>
					</blockquote>
				</div>
			</div>

		<?php endif; ?>

		<?php /** End Author Info **/ ?>

		<?php /** Begin Posts **/ ?>
														
		<?php while ($custom_query->have_posts()) : $custom_query->the_post(); ?>

			<?php $this->get_content_template( 'content/content', get_post_format() ); ?>
		
		<?php endwhile; ?>
		
		<?php /** End Posts **/ ?>

		<?php /** Begin Pages Navigation **/ ?>
			
		<?php if( $gantry->get( 'pagination-enabled', '1' ) && $custom_query->max_num_pages > 1 ) gantry_pagination($custom_query); ?>

		<?php /** End Pages Navigation **/ ?>
	
	<?php else : ?>
																															
		<h1>
			<?php _re("Sorry, but there aren't any posts matching your query."); ?>
		</h1>
													
	<?php endif; ?>
													
	<?php wp_reset_query(); ?>