<?php
/**
 * Template part: Portfolio section.
 *
 * @since 1.0.0
 * @package Web_Studio_Landing
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

$web_studio_portfolio_query = new WP_Query(
    array(
		'post_type'      => 'portfolio',
		'posts_per_page' => 6,
		'orderby'        => 'date',
		'order'          => 'DESC',
    )
);
?>

<section id="portfolio" class="portfolio" aria-label="<?php esc_attr_e( 'Портфолио', 'web-studio-landing' ); ?>">
    <div class="container">
        <h2 class="section-title">
            <?php echo esc_html( get_theme_mod( 'web_studio_portfolio_heading', esc_html__( 'Портфолио', 'web-studio-landing' ) ) ); ?>
        </h2>

        <?php if ( $web_studio_portfolio_query->have_posts() ) : ?>
            <div class="portfolio__grid">
                <?php
                while ( $web_studio_portfolio_query->have_posts() ) :
                    $web_studio_portfolio_query->the_post();
                    $web_studio_categories = get_the_terms( get_the_ID(), 'portfolio_category' );
                    ?>
                    <article class="portfolio__card">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a href="<?php echo esc_url( get_permalink() ); ?>" class="portfolio__image" aria-hidden="true" tabindex="-1">
                                <?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo esc_url( get_permalink() ); ?>" class="portfolio__info" aria-label="
                        <?php
                        /* translators: %s: project title. */
                        echo esc_attr( sprintf( __( 'View project: %s', 'web-studio-landing' ), get_the_title() ) );
                        ?>
                        ">
                            <h3 class="portfolio__name"><?php the_title(); ?></h3>
                            <?php if ( $web_studio_categories && ! is_wp_error( $web_studio_categories ) ) : ?>
                                <p class="portfolio__category">
                                    <?php echo esc_html( $web_studio_categories[0]->name ); ?>
                                </p>
                            <?php endif; ?>
                        </a>
                    </article>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        <?php else : ?>
            <p class="portfolio__empty">
                <?php esc_html_e( 'Проекты скоро появятся. Загляните позже!', 'web-studio-landing' ); ?>
            </p>
        <?php endif; ?>
    </div>
</section>
