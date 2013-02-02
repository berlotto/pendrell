<?php // === PENDRELL DEVELOPMENT AREA === //

// Everything below here might or might not be working... when it has been developed and tested move it above this line

// 404 (TO DO); some suggestions: http://www.alistapart.com/articles/perfect404/ http://justintadlock.com/archives/2009/05/13/customize-your-404-page-from-the-wordpress-admin
function pendrell_404() {
	?><h2><?php _e( 'Nothing found', 'pendrell' ); ?></h2>
	
<?php // Prefill the search form with a half-decent guess.
	$search_term = esc_url( $_SERVER['REQUEST_URI'] );
	pendrell_search_form( $search_term ); 
}

// Smarter search form
function pendrell_search_form( $search_term = '' ) {
	global $search_num;
	++$search_num;
	?>
				<form id="search-form<?php if ( $search_num ) echo "-{$search_num}"; ?>" method="get" action="<?php echo trailingslashit( home_url() ); ?>">
					<div>
						<input type="search" id="search-text<?php if ( $search_num ) echo "-{$search_num}"; ?>" class="search-field" name="s" value="<?php 
							if ( is_search() ) {
								the_search_query();
							} elseif ( !empty( $search_term) ) {
								echo $search_term;
							} else {
								_e( 'Search for&hellip;', 'pendrell' ); ?>" onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;<?php
							} ?>" />
						<input type="submit" id="search-submit<?php if ( $search_num ) echo "-{$search_num}"; ?>" class="search-submit button" value="<?php _e( 'Go!', 'pendrell' ); ?>" />
					</div>
				</form>
<?php 
}

// Excerpt functions from Twentyeleven, slightly modified
function pendrell_continue_reading_link() {
	return ' <a href="'. esc_url( get_permalink() ) . '">' . __( 'Continue reading&nbsp;&rarr;', 'plasticity' ) . '</a>';
}
add_filter( 'the_content_more_link', 'pendrell_continue_reading_link');
function pendrell_auto_excerpt_more( $more ) {
	return '&hellip;' . pendrell_continue_reading_link();
}
add_filter( 'excerpt_more', 'pendrell_auto_excerpt_more' );
function pendrell_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= pendrell_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'pendrell_custom_excerpt_more' );

// Custom excerpt length; source: http://digwp.com/2010/03/wordpress-functions-php-template-custom-functions/
function pendrell_excerpt_length( $length ) {
	return 48;
}
add_filter( 'excerpt_length', 'pendrell_excerpt_length' );