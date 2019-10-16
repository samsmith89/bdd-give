<?php
function top_give_team_forms_function($atts)
{
    // Defaults
    $atts = shortcode_atts(array(
        'limit' => 10,
        'order' => 'DESC',
        'decimals' => false
    ), $atts, 'top_give_forms');
    $args = array(
        'post_type'         => 'give_forms',
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
            <table id="giveTable">
                <th>Team</th>
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
                        <td class="give-form-team">$10,000</td>
                        <td class="give-form-team"><?php echo get_field("team_goal"); ?>
                        </td>
                        <td class="give-form-team"><?php echo $value; ?></td>


                        <td><a href="<?php echo get_permalink(); ?>" class="readmore give-donation-form-link"><?php _e('Donate Now', 'give'); ?> &raquo;</a></td>
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

            ?>
        <!-- <script>
            function sortTable() {
                var table, rows, switching, i, x, y, shouldSwitch;
                table = document.getElementById("giveTable");
                switching = true;
                /*Make a loop that will continue until
                no switching has been done:*/
                while (switching) {
                    //start by saying: no switching is done:
                    switching = false;
                    rows = table.rows;
                    /*Loop through all table rows (except the
                    first, which contains table headers):*/
                    for (i = 1; i < (rows.length - 1); i++) {
                        //start by saying there should be no switching:
                        shouldSwitch = false;
                        /*Get the two elements you want to compare,
                        one from current row and one from the next:*/
                        x = rows[i].getElementsByTagName("TD")[0];
                        y = rows[i + 1].getElementsByTagName("TD")[0];
                        //check if the two rows should switch place:
                        if (Number(x.innerHTML) > Number(y.innerHTML)) {
                            //if so, mark as a switch and break the loop:
                            shouldSwitch = true;
                            break;
                        }
                    }
                    if (shouldSwitch) {
                        /*If a switch has been marked, make the switch
                        and mark that a switch has been done:*/
                        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                        switching = true;
                    }
                }
            }
        </script> -->
    <?php
    }
    add_shortcode('top_give_team_forms', 'top_give_team_forms_function');
