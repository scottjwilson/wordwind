<?php

if ( ! class_exists( 'Custom_Tailwind_Walker' ) ) {
  class Custom_Tailwind_Walker extends Walker_Nav_Menu {

      // Start the element output (li tags)
      public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
          $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

          $classes   = empty( $item->classes ) ? array() : (array) $item->classes;
          // Add custom tailwind classes to all <li> items
          $classes[] = 'mb-4 lg:mb-0 lg:mr-6';

          if ( in_array( 'current-menu-item', $classes, true ) ) {
              // Add specific classes for active/current menu items
              $classes[] = 'underline';
          }

          $class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
          $class_names = ' class="' . esc_attr( $class_names ) . '"';

          $output .= $indent . '<li' . $class_names . '>';

          $atts             = array();
          $atts['title']    = ! empty( $item->attr_title ) ? $item->attr_title : '';
          $atts['target']   = ! empty( $item->target ) ? $item->target : '';
          $atts['rel']      = ! empty( $item->xfn ) ? $item->xfn : '';
          $atts['href']     = ! empty( $item->url ) ? $item->url : '';
          // Add custom tailwind classes to all <a> tags
          $atts['class']    = 'text-gray-200 hover:text-white transition duration-300 p-2 block';

          $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

          $attributes = '';
          foreach ( $atts as $attr => $value ) {
              if ( ! empty( $value ) ) {
                  $value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                  $attributes .= ' ' . $attr . '="' . $value . '"';
              }
          }

          $item_output = $args->before;
          $item_output .= '<a' . $attributes . '>';
          $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
          $item_output .= '</a>';
          $item_output .= $args->after;

          $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
      }

      // You can also override start_lvl() and end_lvl() to customize <ul> wrappers
  }
}
