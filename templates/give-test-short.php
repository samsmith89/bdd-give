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
            $form_value = get_field('team_name');
            $inner_args = array(
                'post_type'         => 'give_forms',
                'title'        =>  $form_value,
                'posts_per_page'    => 10,
                'meta_query' => array(
                    array(
                        'key'     => 'is_team',
                        'value'   => true,
                        'compare' => '='
                    )
                )
            );
            $query = new WP_Query($inner_args);
            while ($query->have_posts()) : $query->the_post();
                //do something
                ?>
                <p><a href="<?php echo the_permalink(); ?>" class="readmore give-donation-form-link"><?php _e('Donate Now', 'give'); ?> &raquo;</a></p>
            <?php
                        endwhile;
                        wp_reset_postdata();
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
