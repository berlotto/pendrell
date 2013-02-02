<?php // === PENDRELL SEARCH FUNCTIONS === //

// Redirect user to single search result: http://wpglee.com/2011/04/redirect-when-search-query-only-returns-one-match/
function pendrell_search_redirect() {
    if ( is_search() && !empty( $_GET['s'] ) ) {
        global $wp_query;
        if ( $wp_query->post_count == 1 ) {
            wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
        } else {
			wp_redirect( site_url( '/search/' ) . get_search_query() );
		}
    }
}
add_action( 'template_redirect', 'pendrell_search_redirect' );
