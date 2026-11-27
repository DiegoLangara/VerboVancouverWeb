( function( $ ) {

    $(document).ready( function() {

        // Accordion Block
        $(window).bind('load resize', function() {
            $('.accordion-body-wrapper').each(function() {
                let height = $(this).children('.accordion-body-inner').outerHeight() + 'px';
                $(this).css('--body-height', height);
            });
        });


        $('.accordion-title').on('click', function() {
            if($(this).parent().hasClass('open')) {
                $(this).parent().removeClass('open');
            } else {
                $('.accordion-item').removeClass('open');
                $(this).parent().addClass('open');
            }
        });

    } );

} )( jQuery );
