<script>
    var skwpalcGoogleReCaptchaV3onloadCallback = function() {

        var skwpalcGoogleReCaptchaV3clientId = grecaptcha.render('skwpalc-google-captchav3-badge', {
            'sitekey': '<?php echo $template_data["google_site_key_v3"]; ?>',
            'badge': 'inline',
            'size': 'invisible'
        });
        // window.addEventListener("load", (event) => {
        // jQuery("#loginform").submit(function(e) {
        //     // if (jQuery("#gctoken").length) {
        //     // } else {
        //     e.preventDefault();
        //     // }

        //     grecaptcha.ready(function() {


        //         grecaptcha
        //             .execute(skwpalcGoogleReCaptchaV3clientId, {
        //                 action: "loginsubmit"
        //             })
        //             .then(function(token) {
        //                 console.log(token);
        //                 jQuery("#gctkn").remove();
        //                 jQuery("#loginform").append(
        //                     '<input id="gctkn" type="hidden" name="g-recaptcha-response" value="' +
        //                     token +
        //                     '" />'
        //                 );
        //                 // jQuery("#loginform").submit();
        //                 jQuery(event.target).submit();
        //             });
        //     });
        // });
        // });


        

        function skwpalcCaptchaV3Setter() {

            grecaptcha.ready(function() {


                grecaptcha
                    .execute(skwpalcGoogleReCaptchaV3clientId, {
                        action: "loginsubmit"
                    })
                    .then(function(token) {
                        // console.log(token);
                        jQuery("#gctkn").val(token);
                        // jQuery("#loginform").append(
                        //     '<input id="gctkn" type="hidden" name="g-recaptcha-response" value="' +
                        //     token +
                        //     '" />'
                        // );
                        // jQuery("#loginform").submit();
                        // jQuery(event.target).submit();
                    });
            });
            setTimeout(skwpalcCaptchaV3Setter, 3000);
        }

        skwpalcCaptchaV3Setter();

    };
</script>