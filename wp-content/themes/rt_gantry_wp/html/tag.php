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
	
		<?php /** Begin Page Heading **/ ?>
		
		<?php if( $gantry->get( $page_context . '-page-heading-enabled', '1' ) ) : ?>
		
			<?php if( $gantry->get( $page_context . '-page-heading-text' ) != '' ) : ?>
			
				<h1>
					<?php echo $gantry->get( $page_context . '-page-heading-text' ); ?>
				</h1>
			
			<?php else : ?>
																												
				<h1>
					<?php printf( _r( 'Tag Archives: %s' ), '<span>' . single_tag_title( '', false ) . '</span>' ); ?>
				</h1>

			<?php endif; ?>

		<?php endif; ?>
		
		<?php /** End Page Heading **/ ?>

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