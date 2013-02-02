<?php // === PENDRELL GENERAL FUNCTIONS === //

// Pendrell is a child theme that relies on:
// * Twenty Twelve
// * Crowdfavorite's WP-Post-Formats plugin: https://github.com/crowdfavorite/wp-post-formats
// Translation notes: anything unmodified from twentytwelve will remain in its text domain; everything new or modified is under 'pendrell'.

// Include theme configuration file, admin functions (only when logged into the dashboard), and call theme setup function
if ( is_readable( get_stylesheet_directory() . '/functions-config.php' ) ) {
	require_once( get_stylesheet_directory() . '/functions-config.php' );	
} else {
	require_once( get_stylesheet_directory() . '/functions-config-sample.php' );
}

function pendrell_setup() {
	// Add full post format support
	global $pendrell_post_formats;
	add_theme_support( 'post-formats', $pendrell_post_formats );
	
	// Add a few additional image sizes for various purposes
	add_image_size( 'thumbnail-150', 150, 150 );
	add_image_size( 'image-navigation', 150, 80, true );
	add_image_size( 'portfolio', 300, 150, true );
	add_image_size( 'third-width', 300, 9999 );
	add_image_size( 'third-width-cropped', 300, 300, true );
	add_image_size( 'half-width', 465, 9999 );
	add_image_size( 'half-width-cropped', 465, 465, true );
	add_image_size( 'full-width', 960, 9999 );
	add_image_size( 'full-width-cropped', 960, 960, true );

	// Set the medium and large size image sizes under media settings; default to our new full width image size in media uploader
	update_option( 'medium_size_w', 624 );
	update_option( 'medium_size_h', 9999 );
	update_option( 'large_size_w', 960 );
	update_option( 'large_size_h', 9999 );
	update_option( 'image_default_size', 'full-width' );

	// $content_width limits the size of the largest image size available via the media uploader
	global $content_width;
	$content_width = 960;
}
add_action( 'after_setup_theme', 'pendrell_setup', 11 );

// Head cleaner: removes useless fluff, Windows Live Writer support, version info, pointless relational links
function pendrell_init() {
	if ( !is_admin() ) {
		remove_action( 'wp_head', 'rsd_link' );
		remove_action( 'wp_head', 'wlwmanifest_link' );
		remove_action( 'wp_head', 'wp_generator' );
		remove_action( 'wp_head', 'start_post_rel_link' );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link' );
	}
}
add_action( 'init', 'pendrell_init' );

// Enqueue scripts
function pendrell_enqueue_scripts() {
	if ( !is_admin() ) { // http://www.ericmmartin.com/5-tips-for-using-jquery-with-wordpress/
		wp_enqueue_script( 'pendrell-functions', get_stylesheet_directory_uri() . '/functions.js', array( 'jquery' ), '0.1', true );
	}
}
add_action( 'wp_enqueue_scripts', 'pendrell_enqueue_scripts' );

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

