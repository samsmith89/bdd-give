<?php

/**
 * Output the team if the rider is part of a team
 *
 * @param $level_text
 * @param $form_id
 * @param $price
 *
 * @return string
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
