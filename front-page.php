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
        <div class="flex items-center justify-between mb-8">
          <h2 class="text-3xl font-bold text-bsj-navy">Athletes</h2>
          <div class="text-sm text-gray-600">NIL Valuations</div>
        </div>
                <!-- Desktop Table View -->
                <div class="hidden lg:block overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
              <tr>
                <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase">Rank</th>
                <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase">Player</th>
                <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase">NIL Valuation</th>
                <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase">School</th>
                <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase">Sponsors</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
              <?php 
              $rank = 1;
              while($athletes->have_posts()) {
                $athletes->the_post(); 
                
                // Get ACF fields
                $position = get_field('position');
                $class_year = get_field('class_year') ?: get_field('year');
                $height = get_field('height');
                $weight = get_field('weight');
                $high_school = get_field('high_school');
                $nil_valuation = get_field('nil_valuation') ?: get_field('valuation');
                
                // Get school
                $school = get_field('school');
                $school_id = 0;
                $school_name = '';
                if ($school) {
                  if (is_array($school)) {
                    $school = $school[0];
                  }
                  if (is_object($school) && isset($school->ID)) {
                    $school_id = $school->ID;
                    $school_name = $school->post_title;
                  } elseif (is_numeric($school)) {
                    $school_id = $school;
                    $school_name = get_the_title($school);
                  }
                }
                
                // Get sponsors
                $sponsors = get_field('sponsors');
                $sponsor_images = array();
                if ($sponsors) {
                  if (!is_array($sponsors)) {
                    $sponsors = array($sponsors);
                  }
                  foreach ($sponsors as $sponsor) {
                    $sponsor_id = 0;
                    if (is_object($sponsor) && isset($sponsor->ID)) {
                      $sponsor_id = $sponsor->ID;
                    } elseif (is_numeric($sponsor)) {
                      $sponsor_id = $sponsor;
                    }
                    if ($sponsor_id) {
                      $sponsor_image = get_the_post_thumbnail($sponsor_id, 'thumbnail', array('class' => 'w-8 h-8 object-contain'));
                      if ($sponsor_image) {
                        $sponsor_images[] = $sponsor_image;
                      }
                    }
                  }
                }
                
                // Format height/weight
                $physical_stats = '';
                if ($height && $weight) {
                  $physical_stats = $height . '/' . $weight;
                } elseif ($height) {
                  $physical_stats = $height;
                } elseif ($weight) {
                  $physical_stats = $weight . ' lbs';
                }
                
                // Format class/year
                $class_display = '';
                if ($class_year) {
                  $class_display = $class_year;
                }
                
                // Build player info line
                $player_info = array();
                if ($class_display) $player_info[] = $class_display;
                if ($physical_stats) $player_info[] = $physical_stats;
                if ($high_school) $player_info[] = $high_school;
              ?>
                <tr class="hover:bg-gray-50 transition-colors">
                  <td class="py-4 px-4">
                    <span class="text-lg font-bold text-bsj-navy"><?php echo $rank; ?></span>
                  </td>
                  <td class="py-4 px-4">
                    <div class="flex items-center gap-3">
                      <div class="flex-shrink-0">
                        <?php if (has_post_thumbnail()) : ?>
                          <?php the_post_thumbnail('thumbnail', array('class' => 'w-12 h-12 rounded-full object-cover')); ?>
                        <?php else : ?>
                          <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400 text-xs font-bold"><?php echo strtoupper(substr(get_the_title(), 0, 2)); ?></span>
                          </div>
                        <?php endif; ?>
                      </div>
                      <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                          <a href="<?php the_permalink(); ?>" class="font-bold text-bsj-navy hover:text-bsj-blue transition">
                            <?php the_title(); ?>
                          </a>
                          <?php if ($position) : ?>
                            <span class="text-xs text-gray-500 font-medium"><?php echo esc_html($position); ?></span>
                          <?php endif; ?>
                        </div>
                        <?php if (!empty($player_info)) : ?>
                          <div class="text-xs text-gray-500 mt-1">
                            <?php echo esc_html(implode(' / ', $player_info)); ?>
                          </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </td>
                  <td class="py-4 px-4">
                    <?php if ($nil_valuation) : ?>
                      <span class="text-sm font-bold text-bsj-navy">
                        <?php 
                        // Format as currency if it's a number
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
                      </span>
                    <?php else : ?>
                      <span class="text-sm text-gray-400">—</span>
                    <?php endif; ?>
                  </td>
                  <td class="py-4 px-4">
                    <?php if ($school_id) : ?>
                      <div class="flex items-center gap-2">
                        <?php 
                        $school_logo = get_the_post_thumbnail($school_id, 'thumbnail', array('class' => 'w-6 h-6 object-contain'));
                        if ($school_logo) {
                          echo $school_logo;
                        }
                        ?>

                      </div>
                    <?php else : ?>
                      <span class="text-sm text-gray-400">—</span>
                    <?php endif; ?>
                  </td>
                  <td class="py-4 px-4">
                    <?php if (!empty($sponsor_images)) : ?>
                      <div class="flex gap-1 flex-wrap">
                        <?php foreach ($sponsor_images as $image) : ?>
                          <div class="flex-shrink-0"><?php echo $image; ?></div>
                        <?php endforeach; ?>
                      </div>
                    <?php else : ?>
                      <span class="text-sm text-gray-400">—</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php 
                $rank++;
              } ?>
            </tbody>
          </table>
        </div>
        
        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
          <?php 
          $rank = 1;
          while($athletes->have_posts()) {
            $athletes->the_post(); 
            
            // Get ACF fields
            $position = get_field('position');
            $class_year = get_field('class_year') ?: get_field('year');
            $height = get_field('height');
            $weight = get_field('weight');
            $high_school = get_field('high_school');
            $nil_valuation = get_field('nil_valuation') ?: get_field('valuation');
            
            // Get school
            $school = get_field('school');
            $school_id = 0;
            $school_name = '';
            if ($school) {
              if (is_array($school)) {
                $school = $school[0];
              }
              if (is_object($school) && isset($school->ID)) {
                $school_id = $school->ID;
                $school_name = $school->post_title;
              } elseif (is_numeric($school)) {
                $school_id = $school;
                $school_name = get_the_title($school);
              }
            }
            
            // Get sponsors
            $sponsors = get_field('sponsors');
            $sponsor_images = array();
            if ($sponsors) {
              if (!is_array($sponsors)) {
                $sponsors = array($sponsors);
              }
              foreach ($sponsors as $sponsor) {
                $sponsor_id = 0;
                if (is_object($sponsor) && isset($sponsor->ID)) {
                  $sponsor_id = $sponsor->ID;
                } elseif (is_numeric($sponsor)) {
                  $sponsor_id = $sponsor;
                }
                if ($sponsor_id) {
                  $sponsor_image = get_the_post_thumbnail($sponsor_id, 'thumbnail', array('class' => 'w-8 h-8 object-contain'));
                  if ($sponsor_image) {
                    $sponsor_images[] = $sponsor_image;
                  }
                }
              }
            }
            
            // Format values
            $physical_stats = '';
            if ($height && $weight) {
              $physical_stats = $height . '/' . $weight;
            } elseif ($height) {
              $physical_stats = $height;
            } elseif ($weight) {
              $physical_stats = $weight . ' lbs';
            }
            
            $class_display = $class_year ?: '';
            $player_info = array();
            if ($class_display) $player_info[] = $class_display;
            if ($physical_stats) $player_info[] = $physical_stats;
            if ($high_school) $player_info[] = $high_school;
          ?>
            <article class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
              <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3 flex-1">
                  <span class="text-lg font-bold text-bsj-navy"><?php echo $rank; ?></span>
                  <div class="flex-shrink-0">
                    <?php if (has_post_thumbnail()) : ?>
                      <?php the_post_thumbnail('thumbnail', array('class' => 'w-12 h-12 rounded-full object-cover')); ?>
                    <?php else : ?>
                      <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400 text-xs font-bold"><?php echo strtoupper(substr(get_the_title(), 0, 2)); ?></span>
                      </div>
                    <?php endif; ?>
                  </div>
                  <div class="min-w-0 flex-1">
                    <a href="<?php the_permalink(); ?>" class="font-bold text-bsj-navy hover:text-bsj-blue transition block">
                      <?php the_title(); ?>
                    </a>
                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                      <?php if ($position) : ?>
                        <span class="text-xs text-gray-500 font-medium"><?php echo esc_html($position); ?></span>
                      <?php endif; ?>
                    </div>
                    <?php if (!empty($player_info)) : ?>
                      <div class="text-xs text-gray-500 mt-1">
                        <?php echo esc_html(implode(' / ', $player_info)); ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              
              <div class="grid grid-cols-2 gap-3 text-sm">
                <?php if ($nil_valuation) : ?>
                  <div>
                    <span class="text-xs text-gray-500 block mb-1">NIL Valuation</span>
                    <span class="font-bold text-bsj-navy">
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
                    </span>
                  </div>
                <?php endif; ?>
                
                <?php if ($school_id) : ?>
                  <div>
                    <span class="text-xs text-gray-500 block mb-1">School</span>
                    <div class="flex items-center gap-2">
                      <?php 
                      $school_logo = get_the_post_thumbnail($school_id, 'thumbnail', array('class' => 'w-6 h-6 object-contain'));
                      if ($school_logo) {
                        echo $school_logo;
                      }
                      ?>

                    </div>
                  </div>
                <?php endif; ?>
                
                <?php if (!empty($sponsor_images)) : ?>
                  <div>
                    <span class="text-xs text-gray-500 block mb-1">Sponsors</span>
                    <div class="flex gap-1 flex-wrap">
                      <?php foreach ($sponsor_images as $image) : ?>
                        <div class="flex-shrink-0"><?php echo $image; ?></div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </article>
          <?php 
            $rank++;
          } ?>
        </div>
        
        <!-- Mobile Card View -->
        <div class="lg:hidden space-y-4">
          <?php 
          $rank = 1;
          while($athletes->have_posts()) {
            $athletes->the_post(); 
            
            // Get ACF fields (same as above)
            $position = get_field('position');
            $class_year = get_field('class_year') ?: get_field('year');
            $height = get_field('height');
            $weight = get_field('weight');
            $high_school = get_field('high_school');
            $rating = get_field('rating') ?: get_field('evaluation');
            $status = get_field('status');
            $nil_valuation = get_field('nil_valuation') ?: get_field('valuation');
            
            // Get school
            $school = get_field('school');
            $school_id = 0;
            $school_name = '';
            if ($school) {
              if (is_array($school)) {
                $school = $school[0];
              }
              if (is_object($school) && isset($school->ID)) {
                $school_id = $school->ID;
                $school_name = $school->post_title;
              } elseif (is_numeric($school)) {
                $school_id = $school;
                $school_name = get_the_title($school);
              }
            }
            
            // Get sponsors
            $sponsors = get_field('sponsors');
            $sponsor_images = array();
            if ($sponsors) {
              if (!is_array($sponsors)) {
                $sponsors = array($sponsors);
              }
              foreach ($sponsors as $sponsor) {
                $sponsor_id = 0;
                if (is_object($sponsor) && isset($sponsor->ID)) {
                  $sponsor_id = $sponsor->ID;
                } elseif (is_numeric($sponsor)) {
                  $sponsor_id = $sponsor;
                }
                if ($sponsor_id) {
                  $sponsor_image = get_the_post_thumbnail($sponsor_id, 'thumbnail', array('class' => 'w-8 h-8 object-contain'));
                  if ($sponsor_image) {
                    $sponsor_images[] = $sponsor_image;
                  }
                }
              }
            }
            
            // Format values
            $physical_stats = '';
            if ($height && $weight) {
              $physical_stats = $height . '/' . $weight;
            } elseif ($height) {
              $physical_stats = $height;
            } elseif ($weight) {
              $physical_stats = $weight . ' lbs';
            }
            
            $class_display = $class_year ?: '';
            $player_info = array();
            if ($class_display) $player_info[] = $class_display;
            if ($physical_stats) $player_info[] = $physical_stats;
            if ($high_school) $player_info[] = $high_school;
          ?>
            <article class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
              <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3 flex-1">
                  <span class="text-lg font-bold text-bsj-navy"><?php echo $rank; ?></span>
                  <div class="flex-shrink-0">
                    <?php if (has_post_thumbnail()) : ?>
                      <?php the_post_thumbnail('thumbnail', array('class' => 'w-12 h-12 rounded-full object-cover')); ?>
                    <?php else : ?>
                      <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400 text-xs font-bold"><?php echo strtoupper(substr(get_the_title(), 0, 2)); ?></span>
                      </div>
                    <?php endif; ?>
                  </div>
                  <div class="min-w-0 flex-1">
                    <a href="<?php the_permalink(); ?>" class="font-bold text-bsj-navy hover:text-bsj-blue transition block">
                      <?php the_title(); ?>
                    </a>
                    <div class="flex items-center gap-2 mt-1 flex-wrap">
                      <?php if ($position) : ?>
                        <span class="text-xs text-gray-500 font-medium"><?php echo esc_html($position); ?></span>
                      <?php endif; ?>
                      <?php if ($rating) : ?>
                        <span class="text-xs font-bold text-bsj-navy"><?php echo esc_html($rating); ?></span>
                      <?php endif; ?>
                    </div>
                    <?php if (!empty($player_info)) : ?>
                      <div class="text-xs text-gray-500 mt-1">
                        <?php echo esc_html(implode(' / ', $player_info)); ?>
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              
              <div class="grid grid-cols-2 gap-3 text-sm">
                <?php if ($status) : ?>
                  <div>
                    <span class="text-xs text-gray-500 block mb-1">Status</span>
                    <span class="inline-block bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">
                      <?php echo esc_html($status); ?>
                    </span>
                  </div>
                <?php endif; ?>
                
                <?php if ($nil_valuation) : ?>
                  <div>
                    <span class="text-xs text-gray-500 block mb-1">NIL Valuation</span>
                    <span class="font-bold text-bsj-navy">
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
                    </span>
                  </div>
                <?php endif; ?>
                
                <?php if ($school_id) : ?>
                  <div>
                    <span class="text-xs text-gray-500 block mb-1">School</span>
                    <div class="flex items-center gap-2">
                      <?php 
                      $school_logo = get_the_post_thumbnail($school_id, 'thumbnail', array('class' => 'w-6 h-6 object-contain'));
                      if ($school_logo) {
                        echo $school_logo;
                      }
                      ?>
                     
                    </div>
                  </div>
                <?php endif; ?>
                
                <?php if (!empty($sponsor_images)) : ?>
                  <div>
                    <span class="text-xs text-gray-500 block mb-1">Sponsors</span>
                    <div class="flex gap-1 flex-wrap">
                      <?php foreach ($sponsor_images as $image) : ?>
                        <div class="flex-shrink-0"><?php echo $image; ?></div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </article>
          <?php 
            $rank++;
          } ?>
        </div>
      </div>
    </section>
    
    <?php wp_reset_postdata(); ?>
  <?php endif; ?>

</main>

<?php get_footer(); ?>
