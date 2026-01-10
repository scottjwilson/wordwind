<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

  <?php
    // Get associated athlete
    $athlete = get_field('associated_athlete'); // Adjust field name if different (e.g., 'associated_athlete')
    $athlete_id = 0;
    $athlete_name = '';
    $athlete_permalink = '';
    $athlete_image = '';

    if ($athlete) {
      // Handle ACF relationship field (can be object, array, or ID)
      if (is_array($athlete)) {
        $athlete = $athlete[0]; // Get first athlete if multiple
      }
      if (is_object($athlete) && isset($athlete->ID)) {
        $athlete_id = $athlete->ID;
        $athlete_name = $athlete->post_title;
        $athlete_permalink = get_permalink($athlete->ID);
        $athlete_image = get_the_post_thumbnail($athlete->ID, 'thumbnail', array('class' => 'w-12 h-12 object-cover'));
      } elseif (is_numeric($athlete)) {
        $athlete_id = $athlete;
        $athlete_name = get_the_title($athlete);
        $athlete_permalink = get_permalink($athlete);
        $athlete_image = get_the_post_thumbnail($athlete, 'thumbnail', array('class' => 'w-12 h-12  object-cover'));
      }
    }

    // Get categories
    $categories = get_the_category();
  ?>

  <main class="site-main bg-gray-100 py-4">
      <div class="max-w-4xl mx-auto bg-white rounded-md">
    <!-- Header Section -->
    <section class="">
      <div class="max-w-4xl px-4 py-4 mx-auto">
        <!-- Post Meta -->
        <div class="flex items-center justify-center py-4">
          <?php if (!empty($categories)) : ?>
            <?php foreach (array_slice($categories, 0, 2) as $category) : ?>
              <span class="text-bsj-gold text-xs font-bold uppercase tracking-wide px-4">
                <?php echo esc_html($category->name); ?>
              </span>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <p class="text-sm text-gray-500 text-center">
          <?php echo get_the_date('M j, Y'); ?>
        </p>

        <!-- Post Title -->
        <h1 class="text-3xl md:text-4xl font-bold text-center">
          <?php the_title(); ?>
        </h1>

        <!-- Associated Athlete Link -->
        <?php if ($athlete_id) : ?>
          <div class=" pt-4 flex items-center justify-center">
            <a href="<?php echo esc_url($athlete_permalink); ?>" class="inline-flex items-center gap-3 p-4">
              <?php if ($athlete_image) : ?>
                <?php echo $athlete_image; ?>
              <?php else : ?>
                <div class="w-12 h-12 flex items-center justify-center">
                  <span class="text-white text-lg font-bold"><?php echo strtoupper(substr($athlete_name, 0, 2)); ?></span>
                </div>
              <?php endif; ?>
              <div>
                <div class="text-xs text-bsj-gold font-bold uppercase tracking-wide mb-1 ">Featuring</div>
                <div class="font-semibold transition">
                  <?php echo esc_html($athlete_name); ?>
                </div>
              </div>
              <svg class="w-5 h-5 ml-auto text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </a>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <!-- Featured Image -->
    <?php if (has_post_thumbnail()) : ?>
      <section>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="overflow-hidden">
            <?php the_post_thumbnail('large', array('class' => 'w-full h-auto')); ?>
          </div>
        </div>
      </section>
    <?php endif; ?>

    <!-- Main Content Section -->
    <section class="py-8">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="prose  max-w-none prose-headings:text-bsj-navy prose-headings:font-bold prose-a:text-bsj-blue prose-a:no-underline hover:prose-a:underline prose-strong:text-bsj-navy prose-img:rounded-lg prose-blockquote:border-l-bsj-gold prose-blockquote:bg-gray-50 prose-blockquote:py-4 prose-blockquote:px-6">
  <?php the_content(); ?>
</div>



        <!-- Post Footer -->
        <div class="mt-12 pt-8 border-t border-gray-200">
          <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
              <?php if (!empty($categories)) : ?>
                <div class="flex items-center gap-2 flex-wrap">
                  <span class="text-sm text-gray-600 font-medium">Categories:</span>
                  <?php foreach ($categories as $category) : ?>
                    <a href="<?php echo get_category_link($category->term_id); ?>" class="text-bsj-blue hover:text-bsj-navy text-sm font-medium transition">
                      <?php echo esc_html($category->name); ?>
                    </a>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>

            <!-- Share or additional actions can go here -->
          </div>
        </div>
      </div>
    </section>

    <!-- Related Posts Section (Optional) -->
    <?php
    $related_posts = new WP_Query(array(
      'post_type' => 'post',
      'posts_per_page' => 3,
      'post__not_in' => array(get_the_ID()),
      'category__in' => wp_list_pluck($categories, 'term_id'),
      'orderby' => 'rand'
    ));

    if ($related_posts->have_posts()) : ?>
      <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 class="text-3xl font-bold text-bsj-navy mb-8">Related Articles</h2>
          <div class="grid md:grid-cols-3 gap-6">
            <?php while ($related_posts->have_posts()) : $related_posts->the_post(); ?>
              <article class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                <a href="<?php the_permalink(); ?>" class="block">
                  <?php if (has_post_thumbnail()) : ?>
                    <div class="aspect-video w-full overflow-hidden bg-gray-200">
                      <?php the_post_thumbnail('medium', array('class' => 'w-full h-full object-cover')); ?>
                    </div>
                  <?php endif; ?>
                  <div class="p-6">
                    <h3 class="text-lg font-bold text-bsj-navy hover:text-bsj-blue transition mb-2">
                      <?php the_title(); ?>
                    </h3>
                    <p class="text-sm text-gray-500">
                      <?php echo get_the_date('M j, Y'); ?>
                    </p>
                  </div>
                </a>
              </article>
            <?php endwhile; ?>
          </div>
        </div>
      </section>
      <?php wp_reset_postdata(); ?>
    <?php endif; ?>
      </div>

  </main>

<?php endwhile; ?>

<?php get_footer(); ?>
