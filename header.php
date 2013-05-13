<!DOCTYPE html>
<!--[if IE 7 | IE 8]>
<html class="ie" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width" />
<title><?php wp_title( '-' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<link href='http://fonts.googleapis.com/css?family=Pontano+Sans|Raleway:300' rel='stylesheet' type='text/css'>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<nav id="site-navigation" class="main-navigation top-navigation" role="navigation">
		<h3 class="menu-toggle"><?php _e( 'Top menu', 'topmenu' ); ?></h3>
		<div class="skip-link assistive-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentytwelve' ); ?>"><?php _e( 'Skip to content', 'twentytwelve' ); ?></a></div>
		<?php wp_nav_menu( array( 'theme_location' => 'topmenu', 'menu_class' => 'nav-menu-top' ) ); ?>
	</nav><!-- #site-navigation -->
	<header id="masthead" class="site-header" role="banner">
		<div style="position: relative;">
		<?php $header_image = get_header_image();
		if ( ! empty( $header_image ) ) : ?>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></a>
		<?php endif; ?>
			<hgroup<?php if ( ! empty( $header_image ) ) : ?> class="with-header-image"<?php endif; ?>>
				<?php if ( !is_multi_author() && 1==0 ) : 
				$avatar_url = get_the_author_meta( 'user_url', PENDRELL_AUTHOR_ID );
				if ( empty( $avatar_url ) )
					$avatar_url = esc_url( home_url( '/' ) ); 
				$avatar_title = get_the_author_meta( 'display_name', PENDRELL_AUTHOR_ID ); ?>
					<a href="<?php echo $avatar_url; ?>" title="<?php echo $avatar_title; ?>" alt="<?php echo $avatar_title; ?>"><?php echo get_avatar( get_the_author_meta( 'user_email', PENDRELL_AUTHOR_ID ), 80 ); ?></a>
				<?php endif; ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
			</hgroup>
		</div>

	</header><!-- #masthead -->
	<nav id="site-navigation" class="main-navigation" role="navigation">
		<h3 class="menu-toggle"><?php _e( 'Menu', 'twentytwelve' ); ?></h3>
		<div class="skip-link assistive-text"><a href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentytwelve' ); ?>"><?php _e( 'Skip to content', 'twentytwelve' ); ?></a></div>
		<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu' ) ); ?>
	</nav><!-- #site-navigation -->

	<div id="main" class="wrapper">