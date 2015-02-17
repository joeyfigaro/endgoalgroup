<?php
/**
 * @version   4.1.2 May 18, 2014
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */

/**
 * Check to see if Gantry is Active
 * 
 * @return bool
 */
function gantry_theme_is_gantry_active()
{
	$active = false;
	$active_plugins = get_option( 'active_plugins' );
	if ( in_array( 'gantry/gantry.php', $active_plugins ) ) {
		$active = true;
	}
	if ( !function_exists( 'is_plugin_active_for_network' ) )
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	if ( is_plugin_active_for_network( 'gantry/gantry.php' ) ) {
		$active = true;
	}
	return $active;
}

/**
 * @return void
 */
function gantry_admin_missing_nag()
{
	$msg = __( 'The active theme requires the Gantry Framework Plugin to be installed and active' );
	echo "<div class='update-nag'>$msg</div>";
}

/**
 * @return void
 */
function gantry_missing_nag()
{
	echo 'This theme requires the Gantry Framework Plugin to be installed and active.';
	die(0);
}


if ( !gantry_theme_is_gantry_active() ) {
	if ( !is_admin() ) {
		add_filter( 'template_include', 'gantry_missing_nag', -10, 0 );
	}
	else {
		add_action( 'admin_notices', 'gantry_admin_missing_nag' );
	}
}

// This will always set the Posts Per Page option to 1 to fix the WordPress bug
// when the pagination would return 404 page. To set the number of posts shown
// on the blog page please use the field under Theme Settings > Content > Blog > Post Count
function gantry_posts_per_page() {
	if( get_option( 'posts_per_page' ) != '1' ) update_option( 'posts_per_page', '1' );
}

add_action( 'init', 'gantry_posts_per_page' );

/**
 * Function to generate post pagination
 */
function gantry_pagination($custom_query) {
	global $gantry;

	if ( !$current_page = get_query_var( 'paged' ) ) $current_page = 1;
			
	$permalinks = get_option( 'permalink_structure' );
	if( is_front_page() ) {
		$format = empty( $permalinks ) ? '?paged=%#%' : 'page/%#%/';
	} else {
		$format = empty( $permalinks ) || is_search() ? '&paged=%#%' : 'page/%#%/';
	}

	$big = 999999999; // need an unlikely integer

	$pagination = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => $format,
		'current' => $current_page,
		'total' => $custom_query->max_num_pages,
		'mid_size' => $gantry->get( 'pagination-count', '8' ),
		'type' => 'list',
		'next_text' => _r( 'Next' ),
		'prev_text' => _r( 'Previous' )
	) );

	$pagination = explode( "\n", $pagination );
	$pagination_mod = array();

	foreach ( $pagination as $item ) {
		( preg_match( '/<ul class=\'page-numbers\'>/i', $item ) ) ? $item = str_replace( '<ul class=\'page-numbers\'>', '<ul>', $item ) : $item;
		( preg_match( '/class="prev/i', $item ) ) ? $item = str_replace( '<li', '<li class="pagination-prev"', $item ) : $item;
		( preg_match( '/class="next/i', $item ) ) ? $item = str_replace( '<li', '<li class="pagination-next"', $item ) : $item;
		( preg_match( '/page-numbers/i', $item ) ) ? $item = str_replace( 'page-numbers', 'page-numbers pagenav', $item ) : $item;
		$pagination_mod[] .= $item;
	}
	
	?>
	
	<div class="pagination">

		<?php if( $gantry->get( 'pagination-show-results', '1' ) ) : ?>
		<p class="counter">
			<?php printf( _r( 'Page %1$s of %2$s' ), $current_page, $custom_query->max_num_pages ); ?>
		</p>
		<?php endif; ?>
	
		<?php foreach( $pagination_mod as $page ) {
			echo $page;
		} ?>

	</div>

<?php }