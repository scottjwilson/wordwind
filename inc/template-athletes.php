<?php
/**
 * Athlete Template Functions
 */

/**
 * Format NIL valuation as currency
 */
function format_nil_valuation($valuation) {
    if (!$valuation) {
        return '—';
    }

    if (is_numeric($valuation)) {
        if ($valuation >= 1000000) {
            return '$' . number_format($valuation / 1000000, 1) . 'M';
        } elseif ($valuation >= 1000) {
            return '$' . number_format($valuation / 1000, 0) . 'K';
        } else {
            return '$' . number_format($valuation);
        }
    }

    return esc_html($valuation);
}

/**
 * Get athlete ACF fields in a standardized format
 */
function get_athlete_fields() {
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
                $sponsor_image = get_the_post_thumbnail($sponsor_id, 'thumbnail', array('class' => 'w-8 h-8 object-contain rounded'));
                if ($sponsor_image) {
                    $sponsor_images[] = $sponsor_image;
                }
            }
        }
    }

    // Format physical stats
    $physical_stats = '';
    if ($height && $weight) {
        $physical_stats = $height . '/' . $weight;
    } elseif ($height) {
        $physical_stats = $height;
    } elseif ($weight) {
        $physical_stats = $weight . ' lbs';
    }

    // Build player info
    $player_info = array();
    if ($class_year) $player_info[] = $class_year;
    if ($physical_stats) $player_info[] = $physical_stats;
    if ($high_school) $player_info[] = $high_school;

    return array(
        'position' => $position,
        'class_year' => $class_year,
        'physical_stats' => $physical_stats,
        'player_info' => $player_info,
        'nil_valuation' => $nil_valuation,
        'school_id' => $school_id,
        'school_name' => $school_name,
        'sponsor_images' => $sponsor_images
    );
}

/**
 * Get athlete fields by ID (for use outside the loop)
 */
function get_athlete_fields_by_id($athlete_id) {
    $position = get_field('position', $athlete_id);
    $class_year = get_field('class_year', $athlete_id) ?: get_field('year', $athlete_id);
    $height = get_field('height', $athlete_id);
    $weight = get_field('weight', $athlete_id);
    $high_school = get_field('high_school', $athlete_id);
    $nil_valuation = get_field('nil_valuation', $athlete_id) ?: get_field('valuation', $athlete_id);

    // Get school
    $school = get_field('school', $athlete_id);
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
    $sponsors = get_field('sponsors', $athlete_id);
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
                $sponsor_image = get_the_post_thumbnail($sponsor_id, 'thumbnail', array('class' => 'w-8 h-8 object-contain rounded'));
                if ($sponsor_image) {
                    $sponsor_images[] = $sponsor_image;
                }
            }
        }
    }

    // Format physical stats
    $physical_stats = '';
    if ($height && $weight) {
        $physical_stats = $height . '/' . $weight;
    } elseif ($height) {
        $physical_stats = $height;
    } elseif ($weight) {
        $physical_stats = $weight . ' lbs';
    }

    // Build player info
    $player_info = array();
    if ($class_year) $player_info[] = $class_year;
    if ($physical_stats) $player_info[] = $physical_stats;
    if ($high_school) $player_info[] = $high_school;

    return array(
        'position' => $position,
        'class_year' => $class_year,
        'physical_stats' => $physical_stats,
        'player_info' => $player_info,
        'nil_valuation' => $nil_valuation,
        'school_id' => $school_id,
        'school_name' => $school_name,
        'sponsor_images' => $sponsor_images
    );
}

/**
 * Render Athletes Table Component
 *
 * @param array $args Configuration options
 */
