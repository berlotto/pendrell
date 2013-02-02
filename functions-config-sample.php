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
include( get_stylesheet_directory() . '/functions/feed.php' );
include( get_stylesheet_directory() . '/functions/images.php' );
include( get_stylesheet_directory() . '/functions/search.php' );
include( get_stylesheet_directory() . '/functions/various.php' );

if ( is_admin() )
	require_once( get_stylesheet_directory() . '/functions/admin.php' );

// If development mode is on...
include( get_stylesheet_directory() . '/functions/dev.php' );
