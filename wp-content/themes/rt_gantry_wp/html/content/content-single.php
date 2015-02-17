<?php
/**
 * @version   4.1.2 May 18, 2014
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined( 'ABSPATH' ) or die( 'Restricted access' );

// Create a shortcut for params.
$category = get_the_category();
?>

			<?php /** Begin Post **/ ?>
					
			<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

				<?php /** Begin Article Title **/ ?>

				<?php if( $gantry->get( 'single-title-enabled', '1' ) ) : ?>

					<h2>
						<?php if( $gantry->get( 'single-title-link', '0' ) ) : ?>
							<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( get_the_title() ); ?>"><?php the_title(); ?></a>
						<?php else : ?>
							<?php the_title(); ?>
						<?php endif; ?>
					</h2>

				<?php endif; ?>

				<?php /** End Article Title **/ ?>

				<?php /** Begin Extended Meta **/ ?>

				<?php if( $gantry->get( 'single-meta-author-enabled', '1' ) || $gantry->get( 'single-meta-category-enabled', '0' ) || $gantry->get( 'single-meta-category-parent-enabled', '0' ) || $gantry->get( 'single-meta-date-enabled', '1' ) || $gantry->get( 'single-meta-modified-enabled', '0' ) || $gantry->get( 'single-meta-comments-enabled', '1' ) ) : ?>
				
					<dl class="article-info">

						<?php /** Begin Parent Category **/ ?>
							
						<?php if( $gantry->get( 'single-meta-category-parent-enabled', '0' ) && !empty( $category ) && $category[0]->parent != '0' ) : ?>

							<dd class="parent-category-name"> 
								<?php
									$parent_category = get_category( ( int ) $category[0]->parent );
									$title = $parent_category->cat_name;
									$link = get_category_link( $parent_category );
									$url = '<a href="' . esc_url( $link ) . '">' . $title . '</a>'; 
								?>

								<?php if( $gantry->get( 'single-meta-category-parent-prefix' ) != '' ) echo $gantry->get( 'single-meta-category-parent-prefix' ); ?>
			
								<?php if( $gantry->get( 'single-meta-category-parent-link', '0' ) ) : ?>
									<?php echo $url; ?>
								<?php else : ?>
									<?php echo $title; ?>
								<?php endif; ?>
							</dd>

						<?php endif; ?>

						<?php /** End Parent Category **/ ?>
	
						<?php /** Begin Category **/ ?>

						<?php if( $gantry->get( 'single-meta-category-enabled', '0' ) && !empty( $category ) ) : ?>

							<dd class="category-name"> 
								<?php 
									$title = $category[0]->cat_name;
									$link = get_category_link( $category[0]->cat_ID );
									$url = '<a href="' . esc_url( $link ) . '">' . $title . '</a>';
								?>

								<?php if( $gantry->get( 'single-meta-category-prefix' ) != '' ) echo $gantry->get( 'single-meta-category-prefix' ); ?>
			
								<?php if( $gantry->get( 'single-meta-category-link', '0' ) ) : ?>
									<?php echo $url; ?>
								<?php else : ?>
									<?php echo $title; ?>
								<?php endif; ?>
							</dd>

						<?php endif; ?>

						<?php /** End Category **/ ?>

						<?php /** Begin Date & Time **/ ?>

						<?php if( $gantry->get( 'single-meta-date-enabled', '1' ) ) : ?>

							<dd class="create"> <?php if( $gantry->get( 'single-meta-date-prefix' ) != '' ) echo $gantry->get( 'single-meta-date-prefix' ) . ' '; ?><?php the_time( $gantry->get( 'single-meta-date-format', 'd F Y' ) ); ?></dd>

						<?php endif; ?>

						<?php /** End Date & Time **/ ?>

						<?php /** Begin Modified Date **/ ?>

						<?php if( $gantry->get( 'single-meta-modified-enabled', '1' ) ) : ?>

							<dd class="modified"> <?php if( $gantry->get( 'single-meta-modified-prefix' ) != '' ) echo $gantry->get( 'single-meta-modified-prefix' ) . ' '; ?><?php the_modified_date( $gantry->get( 'single-meta-modified-format', 'd F Y' ) ); ?></dd>

						<?php endif; ?>

						<?php /** End Modified Date **/ ?>

						<?php /** Begin Author **/ ?>
					
						<?php if( $gantry->get( 'single-meta-author-enabled', '1' ) ) : ?>

							<dd class="createdby"> 
								<?php if( $gantry->get( 'single-meta-author-prefix' ) != '' ) echo $gantry->get( 'single-meta-author-prefix' ) . ' '; ?>

								<?php if( $gantry->get( 'single-meta-author-link', '1' ) ) : ?>
									<?php the_author_posts_link(); ?>
								<?php else : ?>
									<?php the_author(); ?>
								<?php endif; ?>
							</dd>

						<?php endif; ?>

						<?php /** End Author **/ ?>

						<?php /** Begin Comments Count **/ ?>

						<?php if( $gantry->get( 'single-meta-comments-enabled', '1' ) ) : ?>

							<?php if( $gantry->get( 'single-meta-comments-link', '0' ) ) : ?>

								<dd class="comments-count"> 
									<a href="<?php comments_link(); ?>">
										<?php comments_number( _r( '0 Comments' ), _r( '1 Comment' ), _r( '% Comments' ) ); ?>
									</a>
								</dd>

							<?php else : ?>

								<dd class="comments-count"> <?php comments_number( _r( '0 Comments' ), _r( '1 Comment' ), _r( '% Comments' ) ); ?></dd>

							<?php endif; ?>

						<?php endif; ?>

						<?php /** End Comments Count **/ ?>

					</dl>
				
				<?php endif; ?>

				<?php /** End Extended Meta **/ ?>

				<?php /** Begin Featured Image **/ ?>
			
				<?php if( $gantry->get( 'single-featured-image', '1' ) && function_exists( 'the_post_thumbnail' ) && has_post_thumbnail() ) : ?>

					<div class="img-fulltext-<?php echo $gantry->get( 'thumb-position', 'left' ); ?>">
						<?php the_post_thumbnail( 'gantryThumb', array( 'class' => 'rt-image ' ) ); ?>			
					</div>
				
				<?php endif; ?>

				<?php /** End Featured Image **/ ?>
						
				<?php /** Begin Post Content **/ ?>		
						
				<div class="post-content">
						
					<?php the_content(); ?>
				
				</div>
				
				<?php wp_link_pages( 'before=<div class="pagination page-pagination">' . _r( 'Pages:' ) . '&after=</div>' ); ?>

				<?php edit_post_link( _r( 'Edit' ), '<div class="edit-link">', '</div>' ); ?>

				<?php /** Begin Tags **/ ?>
				
				<?php if( has_tag() && $gantry->get( 'single-tags', '1' ) ) : ?>
																																
					<div class="post-tags">
						<div class="rt-block">
							<div class="module-surround">
								<div class="module-content">
									<?php
									$posttags = get_the_tags();
									foreach ( $posttags as $tag ) { ?>
										<a href="<?php echo get_tag_link( $tag->term_id ); ?>" title="<?php esc_attr_e( $tag->name ); ?>" class="btn btn-primary btn-small"><?php echo $tag->name; ?></a>
									<?php }	?>
								</div>
							</div>
						</div>
					</div>

				<?php endif; ?>

				<?php /** End Tags **/ ?>
				
				<?php if( $gantry->get( 'single-footer', '1' ) ) : ?>

					<div class="post-footer">
						<small>
					
						<?php _re('This entry was posted'); ?>
						<?php /* This is commented, because it requires a little adjusting sometimes.
						You'll need to download this plugin, and follow the instructions:
						http://binarybonsai.com/archives/2004/08/17/time-since-plugin/ */
						/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
						<?php _re('on'); ?> <?php the_time('l, F jS, Y') ?> <?php _re('at'); ?> <?php the_time() ?>
						<?php _re('and is filed under'); ?> <?php the_category(', ') ?>.
						<?php _re('You can follow any responses to this entry through the'); ?> <?php post_comments_feed_link('RSS 2.0'); ?> <?php _re('feed'); ?>.

						<?php if (('open' == $post->comment_status) && ('open' == $post->ping_status)) {
						// Both Comments and Pings are open ?>
						<?php _re('You can'); ?> <a href="#respond"><?php _re('leave a response'); ?></a>, <?php _re('or'); ?> <a href="<?php trackback_url(); ?>" rel="trackback"><?php _re('trackback'); ?></a> <?php _re('from your own site.'); ?>

						<?php } elseif (!('open' == $post->comment_status) && ('open' == $post->ping_status)) {
						// Only Pings are Open ?>
						<?php _re('Responses are currently closed, but you can'); ?> <a href="<?php trackback_url(); ?> " rel="trackback"><?php _re('trackback'); ?></a> <?php _re('from your own site.'); ?>

						<?php } elseif (('open' == $post->comment_status) && !('open' == $post->ping_status)) {
						// Comments are open, Pings are not ?>
						<?php _re('You can skip to the end and leave a response. Pinging is currently not allowed.'); ?>

						<?php } elseif (!('open' == $post->comment_status) && !('open' == $post->ping_status)) {
						// Neither Comments, nor Pings are open ?>
						<?php _re('Both comments and pings are currently closed.'); ?>

						<?php } edit_post_link(_r('Edit this entry'),'','.'); ?>

						</small>
					</div>
													
				<?php endif; ?>

				<?php /** End Post Content **/ ?>

				<?php /** Begin Comments **/ ?>
					
				<?php if( comments_open() && $gantry->get( 'single-comments-form-enabled', '1' ) ) : ?>
															
					<?php echo $gantry->displayComments( true, 'standard', 'standard' ); ?>
				
				<?php endif; ?>

				<?php /** End Comments **/ ?>

			</div>
			
			<?php /** End Post **/ ?>