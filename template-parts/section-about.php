<?php
/**
 * Template part: About section.
 *
 * @since 1.0.0
 * @package Web_Studio_Landing
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

$web_studio_about_heading = get_theme_mod( 'web_studio_about_heading', esc_html__( 'О студии', 'web-studio-landing' ) );
$web_studio_about_content = get_theme_mod( 'web_studio_about_content', '' );
$web_studio_about_image   = get_theme_mod( 'web_studio_about_image', '' );

$web_studio_features = array(
    array(
        'icon'  => 'code',
        'title' => esc_html__( 'Разработка', 'web-studio-landing' ),
        'desc'  => esc_html__( 'Чистый код, современные технологии, высокая производительность.', 'web-studio-landing' ),
    ),
    array(
        'icon'  => 'design',
        'title' => esc_html__( 'Дизайн', 'web-studio-landing' ),
        'desc'  => esc_html__( 'Уникальный дизайн, ориентированный на пользователя и задачи бизнеса.', 'web-studio-landing' ),
    ),
    array(
        'icon'  => 'support',
        'title' => esc_html__( 'Поддержка', 'web-studio-landing' ),
        'desc'  => esc_html__( 'Техническая поддержка и развитие проекта после запуска.', 'web-studio-landing' ),
    ),
);
?>

<section id="about" class="about" aria-label="<?php esc_attr_e( 'О студии', 'web-studio-landing' ); ?>">
    <div class="container">
        <h2 class="section-title"><?php echo esc_html( $web_studio_about_heading ); ?></h2>

        <div class="about__inner">
            <div class="about__text">
                <?php if ( $web_studio_about_content ) : ?>
                    <?php echo wp_kses_post( wpautop( $web_studio_about_content ) ); ?>
                <?php else : ?>
                    <p><?php esc_html_e( 'Мы — команда профессионалов, создающая современные веб-решения для бизнеса. Наш подход основан на глубоком понимании задач клиента и использовании передовых технологий.', 'web-studio-landing' ); ?></p>
                <?php endif; ?>

                <div class="about__features">
                    <?php foreach ( $web_studio_features as $web_studio_feature ) : ?>
                        <div class="about__feature">
                            <div class="about__feature-icon" aria-hidden="true">
                                <?php
                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses() applied inside function.
                                echo web_studio_get_icon( $web_studio_feature['icon'] );
                                ?>
                            </div>
                            <div class="about__feature-text">
                                <h3 class="about__feature-title"><?php echo esc_html( $web_studio_feature['title'] ); ?></h3>
                                <p class="about__feature-desc"><?php echo esc_html( $web_studio_feature['desc'] ); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ( $web_studio_about_image ) : ?>
                <div class="about__image">
                    <?php echo wp_get_attachment_image( (int) $web_studio_about_image, 'medium_large', false, array( 'loading' => 'lazy' ) ); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
