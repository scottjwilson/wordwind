<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<div class="sponsor-content">
    <?php the_content(); ?>
</div>

<!-- Related Athletes Section -->
<?php
$athletes = get_field('associated_athletes');

if ($athletes) : ?>
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <?php
            render_athletes_table([
                'athletes' => $athletes,
                'view' => 'cards',
                'show_rank' => false,
                'title' => 'Sponsored Athletes',
            ]);
            ?>
        </div>
    </section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
