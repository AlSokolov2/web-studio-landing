<?php
/**
 * Theme header template.
 *
 * @since 1.0.0
 * @package Web_Studio_Landing
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo esc_attr( get_bloginfo( 'description', 'display' ) ); ?>">

    <meta property="og:title" content="<?php echo esc_attr( wp_get_document_title() ); ?>">
    <meta property="og:description" content="<?php echo esc_attr( get_bloginfo( 'description', 'display' ) ); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo esc_url( home_url() ); ?>">
    <meta property="og:site_name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a href="#main" class="skip-link screen-reader-text">
    <?php esc_html_e( 'Перейти к содержанию', 'web-studio-landing' ); ?>
</a>

<header class="header" role="banner">
    <div class="header__inner container">
        <?php if ( has_custom_logo() ) : ?>
            <div class="header__logo">
                <?php the_custom_logo(); ?>
            </div>
        <?php else : ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="header__site-title" rel="home">
                <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
            </a>
        <?php endif; ?>

        <button
            class="header__toggle"
            type="button"
            aria-controls="primary-nav"
            aria-expanded="false"
            aria-label="<?php esc_attr_e( 'Меню', 'web-studio-landing' ); ?>"
        >
            <span class="header__toggle-bar"></span>
            <span class="header__toggle-bar"></span>
            <span class="header__toggle-bar"></span>
        </button>

        <nav id="primary-nav" class="header__nav" role="navigation" aria-label="<?php esc_attr_e( 'Основное меню', 'web-studio-landing' ); ?>">
            <?php
            wp_nav_menu(
                array(
					'theme_location' => 'primary',
					'menu_class'     => 'header__menu',
					'container'      => false,
					'fallback_cb'    => false,
					'depth'          => 1,
                )
            );
            ?>
        </nav>
    </div>
</header>
