(function ($) {
    "use strict";
    
    // Dropdown on mouse hover (no click toggling to avoid flicker)
    $(document).ready(function () {
        function bindNavbarHover() {
            var $dropdowns = $('.navbar .dropdown');
            // cleanup previous bindings
            $dropdowns.off('mouseenter mouseleave');
            $dropdowns.find('> .dropdown-toggle').off('click.navhover');

            if ($(window).width() > 992) {
                $dropdowns.on('mouseenter', function () {
                    var $dd = $(this);
                    $dd.addClass('show');
                    $dd.find('> .dropdown-toggle').attr('aria-expanded', true);
                    $dd.find('> .dropdown-menu').addClass('show');
                }).on('mouseleave', function () {
                    var $dd = $(this);
                    $dd.removeClass('show');
                    $dd.find('> .dropdown-toggle').attr('aria-expanded', false);
                    $dd.find('> .dropdown-menu').removeClass('show');
                });

                // Prevent click on the toggle from re-toggling while in desktop hover mode
                $dropdowns.find('> .dropdown-toggle').on('click.navhover', function (e) {
                    e.preventDefault();
                });
            } else {
                // Mobile: let Bootstrap handle click behavior
                $dropdowns.removeClass('show')
                    .find('> .dropdown-toggle').attr('aria-expanded', false).end()
                    .find('> .dropdown-menu').removeClass('show');
            }
        }
        bindNavbarHover();
        $(window).on('resize', bindNavbarHover);
    });
    
    
    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        dots: true,
        loop: true,
        items: 1
    });
    
})(jQuery);

