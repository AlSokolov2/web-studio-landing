<?php
/**
 * AJAX handler for the feedback form.
 *
 * @since 1.0.0
 * @package Web_Studio_Landing
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

/**
 * Handles AJAX feedback form submissions.
 *
 * @since 1.0.0
 */
class Web_Studio_Landing_Ajax_Handler {

    /**
     * Constructor. Registers AJAX hooks.
     */
    public function __construct() {
        add_action( 'wp_ajax_submit_feedback', array( $this, 'handle' ) );
        add_action( 'wp_ajax_nopriv_submit_feedback', array( $this, 'handle' ) );
    }

    /**
     * Processes the feedback form submission.
     *
     * @since 1.0.0
     * @return void
     */
    public function handle(): void {
        // 1. Verify nonce.
        if ( ! check_ajax_referer( 'feedback_nonce', 'nonce', false ) ) {
            wp_send_json_error(
                array(
					'message' => esc_html__( 'Ошибка безопасности. Обновите страницу и попробуйте снова.', 'web-studio-landing' ),
                )
            );
        }

        // 2. Honeypot check — hidden field must remain empty.
        if ( ! empty( $_POST['website'] ) ) {
            wp_send_json_error(
                array(
					'message' => esc_html__( 'Спам не приветствуется.', 'web-studio-landing' ),
                )
            );
        }

        // 3. Sanitize input.
        $name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
        $email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
        $subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
        $message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

        // 4. Server-side validation.
        $errors = array();

        if ( '' === $name || mb_strlen( $name ) < 2 ) {
            $errors['name'] = esc_html__( 'Имя должно содержать минимум 2 символа.', 'web-studio-landing' );
        } elseif ( mb_strlen( $name ) > 100 ) {
            $errors['name'] = esc_html__( 'Имя слишком длинное.', 'web-studio-landing' );
        }

        if ( '' === $email || ! is_email( $email ) ) {
            $errors['email'] = esc_html__( 'Введите корректный email адрес.', 'web-studio-landing' );
        }

        if ( '' === $message || mb_strlen( $message ) < 10 ) {
            $errors['message'] = esc_html__( 'Сообщение должно содержать минимум 10 символов.', 'web-studio-landing' );
        } elseif ( mb_strlen( $message ) > 5000 ) {
            $errors['message'] = esc_html__( 'Сообщение слишком длинное.', 'web-studio-landing' );
        }

        if ( ! empty( $errors ) ) {
            wp_send_json_error(
                array(
					'errors'  => $errors,
					'message' => esc_html__( 'Пожалуйста, исправьте ошибки в форме.', 'web-studio-landing' ),
                )
            );
        }

        // 5. Build and send email.
        $to = get_option( 'admin_email' );

        if ( '' !== $subject ) {
            $email_subject = sprintf(
                '[Web Studio] %s',
                $subject
            );
        } else {
            $email_subject = __( '[Web Studio] Новое сообщение с сайта', 'web-studio-landing' );
        }

        $email_body = sprintf(
            "Имя: %s\nEmail: %s\nТема: %s\n\nСообщение:\n%s",
            $name,
            $email,
            $subject ? $subject : esc_html__( 'Не указана', 'web-studio-landing' ),
            $message
        );

        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            sprintf( 'Reply-To: %s', $email ),
        );

        $sent = wp_mail( $to, $email_subject, $email_body, $headers );

        if ( $sent ) {
            wp_send_json_success(
                array(
					'message' => esc_html__( 'Спасибо! Ваше сообщение отправлено. Мы свяжемся с вами в ближайшее время.', 'web-studio-landing' ),
                )
            );
        } else {
            // Log the failure for debugging.
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
                error_log( 'Web Studio: wp_mail() failed for feedback form submission.' );
            }

            wp_send_json_error(
                array(
					'message' => esc_html__( 'Ошибка отправки. Пожалуйста, попробуйте позже.', 'web-studio-landing' ),
                )
            );
        }
    }
}

// Initialize.
new Web_Studio_Landing_Ajax_Handler();
