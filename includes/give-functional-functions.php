<?php

/**
 *
 * Output the team if the rider is part of a team
 *
 */

function is_give_rider_team()
{
    $is_rider = get_field('is_rider');
    // Check is the rider is part of a team
    if ($is_rider == true) {
        $team_name = get_field('team_name');
        //team exists
        $team_form_post = get_page_by_title($team_name, OBJECT, 'give_forms');
        printf('<p>Team Name:<br><a href="%1$s">%2$s</a></p>', get_permalink($team_form_post), $team_name);
    } else { }
}

/**
 *
 * Function for updating the team_goal meta
 *
 */

function update_team_goal_meta()
{
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
}

function give_team_tabs()
{
    ?>

    <script>
        function openList(tableName) {
            var i;
            var x = document.getElementsByClassName("give_table");
            for (i = 0; i < x.length; i++) {
                x[i].style.display = "none";
            }
            document.getElementById(tableName).style.display = "block";
        }
    </script>

<?php
}
add_action('wp_footer', 'give_team_tabs');


function update_give_levels($post_id)
{
    update_post_meta($post_id, '_give_donation_levels', array(
        0 =>
        array(
            '_give_id' =>
            array(
                'level_id' => '0',
            ),
            '_give_amount' => '1.000000',
            '_give_text' => '',
            '_give_default' => 'default',
        ),
        1 =>
        array(
            '_give_id' =>
            array(
                'level_id' => '1',
            ),
            '_give_amount' => '2.000000',
            '_give_text' => '',
        ),
        2 =>
        array(
            '_give_id' =>
            array(
                'level_id' => '2',
            ),
            '_give_amount' => '3.000000',
            '_give_text' => '',
        ),
    ));
}

add_action('save_post_give_forms', 'update_give_levels', 100, 1);

// add_action('save_post_product', 'save_post_callback');

// function save_post_callback($post_id)

// {

//     // Save custom post type details

//     $old = get_post_meta($post_id, 'shipment_price', true);

//     $new = $_POST['shipment_price'];

//     if ($new && $new != $old) {

//         update_post_meta($post_id, 'shipment_price', $new);
//     } elseif (” == $new && $old) {

//         delete_post_meta($post_id, 'shipment_price', $old);
//     }
// }