function render_athletes_table($args = array()) {
    $defaults = array(
        'athletes' => null,
        'view' => 'table',
        'show_rank' => true,
        'show_search' => false,
        'show_filters' => false,
        'container_class' => '',
        'title' => '',
        'title_right' => '',
    );

    $args = wp_parse_args($args, $defaults);

    // Normalize athletes to array of IDs
    $athlete_ids = _get_athlete_ids($args['athletes']);

    if (empty($athlete_ids)) {
        return;
    }

    $view = $args['view'];
    ?>
    <div class="athletes-table-component <?php echo esc_attr($args['container_class']); ?>"
         data-athletes-table
         data-view="<?php echo esc_attr($view); ?>">

        <?php if ($args['title']) : ?>
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-3xl font-bold text-bsj-navy"><?php echo esc_html($args['title']); ?></h2>
                <?php if ($args['title_right']) : ?>
                    <div class="text-sm text-gray-600"><?php echo esc_html($args['title_right']); ?></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($args['show_search'] || $args['show_filters']) : ?>
            <div class="mb-6 flex flex-wrap gap-4 items-center">
                <?php if ($args['show_search']) : ?>
                    <div class="flex-1 min-w-[200px] max-w-md">
                        <input type="text"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bsj-blue focus:border-transparent"
                               placeholder="Search athletes..."
                               data-athletes-search />
                    </div>
                <?php endif; ?>

                <?php if ($args['show_filters']) : ?>
                    <?php
                    $positions = _get_unique_athlete_values($athlete_ids, 'position');
                    $schools = _get_unique_athlete_values($athlete_ids, 'school_name');
                    ?>

                    <?php if (!empty($positions)) : ?>
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bsj-blue focus:border-transparent"
                                data-athletes-filter="position">
                            <option value="">All Positions</option>
                            <?php foreach ($positions as $position) : ?>
                                <option value="<?php echo esc_attr(strtolower($position)); ?>"><?php echo esc_html($position); ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <?php if (!empty($schools)) : ?>
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-bsj-blue focus:border-transparent"
                                data-athletes-filter="school">
                            <option value="">All Schools</option>
                            <?php foreach ($schools as $school) : ?>
                                <option value="<?php echo esc_attr(strtolower($school)); ?>"><?php echo esc_html($school); ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php
        switch ($view) {
            case 'table':
                _render_athletes_table_view($athlete_ids, $args);
                break;
            case 'list':
                _render_athletes_list_view($athlete_ids, $args);
                break;
            case 'cards':
            default:
                _render_athletes_cards_view($athlete_ids, $args);
                break;
        }
        ?>
    </div>
    <?php
}

/**
 * Get unique values from athlete fields for filter dropdowns
 */
function _get_unique_athlete_values($athlete_ids, $field) {
    $values = array();

    foreach ($athlete_ids as $athlete_id) {
        $fields = get_athlete_fields_by_id($athlete_id);
        $value = isset($fields[$field]) ? $fields[$field] : '';

        if (!empty($value) && !in_array($value, $values)) {
            $values[] = $value;
        }
    }

    sort($values);
    return $values;
}

/**
 * Normalize athletes input to array of IDs
 */
function _get_athlete_ids($athletes) {
    $ids = array();

    if ($athletes === null) {
        // Use the main WordPress query
        global $wp_query;
        if ($wp_query->have_posts()) {
            while ($wp_query->have_posts()) {
                $wp_query->the_post();
                $ids[] = get_the_ID();
            }
            rewind_posts();
        }
    } elseif ($athletes instanceof WP_Query) {
        while ($athletes->have_posts()) {
            $athletes->the_post();
            $ids[] = get_the_ID();
        }
        wp_reset_postdata();
    } elseif (is_array($athletes)) {
        foreach ($athletes as $athlete) {
            if (is_object($athlete) && isset($athlete->ID)) {
                $ids[] = $athlete->ID;
            } elseif (is_numeric($athlete)) {
                $ids[] = (int) $athlete;
            }
        }
    }

    return $ids;
}

/**
 * Render table view (desktop table + mobile cards)
 */
