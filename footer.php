<?php
/**
 * Theme footer template.
 *
 * @since 1.0.0
 * @package Web_Studio_Landing
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;
?>

<?php
$web_studio_copyright = get_theme_mod( 'web_studio_footer_copyright', '' );
?>

<footer class="footer" role="contentinfo">
    <div class="footer__inner container">
        <p class="footer__copy">
            <?php if ( $web_studio_copyright ) : ?>
                <?php echo wp_kses_post( $web_studio_copyright ); ?>
            <?php else : ?>
                &copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?>
                <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
                &mdash;
                <?php esc_html_e( 'Все права защищены.', 'web-studio-landing' ); ?>
            <?php endif; ?>
        </p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
