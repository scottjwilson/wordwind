<?php get_header(); ?>

<main class="site-main">
    <!-- Header Section -->
    <section class="bg-gradient-to-br from-bsj-navy to-blue-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-2">Athletes</h1>
            <p class="text-xl text-gray-300">Browse all athletes and their NIL valuations</p>
        </div>
    </section>

    <!-- Athletes List Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php if (have_posts()) : ?>
                <?php
                render_athletes_table([
                    'athletes' => null,
                    'view' => 'list',
                    'show_rank' => false,
                    'show_search' => true,
                    'show_filters' => true,
                ]);
                ?>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    <?php
                    the_posts_pagination(array(
                        'mid_size' => 2,
                        'prev_text' => __('← Previous', 'wordwind'),
                        'next_text' => __('Next →', 'wordwind'),
                    ));
                    ?>
                </div>
            <?php else : ?>
                <div class="text-center py-16">
                    <p class="text-xl text-gray-600">No athletes found.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>
