<?php
/**
 * Wordwind Theme Functions
 */

// Theme setup (enqueue scripts/styles, theme support, menus)
require_once get_template_directory() . '/inc/theme-setup.php';

// Custom nav walker for Tailwind styling
require_once get_template_directory() . '/inc/class-tailwind-walker.php';

// Template functions
require_once get_template_directory() . '/inc/template-sections.php';
require_once get_template_directory() . '/inc/template-athletes.php';
require_once get_template_directory() . '/inc/template-functions.php';
