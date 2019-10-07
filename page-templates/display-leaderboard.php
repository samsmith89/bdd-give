<?php

/**
 *  Template Name: LeaderBoard
 *
 *  This snippet is a page template with an example query that gets
 *  the 10 latest donation forms and displays their goal
 *
 *  IMPORTANT: This snippet, unlike others you might find in our snippet library
 *  does not go in functions.php or a must-use plugin. Paste the entire contents
 *  of this file to a new file named archive-give_forms.php and place it in
 *  the root of your active theme's folder. To view it on your site, naviate to
 *  /donations (assuming you haven't changed the slug or disabled the form archives
 *  in Donations --> Settings --> Display )
 *
 */
get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <form action="/leaderboard-search/" method="get">
            <input type="text" name="search_text">
            <label>Type:</label>
            <select name="type">
                <option value="">Any</option>
                <option value="post">Posts</option>
                <option value="movies">Movies</option>
                <option value="books">Books</option>
            </select>
            <button type="submit" name="">Search</button>
        </form>
        <?php
        /**
         *  Display Donation Forms
         */

        $our_current_page = get_query_var('paged');

        $args = array(
            'post_type'      => 'give_forms',
            'posts_per_page' => 2,
            'paged'          => $our_current_page,
            'meta_query' => array(
                array(
                    'key' => 'is_rider',
                    'value' => true
                )
            )
        );
        $wp_query = new WP_Query($args);
        if ($wp_query->have_posts()) : ?>

            <h2>Leader Board</h2>
            <hr />
            <table>
                <th>Rider</th>
                <th>Goal</th>
                <th>Total</th>
                <th>Team</th>
                <th>Link to Donate</th>
                <?php
                    while ($wp_query->have_posts()) : $wp_query->the_post(); ?>


                    <tr class="<?php post_class(); ?>">

                        <td class="give-form-title"><?php echo get_the_title(); ?></td>

                        <?php //you can output the content or excerpt here
                                ?>



                        <?php
                                if (class_exists('Give')) {
                                    //Output the goal (if enabled)
                                    $id          = get_the_ID();
                                    $goal_amount = give_get_meta($id, '_give_set_goal', true);
                                    $goal_income = give_get_meta($id, '_give_form_earnings', true);

                                    $value = get_field("team_name");
                                }
                                ?>
                        <td class="give-form-team"><?php echo $goal_amount; ?></td>
                        <td class="give-form-team"><?php echo $goal_income; ?></td>
                        <td class="give-form-team"><?php echo $value; ?></td>


                        <td><a href="<?php echo get_permalink(); ?>" class="readmore give-donation-form-link"><?php _e('Donate Now', 'give'); ?> &raquo;</a></td>
                    </tr>

                <?php endwhile;
                    wp_reset_postdata(); // end of Query 1
                    echo paginate_links();
                    ?>
            </table>
        <?php else :
            //If you don't have donation forms that fit this query
            ?>

            <h2>Sorry, no donations found.</h2>

        <?php endif;
        wp_reset_query(); ?>

    </main>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
