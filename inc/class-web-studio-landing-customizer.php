<?php
/**
 * Customizer settings and controls.
 *
 * @since 1.0.0
 * @package Web_Studio_Landing
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

/**
 * Registers Customizer panels, sections, settings, and controls.
 *
 * @since 1.0.0
 */
class Web_Studio_Landing_Customizer {

    /**
     * Constructor. Hooks into customize_register.
     */
    public function __construct() {
        add_action( 'customize_register', array( $this, 'register' ) );
    }

    /**
     * Registers all Customizer components.
     *
     * @since 1.0.0
     * @param WP_Customize_Manager $wp_customize Customizer manager instance.
     * @return void
     */
    public function register( $wp_customize ): void {
        $this->register_hero_section( $wp_customize );
        $this->register_portfolio_section( $wp_customize );
        $this->register_about_section( $wp_customize );
        $this->register_contacts_section( $wp_customize );
        $this->register_feedback_section( $wp_customize );
        $this->register_footer_section( $wp_customize );
    }

    /**
     * Adds a setting and its control to the Customizer.
     *
     * @since 1.0.0
     * @param WP_Customize_Manager $wp_customize       Customizer manager instance.
     * @param string               $setting_id         Setting ID.
     * @param array                $setting_args       Setting arguments.
     * @param array                $control_args       Control arguments.
     * @param string               $section_id         Section ID.
     * @param string               $control_class      Full class name for custom controls (optional).
     * @return void
     */
    private function add_setting_and_control(
        $wp_customize,
        string $setting_id,
        array $setting_args,
        array $control_args,
        string $section_id,
        string $control_class = ''
    ): void {
        $setting_defaults = array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        );

        $wp_customize->add_setting( $setting_id, array_merge( $setting_defaults, $setting_args ) );

        $control_defaults = array(
            'settings' => $setting_id,
            'section'  => $section_id,
        );

        $merged_control = array_merge( $control_defaults, $control_args );

