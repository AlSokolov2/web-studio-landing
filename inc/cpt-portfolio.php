<?php
/**
 * Portfolio custom post type registration.
 *
 * @since 1.0.0
 * @package Web_Studio_Landing
 */

declare( strict_types=1 );

defined( 'ABSPATH' ) || exit;

/**
 * Registers the Portfolio custom post type and taxonomy.
 *
 * @since 1.0.0
 * @return void
 */
function web_studio_register_portfolio_cpt(): void {
    /*
     * Post Type: Portfolio.
     */
    $cpt_labels = array(
        'name'                  => _x( 'Портфолио', 'Post Type General Name', 'web-studio-landing' ),
        'singular_name'         => _x( 'Проект', 'Post Type Singular Name', 'web-studio-landing' ),
        'menu_name'             => esc_html__( 'Портфолио', 'web-studio-landing' ),
        'add_new'               => esc_html__( 'Добавить проект', 'web-studio-landing' ),
        'add_new_item'          => esc_html__( 'Добавить новый проект', 'web-studio-landing' ),
        'edit_item'             => esc_html__( 'Редактировать проект', 'web-studio-landing' ),
        'new_item'              => esc_html__( 'Новый проект', 'web-studio-landing' ),
        'view_item'             => esc_html__( 'Смотреть проект', 'web-studio-landing' ),
        'search_items'          => esc_html__( 'Искать проекты', 'web-studio-landing' ),
        'not_found'             => esc_html__( 'Проекты не найдены', 'web-studio-landing' ),
        'not_found_in_trash'    => esc_html__( 'В корзине проектов нет', 'web-studio-landing' ),
        'all_items'             => esc_html__( 'Все проекты', 'web-studio-landing' ),
        'featured_image'        => esc_html__( 'Изображение проекта', 'web-studio-landing' ),
        'set_featured_image'    => esc_html__( 'Установить изображение', 'web-studio-landing' ),
        'remove_featured_image' => esc_html__( 'Удалить изображение', 'web-studio-landing' ),
    );

    $cpt_args = array(
        'labels'        => $cpt_labels,
        'public'        => true,
        'has_archive'   => true,
        'show_in_rest'  => true,
        'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
        'menu_icon'     => 'dashicons-portfolio',
        'menu_position' => 5,
        'rewrite'       => array(
            'slug' => 'portfolio',
        ),
    );

    register_post_type( 'portfolio', $cpt_args );

    /*
     * Taxonomy: Portfolio Category.
     */
    $tax_labels = array(
        'name'              => _x( 'Категории', 'Taxonomy General Name', 'web-studio-landing' ),
        'singular_name'     => _x( 'Категория', 'Taxonomy Singular Name', 'web-studio-landing' ),
        'search_items'      => esc_html__( 'Искать категории', 'web-studio-landing' ),
        'all_items'         => esc_html__( 'Все категории', 'web-studio-landing' ),
        'parent_item'       => esc_html__( 'Родительская категория', 'web-studio-landing' ),
        'parent_item_colon' => esc_html__( 'Родительская категория:', 'web-studio-landing' ),
        'edit_item'         => esc_html__( 'Редактировать категорию', 'web-studio-landing' ),
        'update_item'       => esc_html__( 'Обновить категорию', 'web-studio-landing' ),
        'add_new_item'      => esc_html__( 'Добавить категорию', 'web-studio-landing' ),
        'new_item_name'     => esc_html__( 'Название новой категории', 'web-studio-landing' ),
        'menu_name'         => esc_html__( 'Категории', 'web-studio-landing' ),
    );

    $tax_args = array(
        'labels'       => $tax_labels,
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => array(
            'slug' => 'project-category',
        ),
    );

    register_taxonomy( 'portfolio_category', array( 'portfolio' ), $tax_args );
}
add_action( 'init', 'web_studio_register_portfolio_cpt' );
