/**
 * @file
 * Modifies the onclick behavior of the extlink Drupal mod to
 * use magnific popup rather than the built-in system confirmation dialog.
 */
(function ($, Drupal, once) {
  'use strict';

  Drupal.behaviors.externalLink = {
    attach: function (context, settings) {

      // Use Drupal's once library to apply behavior only once per link.
      once('mat-external-link', 'a', context).forEach(function (el) {

        // Check for external link alert text.
        if (settings.data.extlink && settings.data.extlink.extAlertText) {
          $('#js-extlink-alert-text').html(settings.data.extlink.extAlertText);
        }

        // Attach event listeners for continue and decline buttons.
        $('.js-extlink-continue').on('click', function (e) {
          $.magnificPopup.close();
        });

        $('.js-extlink-decline').on('click', function (e) {
          e.preventDefault();
          $.magnificPopup.close();
          return false;
        });

        // Define popup click handler for external links.
        Drupal.extlink.popupClickHandler = function (event, originalLink) {
          var externalHref = originalLink.href;
          $('#js-extlink-continue').attr('href', externalHref);
          $('#js-extlink-preview').html(externalHref);
          $.magnificPopup.open({
            items: {
              src: "#extlink-disclaimer",
            }
          });
          return false;
        };
      });
    }
  };

})(jQuery, Drupal, once);