// Dynamic page titles; hooks into wp_title to improve search engine ranking without making a mess
function pendrell_wp_title( $title, $sep = '-', $seplocation = 'right' ) {

	// Flush out whatever came in; let's do it from scratch
	$title = '';

	// Default seperator and spacing
	if ( trim( $sep ) == '' )
		$sep = '-';
	$sep = ' ' . $sep . ' ';

	// Call up page number; show in page title if greater than 1
	$page_num = '';
	if ( is_paged() ) {
		global $page, $paged;
		if ( $paged >= 2 || $page >= 2 )
			$page_num = $sep . sprintf( __( 'Page %d', 'pendrell' ), max( $paged, $page ) );
	}

	if ( is_search() ) {
		if ( trim( get_search_query() ) == '' )
			$title = __( 'No search query entered', 'pendrell' ) . $sep . PENDRELL_NAME;
		else
			$title = sprintf( __( 'Search results for &#8216;%s&#8217;', 'pendrell' ), trim( get_search_query() ) ) . $sep . PENDRELL_NAME . $page_num;
	}

	if ( is_404() )
		$title = __( 'Page not found', 'pendrell' ) . $sep . PENDRELL_NAME;

	if ( is_feed() )
		$title = single_post_title( '', false ) . $sep . PENDRELL_NAME;

	if ( is_front_page() || is_home() ) {
		$title = PENDRELL_NAME;
		if ( PENDRELL_DESC )
			$title .= $sep . PENDRELL_DESC;
	} 

	// Archives; some guidance from Hybrid on times and dates
	if ( is_archive() ) {
		if ( is_author() )
			$title = sprintf( __( 'Posts by %s', 'pendrell' ), get_the_author_meta( 'display_name', get_query_var( 'author' ) ) );
		elseif ( is_category() )
			$title = sprintf( __( '%s category archive', 'pendrell' ), single_term_title( '', false ) );
		elseif ( is_tag() )
			$title = sprintf( __( '%s tag archive', 'pendrell' ), single_term_title( '', false ) );		
		elseif ( is_post_type_archive() )
			$title = sprintf( __( '%s archive', 'pendrell' ), post_type_archive_title( '', false ) );
		elseif ( is_tax() )
			$title = sprintf( __( '%s archive', 'pendrell' ), single_term_title( '', false ) );
		elseif ( is_date() ) {
			if ( get_query_var( 'second' ) || get_query_var( 'minute' ) || get_query_var( 'hour' ) )
				$title = sprintf( __( 'Archive for %s', 'pendrell' ), get_the_time( __( 'g:i a', 'pendrell' ) ) );
			elseif ( is_day() )
				$title = sprintf( __( '%s daily archive', 'pendrell' ), get_the_date() );
			elseif ( get_query_var( 'w' ) )
				$title = sprintf( __( 'Archive for week %1$s of %2$s', 'pendrell' ), get_the_time( __( 'W', 'pendrell' ) ), get_the_time( __( 'Y', 'pendrell' ) ) );
			elseif ( is_month() )
				$title = sprintf( __( '%s monthly archive', 'pendrell' ), get_the_date( _x( 'F Y', 'monthly archives date format', 'pendrell' ) ) );
			elseif ( is_year() )
				$title = sprintf( __( '%s yearly archive', 'pendrell' ), get_the_date( _x( 'Y', 'yearly archives date format', 'pendrell' ) ) );
			else
				$title = get_the_date();
			}
		$title .= $sep . PENDRELL_NAME;
	}

	// Single posts, pages, and attachments
	if ( is_singular() ) {
		if ( is_attachment() )
			$title = single_post_title( '', false );
		elseif ( is_page() || is_single() )
			$title = single_post_title( '', false );
		$title .= $sep . PENDRELL_NAME;
	}

	return esc_html( strip_tags( stripslashes( $title . $page_num ) ) );
}
// Ditch Twenty Twelve's default title filter; there's no need for it
remove_filter( 'wp_title', 'twentytwelve_wp_title', 10, 2 );
// Lower priority than the parent theme function; this way it runs later and titles aren't doubled up
add_filter( 'wp_title', 'pendrell_wp_title', 11, 3 );

// Output a human readable date wrapped in an HTML5 time tag
function pendrell_date( $date ) {
	if ( is_archive() && !pendrell_is_portfolio() ) {
		return $date;
	} else {
		if ( ( current_time( 'timestamp' ) - get_the_time('U') ) < 86400 )
			$pendrell_time = human_time_diff( get_the_time('U'), current_time( 'timestamp' ) ) . ' ago';
		else
			$pendrell_time = get_the_time( 'M j, Y, g:i a', '', '' );
		return '<time datetime="' . get_the_time('c') . '" pubdate>' . $pendrell_time . '</time>';		
	}
}
add_filter( 'get_the_date', 'pendrell_date' );

