<?php get_header(); ?>

<main class="site-main">
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php 
            while (have_posts()) {
                the_post(); 
                ?>
                <h1 class="text-3xl font-bold text-bsj-navy mb-6"><?php the_title(); ?></h1>
                <div class="prose prose-lg max-w-none">
                    <?php the_content(); ?>
                </div>
                <?php 
            }
            ?>
        </div>
    </section>
</main>

<?php get_footer(); ?>