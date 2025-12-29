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
                <div class="space-y-4">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php 
                        $fields = get_athlete_fields();
                        ?>
                        <article class="bg-white border border-gray-200 rounded-lg hover:shadow-lg transition-shadow overflow-hidden">
                            <a href="<?php the_permalink(); ?>" class="block">
                                <div class="flex items-center gap-6 p-6">
                                    <!-- Athlete Image -->
                                    <div class="flex-shrink-0">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('medium', array('class' => 'w-24 h-24 rounded-lg object-cover border-2 border-gray-200')); ?>
                                        <?php else : ?>
                                            <div class="w-24 h-24 rounded-lg bg-gradient-to-br from-bsj-navy to-blue-900 flex items-center justify-center border-2 border-gray-200">
                                                <span class="text-white text-2xl font-bold"><?php echo strtoupper(substr(get_the_title(), 0, 2)); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Athlete Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-4">
                                            <div class="flex-1">
                                                <h3 class="text-xl font-bold text-bsj-navy mb-1 hover:text-bsj-blue transition">
                                                    <?php the_title(); ?>
                                                </h3>
                                                <div class="flex items-center gap-4 flex-wrap text-sm text-gray-600 mb-2">
                                                    <?php if ($fields['position']) : ?>
                                                        <span class="font-medium"><?php echo esc_html($fields['position']); ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($fields['class_year']) : ?>
                                                        <span>•</span>
                                                        <span><?php echo esc_html($fields['class_year']); ?></span>
                                                    <?php endif; ?>
                                                    <?php if ($fields['physical_stats']) : ?>
                                                        <span>•</span>
                                                        <span><?php echo esc_html($fields['physical_stats']); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="flex items-center gap-6 flex-wrap">
                                                    <?php if ($fields['school_id']) : ?>
                                                        <div class="flex items-center gap-2">
                                                            <?php 
                                                            $school_logo = get_the_post_thumbnail($fields['school_id'], 'thumbnail', array('class' => 'w-6 h-6 object-contain'));
                                                            if ($school_logo) {
                                                                echo $school_logo;
                                                            }
                                                            ?>
                                                            <span class="text-sm text-gray-700 font-medium"><?php echo esc_html($fields['school_name']); ?></span>
                                                        </div>
                                                    <?php endif; ?>
                                                    
                                                    <?php if (!empty($fields['sponsor_images'])) : ?>
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-xs text-gray-500">Sponsors:</span>
                                                            <div class="flex gap-1">
                                                                <?php foreach ($fields['sponsor_images'] as $image) : ?>
                                                                    <div class="flex-shrink-0"><?php echo $image; ?></div>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            
                                            <!-- NIL Valuation -->
                                            <div class="flex-shrink-0 text-right">
                                                <?php if ($fields['nil_valuation']) : ?>
                                                    <div class="bg-gradient-to-br from-bsj-navy to-blue-900 text-white rounded-lg p-4 min-w-[140px]">
                                                        <div class="text-xs text-bsj-gold font-bold mb-1 uppercase tracking-wide">NIL Value</div>
                                                        <div class="text-2xl font-bold">
                                                            <?php echo format_nil_valuation($fields['nil_valuation']); ?>
                                                        </div>
                                                    </div>
                                                <?php else : ?>
                                                    <div class="bg-gray-100 text-gray-500 rounded-lg p-4 min-w-[140px] text-center">
                                                        <div class="text-xs font-bold mb-1 uppercase">NIL Value</div>
                                                        <div class="text-lg">—</div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Arrow Icon -->
                                    <div class="flex-shrink-0 text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
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
                    <p class="text-xl text-gray-600">No athletes found.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>