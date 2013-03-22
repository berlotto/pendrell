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

// Output page-specific scripts
function pendrell_print_scripts() {
	// Capture search query for jQuery highlighter
	$query = get_search_query();
	if ( strlen($query) > 0 ) { ?>
		<script type="text/javascript">
			var pendrell_search_query  = "<?php echo $query; ?>";
		</script>
<?php }
}
add_action( 'wp_print_scripts', 'pendrell_print_scripts' );
