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
if ( is_admin() )
	require_once( get_stylesheet_directory() . '/functions-admin.php' );

function pendrell_setup() {
	// Add full post format support
	global $pendrell_post_formats;
	add_theme_support( 'post-formats', $pendrell_post_formats );
	
	// Add a few additional image sizes for various purposes
	add_image_size( 'thumbnail-150', 150, 150 );
	add_image_size( 'image-navigation', 150, 80, true );
	add_image_size( 'portfolio', 300, 150, true );
	add_image_size( 'half-width', 465, 9999 );
	add_image_size( 'full-width', 960, 9999 );

	// Set the medium and large size image sizes under media settings; default to our new full width image size in media uploader
	update_option( 'medium_size_w', 624 );
	update_option( 'medium_size_h', 624 );
	update_option( 'large_size_w', 960 );
	update_option( 'large_size_h', 960 );
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
	if ( false === $post_format ) {
		if ( is_attachment() && wp_attachment_is_image() ) {
			$format = __( 'image', 'pendrell' );
		} else {
			$format = __( 'entry', 'pendrell' );
		}
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
		$utility_text = __( 'This %5$s was posted %3$s in %1$s and tagged %2$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
	} elseif ( $categories_list && ( $post_format === false ) ) {
		$utility_text = __( 'This %5$s was posted %3$s in %1$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
	} elseif ( is_attachment() && wp_attachment_is_image() && $post->post_parent ) {
		$utility_text = __( 'This %5$s was posted %3$s in %6$s.', 'pendrell' );
	} elseif ( is_page() && $post->post_parent ) {
		$utility_text = __( 'This page was posted under %6$s<span class="by-author"> by %4$s</span>.', 'pendrell' );
	} elseif ( is_page() ) {
		$utility_text = __( 'This page was posted<span class="by-author"> by %4$s</span>.', 'pendrell' );
	} else {
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

// Allow HTML in author descriptions on single user blogs
remove_filter( 'pre_user_description', 'wp_filter_kses' );

// Display EXIF data for photographs
function pendrell_image_info( $metadata = array() ) {
	if ( $metadata['image_meta'] ) {
		?><div class="image-info">
			<h2><?php _e( 'Image Info', 'pendrell' ); ?></h2>
			<div class="image-description">
			<?php if ( $metadata['height'] && $metadata['width'] ) { 
					printf( __( 'Full Size: <a href="%1$s" title="Link to full size image">%2$s &times; %3$s</a></br>', 'pendrell' ),
						esc_attr( wp_get_attachment_url() ),
						$metadata['width'],
						$metadata['height']
					);
				}
				if ( $metadata['image_meta']['created_timestamp'] ) { printf( __( 'Taken: %s<br/>', 'pendrell' ), date( get_option( 'date_format' ), $metadata['image_meta']['created_timestamp'] ) ); }
				if ( $metadata['image_meta']['camera'] ) { printf( __( 'Camera: %s</br>', 'pendrell' ), $metadata['image_meta']['camera'] ); }
				if ( $metadata['image_meta']['focal_length'] ) { printf( __( 'Focal Length: %s mm<br/>', 'pendrell' ), $metadata['image_meta']['focal_length'] ); }
				if ( $metadata['image_meta']['aperture'] ) { printf( __( 'Aperture: f/%s<br/>', 'pendrell' ), $metadata['image_meta']['aperture'] ); }
				if ( $metadata['image_meta']['shutter_speed'] ) {
					// Based on http://technology.mattrude.com/2010/07/display-exif-data-on-wordpress-gallery-post-image-2/
					$image_shutter_speed = $metadata['image_meta']['shutter_speed'];
					if ( $image_shutter_speed > 0 ) {
						if ( ( 1 / $image_shutter_speed ) > 1 ) {
							if ( ( number_format( (1 / $image_shutter_speed ), 1 ) ) == 1.3
							or number_format( ( 1 / $image_shutter_speed ), 1 ) == 1.5
							or number_format( ( 1 / $image_shutter_speed ), 1 ) == 1.6
							or number_format( ( 1 / $image_shutter_speed ), 1 ) == 2.5) {
								$pshutter = '1/' . number_format( ( 1 / $image_shutter_speed ), 1, '.', '') . ' ' . __( 'sec', 'pendrell');
							} else {
								$pshutter = '1/' . number_format( ( 1 / $image_shutter_speed ), 0, '.', '') . ' ' . __( 'sec', 'pendrell' );
							}
						} else {
							$pshutter = $image_shutter_speed . ' ' . __( 'sec', 'pendrell' );
						}
					}
			
			
			echo __( 'Shutter Speed: ', 'pendrell' ) . $pshutter . '<br/>';
				}
				if ( $metadata['image_meta']['iso'] ) { echo __( 'ISO/Film: ', 'pendrell') . $metadata['image_meta']['iso'] . '<br/>'; } ?>
			</div>
		</div>
<?php
	}
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

function pendrell_post_thumbnail( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
	// Source: http://wpengineer.com/1735/easier-better-solutions-to-get-pictures-on-your-posts/
	if ( empty( $html ) ) {
		$attachments = get_children( array(
			'post_parent'    => get_the_ID(),
			'post_type'      => 'attachment',
			'numberposts'    => 1,
			'post_status'    => 'inherit',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'menu_order ASC'
		), ARRAY_A );

		if ( $attachments ) {
			echo wp_get_attachment_image( current( array_keys( $attachments ) ), $size );
		}
		// To do: add a default thumbnail!
	} else {
		return $html;
	}
}
add_filter( 'post_thumbnail_html', 'pendrell_post_thumbnail', 11, 5 );

// Modify how many posts per page are displayed in different contexts (e.g. more portfolio items on category archives)
function pendrell_posts_per_page( $query ) {
	// Source: http://wordpress.stackexchange.com/questions/21/show-a-different-number-of-posts-per-page-depending-on-context-e-g-homepage
    if ( pendrell_is_portfolio() ) {
    	$query->set( 'posts_per_page', 12 );
    }
    if ( is_search() ) {
        $query->set( 'posts_per_page', 20 );
    }
}
add_action( 'pre_get_posts', 'pendrell_posts_per_page' );

// Allow HTML in author descriptions on single user blogs
if ( !is_multi_author() ) {
	remove_filter( 'pre_user_description', 'wp_filter_kses' );
}

// Ditch the default gallery styling, yuck
add_filter( 'use_default_gallery_style', '__return_false' );



// === DEVELOPMENT AREA === //
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

?>