        if ( $control_class && class_exists( $control_class ) ) {
            $wp_customize->add_control( new $control_class( $wp_customize, $setting_id . '_control', $merged_control ) );
        } else {
            $wp_customize->add_control( $setting_id . '_control', $merged_control );
        }
    }

    // -------------------------------------------------------------------------
    // Hero Section
    // -------------------------------------------------------------------------
    /**
     * Registers the Hero section and its controls.
     *
     * @since 1.0.0
     * @param WP_Customize_Manager $wp_customize Customizer manager instance.
     * @return void
     */
    private function register_hero_section( $wp_customize ): void {
        $wp_customize->add_section(
            'web_studio_hero',
            array(
				'title'    => esc_html__( 'Hero (первый экран)', 'web-studio-landing' ),
				'priority' => 30,
            )
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_hero_title',
            array( 'default' => __( 'Создаём сайты, которые работают', 'web-studio-landing' ) ),
            array(
                'label' => esc_html__( 'Заголовок', 'web-studio-landing' ),
                'type'  => 'text',
            ),
            'web_studio_hero'
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_hero_subtitle',
            array( 'default' => __( 'Веб-студия полного цикла: от дизайна до запуска', 'web-studio-landing' ) ),
            array(
                'label' => esc_html__( 'Подзаголовок', 'web-studio-landing' ),
                'type'  => 'text',
            ),
            'web_studio_hero'
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_hero_cta_text',
            array( 'default' => __( 'Обсудить проект', 'web-studio-landing' ) ),
            array(
                'label' => esc_html__( 'Текст кнопки', 'web-studio-landing' ),
                'type'  => 'text',
            ),
            'web_studio_hero'
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_hero_cta_url',
            array(
                'default'           => '#feedback',
                'sanitize_callback' => 'esc_url_raw',
            ),
            array(
                'label' => esc_html__( 'Ссылка кнопки', 'web-studio-landing' ),
                'type'  => 'url',
            ),
            'web_studio_hero'
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_hero_bg_image',
            array( 'sanitize_callback' => 'esc_url_raw' ),
            array(
                'label' => esc_html__( 'Фоновое изображение', 'web-studio-landing' ),
                'type'  => 'image',
            ),
            'web_studio_hero',
            'WP_Customize_Image_Control'
        );
    }

    // -------------------------------------------------------------------------
    // Portfolio Section
    // -------------------------------------------------------------------------
    /**
     * Registers the Portfolio section and its controls.
     *
     * @since 1.0.0
     * @param WP_Customize_Manager $wp_customize Customizer manager instance.
     * @return void
     */
    private function register_portfolio_section( $wp_customize ): void {
        $wp_customize->add_section(
            'web_studio_portfolio',
            array(
				'title'    => esc_html__( 'Портфолио', 'web-studio-landing' ),
				'priority' => 40,
            )
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_portfolio_heading',
            array( 'default' => __( 'Портфолио', 'web-studio-landing' ) ),
            array(
                'label' => esc_html__( 'Заголовок секции', 'web-studio-landing' ),
                'type'  => 'text',
            ),
            'web_studio_portfolio'
        );
    }

    // -------------------------------------------------------------------------
    // About Section
    // -------------------------------------------------------------------------
    /**
     * Registers the About section and its controls.
     *
     * @since 1.0.0
     * @param WP_Customize_Manager $wp_customize Customizer manager instance.
     * @return void
     */
    private function register_about_section( $wp_customize ): void {
        $wp_customize->add_section(
            'web_studio_about',
            array(
				'title'    => esc_html__( 'О студии', 'web-studio-landing' ),
				'priority' => 50,
            )
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_about_heading',
            array( 'default' => __( 'О студии', 'web-studio-landing' ) ),
            array(
                'label' => esc_html__( 'Заголовок секции', 'web-studio-landing' ),
                'type'  => 'text',
            ),
            'web_studio_about'
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_about_content',
            array( 'sanitize_callback' => 'wp_kses_post' ),
            array(
                'label' => esc_html__( 'Текст о студии', 'web-studio-landing' ),
                'type'  => 'textarea',
            ),
            'web_studio_about'
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_about_image',
            array( 'sanitize_callback' => 'absint' ),
            array(
                'label' => esc_html__( 'Изображение', 'web-studio-landing' ),
                'type'  => 'image',
            ),
            'web_studio_about',
            'WP_Customize_Image_Control'
        );
    }

    // -------------------------------------------------------------------------
    // Contacts Section
    // -------------------------------------------------------------------------
    /**
     * Registers the Contacts section and its controls.
     *
     * @since 1.0.0
     * @param WP_Customize_Manager $wp_customize Customizer manager instance.
     * @return void
     */
    private function register_contacts_section( $wp_customize ): void {
        $wp_customize->add_section(
            'web_studio_contacts',
            array(
				'title'    => esc_html__( 'Контакты', 'web-studio-landing' ),
				'priority' => 60,
            )
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_contacts_heading',
            array( 'default' => __( 'Контакты', 'web-studio-landing' ) ),
            array(
                'label' => esc_html__( 'Заголовок секции', 'web-studio-landing' ),
                'type'  => 'text',
            ),
            'web_studio_contacts'
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_contacts_phone',
            array( 'sanitize_callback' => 'sanitize_text_field' ),
            array(
                'label' => esc_html__( 'Телефон', 'web-studio-landing' ),
                'type'  => 'text',
            ),
            'web_studio_contacts'
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_contacts_email',
            array(
                'default'           => get_option( 'admin_email' ),
                'sanitize_callback' => 'sanitize_email',
            ),
            array(
                'label' => esc_html__( 'Email', 'web-studio-landing' ),
                'type'  => 'email',
            ),
            'web_studio_contacts'
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_contacts_address',
            array( 'sanitize_callback' => 'sanitize_text_field' ),
            array(
                'label' => esc_html__( 'Адрес', 'web-studio-landing' ),
                'type'  => 'text',
            ),
            'web_studio_contacts'
        );

        // Social links.
        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_contacts_social',
            array( 'sanitize_callback' => 'web_studio_sanitize_social_links' ),
            array(
                'label'       => esc_html__( 'Социальные сети (JSON)', 'web-studio-landing' ),
                'description' => esc_html__( 'Массив объектов: [{"icon":"phone","label":"Telegram","url":"https://t.me/..."}]', 'web-studio-landing' ),
                'type'        => 'textarea',
            ),
            'web_studio_contacts'
        );
    }

    // -------------------------------------------------------------------------
    // Feedback Section
    // -------------------------------------------------------------------------
    /**
     * Registers the Feedback section and its controls.
     *
     * @since 1.0.0
     * @param WP_Customize_Manager $wp_customize Customizer manager instance.
     * @return void
     */
    private function register_feedback_section( $wp_customize ): void {
        $wp_customize->add_section(
            'web_studio_feedback',
            array(
				'title'    => esc_html__( 'Форма обратной связи', 'web-studio-landing' ),
				'priority' => 70,
            )
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_form_heading',
            array( 'default' => __( 'Обратная связь', 'web-studio-landing' ) ),
            array(
                'label' => esc_html__( 'Заголовок секции', 'web-studio-landing' ),
                'type'  => 'text',
            ),
            'web_studio_feedback'
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_form_desc',
            array(
                'default' => __( 'Расскажите о вашем проекте, и мы свяжемся с вами в ближайшее время.', 'web-studio-landing' ),
            ),
            array(
                'label' => esc_html__( 'Описание под заголовком', 'web-studio-landing' ),
                'type'  => 'textarea',
            ),
            'web_studio_feedback'
        );
    }

    // -------------------------------------------------------------------------
    // Footer Section
    // -------------------------------------------------------------------------
    /**
     * Registers the Footer section and its controls.
     *
     * @since 1.0.0
     * @param WP_Customize_Manager $wp_customize Customizer manager instance.
     * @return void
     */
    private function register_footer_section( $wp_customize ): void {
        $wp_customize->add_section(
            'web_studio_footer',
            array(
				'title'    => esc_html__( 'Подвал (footer)', 'web-studio-landing' ),
				'priority' => 90,
            )
        );

        $this->add_setting_and_control(
            $wp_customize,
            'web_studio_footer_copyright',
            array(),
            array(
                'label' => esc_html__( 'Текст копирайта (необязательно)', 'web-studio-landing' ),
                'type'  => 'text',
            ),
            'web_studio_footer'
        );
    }
}

// Initialize.
new Web_Studio_Landing_Customizer();
