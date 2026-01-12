<?php get_header(); ?>
<?php the_title(); ?>

<?php
// Get the current school ID
$school_id = get_the_ID();

// Query athletes that have this school selected in their 'school' field
$athletes = new WP_Query(array(
    'post_type' => 'athlete',
    'posts_per_page' => -1, // Get all, or limit with a number
    'meta_query' => array(
        array(
            'key' => 'school', // The ACF relationship field on athletes
            'value' => '"' . $school_id . '"', // ACF stores relationship IDs in serialized format
            'compare' => 'LIKE'
        )
    )
));

// Display the athletes
if ($athletes->have_posts()) :
    while ($athletes->have_posts()) : $athletes->the_post();
        // Display athlete info
        echo '<div>' . get_the_title() . '</div>';
    endwhile;
    wp_reset_postdata();
endif;
?>



<?php get_footer(); ?>
