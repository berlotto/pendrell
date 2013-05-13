<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo">
		<div id="widgets-footer">
			<?php if ( is_active_sidebar( 'footer-sidebar-left' ) ) : ?>
				<div id="sidebar-footer-left" class="sidebar-footer" role="complementary">
					<?php dynamic_sidebar( 'footer-sidebar-left' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'footer-sidebar-center' ) ) : ?>
				<div id="sidebar-footer-center" class="sidebar-footer" role="complementary">
					<?php dynamic_sidebar( 'footer-sidebar-center' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
			<?php if ( is_active_sidebar( 'footer-sidebar-right' ) ) : ?>
				<div id="sidebar-footer-right" class="sidebar-footer" role="complementary">
					<?php dynamic_sidebar( 'footer-sidebar-right' ); ?>
				</div><!-- #secondary -->
			<?php endif; ?>
		</div>
		<div class="site-info">
			<?php do_action( 'twentytwelve_credits' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>