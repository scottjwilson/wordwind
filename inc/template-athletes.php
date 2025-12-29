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