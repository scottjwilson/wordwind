<?php

function wordwind_enqueue_styles() {
  wp_enqueue_style('wordwind-style', get_stylesheet_uri());
  wp_enqueue_style('tailwind-output', get_template_directory_uri() . '/src/output.css');
}
add_action('wp_enqueue_scripts', 'wordwind_enqueue_styles');

function wordwind_enqueue_scripts() {
  wp_enqueue_script(
    'athletes-table',
    get_template_directory_uri() . '/src/js/athletes-table.js',
    array(),
    filemtime(get_template_directory() . '/src/js/athletes-table.js'),
    true
  );
}
add_action('wp_enqueue_scripts', 'wordwind_enqueue_scripts');

function wordwind_setup() {
  register_nav_menu('menuTop', 'Menu Top');
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
}

add_action('after_setup_theme', 'wordwind_setup');

function wordwind_custom_excerpt_length($length) {
    return 10;
}
add_filter('excerpt_length', 'wordwind_custom_excerpt_length', 999);
