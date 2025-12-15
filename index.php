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
</main>

<?php get_footer(); ?>
