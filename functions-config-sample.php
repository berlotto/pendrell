<?php // === PENDRELL CONFIGURATION === //
define( 'PENDRELL_VERSION', 0.21 );
define( 'PENDRELL_NAME', get_bloginfo( 'name' ) );
define( 'PENDRELL_DESC', get_bloginfo( 'description' ) );
define( 'PENDRELL_HOME', get_bloginfo( 'url' ) );

// === CUSTOMIZE THESE VALUES TO SUIT YOUR NEEDS === //

// The author ID of the blog owner (for use with more highly secured blogs)
define( 'PENDRELL_AUTHOR_ID', 1 );

// Turn author box display on or off
define( 'PENDRELL_AUTHOR_BOX', true );

// Choose a pre-defined font stack: sans, serif, or false (defaults back to Twenty Twelve)
define( 'PENDRELL_FONTSTACK', false );

// Google Analytics code e.g. 'UA-XXXXXX-XX'; false when not in use
define( 'PENDRELL_GOOGLE_ANALYTICS_CODE', false );

// Shadow categories: add category numbers to this string in the format '-1,-2' to hide them from the front page
define( 'PENDRELL_SHADOW_CATS', false );

// Post series functionality
define( 'PENDRELL_SERIES', true );

// Post formats; choose from array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'audio', 'chat', 'video' );
$pendrell_post_formats = array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'audio', 'chat', 'video' );

// Portfolio categories; add or remove any slug to this array to enable matching categories with portfolio functionality
$pendrell_portfolio_cats = array( 'creative', 'design', 'photography', 'portfolio' );

// === ADMIN INTERFACE CUSTOMIZATIONS === //
if ( is_admin() ) {
	define( 'PENDRELL_FONTSTACK_EDITOR', 'Georgia, "Palatino Linotype", Palatino, "URW Palladio L", "Book Antiqua", "Times New Roman", serif;' ); // Admin HTML editor font stack.
	define( 'PENDRELL_FONTSIZE_EDITOR', '16px' ); // Admin HTML editor font size.
}

// === PENDRELL MODULES === //

// Easily disable any of this stuff by commenting it out.
include( get_stylesheet_directory() . '/functions/general.php' );
include( get_stylesheet_directory() . '/functions/feed.php' );
include( get_stylesheet_directory() . '/functions/images.php' );
include( get_stylesheet_directory() . '/functions/search.php' );
include( get_stylesheet_directory() . '/functions/taxonomies.php' );
include( get_stylesheet_directory() . '/functions/various.php' );

if ( PENDRELL_SERIES )
	include( get_stylesheet_directory() . '/functions/series.php' );

if ( is_admin() )
	include( get_stylesheet_directory() . '/functions/admin.php' );

// If development mode is on...
include( get_stylesheet_directory() . '/functions/dev.php' );

// Theme setup; includes some image size definitions and other things that belong here in the config file
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
