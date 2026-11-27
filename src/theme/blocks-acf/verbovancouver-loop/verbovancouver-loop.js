( function( $ ) {

    $(document).ready( function() {

        if ($('.wp-block-verbovancouver-loop').length > 0) {

            const postType = $('.wp-block-verbovancouver-loop').data('post-type');

            //  Init Select 2
            $('.seelct2-filter').select2({
                minimumResultsForSearch: -1,
                dropdownAutoWidth: true,
                multiple: false,
                theme: "flat",
            });

            //  Filter logic for project feed
            //    Preserve current queries
            let url = new URL(window.location);

            //    Update posts
            let loopAjax = function() {
                let dataArgs = {
                    action: 'filter_loop',
                    post_type: postType,
                }
                let ary = url.searchParams.toString().split('&');
                for (i = 0; i <= ary.length - 1; i++) {
                    let key = ary[i].split('=')[0];
                    let val = ary[i].split('=')[1];
                    dataArgs[key] = val;
                }

                $.ajax({
                    type: 'POST',
                    url: '/wp-admin/admin-ajax.php',
                    dataType: 'html',
                    data: dataArgs,
                    beforeSend: function () {
                        $('.loop-inner-grid').animate({'opacity': 0, 'transform': 'translateY(50px)'}, 300);
                    },
                    success: function (res) {
                        if (res === 'END') {
                            $('.loop-inner-grid').html('<p style="text-align: center;padding: 120px 60px;width: 100%;"><strong>There are no items matching your search. Please try different filters.</strong></p>');
                            $('.loop-inner-grid').animate({'opacity': 1, 'transform': 'translateY(0)'}, 300);
                        } else {
                            $('.loop-inner-grid').html(res);
                            window.setTimeout(function () {
                                $('.loop-item').addClass('intersected');
                                $('.loop-inner-grid').animate({'opacity': 1, 'transform': 'translateY(0)'}, 300);
                                let currentPage = url.searchParams.get('pg');
                                $('.pagination-link').removeClass('current-page');
                                $(".pagination-link[data-page='" + currentPage + "']").addClass('current-page');
                                if ($('.pagination-link.current-page').is(':last-child') && $('.pagination-link.current-page').is(':first-child')) {
                                    $('.page-next').css('visibility', 'hidden');
                                    $('.page-back').css('visibility', 'hidden');
                                } else if ($('.pagination-link.current-page').is(':last-child')) {
                                    $('.page-next').css('visibility', 'hidden');
                                    $('.page-back').css('visibility', 'visible');
                                } else if ($('.pagination-link.current-page').is(':first-child')) {
                                    $('.page-next').css('visibility', 'visible');
                                    $('.page-back').css('visibility', 'hidden');
                                } else {
                                    $('.page-next').css('visibility', 'visible');
                                    $('.page-back').css('visibility', 'visible');
                                }
                            }, 300);
                        }
                    }
                })
            }

            //  Update pagination
            let pagesAjax = function() {
                let dataArgs = {
                    action: 'loop_pagination',
                    post_type: postType,
                }
                let ary = url.searchParams.toString().split('&');
                for (i = 0; i <= ary.length - 1; i++) {
                    let key = ary[i].split('=')[0];
                    let val = ary[i].split('=')[1];
                    dataArgs[key] = val;
                }
                $.ajax({
                    type: 'POST',
                    url: '/wp-admin/admin-ajax.php',
                    dataType: 'html',
                    data: dataArgs,
                    beforeSend: function () {
                        $('.loop-pagination').animate({'opacity': 0, 'transform': 'translateY(50px)'}, 300);
                    },
                    success: function (res) {
                        $('.loop-pagination').html(res);
                        window.setTimeout(function () {
                            $('.loop-pagination').animate({'opacity': 1, 'transform': 'translateY(0)'}, 300);
                        }, 300);
                    }
                })
            }

            // Filter change event
            $('.select2-filter').on('change', function () {
                url.searchParams.set('pg', 1);
                let taxonomy = $(this).data('taxonomy');
                let selection = $(this).val();
                if (selection === 'all' || selection === null) {
                    url.searchParams.delete(taxonomy); //remove filter
                } else {
                    url.searchParams.set(taxonomy, selection); //set new value
                }
                pagesAjax();
                loopAjax();
            });


            //Pagination
            $(document).on('click', '.pagination-link', function(e) {
                e.preventDefault();

                let page = $(this).data('page');
                url.searchParams.set('pg', page);

                loopAjax();

                $('html, body').animate({
                    scrollTop: $(".filter-wrapper").offset().top - 200
                }, 600);

            });

            $(document).on('click', '.page-arrow', function(e) {
                e.preventDefault();

                let currentPage = 1;

                if (url.searchParams.get('pg')) {
                    currentPage = parseInt(url.searchParams.get('pg'));
                }
                let page = currentPage;
                if ($(this).hasClass('page-back')) {
                    page = currentPage - 1;
                } else {
                    page = currentPage + 1;
                }
                url.searchParams.set('pg', page);

                loopAjax();

                $('html, body').animate({
                    scrollTop: $(".filter-wrapper").offset().top - 200
                }, 600);

            });

        }

    } );

} )( jQuery );
