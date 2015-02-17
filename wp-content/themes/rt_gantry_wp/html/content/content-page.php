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

			<?php /** Begin Page **/ ?>
					
			<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">

				<?php /** Begin Article Title **/ ?>

				<?php if( $gantry->get( 'page-title-enabled', '1' ) ) : ?>

					<h2>
						<?php if( $gantry->get( 'page-title-link', '0' ) ) : ?>
							<a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( get_the_title() ); ?>"><?php the_title(); ?></a>
						<?php else : ?>
							<?php the_title(); ?>
						<?php endif; ?>
					</h2>

				<?php endif; ?>

				<?php /** End Article Title **/ ?>

				<?php /** Begin Extended Meta **/ ?>

				<?php if( $gantry->get( 'page-meta-author-enabled', '0' ) || $gantry->get( 'page-meta-date-enabled', '0' ) || $gantry->get( 'page-meta-modified-enabled', '0' ) || $gantry->get( 'page-meta-comments-enabled', '0' ) ) : ?>
				
					<dl class="article-info">

						<?php /** Begin Date & Time **/ ?>

						<?php if( $gantry->get( 'page-meta-date-enabled', '0' ) ) : ?>

							<dd class="create"> <?php if( $gantry->get( 'page-meta-date-prefix' ) != '' ) echo $gantry->get( 'page-meta-date-prefix' ) . ' '; ?><?php the_time( $gantry->get( 'page-meta-date-format', 'd F Y' ) ); ?></dd>

						<?php endif; ?>

						<?php /** End Date & Time **/ ?>

						<?php /** Begin Modified Date **/ ?>

						<?php if( $gantry->get( 'page-meta-modified-enabled', '0' ) ) : ?>

							<dd class="modified"> <?php if( $gantry->get( 'page-meta-modified-prefix' ) != '' ) echo $gantry->get( 'page-meta-modified-prefix' ) . ' '; ?><?php the_modified_date( $gantry->get( 'page-meta-modified-format', 'd F Y' ) ); ?></dd>

						<?php endif; ?>

						<?php /** End Modified Date **/ ?>

						<?php /** Begin Author **/ ?>
					
						<?php if( $gantry->get( 'page-meta-author-enabled', '0' ) ) : ?>

							<dd class="createdby"> 
								<?php if( $gantry->get( 'page-meta-author-prefix' ) != '' ) echo $gantry->get( 'page-meta-author-prefix' ) . ' '; ?>

								<?php if( $gantry->get( 'page-meta-author-link', '1' ) ) : ?>
									<?php the_author_posts_link(); ?>
								<?php else : ?>
									<?php the_author(); ?>
								<?php endif; ?>
							</dd>

						<?php endif; ?>

						<?php /** End Author **/ ?>

						<?php /** Begin Comments Count **/ ?>

						<?php if( $gantry->get( 'page-meta-comments-enabled', '0' ) ) : ?>

							<?php if( $gantry->get( 'page-meta-comments-link', '0' ) ) : ?>

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
			
				<?php if( $gantry->get( 'page-featured-image', '0' ) && function_exists( 'the_post_thumbnail' ) && has_post_thumbnail() ) : ?>

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

				<?php /** End Post Content **/ ?>

				<?php /** Begin Comments **/ ?>
					
				<?php if( comments_open() && $gantry->get( 'page-comments-form-enabled', '0' ) ) : ?>
															
					<?php echo $gantry->displayComments( true, 'standard', 'standard' ); ?>
				
				<?php endif; ?>

				<?php /** End Comments **/ ?>

			</div>
			
			<?php /** End Page **/ ?>