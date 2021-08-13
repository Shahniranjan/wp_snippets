<?php
/* Template Name: EOL and ESOL Database */

get_header();
global $wpdb;
$per_page = 8;
$table_name = $wpdb->prefix . "eol_and_esol_table";
$query = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id asc limit $per_page");
$total_data = $wpdb->get_var("SELECT count(id) from $table_name ORDER BY id");
$total_page = ceil($total_data / $per_page);

?>

<form class="form-search" id="searchform" action="<?= admin_url('admin-ajax.php') ?>" method="POST">
    <label for="s" class="sr-only"><?php _e('Search', 'osi-global'); ?></label>
    <div class="input-group">
        <input type="text" class="form-control field" name="s" id="s"
               placeholder="<?php esc_attr_e('Type here.....', 'osi-global'); ?>">

        <!--                        Acton is used to call the function of ajax handler.php
                               search_ajax_handler is the function name so Action Name needs to be search-->

        <input type="hidden" name="action" value="search">


        <div class="input-group-append">
            <!-- button need to have class "db-search-btn" and attribute data_btn_type = search-->
            <button href="javascript:void(0)" class="btn btn-primary submit db-search-btn"
                    current_page="NULL" data_btn_type="search"
                    id="searchsubmit">Search
            </button>
            <img style="display: none" class="ajax_loader"
                 src="<?= get_template_directory_uri(); ?>/images/loader.gif" height="40px"
                 width="40px">
        </div>
    </div>

</form>

<!-- data_btn_type="retrive_all" current_page="1" need to be on loadmore button-->
<?php if ($total_page > 1) { ?>
    <div class="btn-wrap text-center">
        <a href="javascript:void(0)" data_btn_type="retrive_all" current_page="1"
           class="btn btn-primary db-search-btn loadmore-result-btn" tabindex="0">Load More</a>

        <img style="display: none" class="ajax_loader"
             src="<?= get_template_directory_uri(); ?>/images/loader.gif" height="40px" width="40px">
    </div>
    <?php
}
?>


//Results are added to div with id "Response"
<div class="row eol-row" id="response">