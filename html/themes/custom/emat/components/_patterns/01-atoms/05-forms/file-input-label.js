
/**
 * @file
 * A JavaScript file containing the main menu functionality (small/large screen)
 *
 */

(function () {

  'use strict';

  var file_field_containers = document.querySelectorAll('.js-form-type-managed-file');

  for (var i = 0; i < file_field_containers.length; i++) {
    var container = file_field_containers[i];
    var file_field = container.querySelector('.form-item__file');
    file_field.addEventListener('change', function(e) {
      var file_name = e.target.files[0].name;
      e.target.parentElement.querySelector('.js-file-box .file-box__label').innerHTML = file_name;
    });
  }

})();
