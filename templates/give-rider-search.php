<?php

function give_rider_search_function($text)
{
    if ($_GET['search_text'] && !empty($_GET['search_text'])) {
        $text = $_GET['search_text'];
    }

    if ($_GET['type'] && !empty($_GET['type'])) {
        $type = $_GET['type'];
    }
    /**
     *  Display Donation Forms
     */
    global $paged;
    $args = array(
        'post_type'      => 'give_forms',
        'posts_per_page' => 20,
        'paged'          => $paged,
        's'              => $text,
        'meta_query' => array(
            array(
                'key' => 'is_rider',
                'value' => true
            )
        )
    );
    $wp_query = new WP_Query($args);
    if ($wp_query->have_posts()) : ob_start();
        ?>
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

        <a href="/riderlist-shortcode/">Clear Search Results</a>
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
                                    $team_name   = get_field('team_name');
                                }
                                ?>
                    <td class="give-form-team"><?php echo $goal_amount; ?></td>
                    <td class="give-form-team"><?php echo $goal_income; ?></td>
                    <td class="give-form-team"><?php if (!empty($team_name)) {
                                                                //team exists
                                                                $team_form_post = get_page_by_title($team_name, OBJECT, 'give_forms');
                                                                printf('<a href="%1$s">%2$s</a>', get_permalink($team_form_post), $team_name);
                                                            } else {
                                                                //team does not exist
                                                            } ?></td>


                    <td><a href="<?php echo get_permalink(); ?>" class="readmore give-donation-form-link"><?php _e('Donate Now', 'give'); ?> &raquo;</a></td>
                </tr>

            <?php endwhile;
                    wp_reset_postdata(); // end of Query 1
                    wp_pagenavi();
                    ?>
        </table>
    <?php else :
            //If you don't have donation forms that fit this query
            ?>

        <h2>Sorry, no donation forms found.</h2>
        <a href="/riderlist-shortcode/">Let's try again</a>

<?php endif;
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
    wp_reset_query();
}
add_shortcode('give_rider_search', 'give_rider_search_function');
