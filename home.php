<?php get_header(); ?>

<main class="site-main">
    <!-- Header Section -->
    <?php getHeaderSection('News', 'Latest sports news, analysis, and coverage'); ?>
    <!-- News List Section -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- News List (2 columns) -->
                <div class="lg:col-span-2">
                    <?php if (have_posts()) : ?>
                        <div class="space-y-6">
                            <?php while (have_posts()) : the_post(); ?>
                                <article class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                                    <a href="<?php the_permalink(); ?>" class="block">
                                        <div class="md:flex">
                                            <?php if (has_post_thumbnail()) : ?>
                                                <div class="md:w-1/3 flex-shrink-0">
                                                    <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover rounded-lg')); ?>
                                                </div>
                                            <?php endif; ?>
                                            <div class="<?php echo has_post_thumbnail() ? 'md:w-2/3' : 'w-full'; ?> p-6">
                                                <h2 class="text-xl font-bold text-bsj-navy hover:text-bsj-blue transition mb-2">
                                                    <?php the_title(); ?>
                                                </h2>
                                                <?php if (get_the_date()) : ?>
                                                    <p class="text-sm text-gray-500 mb-3">
                                                        <?php echo get_the_date('F j, Y'); ?>
                                                    </p>
                                                <?php endif; ?>
                                                <?php if (get_the_excerpt()) : ?>
                                                    <p class="text-gray-700">
                                                        <?php echo esc_html(get_the_excerpt()); ?>
                                                    </p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </a>
                                </article>
                            <?php endwhile; ?>
                        </div>
                        
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
                            <p class="text-xl text-gray-600">No news found.</p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Categories Sidebar (1 column) -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-bsj-navy mb-4">Categories</h3>
                        <?php
                        $categories = get_categories(array(
                            'orderby' => 'name',
                            'order' => 'ASC',
                            'hide_empty' => true
                        ));
                        
                        if (!empty($categories)) : ?>
                            <ul class="space-y-2">
                                <?php foreach ($categories as $category) : ?>
                                    <li>
                                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                           class="text-bsj-navy hover:text-bsj-blue transition flex items-center justify-between group">
                                            <span><?php echo esc_html($category->name); ?></span>
                                            <span class="text-sm text-gray-500 group-hover:text-bsj-blue">
                                                (<?php echo $category->count; ?>)
                                            </span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <p class="text-gray-600">No categories found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>