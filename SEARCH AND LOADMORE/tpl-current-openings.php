<?php
/* Template Name: In-House Current Openings */
/* Template Post Type: ss_works */

get_header();
global $wp_query;
$application_taxonomies = get_terms('ss_application_category', array('hide_empty' => 0));

?>

                <form id="filter" class="form" action="<?= admin_url('admin-ajax.php') ?>" method="POST">
                    <div class="form-section row">
                        <div class="form-group col-lg-6 form-group--alt">
                            <input type="text" name="s" class="form-control" placeholder="Job Name" required>
                        </div>
                        
                        <?php if ($application_taxonomies) : ?>
                            <div class="form-group col-lg-2">
                                <select name="category" id="category" class="form-control">
                                    <option value="" selected>Category</option>
                                    <?php foreach ($application_taxonomies as $application_taxonomy) : ?>
                                        <option value="<?= $application_taxonomy->term_id ?>"><?= $application_taxonomy->name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                        
                           <div class="form-group col-lg-2">
                            <input type="text" name="location" placeholder="Location" class="form-control">
                        </div>
                        
                           <div class="form-group col-lg-2">
                            <button href="javascript:void(0)" data-btn-type="search"
                                    class="opening-form-btn btn btn-primary">Search
                            </button>
                        </div><!-- .form-foot -->
                    </div><!-- .form-section -->
                    <input type="hidden" name="action" value="search">
                </form>
         

 
                <div id="response" class="filter-panel">

                </div>
       


        <div class="btn-wrap text-center">
            <a href="javascript:void(0)" style="display: none;" data-btn-type="loadmore_result"
               class="btn btn-primary opening-form-btn loadmore-result-btn" tabindex="0">Load More</a>
        </div>


<?php
get_footer();
