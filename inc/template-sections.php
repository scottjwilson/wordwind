<?php
/**
 * Template Section Functions
 * Hero, Featured Stories, Coverage Areas, Newsletter
 */

 function getHeroSection($args = array()) {
    // Set default values
    $defaults = array(
        'badge' => 'BREAKING NEWS',
        'title' => 'Where Sports Meets Business Intelligence',
        'subtitle' => 'In-depth coverage of NIL deals, athlete contracts, betting markets, and the billion-dollar business behind professional and college athletics.',
        'primary_button_text' => 'Read Latest',
        'primary_button_link' => '#',
        'secondary_button_text' => 'Free Newsletter',
        'secondary_button_link' => '#',
        'show_market_watch' => true, // Option to hide/show the market watch section
    );

    // Merge defaults with passed arguments
    $args = wp_parse_args($args, $defaults);

    echo '<section class="bg-gradient-to-br from-bsj-navy to-blue-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <div class="inline-block bg-bsj-gold text-bsj-navy px-4 py-1 rounded-full text-sm font-bold mb-4">
                        ' . esc_html($args['badge']) . '
                    </div>
                    <h1 class="text-5xl font-bold mb-6 leading-tight">
                        ' . esc_html($args['title']) . '
                    </h1>
                    <p class="text-xl text-gray-300 mb-8">
                        ' . esc_html($args['subtitle']) . '
                    </p>
                    <div class="flex gap-4">
                        <a href="' . esc_url($args['primary_button_link']) . '" class="bg-bsj-gold text-bsj-navy px-8 py-3 rounded-md font-bold hover:bg-yellow-500 transition">
                            ' . esc_html($args['primary_button_text']) . '
                        </a>
                        <a href="' . esc_url($args['secondary_button_link']) . '" class="border-2 border-white px-8 py-3 rounded-md font-bold hover:bg-white hover:text-bsj-navy transition">
                            ' . esc_html($args['secondary_button_text']) . '
                        </a>
                    </div>
                </div>';

    // Conditionally show market watch section
    if ($args['show_market_watch']) {
        echo '<div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 border border-white/20">
                    <div class="text-sm text-bsj-gold font-bold mb-2">MARKET WATCH</div>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="font-medium">NBA Salary Cap</span>
                            <span class="text-green-400 font-bold">$136M ↑ 3.5%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">NIL Market Value</span>
                            <span class="text-green-400 font-bold">$1.2B ↑ 18%</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-medium">Sports Betting Rev</span>
                            <span class="text-green-400 font-bold">$7.5B ↑ 25%</span>
                        </div>
                    </div>
                </div>';
    }

    echo '</div>
        </div>
    </section>';
}
function getFeaturedStories() {
  // Featured stories code
}

function getCoverageAreas() {
  // Coverage areas code
}


function getHeaderSection($title = 'title', $subtitle = 'subtitle') {
    // Header section code here
    // You can customize the HTML output based on your needs
    echo '<section class="bg-gradient-to-br from-bsj-navy to-blue-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold mb-4">' . esc_html($title) . '</h2>
            <p class="text-xl text-gray-300 mb-8">' . esc_html($subtitle) . '</p>
        </div>
    </section>';
}


function getNewsletter(){
    echo '<section class="py-20 bg-bsj-navy text-white">
          <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
              <h2 class="text-4xl font-bold mb-4">Stay Ahead of the Game</h2>
              <p class="text-xl text-gray-300 mb-8">
                  Get daily insights on the business of sports delivered to your inbox
              </p>
              <div class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto">
                  <input
                      type="email"
                      placeholder="Enter your email"
                      class="flex-1 px-6 py-4 rounded-md text-gray-900 ring-1 ring-bsj-gold focus:outline-none focus:ring-2"
                  />
                  <button class="bg-bsj-gold text-bsj-navy px-8 py-4 rounded-md font-bold hover:bg-yellow-500 transition whitespace-nowrap">
                      Subscribe Free
                  </button>
              </div>
              <p class="text-sm text-gray-400 mt-4">Join 50,000+ sports business professionals</p>
          </div>
      </section>';
  }


function getCategoryEmoji($category_name) {
    // Map category names to emojis
    $emoji_map = array(
        'NIL' => '🎓',
        'Contracts' => '📝',
        'Betting' => '🎲',
        'Trades' => '🔄',
        'Business' => '💼',
    );

    // Try exact match first
    if (isset($emoji_map[$category_name])) {
        return $emoji_map[$category_name];
    }

    // Try case-insensitive match
    foreach ($emoji_map as $key => $emoji) {
        if (strcasecmp($category_name, $key) === 0) {
            return $emoji;
        }
    }

    // Default emoji if no match found
    return '📰';
}
