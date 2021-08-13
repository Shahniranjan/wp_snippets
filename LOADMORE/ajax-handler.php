<?php
function load_more_scripts()
{


    if (!file_exists(get_template_directory() . '/js/ss_loadmore.js')) {
        wp_die('<div style="text-align:center"><h1 style="font-weight:normal">Filtering script Not Found</h1><p>Opps we have got error!<br>It appears the Filtering script for inventory may be missing.</p></div>', 'Filtering script Not Found');
    } else {
        global $wp_query;

        wp_register_script('loadmore', get_template_directory_uri() . '/js/ss_loadmore.js', array('jquery'), null, true);

        wp_localize_script('loadmore', 'loadmore_params', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'posts' => json_encode($wp_query->query_vars),
            'current_page' => get_query_var('paged') ? get_query_var('paged') : 1,
            'max_page' => $wp_query->max_num_pages
        ));

        wp_enqueue_script('loadmore');
    }

}

add_action('wp_enqueue_scripts', 'load_more_scripts');

function loadmore_ajax_handler()
{

    $args = json_decode(stripslashes($_POST['query']), true);
    $args['paged'] = $_POST['page'] + 1;
    $args['post_status'] = 'publish';


    $query = new WP_Query($args);


    if ($query->have_posts()) :
        while ($query->have_posts()): $query->the_post();

            if ($args['post_type'] == 'ss_industry_we_serve') {
                get_template_part('template-parts/content', 'ss_industry_we_serve');
            } else {
                get_template_part('template-parts/content');
            }
        endwhile;
        wp_reset_postdata();
    endif;

    die;
}

add_action('wp_ajax_loadmore', 'loadmore_ajax_handler');
add_action('wp_ajax_nopriv_loadmore', 'loadmore_ajax_handler');

/**
 * Ajax loadmore and loadmore result
 */

function search_ajax_handler()
{
    global $wpdb;
    $search_input = $_POST['s'];
    $table_name = $wpdb->prefix . "eol_and_esol_table";
    $per_page = 8;
    $paged = $_POST['current_page'] == 'NULL' ? 1 : $_POST['current_page']+ 1;
    $offset = $paged * $per_page - $per_page;


    if ($_POST['btn_type'] == 'retrive_all') {
        $query = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id asc limit $per_page offset $offset");
        $total_data = $wpdb->get_var("SELECT count(id) from $table_name ORDER BY id");
    } else {
        $query = $wpdb->get_results("SELECT * FROM $table_name WHERE ModelName LIKE '%$search_input%' OR ModelNumber LIKE '%$search_input%' ORDER BY id asc limit $per_page offset $offset");
        $total_data = $wpdb->get_var("SELECT count(id) FROM $table_name WHERE ModelName LIKE '%$search_input%' ORDER BY id asc");
    }

    $total_page = ceil($total_data / $per_page);

    if ($query):
        ob_start();

        // Query is added for loadmore search result

        foreach ($query as $post):
            echo $post->Id . '   ' . ($post->ModelName) . '<br>';
        endforeach;

        $posts = ob_get_clean();

        $result = array();
        $result['btn_type'] = $_POST['btn_type'];
        $result['post_found'] = true;
        $result['posts'] = $posts;
        $result['current_page'] = $paged ? $paged : 1;
        $result['total_page'] = $total_page;


    else :
        $result['post_found'] = false;
    endif;
    return wp_send_json_success($result);

}

add_action('wp_ajax_search', 'search_ajax_handler');
add_action('wp_ajax_nopriv_search', 'search_ajax_handler');