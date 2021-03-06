<?php
function top_give_rider_forms_function($atts)
{
    // Defaults
    $atts = shortcode_atts(array(
        'limit' => 10,
        'order' => 'DESC',
        'decimals' => false
    ), $atts, 'top_give_rider_forms');
    $args = array(
        'post_type'         => 'give_forms',
        'posts_per_page'    => $atts['limit'],
        'meta_key'          => '_give_form_earnings',
        'orderby'           => 'meta_value_num',
        'order'             => $atts['order'],
        'meta_query' => array(
            array(
                'key' => 'is_rider',
                'value' => true
            )
        )
    );
    $wp_query = new WP_Query($args);
    if ($wp_query->have_posts()) :
        ob_start();
        ?>

        <div class="top-donation-forms-list">

            <h3>Top Performing Riders</h3>
            <!-- start of the table -->
            <table>
                <th>Team</th>
                <th>Goal</th>
                <th>Total</th>
                <th>Team</th>
                <th>Link to Donate</th>
                <?php
                        while ($wp_query->have_posts()) : $wp_query->the_post(); ?>

                    <!-- start of the row -->
                    <tr class="<?php post_class(); ?>">
                        <!-- Rider Name -->
                        <td class="give-form-title"><?php echo get_the_title(); ?></td>
                        <?php
                                    if (class_exists('Give')) {
                                        //Output the goal (if enabled)
                                        $id          = get_the_ID();
                                        $goal_amount = give_get_meta($id, '_give_set_goal', true);
                                        $goal_income = give_get_meta($id, '_give_form_earnings', true);

                                        $team_name = get_field("team_name");
                                    }
                                    ?>
                        <!-- Rider Goal -->
                        <td class="give-form-team">$<?php echo round($goal_amount); ?></td>
                        <!-- Rider Donation Form Income -->
                        <td class="give-form-team">$<?php echo $goal_income; ?></td>
                        <!-- Team associated with the rider and Team Link -->
                        <td class="give-form-team"><?php if (!empty($team_name)) {
                                                                    //team exists
                                                                    $team_form_post = get_page_by_title($team_name, OBJECT, 'team');
                                                                    printf('<a href="%1$s">%2$s</a>', get_permalink($team_form_post), $team_name);
                                                                } else {
                                                                    //team does not exist
                                                                } ?></td>

                        <!-- Link to doante to Rider -->
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

        <?php endif; ?>

        </div>

    <?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
        wp_reset_query();
    }
    add_shortcode('top_give_rider_forms', 'top_give_rider_forms_function');
