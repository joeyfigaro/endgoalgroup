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

	<div class="blog-featured">
	
		<?php /** Begin Page Heading **/ ?>

		<?php if( $gantry->get( 'blog-page-heading-enabled', '1' ) && $gantry->get( 'blog-page-heading-text' ) != '' ) : ?>
		
			<h1>
				<?php echo $gantry->get( 'blog-page-heading-text' ); ?>
			</h1>
		
		<?php endif; ?>
		
		<?php /** End Page Heading **/ ?>
		
		<?php /** Begin Query Setup **/ ?>

		<?php 

		$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
		$posts_per_page = $gantry->get( 'blog-post-lead-items', '1' ) + $gantry->get( 'blog-post-intro-items', '2' );
			
		if ( $gantry->get( 'blog-query-custom' ) != '' ) {
		
			$custom_query = new WP_Query( 'posts_per_page=' . $posts_per_page . '&paged=' . $paged . '&' . $gantry->get( 'blog-query-custom' ) );
		
		} else {
		
			$custom_query = new WP_Query( 'posts_per_page=' . $posts_per_page . '&paged=' . $paged . '&orderby=' . $gantry->get( 'blog-query-order', 'date' ) . '&cat=' . $gantry->get( 'blog-cat' ) . '&post_type=' . $gantry->get( 'blog-query-type', 'post' ) );
		
		}
		
		?>

		<?php /** End Query Setup **/ ?>

		<?php /** Begin Leading Posts **/ ?>

		<?php $leadingcount = 0; ?>

		<?php if( $custom_query->have_posts() && $gantry->get( 'blog-post-lead-items', '1' ) > 0 ) : ?>

			<div class="items-leading">

				<?php while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>

				<div class="leading-<?php echo $leadingcount; ?>">

					<?php $this->get_content_template( 'content/content', get_post_format() ); ?>

				</div>

				<?php $leadingcount++; ?>

				<?php if( $leadingcount == $gantry->get( 'blog-post-lead-items', '1' ) ) break; ?>

				<?php endwhile; ?>

			</div>

		<?php endif; ?>

		<?php /** End Leading Posts **/ ?>

		<?php /** Begin Posts **/ ?>

		<?php if( ( $custom_query->have_posts() && $leadingcount > 0 && $custom_query->current_post != -1 ) || ( $custom_query->have_posts() && $leadingcount == 0 ) ) : ?>

			<?php 

			$introcount = ( $custom_query->post_count - $leadingcount ); 
			$counter = 0; 
			
			if( $gantry->get( 'blog-post-columns', '1' ) <= 0 ) $gantry->set( 'blog-post-columns', 1 );
			if( $gantry->get( 'blog-post-columns', '1' ) > 4 ) $gantry->set( 'blog-post-columns', 4 );

			?>

			<?php while ( $custom_query->have_posts() ) : $custom_query->the_post(); ?>

			<?php 

			$key = ( $custom_query->current_post - $leadingcount ) + 1;
			$rowcount = ( ( ( int )$key - 1 ) % ( int ) $gantry->get( 'blog-post-columns', '1' ) ) + 1;
			$row = $counter / $gantry->get( 'blog-post-columns', '1' );

			if ( $rowcount == 1 ) : ?>
			<div class="items-row cols-<?php echo ( int ) $gantry->get( 'blog-post-columns', '1' ); ?> <?php echo 'row-' . $row; ?>">
			<?php endif; ?>

			<div class="item column-<?php echo $rowcount;?>">

				<?php $this->get_content_template( 'content/content', get_post_format() ); ?>
			
			</div>

			<?php $counter++; ?>

			<?php if( ( $rowcount == $gantry->get( 'blog-post-columns', '1' ) ) || ( $counter == $introcount ) ) : ?>
				<span class="row-separator"></span>
			</div>
			<?php endif; ?>

			<?php endwhile; ?>

		<?php endif; ?>

		<?php /** End Posts **/ ?>
		
		<?php /** Begin Pages Navigation **/ ?>
			
		<?php if( $gantry->get( 'pagination-enabled', '1' ) && $custom_query->max_num_pages > 1 ) gantry_pagination($custom_query); ?>

		<?php /** End Pages Navigation **/ ?>
		
	</div>