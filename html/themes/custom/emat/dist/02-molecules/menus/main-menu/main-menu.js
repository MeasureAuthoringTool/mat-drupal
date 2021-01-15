"use strict";/**
 * @file
 * A JavaScript file containing the main menu functionality (small/large screen)
 *
 */ // JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - https://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth
// (function (Drupal) { // UNCOMMENT IF DRUPAL.
//
//   Drupal.behaviors.mainMenu = {
//     attach: function (context) {
(function(){// REMOVE IF DRUPAL.
'use strict';// Use context instead of document IF DRUPAL.
var a=document.getElementById("toggle-expand"),b=document.getElementById("main-nav"),c=b.getElementsByClassName("expand-sub");a.addEventListener("click",function(){a.classList.toggle("toggle-expand--open"),b.classList.toggle("main-nav--open")});// Expose mobile sub menu on click.
for(var d=0;d<c.length;d++)c[d].addEventListener("click",function(a){var b=a.currentTarget,c=b.nextElementSibling;b.classList.toggle("expand-sub--open"),c.classList.toggle("main-menu--sub-open")})})();
//# sourceMappingURL=main-menu.js.map
