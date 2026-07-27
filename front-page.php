<?php
/**
 * Front page template.
 *
 * @since 1.0.0
 * @package Web_Studio_Landing
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main id="main" class="main" role="main">
    <?php
    get_template_part( 'template-parts/section', 'hero' );
    get_template_part( 'template-parts/section', 'portfolio' );
    get_template_part( 'template-parts/section', 'about' );
    get_template_part( 'template-parts/section', 'contacts' );
    get_template_part( 'template-parts/section', 'feedback' );
    ?>
</main>

<?php
get_footer();
