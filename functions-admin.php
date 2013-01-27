<?php // === PENDRELL ADMIN FUNCTIONS === //

// HTML editor fontstack and fontsize hack; source: http://justintadlock.com/archives/2011/07/06/fixing-wordpress-3-2s-html-editor-font
function pendrell_html_editor_fontstack() {
?>
				<style type="text/css">#wp-content-editor-container textarea#content, textarea#wp_mce_fullscreen { font-size: <?php echo PENDRELL_FONTSIZE_EDITOR; ?>; font-family: <?php echo PENDRELL_FONTSTACK_EDITOR; ?> }</style>
<?php }
add_action( 'admin_head-post.php', 'pendrell_html_editor_fontstack' );
add_action( 'admin_head-post-new.php', 'pendrell_html_editor_fontstack' );

// Add custom images sizes to the media uploader dropdown
function pendrell_image_sizes( $sizes ) {
	// Cheap hack to keep "full size" at the bottom of the dropdown: unset and reset it after adding our custom sizes
	unset ( $sizes['medium'] );
	unset ( $sizes['large'] );
	unset ( $sizes['full'] );
	$sizes['third-width'] = __( 'Third Width', 'pendrell');
	$sizes['third-width-cropped'] = __( 'Third Width Cropped', 'pendrell');
	$sizes['medium'] = __( 'Medium Size', 'pendrell');
	$sizes['half-width'] = __( 'Half Width', 'pendrell');
	$sizes['half-width-cropped'] = __( 'Half Width Cropped', 'pendrell');
	$sizes['large'] = __( 'Large Size', 'pendrell');
	$sizes['full-width'] = __( 'Full Width', 'pendrell');
	$sizes['full-width-cropped'] = __( 'Full Width Cropped', 'pendrell');
	$sizes['full'] = __( 'Full Size', 'pendrell');
	return $sizes;
}
add_filter( 'image_size_names_choose', 'pendrell_image_sizes' );

?>