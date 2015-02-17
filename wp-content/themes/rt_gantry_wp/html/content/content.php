<?php
/**
 * @version   4.1.2 May 18, 2014
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2014 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
// no direct access
defined( 'ABSPATH' ) or die( 'Restricted access' );

$page_context = $this->getContext();

// Create a shortcut for params.
$category = get_the_category();
?>

		<?php /** Begin Post **/ ?>
				
		<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

			<?php /** Begin Article Title **/ ?>

			<?php if( $gantry->get( $page_context . '-post-title-enabled', '1' ) ) : ?>

				<h2>
					<?php if( $gantry->get( $page_context . '-post-title-link', '0' ) ) : ?>
						<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( get_the_title() ); ?>"><?php the_title(); ?></a>
					<?php else : ?>
						<?php the_title(); ?>
					<?php endif; ?>
				</h2>

			<?php endif; ?>

			<?php /** End Article Title **/ ?>

			<?php /** Begin Extended Meta **/ ?>

			<?php if( $gantry->get( $page_context . '-meta-author-enabled', '1' ) || $gantry->get( $page_context . '-meta-category-enabled', '0' ) || $gantry->get( $page_context . '-meta-category-parent-enabled', '0' ) || $gantry->get( $page_context . '-meta-date-enabled', '1' ) || $gantry->get( $page_context . '-meta-modified-enabled' ) || $gantry->get( $page_context . '-meta-comments-enabled', '1' ) ) : ?>
			
				<dl class="article-info">

					<?php /** Begin Parent Category **/ ?>
					
					<?php if( $gantry->get( $page_context . '-meta-category-parent-enabled', '0' ) && !empty( $category ) && $category[0]->parent != '0' ) : ?>

						<dd class="parent-category-name"> 
							<?php
								$parent_category = get_category( ( int ) $category[0]->parent );
								$title = $parent_category->cat_name;
								$link = get_category_link( $parent_category );
								$url = '<a href="' . esc_url( $link ) . '">' . $title . '</a>'; 
							?>

							<?php if( $gantry->get( $page_context . '-meta-category-parent-prefix' ) != '' ) echo $gantry->get( $page_context . '-meta-category-parent-prefix' ); ?>

							<?php if( $gantry->get( $page_context . '-meta-category-parent-link', '0' ) ) : ?>
								<?php echo $url; ?>
							<?php else : ?>
								<?php echo $title; ?>
							<?php endif; ?>
						</dd>

					<?php endif; ?>

					<?php /** End Parent Category **/ ?>

					<?php /** Begin Category **/ ?>

					<?php if( $gantry->get( $page_context . '-meta-category-enabled', '0' ) && !empty( $category ) ) : ?>

						<dd class="category-name"> 
							<?php 
								$title = $category[0]->cat_name;
								$link = get_category_link( $category[0]->cat_ID );
								$url = '<a href="' . esc_url( $link ) . '">' . $title . '</a>';
							?>

							<?php if( $gantry->get( $page_context . '-meta-category-prefix' ) != '' ) echo $gantry->get( $page_context . '-meta-category-prefix' ); ?>

							<?php if( $gantry->get( $page_context . '-meta-category-link', '0' ) ) : ?>
								<?php echo $url; ?>
							<?php else : ?>
								<?php echo $title; ?>
							<?php endif; ?>
						</dd>

					<?php endif; ?>

					<?php /** End Category **/ ?>

					<?php /** Begin Date & Time **/ ?>

					<?php if( $gantry->get( $page_context . '-meta-date-enabled', '1' ) ) : ?>

						<dd class="create"> <?php if( $gantry->get( $page_context . '-meta-date-prefix' ) != '' ) echo $gantry->get( $page_context . '-meta-date-prefix' ) . ' '; ?><?php the_time( $gantry->get( $page_context . '-meta-date-format', 'd F Y' ) ); ?></dd>

					<?php endif; ?>

					<?php /** End Date & Time **/ ?>

					<?php /** Begin Modified Date **/ ?>

					<?php if( $gantry->get( $page_context . '-meta-modified-enabled', '0' ) ) : ?>

						<dd class="modified"> <?php if( $gantry->get( $page_context . '-meta-modified-prefix' ) != '' ) echo $gantry->get( $page_context . '-meta-modified-prefix' ) . ' '; ?><?php the_modified_date( $gantry->get( $page_context . '-meta-modified-format', 'd F Y' ) ); ?></dd>

					<?php endif; ?>

					<?php /** End Modified Date **/ ?>

					<?php /** Begin Author **/ ?>
				
					<?php if( $gantry->get( $page_context . '-meta-author-enabled', '1' ) ) : ?>

						<dd class="createdby"> 
							<?php if( $gantry->get( $page_context . '-meta-author-prefix' ) != '' ) echo $gantry->get( $page_context . '-meta-author-prefix' ) . ' '; ?>

							<?php if( $gantry->get( $page_context . '-meta-author-link', '1' ) ) : ?>
								<?php the_author_posts_link(); ?>
							<?php else : ?>
								<?php the_author(); ?>
							<?php endif; ?>
						</dd>

					<?php endif; ?>

					<?php /** End Author **/ ?>

					<?php /** Begin Comments Count **/ ?>

					<?php if( $gantry->get( $page_context . '-meta-comments-enabled', '1' ) ) : ?>

						<?php if( $gantry->get( $page_context . '-meta-comments-link', '0' ) ) : ?>

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

			<?php if( function_exists( 'the_post_thumbnail' ) && has_post_thumbnail() ) : ?>

				<div class="img-intro-<?php echo $gantry->get( 'thumb-position', 'left' ); ?>">
					<?php the_post_thumbnail( 'gantryThumb', array( 'class' => 'rt-image ' ) ); ?>			
				</div>
			
			<?php endif; ?>

			<?php /** End Featured Image **/ ?>
			
			<?php /** Begin Post Content **/ ?>	

			<div class="post-content">

				<?php if( $gantry->get( $page_context . '-content', 'content' ) == 'excerpt' ) : ?>
				
					<?php the_excerpt(); ?>
									
				<?php else : ?>

					<?php the_content( false ); ?>
										
				<?php endif; ?>
			
			</div>
			
			<?php if( $gantry->get( $page_context . '-readmore-show', 'auto' ) == 'always' || ( $gantry->get( $page_context . '-readmore-show', 'auto' ) == 'auto' && ( preg_match( '/<!--more(.*?)?-->/', $post->post_content, $readmore_matches ) || $gantry->get( $page_context . '-content', 'content' ) == 'excerpt' ) ) ) : ?>
			
				<p class="readmore">																			
					<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( get_the_title() ); ?>"><?php echo ( !empty( $readmore_matches[1] ) ? trim( $readmore_matches[1] ) : $gantry->get( $page_context . '-readmore-text', 'Read more ...' ) ); ?></a>
				</p>
			
			<?php endif; ?>
			
			<?php /** End Post Content **/ ?>

		</div>

		<?php /** End Post **/ ?>

		<div class="item-separator"></div>