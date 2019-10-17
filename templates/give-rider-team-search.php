<?php

/**
 *
 * Shortcode that outputs the Team List Search if needed
 *
 */

function give_team_list_function($atts)
{

    /**
     *  Display Donation Forms
     */


    if ($_GET['search_text'] && !empty($_GET['search_text'])) {
        $text = $_GET['search_text'];
    }

    if ($_GET['type'] && !empty($_GET['type'])) {
        $type = $_GET['type'];
    }

    $our_current_page = get_query_var('paged');

    $args = array(
        'post_type'      => 'give_forms',
        'posts_per_page' => 20,
        'paged'          => $our_current_page,
        's'              => $text
    );
    $wp_query = new WP_Query($args);
    if ($wp_query->have_posts()) : ob_start(); ?>

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
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
        wp_reset_query(); ?>

<?php
}
add_shortcode('give_team_search', 'give_team_list_function');