function _render_athletes_table_view($athlete_ids, $args) {
    ?>
    <!-- Desktop Table View -->
    <div class="hidden lg:block overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50 border-b-2 border-gray-200">
                <tr>
                    <?php if ($args['show_rank']) : ?>
                        <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase">Rank</th>
                    <?php endif; ?>
                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase">Player</th>
                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase">NIL Valuation</th>
                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase">School</th>
                    <th class="text-left py-3 px-4 text-xs font-bold text-gray-600 uppercase">Sponsors</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php
                $rank = 1;
                foreach ($athlete_ids as $athlete_id) :
                    $fields = get_athlete_fields_by_id($athlete_id);
                ?>
                    <tr class="hover:bg-gray-50 transition-colors"
                        data-athlete-row
                        data-name="<?php echo esc_attr(strtolower(get_the_title($athlete_id))); ?>"
                        data-position="<?php echo esc_attr(strtolower($fields['position'])); ?>"
                        data-school="<?php echo esc_attr(strtolower($fields['school_name'])); ?>"
                        data-nil="<?php echo esc_attr($fields['nil_valuation']); ?>">
                        <?php if ($args['show_rank']) : ?>
                            <td class="py-4 px-4">
                                <span class="text-lg font-bold text-bsj-navy"><?php echo $rank; ?></span>
                            </td>
                        <?php endif; ?>
                        <td class="py-4 px-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0">
                                    <?php if (has_post_thumbnail($athlete_id)) : ?>
                                        <?php echo get_the_post_thumbnail($athlete_id, 'thumbnail', array('class' => 'w-12 h-12 rounded-full object-cover')); ?>
                                    <?php else : ?>
                                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-400 text-xs font-bold"><?php echo strtoupper(substr(get_the_title($athlete_id), 0, 2)); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <a href="<?php echo get_permalink($athlete_id); ?>" class="font-bold text-bsj-navy hover:text-bsj-blue transition">
                                            <?php echo get_the_title($athlete_id); ?>
                                        </a>
                                        <?php if ($fields['position']) : ?>
                                            <span class="text-xs text-gray-500 font-medium"><?php echo esc_html($fields['position']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($fields['player_info'])) : ?>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <?php echo esc_html(implode(' / ', $fields['player_info'])); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-4">
                            <?php if ($fields['nil_valuation']) : ?>
                                <span class="text-sm font-bold text-bsj-navy">
                                    <?php echo format_nil_valuation($fields['nil_valuation']); ?>
                                </span>
                            <?php else : ?>
                                <span class="text-sm text-gray-400">—</span>
                            <?php endif; ?>
                        </td>
                        <td class="py-4 px-4">
                            <?php if ($fields['school_id']) : ?>
                                <div class="flex items-center gap-2">
                                    <?php
                                    $school_logo = get_the_post_thumbnail($fields['school_id'], 'thumbnail', array('class' => 'w-6 h-6 object-contain'));
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
                            <?php if (!empty($fields['sponsor_images'])) : ?>
                                <div class="flex gap-1 flex-wrap">
                                    <?php foreach ($fields['sponsor_images'] as $image) : ?>
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
                endforeach;
                ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="lg:hidden space-y-4">
        <?php
        $rank = 1;
        foreach ($athlete_ids as $athlete_id) :
            $fields = get_athlete_fields_by_id($athlete_id);
        ?>
            <article class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow"
                     data-athlete-row
                     data-name="<?php echo esc_attr(strtolower(get_the_title($athlete_id))); ?>"
                     data-position="<?php echo esc_attr(strtolower($fields['position'])); ?>"
                     data-school="<?php echo esc_attr(strtolower($fields['school_name'])); ?>"
                     data-nil="<?php echo esc_attr($fields['nil_valuation']); ?>">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-3 flex-1">
                        <?php if ($args['show_rank']) : ?>
                            <span class="text-lg font-bold text-bsj-navy"><?php echo $rank; ?></span>
                        <?php endif; ?>
                        <div class="flex-shrink-0">
                            <?php if (has_post_thumbnail($athlete_id)) : ?>
                                <?php echo get_the_post_thumbnail($athlete_id, 'thumbnail', array('class' => 'w-12 h-12 rounded-full object-cover')); ?>
                            <?php else : ?>
                                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400 text-xs font-bold"><?php echo strtoupper(substr(get_the_title($athlete_id), 0, 2)); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="min-w-0 flex-1">
                            <a href="<?php echo get_permalink($athlete_id); ?>" class="font-bold text-bsj-navy hover:text-bsj-blue transition block">
                                <?php echo get_the_title($athlete_id); ?>
                            </a>
                            <div class="flex items-center gap-2 mt-1 flex-wrap">
                                <?php if ($fields['position']) : ?>
                                    <span class="text-xs text-gray-500 font-medium"><?php echo esc_html($fields['position']); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($fields['player_info'])) : ?>
                                <div class="text-xs text-gray-500 mt-1">
                                    <?php echo esc_html(implode(' / ', $fields['player_info'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 text-sm">
                    <?php if ($fields['nil_valuation']) : ?>
                        <div>
                            <span class="text-xs text-gray-500 block mb-1">NIL Valuation</span>
                            <span class="font-bold text-bsj-navy">
                                <?php echo format_nil_valuation($fields['nil_valuation']); ?>
                            </span>
                        </div>
                    <?php endif; ?>

                    <?php if ($fields['school_id']) : ?>
                        <div>
                            <span class="text-xs text-gray-500 block mb-1">School</span>
                            <div class="flex items-center gap-2">
                                <?php
                                $school_logo = get_the_post_thumbnail($fields['school_id'], 'thumbnail', array('class' => 'w-6 h-6 object-contain'));
                                if ($school_logo) {
                                    echo $school_logo;
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($fields['sponsor_images'])) : ?>
                        <div>
                            <span class="text-xs text-gray-500 block mb-1">Sponsors</span>
                            <div class="flex gap-1 flex-wrap">
                                <?php foreach ($fields['sponsor_images'] as $image) : ?>
                                    <div class="flex-shrink-0"><?php echo $image; ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        <?php
            $rank++;
        endforeach;
        ?>
    </div>
    <?php
}

/**
 * Render list view (horizontal cards like archive-athlete.php)
 */
function _render_athletes_list_view($athlete_ids, $args) {
    ?>
    <div class="space-y-4">
        <?php foreach ($athlete_ids as $athlete_id) :
            $fields = get_athlete_fields_by_id($athlete_id);
        ?>
            <article class="bg-white border border-gray-200 rounded-lg hover:shadow-lg transition-shadow overflow-hidden"
                     data-athlete-row
                     data-name="<?php echo esc_attr(strtolower(get_the_title($athlete_id))); ?>"
                     data-position="<?php echo esc_attr(strtolower($fields['position'])); ?>"
                     data-school="<?php echo esc_attr(strtolower($fields['school_name'])); ?>"
                     data-nil="<?php echo esc_attr($fields['nil_valuation']); ?>">
                <a href="<?php echo get_permalink($athlete_id); ?>" class="block">
                    <div class="flex items-center gap-6 p-6">
                        <!-- Athlete Image -->
                        <div class="flex-shrink-0">
                            <?php if (has_post_thumbnail($athlete_id)) : ?>
                                <?php echo get_the_post_thumbnail($athlete_id, 'medium', array('class' => 'w-24 h-24 rounded-lg object-cover border-2 border-gray-200')); ?>
                            <?php else : ?>
                                <div class="w-24 h-24 rounded-lg bg-gradient-to-br from-bsj-navy to-blue-900 flex items-center justify-center border-2 border-gray-200">
                                    <span class="text-white text-2xl font-bold"><?php echo strtoupper(substr(get_the_title($athlete_id), 0, 2)); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Athlete Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <h3 class="text-xl font-bold text-bsj-navy mb-1 hover:text-bsj-blue transition">
                                        <?php echo get_the_title($athlete_id); ?>
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
                                <div class="flex-shrink-0 text-right hidden sm:block">
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
                        <div class="flex-shrink-0 text-gray-400 hidden md:block">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            </article>
        <?php endforeach; ?>
    </div>
    <?php
}

/**
 * Render cards view (grid of athlete cards)
 */
function _render_athletes_cards_view($athlete_ids, $args) {
    ?>
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($athlete_ids as $athlete_id) :
            $fields = get_athlete_fields_by_id($athlete_id);
        ?>
            <article class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow"
                     data-athlete-row
                     data-name="<?php echo esc_attr(strtolower(get_the_title($athlete_id))); ?>"
                     data-position="<?php echo esc_attr(strtolower($fields['position'])); ?>"
                     data-school="<?php echo esc_attr(strtolower($fields['school_name'])); ?>"
                     data-nil="<?php echo esc_attr($fields['nil_valuation']); ?>">
                <a href="<?php echo get_permalink($athlete_id); ?>" class="block">
                    <?php if (has_post_thumbnail($athlete_id)) : ?>
                        <?php echo get_the_post_thumbnail($athlete_id, 'medium', array('class' => 'w-full h-48 object-cover rounded-lg mb-3')); ?>
                    <?php else : ?>
                        <div class="w-full h-48 rounded-lg bg-gradient-to-br from-bsj-navy to-blue-900 flex items-center justify-center mb-3">
                            <span class="text-white text-4xl font-bold"><?php echo strtoupper(substr(get_the_title($athlete_id), 0, 2)); ?></span>
                        </div>
                    <?php endif; ?>
                    <h3 class="font-bold text-bsj-navy hover:text-bsj-blue transition">
                        <?php echo get_the_title($athlete_id); ?>
                    </h3>
                    <?php if ($fields['position']) : ?>
                        <p class="text-sm text-gray-600 mt-1"><?php echo esc_html($fields['position']); ?></p>
                    <?php endif; ?>
                </a>
            </article>
        <?php endforeach; ?>
    </div>
    <?php
}
