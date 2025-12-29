<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
  
  <?php
    // Get ACF fields
    $position = get_field('position');
    $class_year = get_field('class_year') ?: get_field('year');
    $height = get_field('height');
    $weight = get_field('weight');
    $high_school = get_field('high_school');
    $high_school_location = get_field('high_school_location');
    $nil_valuation = get_field('nil_valuation') ?: get_field('valuation');

        // Get news posts
        $news = get_field('news');
        $news_posts = array();
        if ($news) {
          if (!is_array($news)) {
            $news = array($news);
          }
          foreach ($news as $news_item) {
            $news_id = 0;
            $news_title = '';
            $news_permalink = '';
            $news_excerpt = '';
            $news_date = '';
            $news_image = '';
            
            if (is_object($news_item) && isset($news_item->ID)) {
              $news_id = $news_item->ID;
              $news_title = $news_item->post_title;
              $news_permalink = get_permalink($news_item->ID);
              $news_excerpt = get_the_excerpt($news_item->ID);
              $news_date = get_the_date('F j, Y', $news_item->ID);
              $news_image = get_the_post_thumbnail($news_id, 'medium', array('class' => 'w-full h-48 object-cover rounded-lg'));
            } elseif (is_numeric($news_item)) {
              $news_id = $news_item;
              $news_title = get_the_title($news_id);
              $news_permalink = get_permalink($news_id);
              $news_excerpt = get_the_excerpt($news_id);
              $news_date = get_the_date('F j, Y', $news_id);
              $news_image = get_the_post_thumbnail($news_id, 'medium', array('class' => 'w-full h-48 object-cover rounded-lg'));
            }
            
            if ($news_id) {
              $news_posts[] = array(
                'id' => $news_id,
                'title' => $news_title,
                'permalink' => $news_permalink,
                'excerpt' => $news_excerpt,
                'date' => $news_date,
                'image' => $news_image
              );
            }
          }
        }
    
    // Get school
    $school = get_field('school');
    $school_id = 0;
    $school_name = '';
    $school_permalink = '';
    if ($school) {
      if (is_array($school)) {
        $school = $school[0];
      }
      if (is_object($school) && isset($school->ID)) {
        $school_id = $school->ID;
        $school_name = $school->post_title;
        $school_permalink = get_permalink($school->ID);
      } elseif (is_numeric($school)) {
        $school_id = $school;
        $school_name = get_the_title($school);
        $school_permalink = get_permalink($school);
      }
    }
    
    // Get sponsors
    $sponsors = get_field('sponsors');
    $sponsor_data = array();
    if ($sponsors) {
      if (!is_array($sponsors)) {
        $sponsors = array($sponsors);
      }
      foreach ($sponsors as $sponsor) {
        $sponsor_id = 0;
        $sponsor_name = '';
        $sponsor_permalink = '';
        if (is_object($sponsor) && isset($sponsor->ID)) {
          $sponsor_id = $sponsor->ID;
          $sponsor_name = $sponsor->post_title;
          $sponsor_permalink = get_permalink($sponsor->ID);
        } elseif (is_numeric($sponsor)) {
          $sponsor_id = $sponsor;
          $sponsor_name = get_the_title($sponsor);
          $sponsor_permalink = get_permalink($sponsor);
        }
        if ($sponsor_id) {
          $sponsor_image = get_the_post_thumbnail($sponsor_id, 'medium', array('class' => 'w-16 h-16 object-contain'));
          $sponsor_data[] = array(
            'id' => $sponsor_id,
            'name' => $sponsor_name,
            'image' => $sponsor_image,
            'permalink' => $sponsor_permalink
          );
        }
      }
    }
    
    // Format physical stats
    $physical_stats = '';
    if ($height && $weight) {
      $physical_stats = $height . ' / ' . $weight;
    } elseif ($height) {
      $physical_stats = $height;
    } elseif ($weight) {
      $physical_stats = $weight;
    }
  ?>

  <main class="site-main">
    <!-- Hero Section -->

    <section class="bg-gradient-to-br from-bsj-navy to-blue-900 text-white py-12">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-6">
          <!-- <a href="<?php echo get_post_type_archive_link('athlete'); ?>" class="text-bsj-gold hover:text-yellow-400 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Athletes
          </a> -->
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 items-start">
          <!-- Athlete Photo -->
          <div class="md:col-span-1">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 border border-white/20">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('large', array('class' => 'w-full h-auto rounded-lg')); ?>
              <?php else : ?>
                <div class="w-full aspect-square bg-gray-700 rounded-lg flex items-center justify-center">
                  <span class="text-6xl font-bold text-gray-500"><?php echo strtoupper(substr(get_the_title(), 0, 2)); ?></span>
                </div>
              <?php endif; ?>
            </div>
          </div>
          
          <!-- Athlete Info -->
          <div class="md:col-span-2">
            <h1 class="text-5xl font-bold mb-4"><?php the_title(); ?></h1>
            
            <div class="flex flex-wrap items-center gap-4 mb-6">
              <?php if ($position) : ?>
                <span class="bg-bsj-gold text-bsj-navy px-4 py-2 rounded-full text-sm font-bold">
                  <?php echo esc_html($position); ?>
                </span>
              <?php endif; ?>
              
              <?php if ($class_year) : ?>
                <span class="text-gray-300 text-lg"><?php echo esc_html($class_year); ?></span>
              <?php endif; ?>
              
              <?php if ($physical_stats) : ?>
                <span class="text-gray-300 text-lg"><?php echo esc_html($physical_stats); ?></span>
              <?php endif; ?>
            </div>
            
            <?php if ($school_id) : ?>
              <div class="flex items-center gap-3 mb-6">
                <?php 
                $school_logo = get_the_post_thumbnail($school_id, 'thumbnail', array('class' => 'w-12 h-12 object-contain'));
                if ($school_logo) {
                  echo $school_logo;
                }
                ?>
                <?php if ($school_permalink) : ?>
                  <a href="<?php echo esc_url($school_permalink); ?>" class="text-xl font-semibold hover:text-bsj-gold transition">
                    <?php echo esc_html($school_name); ?>
                  </a>
                <?php else : ?>
                  <span class="text-xl font-semibold"><?php echo esc_html($school_name); ?></span>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            
            <?php if ($high_school) : ?>
              <div class="text-gray-300 mb-2">
                <span class="font-semibold">High School:</span> 
                <?php echo esc_html($high_school); ?>
                <?php if ($high_school_location) : ?>
                  <span class="text-gray-400">(<?php echo esc_html($high_school_location); ?>)</span>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            
            <?php if ($nil_valuation) : ?>
              <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20 mt-6">
                <div class="text-sm text-bsj-gold font-bold mb-1">NIL VALUATION</div>
                <div class="text-3xl font-bold">
                  <?php 
                  if (is_numeric($nil_valuation)) {
                    if ($nil_valuation >= 1000000) {
                      echo '$' . number_format($nil_valuation / 1000000, 1) . 'M';
                    } elseif ($nil_valuation >= 1000) {
                      echo '$' . number_format($nil_valuation / 1000, 0) . 'K';
                    } else {
                      echo '$' . number_format($nil_valuation);
                    }
                  } else {
                    echo esc_html($nil_valuation);
                  }
                  ?>
                </div>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Main Content Section -->
    <section class="py-16 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-3 gap-8">
          <!-- Main Content -->
          <div class="lg:col-span-2">

          <?php if (!empty($news_posts)) : ?>
              <div class="mb-8">
                <h2 class="text-3xl font-bold text-bsj-navy mb-6">News</h2>
                <div class="space-y-6">
                  <?php foreach ($news_posts as $news_post) : ?>
                    <article class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                      <a href="<?php echo esc_url($news_post['permalink']); ?>" class="block">
                        <div class="md:flex">
                          <?php if ($news_post['image']) : ?>
                            <div class="md:w-1/3 flex-shrink-0">
                              <?php echo $news_post['image']; ?>
                            </div>
                          <?php endif; ?>
                          <div class="<?php echo $news_post['image'] ? 'md:w-2/3' : 'w-full'; ?> p-6">
                            <h3 class="text-xl font-bold text-bsj-navy hover:text-bsj-blue transition mb-2">
                              <?php echo esc_html($news_post['title']); ?>
                            </h3>
                            <?php if ($news_post['date']) : ?>
                              <p class="text-sm text-gray-500 mb-3">
                                <?php echo esc_html($news_post['date']); ?>
                              </p>
                            <?php endif; ?>
                            <?php if ($news_post['excerpt']) : ?>
                              <p class="text-gray-700">
                                <!-- <?php echo esc_html($news_post['excerpt']); ?> -->
                              </p>
                            <?php endif; ?>
                          </div>
                        </div>
                      </a>
                    </article>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endif; ?>

        
            
            <!-- Additional Content Sections -->
            <?php if (get_field('bio') || get_field('achievements') || get_field('stats')) : ?>
              <div class="space-y-8">
                <?php if (get_field('bio')) : ?>
                  <div>
                    <h2 class="text-2xl font-bold text-bsj-navy mb-4">Biography</h2>
                    <div class="prose max-w-none text-gray-700">
                      <?php the_field('bio'); ?>
                    </div>
                  </div>
                <?php endif; ?>
                
                <?php if (get_field('achievements')) : ?>
                  <div>
                    <h2 class="text-2xl font-bold text-bsj-navy mb-4">Achievements</h2>
                    <div class="prose max-w-none text-gray-700">
                      <?php the_field('achievements'); ?>
                    </div>
                  </div>
                <?php endif; ?>
                
                <?php if (get_field('stats')) : ?>
                  <div>
                    <h2 class="text-2xl font-bold text-bsj-navy mb-4">Statistics</h2>
                    <div class="prose max-w-none text-gray-700">
                      <?php the_field('stats'); ?>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            <?php endif; ?>
          </div>
          
          <!-- Sidebar -->
          <div class="lg:col-span-1">
            <div class="space-y-6">
              <!-- Sponsors Card -->
              <?php if (!empty($sponsor_data)) : ?>
                <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                  <h3 class="text-xl font-bold text-bsj-navy mb-4">Sponsors</h3>
                  <div class="space-y-4">
                    <?php foreach ($sponsor_data as $sponsor) : ?>
                      <div class="flex items-center gap-3 p-3 bg-white rounded-lg border border-gray-200 hover:border-bsj-gold transition">
                        <?php if ($sponsor['image']) : ?>
                          <div class="flex-shrink-0">
                            <?php echo $sponsor['image']; ?>
                          </div>
                        <?php endif; ?>
                        <div class="flex-1 min-w-0">
                          <?php if ($sponsor['permalink']) : ?>
                            <a href="<?php echo esc_url($sponsor['permalink']); ?>" class="font-semibold text-bsj-navy hover:text-bsj-blue transition block">
                              <?php echo esc_html($sponsor['name']); ?>
                            </a>
                          <?php else : ?>
                            <span class="font-semibold text-bsj-navy"><?php echo esc_html($sponsor['name']); ?></span>
                          <?php endif; ?>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              <?php endif; ?>
              
              <!-- Quick Stats Card -->
              <div class="bg-gray-50 rounded-lg p-6 border border-gray-200">
                <h3 class="text-xl font-bold text-bsj-navy mb-4">Quick Facts</h3>
                <dl class="space-y-3">
                  <?php if ($position) : ?>
                    <div class="flex justify-between">
                      <dt class="text-gray-600">Position</dt>
                      <dd class="font-semibold text-bsj-navy"><?php echo esc_html($position); ?></dd>
                    </div>
                  <?php endif; ?>
                  
                  <?php if ($class_year) : ?>
                    <div class="flex justify-between">
                      <dt class="text-gray-600">Class</dt>
                      <dd class="font-semibold text-bsj-navy"><?php echo esc_html($class_year); ?></dd>
                    </div>
                  <?php endif; ?>
                  
                  <?php if ($height) : ?>
                    <div class="flex justify-between">
                      <dt class="text-gray-600">Height</dt>
                      <dd class="font-semibold text-bsj-navy"><?php echo esc_html($height); ?></dd>
                    </div>
                  <?php endif; ?>
                  
                  <?php if ($weight) : ?>
                    <div class="flex justify-between">
                      <dt class="text-gray-600">Weight</dt>
                      <dd class="font-semibold text-bsj-navy"><?php echo esc_html($weight); ?></dd>
                    </div>
                  <?php endif; ?>
                  
                  <?php if ($high_school) : ?>
                    <div class="flex justify-between">
                      <dt class="text-gray-600">High School</dt>
                      <dd class="font-semibold text-bsj-navy text-right"><?php echo esc_html($high_school); ?></dd>
                    </div>
                  <?php endif; ?>
                  
                  <?php if ($school_name) : ?>
                    <div class="flex justify-between">
                      <dt class="text-gray-600">School</dt>
                      <dd class="font-semibold text-bsj-navy text-right"><?php echo esc_html($school_name); ?></dd>
                    </div>
                  <?php endif; ?>
                </dl>
              </div>
              
              <!-- Share Card -->
              <div class="bg-bsj-navy rounded-lg p-6 text-white">
                <h3 class="text-xl font-bold mb-4">Share This Profile</h3>
                <div class="flex gap-3">
                  <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode(get_the_title()); ?>&url=<?php echo urlencode(get_permalink()); ?>" 
                     target="_blank" 
                     class="bg-blue-500 hover:bg-blue-600 px-4 py-2 rounded-md transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
                    </svg>
                    Twitter
                  </a>
                  <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                     target="_blank" 
                     class="bg-blue-700 hover:bg-blue-800 px-4 py-2 rounded-md transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                    </svg>
                    Facebook
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    
    <!-- Related Athletes Section (Optional) -->
    <?php
    $related_athletes = new WP_Query(array(
      'post_type' => 'athlete',
      'posts_per_page' => 4,
      'post__not_in' => array(get_the_ID()),
      'orderby' => 'rand'
    ));
    
    if ($related_athletes->have_posts()) : ?>
      <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 class="text-3xl font-bold text-bsj-navy mb-8">Related Athletes</h2>
          <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php while ($related_athletes->have_posts()) : $related_athletes->the_post(); ?>
              <article class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                <a href="<?php the_permalink(); ?>" class="block">
                  <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover rounded-lg mb-3')); ?>
                  <?php endif; ?>
                  <h3 class="font-bold text-bsj-navy hover:text-bsj-blue transition">
                    <?php the_title(); ?>
                  </h3>
                  <?php if (get_field('position')) : ?>
                    <p class="text-sm text-gray-600 mt-1"><?php the_field('position'); ?></p>
                  <?php endif; ?>
                </a>
              </article>
            <?php endwhile; ?>
          </div>
        </div>
      </section>
      <?php wp_reset_postdata(); ?>
    <?php endif; ?>
    
  </main>

<?php endwhile; ?>

<?php get_footer(); ?>