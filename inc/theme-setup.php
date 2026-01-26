<?php

function wordwind_enqueue_styles()
{
    wp_enqueue_style("wordwind-style", get_stylesheet_uri());
    wp_enqueue_style(
        "tailwind-output",
        get_template_directory_uri() . "/src/output.css",
    );
}
add_action("wp_enqueue_scripts", "wordwind_enqueue_styles");

function wordwind_enqueue_scripts()
{
    wp_enqueue_script(
        "athletes-table",
        get_template_directory_uri() . "/src/js/athletes-table.js",
        [],
        filemtime(get_template_directory() . "/src/js/athletes-table.js"),
        true,
    );
}
add_action("wp_enqueue_scripts", "wordwind_enqueue_scripts");

function wordwind_setup()
{
    register_nav_menu("menuTop", "Menu Top");
    add_theme_support("title-tag");
    add_theme_support("post-thumbnails");
}

add_action("after_setup_theme", "wordwind_setup");

function wordwind_custom_excerpt_length($length)
{
    return 10;
}
add_filter("excerpt_length", "wordwind_custom_excerpt_length", 999);

/**
 * Get all athletes data for search
 */
function wordwind_get_all_athletes_data()
{
    $args = [
        "post_type" => "athlete",
        "posts_per_page" => -1,
        "post_status" => "publish",
        "orderby" => "title",
        "order" => "ASC",
    ];

    $query = new WP_Query($args);
    $athletes = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $athlete_id = get_the_ID();

            // Get athlete fields
            $position = get_field("position", $athlete_id);
            $nil_valuation =
                get_field("nil_valuation", $athlete_id) ?:
                get_field("valuation", $athlete_id);

            // Get school name
            $school = get_field("school", $athlete_id);
            $school_name = "";
            if ($school) {
                if (is_array($school)) {
                    $school = $school[0];
                }
                if (is_object($school) && isset($school->ID)) {
                    $school_name = $school->post_title;
                } elseif (is_numeric($school)) {
                    $school_name = get_the_title($school);
                }
            }

            // Get thumbnail
            $thumbnail = "";
            if (has_post_thumbnail($athlete_id)) {
                $thumbnail = get_the_post_thumbnail_url(
                    $athlete_id,
                    "thumbnail",
                );
            }

            // Format NIL valuation
            $formatted_nil = "";
            if ($nil_valuation && is_numeric($nil_valuation)) {
                if ($nil_valuation >= 1000000) {
                    $formatted_nil =
                        '$' . number_format($nil_valuation / 1000000, 1) . "M";
                } elseif ($nil_valuation >= 1000) {
                    $formatted_nil =
                        '$' . number_format($nil_valuation / 1000, 0) . "K";
                } else {
                    $formatted_nil = '$' . number_format($nil_valuation);
                }
            }

            $athletes[] = [
                "id" => $athlete_id,
                "name" => get_the_title(),
                "url" => get_permalink(),
                "thumbnail" => $thumbnail,
                "position" => $position ?: "",
                "school" => $school_name,
                "nil_valuation" => $formatted_nil,
            ];
        }
        wp_reset_postdata();
    }

    return $athletes;
}

/**
 * Enqueue header search script with preloaded athlete data
 */
function wordwind_enqueue_header_search()
{
    wp_enqueue_script(
        "header-search",
        get_template_directory_uri() . "/src/js/header-search.js",
        [],
        filemtime(get_template_directory() . "/src/js/header-search.js"),
        true,
    );

    wp_localize_script("header-search", "wordwindSearch", [
        "athletes" => wordwind_get_all_athletes_data(),
    ]);
}
add_action("wp_enqueue_scripts", "wordwind_enqueue_header_search");
