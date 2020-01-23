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
        $team_form_post = get_page_by_title($team_name, OBJECT, 'team');
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

/**
 *
 * Function for adding donation levels
 *
 */
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

/**
 *
 * Usage
 *
 * 1 - Enable "Allow field to be populated dynamically" option on field which should be populated.
 * 2 - In the "Parameter Name" input, enter the merge tag (or merge tags) of the field whose value whould be populated into this field.
 *
 * Basic Fields
 *
 * To map a single input field (and most other non-multi-choice fields) enter: {Field Label:1}. It is useful to note that
 * you do not actually need the field label portion of any merge tag. {:1} would work as well. Change the "1" to the ID of your field.
 *
 * Multi-input Fields (i.e. Name, Address, etc)
 *
 * To map the first and last name of a Name field to a single field, follow the steps above and enter {First Name:1.3} {Last Name:1.6}.
 * In this example it is assumed that the name field ID is "1". The input IDs for the first and last name of this field will always be "3" and "6".
 *
 * # Uses
 *
 *  - use merge tags as post tags
 *  - aggregate list of checked checkboxes
 *  - map multiple conditional fields to a single field so you can refer to the single field for the selected value
 *
 */
class GWMapFieldToField
{
    public $lead = null;
    function __construct()
    {
        add_filter('gform_pre_validation', array($this, 'map_field_to_field'), 11);
    }
    function map_field_to_field($form)
    {
        foreach ($form['fields'] as $field) {
            if (is_array($field['inputs'])) {
                $inputs = $field['inputs'];
            } else {
                $inputs = array(
                    array(
                        'id' => $field['id'],
                        'name' => $field['inputName']
                    )
                );
            }
            foreach ($inputs as $input) {
                $value = rgar($input, 'name');
                if (!$value)
                    continue;
                $post_key = 'input_' . str_replace('.', '_', $input['id']);
                $current_value = rgpost($post_key);
                preg_match_all('/{[^{]*?:(\d+(\.\d+)?)(:(.*?))?}/mi', $input['name'], $matches, PREG_SET_ORDER);
                // if there is no merge tag in inputName - OR - if there is already a value populated for this field, don't overwrite
                if (empty($matches))
                    continue;
                $entry = $this->get_lead($form);
                foreach ($matches as $match) {
                    list($tag, $field_id, $input_id, $filters, $filter) = array_pad($match, 5, 0);
                    $force = $filter === 'force';
                    $tag_field = RGFormsModel::get_field($form, $field_id);
                    // only process replacement if there is no value OR if force filter is provided
                    $process_replacement = !$current_value || $force;
                    if ($process_replacement && !RGFormsModel::is_field_hidden($form, $tag_field, array())) {
                        $field_value = GFCommon::replace_variables($tag, $form, $entry);
                        if (is_array($field_value)) {
                            $field_value = implode(',', array_filter($field_value));
                        }
                    } else {
                        $field_value = '';
                    }
                    $value = trim(str_replace($match[0], $field_value, $value));
                }
                if ($value) {
                    $_POST[$post_key] = $value;
                }
            }
        }
        return $form;
    }
    function get_lead($form)
    {
        if (!$this->lead)
            $this->lead = GFFormsModel::create_lead($form);
        return $this->lead;
    }
}
new GWMapFieldToField();

/**
 *
 * Usage:
 *
 * Populates a gravity form field dropdown with the available teams posts
 *
 * Be sure to change the ID of the Gravity Form when migrating in the filters at the top
 *
 * Ensure the CSS of the field you want populated with the teams is 'populate-posts'
 *
 */
// add_filter('gform_pre_render_6', 'populate_posts');
// add_filter('gform_pre_validation_6', 'populate_posts');
// add_filter('gform_pre_submission_filter_6', 'populate_posts');
// add_filter('gform_admin_pre_render_6', 'populate_posts');
// function populate_posts($form)
// {

//     foreach ($form['fields'] as &$field) {

//         if ($field->type != 'select' || strpos($field->cssClass, 'populate-posts') === false) {
//             continue;
//         }

//         // you can add additional parameters here to alter the posts that are retrieved
//         // more info: http://codex.wordpress.org/Template_Tags/get_posts
//         $posts = get_posts('post_type=team&numberposts=-1');

//         $choices = array();

//         foreach ($posts as $post) {
//             $choices[] = array('text' => $post->post_title, 'value' => $post->post_title);
//         }

//         // update 'Select a Post' to whatever you'd like the instructive option to be
//         $field->placeholder = 'Select a Team';
//         $field->choices = $choices;
//     }

//     return $form;
// }
