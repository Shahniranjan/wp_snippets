<?php

/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package OSI_Global
 */
get_header();
global $wp_query; // NO CUSTOM QUERY SO WE CALL GLOBAL WP QUERY
?>
   
                <div class="row inner loadmore-wrapper">
                    

                </div>
                
                <?php if ($wp_query->max_num_pages > 1) { ?>
                    <div class="btn-wrap text-center">
                        <button href="javascript:void(0)" class="btn btn-primary loadmore_btn" tabindex="0">View More
                        </button>
                        <img style="display: none" class="ajax_loader"
                             src="<?= get_template_directory_uri(); ?>/images/loader.gif" height="40px" width="40px">
                    </div>
                <?php } ?>
        