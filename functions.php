<?php

// Pendrell is a child theme that relies on:
// * Twenty Twelve
// * Crowdfavorite's WP-Post-Formats plugin: https://github.com/crowdfavorite/wp-post-formats
// Translation notes: anything unmodified from twentytwelve will remain in its text domain; everything new or modified is under 'pendrell'.

if ( is_readable( get_stylesheet_directory() . '/functions-config.php' ) ) {
	require_once( get_stylesheet_directory() . '/functions-config.php' );
} else {
	require_once( get_stylesheet_directory() . '/functions-config-sample.php' );
}
