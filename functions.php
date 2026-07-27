<?php
/**
 * Theme functions and definitions.
 *
 * @since 1.0.0
 * @package Web_Studio_Landing
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

// -----------------------------------------------------------------------------
// Include required files
// -----------------------------------------------------------------------------
$web_studio_inc = array(
    'inc/cpt-portfolio.php',
    'inc/class-web-studio-landing-customizer.php',
    'inc/class-web-studio-landing-ajax-handler.php',
);

foreach ( $web_studio_inc as $web_studio_file ) {
    $web_studio_path = get_template_directory() . '/' . $web_studio_file;
    if ( file_exists( $web_studio_path ) ) {
        require_once $web_studio_path;
    }
}

// -----------------------------------------------------------------------------
// Theme setup
// -----------------------------------------------------------------------------
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * @since 1.0.0
 * @return void
 */
function web_studio_setup(): void {
    /*
     * Make theme available for translation.
     */
    load_theme_textdomain( 'web-studio-landing', get_template_directory() . '/languages' );

    /*
     * Let WordPress manage the document title.
     */
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails.
     */
    add_theme_support( 'post-thumbnails' );

    /*
     * Switch default core markup to output valid HTML5.
     */
    add_theme_support(
        'html5',
        array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
			'navigation-widgets',
        )
    );

    /*
     * Custom logo support.
     */
    add_theme_support(
        'custom-logo',
        array(
			'height'      => 60,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
        )
    );

    /*
     * Responsive embeds.
     */
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'responsive-embeds' );

    /*
     * Selective refresh for widgets in Customizer.
     */
    add_theme_support( 'customize-selective-refresh-widgets' );

    /*
     * Register navigation menus.
     */
    register_nav_menus(
        array(
			'primary' => esc_html__( 'Основное меню', 'web-studio-landing' ),
        )
    );
}
add_action( 'after_setup_theme', 'web_studio_setup' );

// -----------------------------------------------------------------------------
// Enqueue assets
// -----------------------------------------------------------------------------
/**
 * Enqueues scripts and styles for the front end.
 *
 * @since 1.0.0
 * @return void
 */
function web_studio_enqueue_assets(): void {
    $web_studio_version = wp_get_theme()->get( 'Version' );

    // Styles: loaded on every page.
    wp_enqueue_style(
        'web-studio-main',
        get_theme_file_uri( 'assets/css/main.css' ),
        array(),
        $web_studio_version
    );

    // Navigation script: loaded on every page.
    wp_enqueue_script(
        'web-studio-navigation',
        get_theme_file_uri( 'assets/js/navigation.js' ),
        array(),
        $web_studio_version,
        array(
            'strategy' => 'defer',
        )
    );

    // Main script: front page only (AJAX form, scroll, animations).
    if ( is_front_page() ) {
        wp_enqueue_script(
            'web-studio-main',
            get_theme_file_uri( 'assets/js/main.js' ),
            array(),
            $web_studio_version,
            array(
                'strategy' => 'defer',
            )
        );

        $web_studio_js_data = wp_json_encode(
            array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'feedback_nonce' ),
				'i18n'    => array(
					'required'     => esc_html__( 'Это поле обязательно.', 'web-studio-landing' ),
					'emailInvalid' => esc_html__( 'Введите корректный email.', 'web-studio-landing' ),
					'sending'      => esc_html__( 'Отправка...', 'web-studio-landing' ),
					'submit'       => esc_html__( 'Отправить', 'web-studio-landing' ),
					'networkError' => esc_html__( 'Ошибка соединения. Попробуйте позже.', 'web-studio-landing' ),
				),
            )
        );

        wp_add_inline_script(
            'web-studio-main',
            'window.wsData=' . $web_studio_js_data . ';',
            'before'
        );
    }
}
add_action( 'wp_enqueue_scripts', 'web_studio_enqueue_assets' );

// -----------------------------------------------------------------------------
// Performance: remove unnecessary WP features
// -----------------------------------------------------------------------------
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

// -----------------------------------------------------------------------------
// SVG icon helper
// -----------------------------------------------------------------------------
/**
 * Returns inline SVG markup for a named icon.
 *
 * @since 1.0.0
 * @param string $name Icon identifier.
 * @return string SVG markup or empty string if not found.
 */
function web_studio_get_icon( string $name ): string {
    $icons = array(
        'code'     => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>',
        'design'   => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>',
        'support'  => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
        'phone'    => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>',
        'email'    => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
        'location' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>',
        'link'     => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>',
    );

    $svg = $icons[ $name ] ?? '';

    if ( '' === $svg ) {
        return '';
    }

    // Allow SVG tags for safe inlining of hardcoded icons.
    $allowed_html = array(
        'svg'      => array(
            'xmlns'           => true,
            'width'           => true,
            'height'          => true,
            'viewbox'         => true,
            'fill'            => true,
            'stroke'          => true,
            'stroke-width'    => true,
            'stroke-linecap'  => true,
            'stroke-linejoin' => true,
            'aria-hidden'     => true,
            'role'            => true,
            'class'           => true,
        ),
        'path'     => array(
            'd'      => true,
            'fill'   => true,
            'stroke' => true,
        ),
        'polyline' => array(
            'points' => true,
        ),
        'line'     => array(
            'x1' => true,
            'y1' => true,
            'x2' => true,
            'y2' => true,
        ),
        'circle'   => array(
            'cx' => true,
            'cy' => true,
            'r'  => true,
        ),
    );

    return wp_kses( $svg, $allowed_html );
}

// -----------------------------------------------------------------------------
// Social links sanitizer (used by Customizer)
// -----------------------------------------------------------------------------
/**
 * Sanitizes social links JSON string for Customizer storage.
 *
 * Validates JSON structure and sanitizes individual fields:
 * url via esc_url_raw, label via sanitize_text_field, icon via a whitelist.
 *
 * @since 1.0.0
 * @param string $value Raw JSON string from Customizer.
 * @return string Sanitized JSON string, or empty string on failure.
 */
function web_studio_sanitize_social_links( string $value ): string {
    if ( '' === $value ) {
        return '';
    }

    $decoded = json_decode( $value, true );

    if ( ! is_array( $decoded ) ) {
        return '';
    }

    $allowed_icons = array( 'code', 'design', 'support', 'phone', 'email', 'location', 'link' );
    $sanitized     = array();

    foreach ( $decoded as $item ) {
        if ( ! is_array( $item ) || empty( $item['url'] ) ) {
            continue;
        }

        $sanitized[] = array(
            'url'   => esc_url_raw( $item['url'] ),
            'label' => isset( $item['label'] ) ? sanitize_text_field( $item['label'] ) : '',
            'icon'  => isset( $item['icon'] ) && in_array( $item['icon'], $allowed_icons, true )
                ? $item['icon']
                : 'link',
        );
    }

    return empty( $sanitized ) ? '' : wp_json_encode( $sanitized );
}
