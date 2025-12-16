<?php get_header(); ?>

<main class="site-main">
  <?php if ( have_posts() ) : ?>
    <?php while ( have_posts() ) : the_post(); ?>
      <article <?php post_class(); ?>>
        <h1 class="text-5xl font-bold">
           <a href="<?php the_permalink();?>">
             <?php the_title(); ?>
           </a>

      </h1>
        <div class="content">

          <?php the_content(); ?>
        </div>
      </article>
    <?php endwhile; ?>
  <?php else : ?>
    <p>No content found.</p>
  <?php endif; ?>


  <?php
    $athletes = new WP_Query([
      'posts_per_page' => 10,
      'post_type' => 'athlete',
    ]);

    while($athletes->have_posts()) {
      $athletes->the_post(); ?>
      <p><?php the_title(); ?></p>
      <?php

    }
      
  ?>
</main>

<?php get_footer(); ?>
