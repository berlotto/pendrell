<?php // === PENDRELL POST FORMAT FUNCTIONS === //

// Get quotation metadata; assumes WP Post Formats or equivalent is in use
function get_quotation_metadata() {

	global $post;

	$source_name = get_post_meta( $post->ID, '_format_quote_source_name', true );
	$source_title = get_post_meta( $post->ID, '_format_quote_source_title', true );
	$source_date = get_post_meta( $post->ID, '_format_quote_source_date', true );
	$source_url = get_post_meta( $post->ID, '_format_quote_source_url', true );

	if ( $source_name || $source_title || $source_url ) {

		$source_data = array();

		if ( $source_name ) {
			if ( !$source_title && $source_url ) {
				$source_data[] = '<a href="' . $source_url . '">' . $source_name . '</a>';
			} else {
				$source_data[] = $source_name;
			}
		}

		if ( $source_title ) {
			if ( $source_url ) {
				$source_data[] = '<cite><a href="' . $source_url . '">' . $source_title . '</a></cite>'; 
			} else {
				$source_data[] = '<cite>' . $source_title . '</cite>';
			}
		}

		// If we only have the URL at least make it look nice
		if ( $source_url && !$source_name && !$source_title ) {
			$source_data[] = '<a href="' . $source_url . '">' . parse_url( $source_url, PHP_URL_HOST ) . '</a>';
		}

		if ( $source_date ) {
			$source_data[] = $source_date;
		}

		// Concatenate with commas
		$source = implode( ', ', $source_data );
	} else {
		$source = '';
	}

	return $source;

}
