<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package OSI_Global
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function osi_global_body_classes($classes)
{
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class of no-sidebar when there is no sidebar present.
    if (!is_active_sidebar('sidebar')) {
        $classes[] = 'no-sidebar';
    } else {
        $classes[] = 'has-sidebar';
    }

    return $classes;
}

add_filter('body_class', 'osi_global_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function osi_global_pingback_header()
{
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}

add_action('wp_head', 'osi_global_pingback_header');


/***********************************************
 * Banner Image and Title for Posts and Pages **
 ***********************************************/
function ss_return_image_html($get_image_object, $main_image_height_width = [], $variation_sizes = [])
{
    ob_start();
    $default_image_url = $get_image_object['url'];
    $alt = $get_image_object['alt'] ? $get_image_object['alt'] : $get_image_object['title'];
    $type = $get_image_object['mime_type'];

    $length_attr = $custom_size_url = '';
    if (array_key_exists('width', $main_image_height_width) && array_key_exists('height', $main_image_height_width)) {
        $length_attr = 'width="' . $main_image_height_width['width'] . '" height="' . $main_image_height_width['height'] . '"';
    }

    if ($variation_sizes) {
        foreach ($variation_sizes as $custom_size => $size) {
            if (array_key_exists($size, $get_image_object['sizes'])) {
                $custom_size_url = $get_image_object['sizes'][$custom_size];
            }
            $url = $custom_size_url ? $custom_size_url : $default_image_url;
            if ($size == 'no-media') {
                ?>
                <source srcset="<?php echo esc_url($url); ?>.webp"
                        type="image/webp">
                <source srcset="<?php echo esc_url($url); ?>"
                        type="<?php echo $type; ?>">

                <?php
                continue;
            }
            if ($size) { ?>
                <source media="(min-width: <?= $size ?>px)" srcset="<?php echo esc_url($url); ?>.webp"
                        type="image/webp">
                <source media="(min-width: <?= $size ?>px)" srcset="<?php echo esc_url($url); ?>"
                        type="<?php echo $type; ?>">
            <?php }
        }
    }
    ?>

    <img <?= $length_attr ?> src="<?= esc_url($default_image_url) ?>"
                             alt="<?= $alt ?>">

    <?php
    $get_image_html = ob_get_clean();
    return $get_image_html;
}

/***************************************/


/***********************************************
 * Thumbnail Image With Scrset  **
 ***********************************************/
function ss_return_thumbnail_html($thumbnail_url, $main_image_height_width = [])
{
    ob_start();
    $webp_url = $thumbnail_url . '.webp';


    $length_attr = '';
    if (array_key_exists('width', $main_image_height_width) && array_key_exists('height', $main_image_height_width)) {
        $length_attr = 'width="' . $main_image_height_width['width'] . '" height="' . $main_image_height_width['height'] . '"';
    }; ?>


    <source srcset="<?= $webp_url ?>"
            type="image/webp">
    <source srcset="<?= $thumbnail_url; ?>"
            type="<?= wp_get_image_mime($thumbnail_url) ?>">

    <img <?= $length_attr ?> src="<?= esc_url($thumbnail_url) ?>"
                             alt="<?= the_title() ?>">

    <?php
    $get_image_html = ob_get_clean();
    return $get_image_html;
}

/***************************************/

/*******************************************************
 * Function to return the ACF Link field array object **
 *******************************************************/
function ss_get_btn_link_title_and_target($button)
{
    if (!is_array($button)) return;
    if (class_exists('ACF')) {
        $btn_title = $button['title'] ? $button['title'] : 'Click Me';
        $btn_link = $button['url'];
        $btn_target = $button['target'] ? $button['target'] : '_self';
        $btn_details = array(
            'title' => $btn_title,
            'url' => $btn_link,
            'target' => $btn_target
        );
        return $btn_details;
    }
}

/***************************************/
/*******************************************************
 * Function to return custom excerpt **
 *******************************************************/

function custom_length_excerpt($word_count_limit, $custom_field)
{
    if ($custom_field) {
        $content = $custom_field;
    } else {
        $content = get_the_content();
    }
    $content = wp_strip_all_tags($content, true);
    echo wp_trim_words($content, $word_count_limit);
}


function format_phone_number($custom_field)
{
    $phone_number = str_replace(str_split('()- '), '', $custom_field);
    return $phone_number;
}

// Post Category
function category_name($id)
{
    $category_detail = get_the_category($id);
    if ($category_detail) {
        $s = '';
        // echo '| ';
        if (is_array($category_detail)) {
            foreach ($category_detail as $cat) {
                $cat_id = $cat->term_id;
                $category_link = get_category_link($cat);
                if ($s) $s .= ', ';
                // $s .= '<a href="' . $category_link . '">';
                $s .= $cat->name;
                // $s .= '</a>';
            }
            echo $s;
        }
    }
}
