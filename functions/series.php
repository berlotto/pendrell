<?php
// Post series

function pendrell_series_init() {
	// Add new "Series" taxonomy to Posts
	register_taxonomy('series', 'post', array(
		// Hierarchical taxonomy (like categories)
		'hierarchical' => false,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels' => array(
			'name' => _x( 'Series', 'taxonomy general name' ),
			'singular_name' => _x( 'Series', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Series' ),
			'all_items' => __( 'All Series' ),
			'parent_item' => __( 'Parent Series' ),
			'parent_item_colon' => __( 'Parent Series:' ),
			'edit_item' => __( 'Edit Series' ),
			'update_item' => __( 'Update Series' ),
			'add_new_item' => __( 'Add New Series' ),
			'new_item_name' => __( 'New Series Name' ),
			'menu_name' => __( 'Series' ),
		),
		// Control the slugs used for this taxonomy
		'rewrite' => array(
			'slug' => 'series', // This controls the base slug that will display before each term
			'with_front' => false, // Don't display the category base
			'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
		),
	));
}
add_action( 'init', 'pendrell_series_init', 0 );

function pendrell_series_list() {

	global $post;

	if ( is_single() ) {
		$series_terms = wp_get_post_terms( $post->ID, 'series' );

		if ( $series_terms ) {
			foreach ( $series_terms as $series_term ) {
				$series_query = new WP_Query( array(
					'order' => 'ASC',
					'tax_query' => array(
						array(
							'taxonomy' => 'series',
							'field' => 'slug',
							'terms' => $series_term->slug
						)
					)
				) );

				// Display the list of posts in the series
				if ( $series_query->have_posts() ): ?>
				<div class="entry-meta-series">
					<h2><?php printf( __( 'This post is a part of the &#8216;<a href="%1$s">%2$s</a>&#8217; series:', 'pendrell' ),
						get_term_link( $series_term->slug, 'series' ),
						$series_term->name );
					?></h2>
					<ol>
					<?php while ( $series_query->have_posts() ) : $series_query->the_post(); ?>
						<li><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></li>
					<?php endwhile; wp_reset_postdata(); ?>
					</ol><!--// end .postlist -->
				</div>
				<?php endif;
			}
		}
	}
}
add_action( 'pre_entry_meta', 'pendrell_series_list' );

// Test to see whether the post is part of a series
function pendrell_in_series() {
	if ( taxonomy_exists( 'series' ) && has_term( '', 'series' ) ) {
		return true;
	} else {
		return false;
	}
}

function pendrell_series_get_posts( $query ) {
	if( is_tax ( 'series') ) {
		$query->set( 'order', 'ASC' );
	}
	return $query;
}
add_filter( 'pre_get_posts', 'pendrell_series_get_posts' );
