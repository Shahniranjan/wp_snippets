<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package OSI_Global
 */
get_header();

//CUSTOM ARGUMENTS ARRAY as $args
$args = array(
    'orderby' => 'post__in',
    'posts_per_page' => $no_of_post,
    'post__in' => $post_ids,
);

$query = new WP_Query($args);

?>

<div class="row inner loadmore-wrapper ">
    <!--   CONTENTS ARE ADDED INSIDE "loadmore-wrapper"-->

</div>


<?php if ($query->max_num_pages > 1) { ?>
    <div class="btn-wrap text-center">
        <button href="javascript:void(0)" class="btn btn-primary loadmore_btn" tabindex="0">View More</button>
        <img style="display: none" class="ajax_loader"
             src="<?= get_template_directory_uri(); ?>/images/loader.gif" height="40px" width="40px">
        <script type="text/javascript">
            window.customarg = '<?= json_encode($args)  ?>';
            window.max_num_page = '<?= $query->max_num_pages ?>';
        </script>
    </div>
    <?php
}
wp_reset_postdata();
?>
  