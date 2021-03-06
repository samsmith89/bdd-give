<?php

/**
 *
 * Shortcode that outputs the top 10 Teams
 *
 */
function top_give_team_forms_function($atts)
{
    // Defaults
    $atts = shortcode_atts(array(
        'limit' => 10,
        'order' => 'DESC',
        'decimals' => false
    ), $atts, 'top_give_forms');
    $args = array(
        'post_type'         => 'team',
        'posts_per_page'    => $atts['limit'],
        'meta_key'          => 'team_goal',
        'orderby'           => 'meta_value_num',
        'order'             => $atts['order'],
        'meta_query' => array(
            array(
                'key' => 'is_team',
                'value' => true
            )
        )
    );
    $wp_query = new WP_Query($args);
    if ($wp_query->have_posts()) :
        ob_start();
        ?>

        <div class="top-donation-forms-list">

            <h3>Top Performing Teams</h3>
            <!-- start of the table -->
            <table id="giveTable">
                <th>Team</th>
                <th>Goal</th>
                <th>Total</th>
                <th>Link to Donate</th>
                <?php
                        while ($wp_query->have_posts()) : $wp_query->the_post();
                        $team_link = get_permalink();
                        ?>

                    <!-- start of the row -->
                    <tr class="<?php post_class(); ?>">
                        <!-- Team Name -->
                        <td class="give-form-title"><?php echo get_the_title(); ?></td>
                        <!-- Team Goal -->
                        <td class="give-form-team">$10,000</td>
                        <!-- Team Income -->
                        <td class="give-form-team">$<?php

                            $team_name   = get_field('team_name');
                            $args = array(
                                'post_type'         => 'give_forms',
                                'posts_per_page'    => 2,
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
                            $queryTwo = new WP_Query($args);
                            while ($queryTwo->have_posts()) : $queryTwo->the_post();
                                //do something
                                $id = get_the_ID();
                                $earning = give_get_meta($id, '_give_form_earnings', true);
                                array_push($team_goal, $earning);

                            endwhile;

                            $team_goal_final = array_sum($team_goal);
                            echo $team_goal_final;
                         ?>
                        </td>
                        <!-- Team donation form link -->
                        <td><a href="<?php echo $team_link ?>" class="readmore give-donation-form-link"><?php _e('Donate Now', 'give'); ?> &raquo;</a></td>
                    </tr>
                <?php
                        endwhile;
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
    add_shortcode('top_give_team_forms', 'top_give_team_forms_function');