function twentytwelve_entry_meta() {

	global $post;

	$categories_list = get_the_category_list( __( ', ', 'twentytwelve' ) );

	$tag_list = get_the_tag_list( '', __( ', ', 'twentytwelve' ) );

	$date = sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
		esc_url( get_permalink() ),
		esc_attr( get_the_time() ),
		get_the_date()
	);

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'twentytwelve' ), get_the_author() ) ),
		get_the_author()
	);
	
	$post_format = get_post_format();
	if ( $post_format === false ) {
		if ( is_attachment() && wp_attachment_is_image() ) {
			$format = __( 'image', 'pendrell' );
		} else {
			$format = __( 'entry', 'pendrell' );
		}
	} elseif ( $post_format === 'quote' ) {
		// Formality, please!
		$format = sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
			esc_url( get_post_format_link( $post_format ) ),
			__( 'Quotation archive', 'pendrell' ),
			__( 'quotation', 'pendrell' )
		);
	} else {
		$format = sprintf( '<a href="%1$s" title="%2$s">%3$s</a>',
			esc_url( get_post_format_link( $post_format ) ),
			esc_attr( sprintf( __( '%s archive', 'pendrell' ), get_post_format_string( $post_format ) ) ),
			esc_attr( strtolower( get_post_format_string( $post_format ) ) )
		);
	}
	
	$parent = '';
	if ( ( is_attachment() && wp_attachment_is_image() && $post->post_parent ) || ( is_page() && $post->post_parent ) ) {
		if ( is_attachment() && wp_attachment_is_image() && $post->post_parent ) {
			$parent_rel = 'gallery';
		} elseif ( is_page() && $post->post_parent ) {
			$parent_rel = 'parent';
		}
		$parent = sprintf( __( '<a href="%1$s" title="Return to %2$s" rel="%3$s">%4$s</a>', 'pendrell' ),
			esc_url( get_permalink( $post->post_parent ) ),
			esc_attr( strip_tags( get_the_title( $post->post_parent ) ) ),
			$parent_rel,
			get_the_title( $post->post_parent )
		);
	}

	// Translators: 1 is category, 2 is tag, 3 is the date, 4 is the author's name, 5 is post format, and 6 is post parent.
	if ( $tag_list && ( $post_format === false ) ) {
		// Posts with tags and categories
		$utility_text = __( 'This %5$s was posted %3$s in %1$s and tagged %2$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
	} elseif ( $categories_list && ( $post_format === false ) ) {
		// Posts with no tags
		$utility_text = __( 'This %5$s was posted %3$s in %1$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
	} elseif ( is_attachment() && wp_attachment_is_image() && $post->post_parent ) {
		// Images with a parent post
		$utility_text = __( 'This %5$s was posted %3$s in %6$s.', 'pendrell' );
	} elseif ( is_page() && $post->post_parent ) {
		// Pages with a parent (sub-pages)
		$utility_text = __( 'This page was posted under %6$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
	} elseif ( is_page() ) {
		// Pages
		$utility_text = __( 'This page was posted<span class="by-author"> by %4$s</span>.', 'pendrell' );
	} else {
		// Post formats
		$utility_text = __( 'This %5$s was posted %3$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
	}

	printf(
		$utility_text,
		$categories_list,
		$tag_list,
		$date,
		$author,
		$format,
		$parent
	);

	?><div class="entry-meta-buttons"><?php
	
	edit_post_link( __( 'Edit', 'twentytwelve' ), ' <span class="edit-link button">', '</span>' );
	
	if ( comments_open() && !is_singular() ) { ?>
		<span class="leave-reply button"><?php comments_popup_link( __( 'Respond', 'pendrell' ), __( '1 Response', 'pendrell' ), __( '% Responses', 'pendrell' ) );
		?></span><?php 
	}

	?></div><?php

}

// Footer credits
function pendrell_credits() {
	printf( __( '<a href="%1$s" title="%2$s" rel="generator">Powered by WordPress</a> and themed with <a href="%3$s" title="%4$s">Pendrell %5$s</a>.', 'pendrell' ),
		esc_url( __( 'http://wordpress.org/', 'twentytwelve' ) ),
		esc_attr( __( 'Semantic Personal Publishing Platform', 'twentytwelve' ) ),
		esc_url( __( 'http://github.com/Synapticism/pendrell', 'pendrell' ) ),
		esc_attr( __( 'Pendrell: Twenty Twelve Child Theme by Alexander Synaptic', 'pendrell' ) ),
		PENDRELL_VERSION
	);
}
add_action( 'twentytwelve_credits', 'pendrell_credits' );

// Google Analytics code
function pendrell_analytics() {
	if ( PENDRELL_GOOGLE_ANALYTICS_CODE ) { ?>
				<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?php echo PENDRELL_GOOGLE_ANALYTICS_CODE; ?>']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script><?php
	}
}
add_action( 'wp_footer', 'pendrell_analytics' );

// Body class filter
function pendrell_body_class( $classes ) {
	$classes[] = PENDRELL_FONTSTACK;
	if ( pendrell_is_portfolio() ) {
		$classes[] = 'full-width portfolio';
	}
	return $classes;
}
add_filter( 'body_class', 'pendrell_body_class' );

// Test to see whether we are viewing a portfolio post or category archive
function pendrell_is_portfolio() {
	global $pendrell_portfolio_cats;
	if ( is_category( $pendrell_portfolio_cats ) || ( is_singular() && in_category( $pendrell_portfolio_cats ) ) ) {
		return true;
	} else {
		return false;
	}
}

function pendrell_pre_get_posts( $query ) {
	// Modify how many posts per page are displayed in different contexts (e.g. more portfolio items on category archives)
	// Source: http://wordpress.stackexchange.com/questions/21/show-a-different-number-of-posts-per-page-depending-on-context-e-g-homepage
    if ( pendrell_is_portfolio() ) {
    	$query->set( 'posts_per_page', 24 );
    }
    if ( is_search() ) {
        $query->set( 'posts_per_page', 20 );
    }
    if ( is_front_page() && PENDRELL_SHADOW_CATS ) {
    	$query->set( 'cat', PENDRELL_SHADOW_CATS );
	}
}
add_action( 'pre_get_posts', 'pendrell_pre_get_posts' );

// Allow HTML in author descriptions on single user blogs
if ( !is_multi_author() ) {
	remove_filter( 'pre_user_description', 'wp_filter_kses' );
}

// Ditch the default gallery styling, yuck
add_filter( 'use_default_gallery_style', '__return_false' );
