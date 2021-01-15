"use strict";/**
 * @file
 * Modifies the onclick behavior of the extlink Drupal mod to
 * use magnific popup rather than the built-in system confirmation
 * dialog.
 */(function(a,b){'use strict';b.behaviors.externalLink={attach:function attach(){a(document).once("mat-external-link").each(function(){drupalSettings.data.extlink.extAlertText&&a("#js-extlink-alert-text").html(drupalSettings.data.extlink.extAlertText),document.querySelector(".js-extlink-continue").addEventListener("click",function(){a.magnificPopup.close()}),document.querySelector(".js-extlink-decline").addEventListener("click",function(b){return b.preventDefault(),a.magnificPopup.close(),!1}),b.extlink.popupClickHandler=function(b,c){var d=c.href;return a("#js-extlink-continue").attr("href",d),a("#js-extlink-preview").html(d),a.magnificPopup.open({items:{src:"#extlink-disclaimer"}}),!1}})}}})(jQuery,Drupal);
//# sourceMappingURL=external-link.js.map
