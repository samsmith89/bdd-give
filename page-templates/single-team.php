<?php

/**
 * The template for displaying all Team posts
 *
 */

get_header(); ?>

<div id="main-content">
    <?php
    if (et_builder_is_product_tour_enabled()) :
        // load fullwidth page in Product Tour mode
        while (have_posts()) : the_post(); ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('et_pb_post'); ?>>
                <div class="entry-content">
                    <?php
                            the_content();
                            ?>
                </div> <!-- .entry-content -->

            </article> <!-- .et_pb_post -->

        <?php endwhile;
        else :
            ?>
        <div class="container">
            <div id="content-area" class="clearfix">
                <div id="left-area">
                    <?php while (have_posts()) : the_post(); ?>
                        <?php
                                /**
                                 * Fires before the title and post meta on single posts.
                                 *
                                 * @since 3.18.8
                                 */
                                do_action('et_before_post');
                                ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('et_pb_post'); ?>>
                            <?php if (('off' !== $show_default_title && $is_page_builder_used) || !$is_page_builder_used) { ?>
                                <div class="et_post_meta_wrapper">
                                    <h1 class="entry-title"><?php the_title(); ?></h1>
                                    <div class=js-give-team-goal-place></div>

                                    <?php
                                                if (!post_password_required()) :

                                                    et_divi_post_meta();

                                                    $thumb = '';

                                                    $width = (int) apply_filters('et_pb_index_blog_image_width', 1080);

                                                    $height = (int) apply_filters('et_pb_index_blog_image_height', 675);
                                                    $classtext = 'et_featured_image';
                                                    $titletext = get_the_title();
                                                    $thumbnail = get_thumbnail($width, $height, $classtext, $titletext, $titletext, false, 'Blogimage');
                                                    $thumb = $thumbnail["thumb"];

                                                    $post_format = et_pb_post_format();

                                                    if ('video' === $post_format && false !== ($first_video = et_get_first_video())) {
                                                        printf(
                                                            '<div class="et_main_video_container">
											%1$s
										</div>',
                                                            et_core_esc_previously($first_video)
                                                        );
                                                    } else if (!in_array($post_format, array('gallery', 'link', 'quote')) && 'on' === et_get_option('divi_thumbnails', 'on') && '' !== $thumb) {
                                                        print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height);
                                                    } else if ('gallery' === $post_format) {
                                                        et_pb_gallery_images();
                                                    }
                                                    ?>

                                        <?php
                                                        $text_color_class = et_divi_get_post_text_color();

                                                        $inline_style = et_divi_get_post_bg_inline_style();

                                                        switch ($post_format) {
                                                            case 'audio':
                                                                $audio_player = et_pb_get_audio_player();

                                                                if ($audio_player) {
                                                                    printf(
                                                                        '<div class="et_audio_content%1$s"%2$s>
													%3$s
												</div>',
                                                                        esc_attr($text_color_class),
                                                                        et_core_esc_previously($inline_style),
                                                                        et_core_esc_previously($audio_player)
                                                                    );
                                                                }

                                                                break;
                                                            case 'quote':
                                                                printf(
                                                                    '<div class="et_quote_content%2$s"%3$s>
												%1$s
											</div> <!-- .et_quote_content -->',
                                                                    et_core_esc_previously(et_get_blockquote_in_content()),
                                                                    esc_attr($text_color_class),
                                                                    et_core_esc_previously($inline_style)
                                                                );

                                                                break;
                                                            case 'link':
                                                                printf(
                                                                    '<div class="et_link_content%3$s"%4$s>
												<a href="%1$s" class="et_link_main_url">%2$s</a>
											</div> <!-- .et_link_content -->',
                                                                    esc_url(et_get_link_url()),
                                                                    esc_html(et_get_link_url()),
                                                                    esc_attr($text_color_class),
                                                                    et_core_esc_previously($inline_style)
                                                                );

                                                                break;
                                                        }

                                                    endif;
                                                    ?>
                                </div> <!-- .et_post_meta_wrapper -->
                            <?php  } ?>

                            <div class="entry-content">
                                <?php
                                        do_action('et_before_content');

                                        the_content();

                                        wp_link_pages(array('before' => '<div class="page-links">' . esc_html__('Pages:', 'Divi'), 'after' => '</div>'));
                                        ?>
                            </div> <!-- .entry-content -->
                            <div class="et_post_meta_wrapper">
                                <?php
                                        if (et_get_option('divi_468_enable') === 'on') {
                                            echo '<div class="et-single-post-ad">';
                                            if (et_get_option('divi_468_adsense') !== '') echo et_core_intentionally_unescaped(et_core_fix_unclosed_html_tags(et_get_option('divi_468_adsense')), 'html');
                                            else { ?>
                                        <a href="<?php echo esc_url(et_get_option('divi_468_url')); ?>"><img src="<?php echo esc_attr(et_get_option('divi_468_image')); ?>" alt="468" class="foursixeight" /></a>
                                    <?php     }
                                                echo '</div> <!-- .et-single-post-ad -->';
                                            }

                                            /**
                                             * Fires after the post content on single posts.
                                             *
                                             * @since 3.18.8
                                             */
                                            do_action('et_after_post');

                                            if ((comments_open() || get_comments_number()) && 'on' === et_get_option('divi_show_postcomments', 'on')) {
                                                comments_template('', true);
                                            }

                                            $is_team = get_field("is_team");
                                            $team_name = get_field("team_name");
                                            $give_goal_ids = array();
                                            // If the Give form is a team form
                                            if ($is_team == true) {
                                                // Grabs the teammates forms if $team is true
                                                $args = array(
                                                    'post_type'      => 'give_forms',
                                                    'posts_per_page' => 2,
                                                    'meta_query' => array(
                                                        array(
                                                            'key' => 'is_rider',
                                                            'value' => true
                                                        ),
                                                        array(
                                                            'key' => 'team_name',
                                                            'value' => get_the_title()
                                                        )
                                                    )
                                                );
                                                $wp_query = new WP_Query($args);
                                                if ($wp_query->have_posts()) : ?>
                                        <?php
                                                        while ($wp_query->have_posts()) : $wp_query->the_post(); ?>


                                            <div class="<?php post_class(); ?>">

                                                <h2 class="give-form-title"><?php echo get_the_title(); ?></h2>
                                                <?php
                                                                    if (class_exists('Give')) {
                                                                        //Output the goal (if enabled)
                                                                        $id          = get_the_ID();
                                                                        $goal_option = give_get_meta($id, '_give_goal_option', true);
                                                                        if (give_is_setting_enabled($goal_option)) { }
                                                                    }
                                                                    array_push($give_goal_ids, $id)
                                                                    ?>
                                                <img src="<?php echo get_the_post_thumbnail(); ?>">
                                                <a href=" <?php echo get_permalink(); ?>" class="button readmore give-donation-form-link"><?php _e('Donate Now', 'give'); ?> &raquo;</a>


                                            </div>

                                        <?php endwhile;
                                                        wp_reset_postdata(); // end of Query 1
                                                        ?>
                                        <?php

                                                        $give_goal_id_one = $give_goal_ids[0];
                                                        $give_goal_id_two = $give_goal_ids[1];
                                                        ?>
                                                        <div class="js-give-team-totals" style="display: none;"><?php echo do_shortcode('[give_totals ids="' . $give_goal_id_one . ', ' . $give_goal_id_two . '" total_goal="$10,000" message="Hey! We\'ve raised {total} of the {total_goal} we are trying to raise for this campaign!" link="/go-here/" link_text="Donate!" progress_bar="true"]');?></div>

                                    <?php else :
                                                    //If you don't have team donation forms that fit this query
                                                    ?>

                                        <h2>Sorry, no team members found.</h2>

                                <?php endif;
                                            wp_reset_query();
                                        } else {
                                            //do nothing
                                        }
                                        ?>
                            </div> <!-- .et_post_meta_wrapper -->
                        </article> <!-- .et_pb_post -->

                    <?php endwhile; ?>
                </div> <!-- #left-area -->

                <?php get_sidebar(); ?>
            </div> <!-- #content-area -->
        </div> <!-- .container -->
    <?php endif; ?>
</div> <!-- #main-content -->
<script>
    var yep = document.querySelector('.js-give-team-totals').innerHTML;
    document.querySelector('.js-give-team-goal-place').innerHTML = yep;
</script>

<?php get_footer(); ?>
