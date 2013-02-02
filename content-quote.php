<?php
/**
 * The template for displaying posts in the Quote post format
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

// Get quotation metadata; assumes WP Post Formats or equivalent is in use
$source_name = get_post_meta( $post->ID, '_format_quote_source_name', true );
$source_title = get_post_meta( $post->ID, '_format_quote_source_title', true );
$source_date = get_post_meta( $post->ID, '_format_quote_source_date', true );
$source_url = get_post_meta( $post->ID, '_format_quote_source_url', true );

if ( $source_name || $source_title || $source_url ) {

	$source_data = array();

	if ( $source_name ) {
		if ( !$source_title && $source_url ) {
			$source_data[] = '<a href="' . $source_url . '">' . $source_name . '</a>';
		} else {
			$source_data[] = $source_name;
		}
	}

	if ( $source_title ) {
		if ( $source_url ) {
			$source_data[] = '<cite><a href="' . $source_url . '">' . $source_title . '</a></cite>'; 
		} else {
			$source_data[] = '<cite>' . $source_title . '</cite>';
		}
	}

	// If we only have the URL at least make it look nice
	if ( $source_url && !$source_name && !$source_title ) {
		$source_data[] = '<a href="' . $source_url . '">' . parse_url( $source_url, PHP_URL_HOST ) . '</a>';
	}

	if ( $source_date ) {
		$source_data[] = $source_date;
	}

	// Concatenate with commas
	$source = implode( ', ', $source_data );
} else {
	$source = '';
} ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="entry-content">
			<article>
				<blockquote<?php if ( !empty( $source_url ) ) { ?> cite="<?php echo $source_url; ?>"<?php } ?>>
					<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentytwelve' ) ); ?>
				</blockquote>
				<?php if ( !empty( $source ) ) { ?><footer>
					<?php echo $source; ?>
				</footer><?php } ?>
			</article>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php twentytwelve_entry_meta(); ?>
		</footer><!-- .entry-meta -->
	</article><!-- #post -->
