<?php
/**
 * Template part: Hero section.
 *
 * @since 1.0.0
 * @package Web_Studio_Landing
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

$web_studio_hero_title    = get_theme_mod( 'web_studio_hero_title', esc_html__( 'Создаём сайты, которые работают', 'web-studio-landing' ) );
$web_studio_hero_subtitle = get_theme_mod( 'web_studio_hero_subtitle', esc_html__( 'Веб-студия полного цикла: от дизайна до запуска', 'web-studio-landing' ) );
$web_studio_hero_cta_text = get_theme_mod( 'web_studio_hero_cta_text', esc_html__( 'Обсудить проект', 'web-studio-landing' ) );
$web_studio_hero_cta_url  = get_theme_mod( 'web_studio_hero_cta_url', '#feedback' );
$web_studio_hero_bg       = get_theme_mod( 'web_studio_hero_bg_image', '' );
?>

<section id="hero" class="hero" aria-label="<?php esc_attr_e( 'Hero', 'web-studio-landing' ); ?>"
    <?php if ( $web_studio_hero_bg ) : ?>
        style="background-image: url('<?php echo esc_url( $web_studio_hero_bg ); ?>')"
    <?php endif; ?>
>
    <div class="hero__overlay"></div>
    <div class="hero__inner container">
        <h1 class="hero__title"><?php echo esc_html( $web_studio_hero_title ); ?></h1>
        <p class="hero__subtitle"><?php echo esc_html( $web_studio_hero_subtitle ); ?></p>
        <a href="<?php echo esc_url( $web_studio_hero_cta_url ); ?>" class="btn btn--accent hero__cta">
            <?php echo esc_html( $web_studio_hero_cta_text ); ?>
        </a>
    </div>
</section>
