<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
   <nav class="bg-bsj-navy text-white sticky top-0 z-50 border-b-2 border-bsj-gold">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center">
                    <div class="text-2xl font-bold tracking-tight">
                        <span class="text-bsj-gold">BALL STREET</span>
                        <span class="text-white ml-2">SPORTS JOURNAL</span>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="hover:text-bsj-gold transition font-medium">NIL</a>
                    <a href="#" class="hover:text-bsj-gold transition font-medium">Contracts</a>
                    <a href="#" class="hover:text-bsj-gold transition font-medium">Betting</a>
                    <a href="#" class="hover:text-bsj-gold transition font-medium">Trades</a>
                    <a href="#" class="hover:text-bsj-gold transition font-medium">Deals</a>
                    <button class="bg-bsj-gold text-bsj-navy px-6 py-2 rounded-md font-semibold hover:bg-yellow-500 transition">Subscribe</button>
                </div>
            </div>
        </div>
    </nav>
  <!-- <h1>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <?php bloginfo( 'name' ); ?>
    </a>
  </h1>

<div class="bg-purple-300">

  <ul>
    <li>
      
    </li>
  </ul>
  <?php wp_nav_menu([
    'theme_location' => 'primary',
    'container' => false,
  ]); ?>

  </div> -->

</header>
