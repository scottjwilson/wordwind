<?php get_header(); ?>

<main class="site-main">
   <?php getHeroSection(); ?>
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-bsj-navy">Featured Stories</h2>
                <a href="#" class="text-bsj-blue font-semibold hover:text-bsj-gold transition">View All →</a>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <?php
                // Query featured posts with ACF boolean field
                $featured_posts = new WP_Query([
                    'posts_per_page' => 3,
                    'post_type' => 'post',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'meta_query' => [
                        [
                            'key' => 'isFeatured',
                            'value' => '1',
                            'compare' => '='
                        ]
                    ]
                ]);

                if ($featured_posts->have_posts()) :
                    while ($featured_posts->have_posts()) : $featured_posts->the_post();
                        // Get post category for label
                        $categories = get_the_category();
                        $category_name = !empty($categories) ? strtoupper($categories[0]->name) : 'FEATURED';
                        $category_emoji = !empty($categories) ? getCategoryEmoji($categories[0]->name) : '📰';

                        // Get featured image or use gradient background
                        $has_thumbnail = has_post_thumbnail();
                        $thumbnail_url = $has_thumbnail ? get_the_post_thumbnail_url(get_the_ID(), 'large') : '';

                        // Get excerpt or trimmed content
                        $excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 20);

                        // Get read time (estimate based on word count)
                        $word_count = str_word_count(strip_tags(get_the_content()));
                        $read_time = max(1, round($word_count / 200)); // Assuming 200 words per minute
                ?>
                <article class="group cursor-pointer">
                    <a href="<?php the_permalink(); ?>">
                        <div class="h-48 rounded-lg mb-4 flex items-center justify-center overflow-hidden <?php echo $has_thumbnail ? '' : 'bg-gradient-to-br from-blue-600 to-blue-800'; ?>" <?php echo $has_thumbnail ? 'style="background-image: url(' . esc_url($thumbnail_url) . '); background-size: cover; background-position: center;"' : ''; ?>>
                            <?php if (!$has_thumbnail) : ?>
                                <div class="text-white text-6xl font-bold opacity-20 group-hover:scale-110 transition duration-300"><?php echo $category_emoji; ?></div>
                            <?php else : ?>
                                <!-- <div class="w-full h-full bg-black bg-opacity-30 group-hover:bg-opacity-20 transition duration-300"></div> -->
                            <?php endif; ?>
                        </div>
                        <div class="text-xs text-bsj-gold font-bold mb-2"><?php echo esc_html($category_name); ?></div>
                        <h3 class="text-xl font-bold mb-2 group-hover:text-bsj-blue transition">
                            <?php the_title(); ?>
                        </h3>
                        <p class="text-gray-600 mb-3">
                            <?php echo esc_html($excerpt); ?>
                        </p>
                        <div class="text-sm text-gray-500"><?php echo $read_time; ?> min read • <?php echo get_the_date('M j, Y'); ?></div>
                    </a>
                </article>

                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                <!-- Fallback if no featured posts found -->
                <p class="col-span-3 text-center text-gray-500">No featured stories available.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-bsj-navy mb-10 text-center">Coverage Areas</h2>

            <div class="grid md:grid-cols-5 gap-6">
            <?php
                $categories = get_categories(array(
                    'orderby' => 'name',
                    'order' => 'ASC',
                    'hide_empty' => false
                ));

                if (!empty($categories)) :
                    foreach ($categories as $category) :
                        $category_emoji = getCategoryEmoji($category->name);
                        $category_description = wp_strip_all_tags($category->description);
                ?>
                <a href="<?php echo get_category_link($category->term_id); ?>">
                    <div class="bg-white p-6 rounded-lg border-2 border-transparent hover:border-bsj-gold transition cursor-pointer text-center">
                        <div class="text-4xl mb-3"><?php echo $category_emoji; ?></div>
                        <h3 class="font-bold text-bsj-navy mb-2"><?php echo esc_html($category->name); ?></h3>
                        <p class="text-sm text-gray-600"><?php echo esc_html($category_description ?: 'Category posts'); ?></p>
                    </div>
                </a>
                <?php
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </section>

    <!-- Newsletter CTA -->
    <?php getNewsletter(); ?>

    <!-- Athletes Section -->
    <?php
    $athletes = new WP_Query([
        'posts_per_page' => 10,
        'post_type' => 'athlete',
        'orderby' => 'date',
        'order' => 'DESC',
    ]);

    if ($athletes->have_posts()) : ?>
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <?php
                render_athletes_table([
                    'athletes' => $athletes,
                    'view' => 'table',
                    'show_rank' => true,
                    'title' => 'Athletes',
                    'title_right' => 'NIL Valuations',
                ]);
                ?>
            </div>
        </section>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
