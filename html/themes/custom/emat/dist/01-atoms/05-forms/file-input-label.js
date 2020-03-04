"use strict";/**
 * @file
 * A JavaScript file containing the main menu functionality (small/large screen)
 *
 */(function(){'use strict';for(var a=document.querySelectorAll(".js-form-type-managed-file"),b=0;b<a.length;b++){var c=a[b],d=c.querySelector(".form-item__file");d.addEventListener("change",function(a){var b=a.target.files[0].name;a.target.parentElement.querySelector(".js-file-box .file-box__label").innerHTML=b})}})();
//# sourceMappingURL=file-input-label.js.map
