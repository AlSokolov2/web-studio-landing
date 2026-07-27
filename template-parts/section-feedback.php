<?php
/**
 * Template part: Feedback form section.
 *
 * @since 1.0.0
 * @package Web_Studio_Landing
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

$web_studio_form_heading = get_theme_mod( 'web_studio_form_heading', esc_html__( 'Обратная связь', 'web-studio-landing' ) );
$web_studio_form_desc    = get_theme_mod( 'web_studio_form_desc', esc_html__( 'Расскажите о вашем проекте, и мы свяжемся с вами в ближайшее время.', 'web-studio-landing' ) );
?>

<section id="feedback" class="feedback" aria-label="<?php esc_attr_e( 'Обратная связь', 'web-studio-landing' ); ?>">
    <div class="container">
        <h2 class="section-title"><?php echo esc_html( $web_studio_form_heading ); ?></h2>
        <p class="feedback__desc"><?php echo esc_html( $web_studio_form_desc ); ?></p>

        <form id="feedback-form" class="form" method="post" novalidate>
            <!-- Spam honeypot: hidden from users, visible to bots -->
            <div class="form__group form__group--hidden" aria-hidden="true">
                <label for="form-website"><?php esc_html_e( 'Website', 'web-studio-landing' ); ?></label>
                <input type="text" id="form-website" name="website" tabindex="-1" autocomplete="off">
            </div>

            <div class="form__row">
                <div class="form__group">
                    <label for="form-name" class="form__label">
                        <?php esc_html_e( 'Имя', 'web-studio-landing' ); ?>
                        <span class="form__required" aria-hidden="true">*</span>
                    </label>
                    <input
                        type="text"
                        id="form-name"
                        name="name"
                        class="form__field"
                        required
                        aria-required="true"
                        placeholder="<?php esc_attr_e( 'Ваше имя', 'web-studio-landing' ); ?>"
                    >
                    <span class="form__error" role="alert"></span>
                </div>

                <div class="form__group">
                    <label for="form-email" class="form__label">
                        <?php esc_html_e( 'Email', 'web-studio-landing' ); ?>
                        <span class="form__required" aria-hidden="true">*</span>
                    </label>
                    <input
                        type="email"
                        id="form-email"
                        name="email"
                        class="form__field"
                        required
                        aria-required="true"
                        placeholder="<?php esc_attr_e( 'your@email.com', 'web-studio-landing' ); ?>"
                    >
                    <span class="form__error" role="alert"></span>
                </div>
            </div>

            <div class="form__group">
                <label for="form-subject" class="form__label">
                    <?php esc_html_e( 'Тема', 'web-studio-landing' ); ?>
                </label>
                <input
                    type="text"
                    id="form-subject"
                    name="subject"
                    class="form__field"
                    placeholder="<?php esc_attr_e( 'О чём хотите поговорить?', 'web-studio-landing' ); ?>"
                >
            </div>

            <div class="form__group">
                <label for="form-message" class="form__label">
                    <?php esc_html_e( 'Сообщение', 'web-studio-landing' ); ?>
                    <span class="form__required" aria-hidden="true">*</span>
                </label>
                <textarea
                    id="form-message"
                    name="message"
                    class="form__field form__field--textarea"
                    rows="5"
                    required
                    aria-required="true"
                    placeholder="<?php esc_attr_e( 'Опишите ваш проект или задайте вопрос...', 'web-studio-landing' ); ?>"
                ></textarea>
                <span class="form__error" role="alert"></span>
            </div>

            <button type="submit" class="btn btn--primary form__submit">
                <?php esc_html_e( 'Отправить', 'web-studio-landing' ); ?>
            </button>

            <div id="form-status" class="form__status" role="status" aria-live="polite"></div>
        </form>
    </div>
</section>
