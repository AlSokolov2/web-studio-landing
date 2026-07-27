<?php
/**
 * Template part: Contacts section.
 *
 * @since 1.0.0
 * @package Web_Studio_Landing
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

$web_studio_contacts_heading = get_theme_mod( 'web_studio_contacts_heading', esc_html__( 'Контакты', 'web-studio-landing' ) );
$web_studio_contacts_phone   = get_theme_mod( 'web_studio_contacts_phone', '' );
$web_studio_contacts_email   = get_theme_mod( 'web_studio_contacts_email', '' );
$web_studio_contacts_address = get_theme_mod( 'web_studio_contacts_address', '' );
$web_studio_contacts_social  = get_theme_mod( 'web_studio_contacts_social', '' );

$web_studio_social_links = $web_studio_contacts_social ? json_decode( $web_studio_contacts_social, true ) : array();
?>

<section id="contacts" class="contacts" aria-label="<?php esc_attr_e( 'Контакты', 'web-studio-landing' ); ?>">
    <div class="container">
        <h2 class="section-title"><?php echo esc_html( $web_studio_contacts_heading ); ?></h2>

        <div class="contacts__inner" itemscope itemtype="https://schema.org/LocalBusiness">
            <meta itemprop="name" content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">

            <ul class="contacts__list">
                <?php if ( $web_studio_contacts_phone ) : ?>
                    <li class="contacts__item">
                        <span class="contacts__icon" aria-hidden="true">
                            <?php
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses() applied inside function.
                            echo web_studio_get_icon( 'phone' );
                            ?>
                        </span>
                        <a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $web_studio_contacts_phone ) ); ?>" itemprop="telephone">
                            <?php echo esc_html( $web_studio_contacts_phone ); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ( $web_studio_contacts_email ) : ?>
                    <li class="contacts__item">
                        <span class="contacts__icon" aria-hidden="true">
                            <?php
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses() applied inside function.
                            echo web_studio_get_icon( 'email' );
                            ?>
                        </span>
                        <a href="mailto:<?php echo esc_attr( $web_studio_contacts_email ); ?>" itemprop="email">
                            <?php echo esc_html( $web_studio_contacts_email ); ?>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if ( $web_studio_contacts_address ) : ?>
                    <li class="contacts__item">
                        <span class="contacts__icon" aria-hidden="true">
                            <?php
                            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses() applied inside function.
                            echo web_studio_get_icon( 'location' );
                            ?>
                        </span>
                        <span itemprop="address"><?php echo esc_html( $web_studio_contacts_address ); ?></span>
                    </li>
                <?php endif; ?>
            </ul>

            <?php if ( $web_studio_social_links ) : ?>
                <div class="contacts__social">
                    <?php foreach ( $web_studio_social_links as $web_studio_link ) : ?>
                        <?php if ( ! empty( $web_studio_link['url'] ) && ! empty( $web_studio_link['label'] ) ) : ?>
                            <a
                                href="<?php echo esc_url( $web_studio_link['url'] ); ?>"
                                class="contacts__social-link"
                                target="_blank"
                                rel="noopener noreferrer"
                                aria-label="<?php echo esc_attr( $web_studio_link['label'] ?? '' ); ?>"
                            >
                                <?php
                                // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_kses() applied inside function.
                                echo web_studio_get_icon( $web_studio_link['icon'] ?? 'link' );
                                ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
