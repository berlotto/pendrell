<?php // === PENDRELL CONFIGURATION === //
define( 'PENDRELL_VERSION', 0.3 );
define( 'PENDRELL_NAME', get_bloginfo( 'name' ) );
define( 'PENDRELL_DESC', get_bloginfo( 'description' ) );
define( 'PENDRELL_HOME', get_bloginfo( 'url' ) );

// Customize these values to suit your needs!
define( 'PENDRELL_AUTHOR_ID', 1 ); // The author ID of the blog owner (for use with more highly secured blogs)
define( 'PENDRELL_AUTHOR_BOX', true ); // Turn author box display on or off
define( 'PENDRELL_FONTSTACK', false ); // Choose a pre-defined font stack: sans, serif, or false (defaults back to Twenty Twelve)
define( 'PENDRELL_GOOGLE_ANALYTICS_CODE', false ); // Google Analytics code e.g. 'UA-XXXXXX-XX'; false when not in use

// Post formats; choose from array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'audio', 'chat', 'video' );
$pendrell_post_formats = array( 'quote', 'status', 'video', 'image', 'gallery' );

// Portfolio categories; add or remove any slug to this array to enable matching categories with portfolio functionality
$pendrell_portfolio_cats = array( 'creative', 'design', 'photography', 'portfolio' );

// Admin
if ( is_admin() ) {
	define( 'PENDRELL_FONTSTACK_EDITOR', 'Georgia, "Palatino Linotype", Palatino, "URW Palladio L", "Book Antiqua", "Times New Roman", serif;' ); // Admin HTML editor font stack.
	define( 'PENDRELL_FONTSIZE_EDITOR', '16px' ); // Admin HTML editor font size.
}

?>