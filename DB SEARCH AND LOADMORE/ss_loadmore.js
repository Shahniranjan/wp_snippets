(function ($) {
    let ssLoadMore;
    ssLoadMore = {
        init: function () {
            this.LoadMore();
            this.Search();

        },

        LoadMore: function () {
            $('.loadmore_btn').click(function () {
                var ajax_loader = $('.ajax_loader');
                var query = loadmore_params.posts;
                var max_num_page = loadmore_params.max_page;
                if (window.customarg) {
                    query = window.customarg;
                }
                if (window.max_num_page) {
                    max_num_page = window.max_num_page;
                }

                var button = $(this),
                    data = {
                        'action': 'loadmore',
                        'query': query,
                        'page': loadmore_params.current_page
                    };

                $.ajax({
                    url: loadmore_params.ajaxurl,
                    data: data,
                    type: 'POST',
                    beforeSend: function (xhr) {
                        ajax_loader.show();
                        button.hide();
                    },
                    success: function (data) {
                        if (data) {

                            ajax_loader.hide();
                            button.show();
                            $('.loadmore-wrapper').append(data);
                            loadmore_params.current_page++;

                            if (loadmore_params.current_page == max_num_page) {
                                button.text('No more posts');
                                button.prop('disabled', true);

                            }


                        } else {
                            button.text('No more posts');
                            button.prop('disabled', true);
                        }
                    }
                });
            });
        },
        Search: function () {
            $('body').on('click', '.db-search-btn', function () {
                var ajax_loader = $('.ajax_loader');
                var btn_type = $(this).attr('data_btn_type'); // Set Button type 'Search' or 'Load More Result' or 'retrieve_all'
                var filter = $('#searchform'); //Form Selector
                var currentPage = $(this).attr('current_page');
                var button = $(this);

                // SET AJAX SETTING
                data = filter.serialize() + '&btn_type=' + btn_type + '&current_page=' + currentPage;
                url = filter.attr('action');
                type = filter.attr('method');

                $.ajax({
                    url: url,
                    data: data,
                    type: type,

                    beforeSend: function () {
                        ajax_loader.show();
                        button.hide();

                    },
                    success: function (response) {
                        ajax_loader.hide();
                        let responseContainer = $('#response');
                        let loadmoreBtn = $('.loadmore-result-btn');
                        let postFound = response.data.post_found;
                        let posts = response.data.posts;
                        let btnType = response.data.btn_type;
                        let totalPage = response.data.total_page;
                        let currentPage = response.data.current_page;


                        if (postFound) {
                            // Append results when loadmore button is pressed else replaces with new results
                            if (currentPage === totalPage) {
                                loadmoreBtn.hide();
                            }
                            if (btnType === 'loadmore_result' || btnType === 'retrive_all') {
                                button.show();
                                responseContainer.append(posts);
                            } else {
                                responseContainer.html(posts);
                                button.show();
                            }
                            if (totalPage > 1 && currentPage < totalPage) {
                                loadmoreBtn.attr({
                                    current_page: currentPage,
                                    total_page: totalPage,
                                }).show();
                            }
                            if (btnType === 'search') {
                                loadmoreBtn.attr({
                                    data_btn_type: 'loadmore_result',
                                })
                            }

                        } else {
                            responseContainer.html('<h2>No Post Found</h2>');
                            filter.find('button').text('Search');
                            loadmoreBtn.hide();
                        }

                    }
                });
                return false;
            });
        },

    };
    ssLoadMore.init();
})(jQuery);