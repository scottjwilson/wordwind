<?php
function wordwind_enqueue_styles() {
  wp_enqueue_style('wordwind-style', get_stylesheet_uri());
  wp_enqueue_style('tailwind-output', get_template_directory_uri() . '/src/output.css');
}
add_action('wp_enqueue_scripts', 'wordwind_enqueue_styles');

function wordwind_setup() { 
  register_nav_menu('navigationHeader', 'Header Navigation Menu');
  add_theme_support('title-tag');
}

add_action('after_setup_theme', 'wordwind_setup');
