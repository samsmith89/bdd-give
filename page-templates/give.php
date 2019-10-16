<?php

/**
 * The template for displaying all single Give Donation Forms
 *
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php

        //updating the Team Goal Field

        $team_name   = get_field('team_name');
        $args = array(
            'post_type'         => 'give_forms',
            'posts_per_page'    => 3,
            'meta_query' => array(
                array(
                    'key'     => 'team_name',
                    'value'   => $team_name,
                ),
                array(
                    'key'     => 'has_team',
                    'value'   => true,
                )
            )
        );
        $id = get_the_ID();
        $team_goal = array('');
        $query = new WP_Query($args);
        while ($query->have_posts()) : $query->the_post();
            //do something
            $id = get_the_ID();
            $earning = give_get_meta($id, '_give_form_earnings', true);
            array_push($team_goal, $earning);

        endwhile;
        wp_reset_postdata();

        $team_goal_final = array_sum($team_goal);
        update_field('team_goal', $team_goal_final);


        $is_rider = get_field('is_rider');
        $has_team = get_field('has_team');
        do_action('give_before_main_content');
        if (($is_rider == true) && ($has_team == true)) {
            //if this is a rider form
            add_filter('give_pre_form_output', 'is_give_rider_team');
        } else {
            //do nothing
        };
        // Start the loop.
        while (have_posts()) : the_post();

            give_get_template_part('single-give-form/content', 'single-give-form');

        // End the loop.
        endwhile;

        $is_team = get_field("is_team");
        $team_name = get_field("team_name");

        if ($is_team == true) {
            // Grabs the two teammates forms if $team is true

            $args = array(
                'post_type'      => 'give_forms',
                'posts_per_page' => 2,
                'meta_query' => array(
                    array(
                        'key' => 'is_rider',
                        'value' => true
                    ),
                    array(
                        'key' => 'team_name',
                        'value' => get_the_title()
                    )
                )
            );
            $wp_query = new WP_Query($args);
            if ($wp_query->have_posts()) : ?>
                <?php
                        while ($wp_query->have_posts()) : $wp_query->the_post(); ?>


                    <div class="<?php post_class(); ?>">

                        <h2 class="give-form-title"><?php echo get_the_title(); ?></h2>

                        <?php //you can output the content or excerpt here
                                    ?>

                        <?php
                                    if (class_exists('Give')) {
                                        //Output the goal (if enabled)
                                        $id          = get_the_ID();
                                        $goal_option = give_get_meta($id, '_give_goal_option', true);
                                        if (give_is_setting_enabled($goal_option)) {
                                            echo do_shortcode('[give_goal id="' . $id . '"]');
                                        }
                                        $give_thumb = get_the_post_thumbnail();
                                    }
                                    ?>

                        <img src="<?php echo $give_thumb ?>">
                        <a href=" <?php echo get_permalink(); ?>" class="button readmore give-donation-form-link"><?php _e('Donate Now', 'give'); ?> &raquo;</a>


                    </div>

                <?php endwhile;
                        wp_reset_postdata(); // end of Query 1
                        ?>

            <?php else :
                    //If you don't have donation forms that fit this query
                    ?>

                <h2>Sorry, no donations found.</h2>

        <?php endif;
            wp_reset_query();
        } else {
            //do nothing
        }


        do_action('give_after_main_content');
        ?>

    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
