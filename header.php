<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo("charset"); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
   <nav class="bg-bsj-navy text-white ">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14">
                <div class="flex items-center">
                    <div class="text-2xl font-bold tracking-tight">
                      <a href="<?php echo home_url(); ?>">
                        <span class="text-bsj-gold">BSSJ</span>
                      </a>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="flex flex-1 justify-center px-8">
                    <form action="<?php echo esc_url(
                        home_url("/"),
                    ); ?>" method="get" class="w-full max-w-md relative" data-header-search>
                        <input type="text"
                               name="s"
                               class="w-full px-4 py-2 bg-bsj-navy-light border border-bsj-navy-light rounded-md focus:ring-2 focus:ring-bsj-blue focus:border-transparent text-white placeholder-gray-400"
                               placeholder="Search athletes..."
                               value="<?php echo get_search_query(); ?>"
                               autocomplete="off"
                               data-header-search-input />

                        <!-- Autocomplete Dropdown -->
                        <div class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-gray-200 rounded-md shadow-lg z-50 max-h-96 overflow-y-auto"
                             data-header-search-dropdown>
                            <div data-header-search-results></div>
                        </div>
                    </form>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <div class="flex space-x-8 text-sm">
                        <a href="/news" class="text-white hover:text-bsj-gold">News</a>
                        <a href="/athletes" class="text-white hover:text-bsj-gold">Athletes</a>
                        <a href="/sponsors" class="text-white hover:text-bsj-gold">Sponsors</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
  <!-- <h1>
    <a href="<?php echo esc_url(home_url("/")); ?>">
      <?php bloginfo("name"); ?>
    </a>
  </h1>

<div class="bg-purple-300">

  <ul>
    <li>

    </li>
  </ul>
  <?php wp_nav_menu([
      "theme_location" => "primary",
      "container" => false,
  ]); ?>

  </div> -->

</header>
