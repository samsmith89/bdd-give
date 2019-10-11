<?php

function give_team_forms_function($atts)
{
    // Defaults
    $args = array(
        'post_type'         => 'give_forms',
        'posts_per_page'    => 20,
        'meta_query'        => array(
            array(
                'key' => 'is_rider',
                'value' => true
            )
        )
    );
    $wp_query = new WP_Query($args);
    if ($wp_query->have_posts()) :
        ob_start();
        while ($wp_query->have_posts()) : $wp_query->the_post();
            //Output the goal (if enabled)
            $form_name   = get_the_title();
            $team_name = get_field('team_name');

            if (!empty($team_name)) {
                //team exists
                $team_form_post = get_page_by_title($team_name, OBJECT, 'give_forms');
                printf('<a href="%s">It worked</a>', get_permalink($team_form_post));
            } else {
                //team does not exist
            }

            // var_dump($team_form_post);

            //get the post ID of the Give form of that team name
            //get the URL of the team associated with that form
            //and then echo out the row

            ?>

            <p class="give-form-team"><?php echo $form_name; ?></p>

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
add_shortcode('top_team_forms', 'give_team_forms_function');
