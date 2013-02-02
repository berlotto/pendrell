<?php

// Allow HTML in author descriptions on single user blogs
remove_filter( 'pre_user_description', 'wp_filter_kses' );