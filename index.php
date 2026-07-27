<?php
/**
 * Fallback template.
 *
 * @since 1.0.0
 * @package Web_Studio_Landing
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="main container" role="main">
    <?php
    if ( have_posts() ) :
        while ( have_posts() ) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content' ); ?>>
                <h1 class="page-content__title"><?php the_title(); ?></h1>
                <div class="page-content__body">
                    <?php the_content(); ?>
                    <?php
                    wp_link_pages( array(
                        'before' => '<nav class="page-links">' . esc_html__( 'Страницы:', 'web-studio-landing' ),
                        'after'  => '</nav>',
                    ) );
                    ?>
                </div>
            </article>
            <?php
        endwhile;
    else :
        ?>
        <p><?php esc_html_e( 'Записей не найдено.', 'web-studio-landing' ); ?></p>
    <?php endif; ?>
</main>

<?php
get_footer();
