(function ($) {
    'use strict';
    $(document).ready(function () {
        $(".skwpalc-slider li").each(function () {
            if ($(this).is(":empty"))
            {
                $(this).remove();
            }
        });
        if ($(".skwpalc-slider").length != 0) {
            $(".skwpalc-slider").responsiveSlides({
                auto: true, // Boolean: Animate automatically, true or false
                speed: 500, // Integer: Speed of the transition, in milliseconds
                timeout: 3000, // Integer: Time between slide transitions, in milliseconds
                pager: false, // Boolean: Show pager, true or false
                nav: false, // Boolean: Show navigation, true or false
                random: false, // Boolean: Randomize the order of the slides, true or false
                pause: true, // Boolean: Pause on hover, true or false
                pauseControls: true, // Boolean: Pause when hovering controls, true or false
                prevText: "<", // String: Text for the "previous" button
                nextText: ">", // String: Text for the "next" button
                maxwidth: "", // Integer: Max-width of the slideshow, in pixels
                navContainer: "", // Selector: Where controls should be appended to, default is after the 'ul'
                manualControls: "", // Selector: Declare custom pager navigation
                namespace: "rslides", // String: Change the default namespace used
                before: function () {
                }, // Function: Before callback
                after: function () {
                }     // Function: After callback
            });
        }
    });

})(jQuery);
