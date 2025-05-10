// window.addEventListener("load", (event) => {
//   jQuery("#loginform").submit(function (e) {
//     // if (jQuery("#gctoken").length) {
//     // } else {
//       e.preventDefault();
//     // }

//     grecaptcha.ready(function () {
//       grecaptcha
//         .execute(google_site_key_v3, { action: "loginsubmit" })
//         .then(function (token) {
//           jQuery("#gctkn").remove();
//           jQuery("#loginform").append(
//             '<input id="gctkn" type="hidden" name="g-recaptcha-response" value="' +
//               token +
//               '" />'
//           );
//           // jQuery("#loginform").submit();
//           // jQuery(event.target).submit();
//         });
//     });
//   });
// });
