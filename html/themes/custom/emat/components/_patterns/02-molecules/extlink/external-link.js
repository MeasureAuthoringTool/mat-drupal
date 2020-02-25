/**
 * @file
 * Modifies the onclick behavior of the extlink Drupal mod to
 * use magnific popup rather than the built-in system confirmation
 * dialog.
 */
(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.externalLink = {
    attach: function(context, settings) {

      $(document).once('mat-external-link').each(function() {

        if (drupalSettings.data.extlink.extAlertText) {
          $('#js-extlink-alert-text').html(drupalSettings.data.extlink.extAlertText);
        }

        document.querySelector('.js-extlink-continue').addEventListener('click', function(e) {
          $.magnificPopup.close();
        });

        document.querySelector('.js-extlink-decline').addEventListener('click', function(e) {
          e.preventDefault();
          $.magnificPopup.close();
          return false;
        });

        Drupal.extlink.popupClickHandler = function(event, originalLink) {
          $('#js-extlink-continue').attr('href', originalLink.href)
          $.magnificPopup.open({
            items: {
              src: "#extlink-disclaimer",
            }
          });
          return false;
        };

      });
    }
  }

})(jQuery, Drupal);
