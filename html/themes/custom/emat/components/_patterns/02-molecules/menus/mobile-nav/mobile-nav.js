/**
 * @file
 * A JavaScript file containing the mobile nav functionality.
 *
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - https://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth


// (function (Drupal) { // UNCOMMENT IF DRUPAL.
//
//   Drupal.behaviors.mainMenu = {
//     attach: function (context) {

(function () { // REMOVE IF DRUPAL.

  'use strict';

  document.querySelector('.js-mobile-toggle').addEventListener('click', function(e) {
    e.preventDefault();

    var target = e.target;
    if (target.nodeName !== "BUTTON") {
      target = target.parentElement;
    }
    target.classList.toggle('mobile-nav__toggle--active');

    var body = document.body;
    body.classList.toggle('js-mobile-nav-active');

    return false;
  });

})(); // REMOVE IF DRUPAL.

// })(Drupal); // UNCOMMENT IF DRUPAL.
