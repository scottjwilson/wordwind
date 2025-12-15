<?php
function wordflow_enqueue_styles() {
  wp_enqueue_style('wordflow-style', get_stylesheet_uri());
  wp_enqueue_style('tailwind-output', get_template_directory_uri() . '/src/output.css');
}
add_action('wp_enqueue_scripts', 'wordflow_enqueue_styles');