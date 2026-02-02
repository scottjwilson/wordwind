<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo("charset"); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header sticky top-0 z-50">
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
                    ); ?>" method="get" class="w-full max-w-md flex" data-header-search>
                        <input type="text"
                               name="s"
                               class="w-full px-4 py-2 bg-bsj-navy-light border border-bsj-navy-light rounded-l-md focus:outline-none focus:ring-2 focus:ring-inset focus:ring-bsj-blue text-white placeholder-gray-400"
                               placeholder="Search athletes..."
                               value="<?php echo get_search_query(); ?>"
                               autocomplete="off"
                               data-header-search-input />
                        <button type="submit" class="px-4 py-2 bg-bsj-gold hover:bg-amber-500 rounded-r-md transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-bsj-navy" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </form>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <div class="flex space-x-8">
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
