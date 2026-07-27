<?php
/**
 * 404 error template.
 *
 * @since 1.0.0
 * @package Web_Studio_Landing
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="main container" role="main">
    <section class="not-found">
        <h1 class="not-found__title">
            <?php esc_html_e( '404 — Страница не найдена', 'web-studio-landing' ); ?>
        </h1>
        <p class="not-found__text">
            <?php esc_html_e( 'Запрашиваемая страница не существует или была перемещена.', 'web-studio-landing' ); ?>
        </p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn--primary">
            <?php esc_html_e( 'На главную', 'web-studio-landing' ); ?>
        </a>
    </section>
</main>

<?php
get_footer();
