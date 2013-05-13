<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
if ( pendrell_is_portfolio() && is_archive() ) { // Portfolio archive items 
	
	// Sourced from Hatch, probably by way of Hybrid: http://wordpress.org/extend/themes/hatch

	global $counter; $counter++; // Initialize counter and then bump it to 1 on the first pass
	
	$post_class = '';
	if ( ( $counter % 3 ) == 0 ) { $post_class .= 'divisible-by-3 '; }
	if ( ( $counter % 2 ) == 0 ) { $post_class .= 'divisible-by-2 '; }
	$post_class = trim( $post_class ); ?>
	
	<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
		<div class="entry-content">
			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" ><?php the_post_thumbnail( 'portfolio' ); ?></a>
		</div>
		<header class="entry-header">
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentytwelve' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h1>
		</header>
		<footer class="entry-meta"><?php echo get_the_date(); ?></footer>
	</article>

<?php } else { ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( is_sticky() && is_home() && ! is_paged() ) : ?>
		<div class="featured-post">
			<?php _e( 'Featured post', 'twentytwelve' ); ?>
		</div>
		<?php endif; ?>
		<header class="entry-header">
			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'twentytwelve' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			</h1>
		</header><!-- .entry-header -->

		<?php if ( is_search() ) : // Only display Excerpts for Search ?>
		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
		<?php else : ?>
		<div class="entry-content">
			<?php 
				if( !is_single() ):
					#Na listagem da capa mostra somente os Excerpts.
					the_post_thumbnail( 'frontpage-thumb' );
					the_excerpt(); 
				else:
					#Na pagina do post, mostra ele completo.
					the_content();
				endif;
			?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'twentytwelve' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<?php endif; ?>

		<footer class="entry-meta">
			<?php twentytwelve_entry_meta(); ?>
			<?php if ( is_singular() && get_the_author_meta( 'description' ) && PENDRELL_AUTHOR_BOX ) : // If a user has filled out their description show a bio on their entries. ?>
				<div class="author-info">
					<div class="author-avatar">
						<a href="<?php the_author_meta( 'user_url' ); ?>" title="<?php the_author_meta( 'display_name' ); ?>"><?php echo get_avatar( get_the_author_meta( 'user_email' ), 80 ); ?></a>
					</div><!-- .author-avatar -->
					<div class="author-description">
						<h2><?php printf( __( 'About %s', 'twentytwelve' ), get_the_author() ); ?></h2>
						<p><?php the_author_meta( 'description' ); ?></p>
						<?php if ( is_multi_author() ) : ?>
						<div class="author-link">
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
								<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'twentytwelve' ), get_the_author() ); ?>
							</a>
						</div><!-- .author-link	-->
						<?php endif; ?>
					</div><!-- .author-description -->
				</div><!-- .author-info -->
			<?php endif; ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->

<?php } ?>
