"use strict";(function(){var a={second:1e3};a.minute=60*a.second,a.hour=60*a.minute,a.day=24*a.hour,document.onreadystatechange=function(){if(b(),"interactive"===document.readyState||"complete"===document.readyState)setInterval(b,1e3)};var b=function(){for(var a,b=document.querySelectorAll(".js-countdown"),d=0;d<b.length;d++)a=b[d],c(b[d])},c=function(b){var c=Math.floor,d=new Date(b.dataset.countdownDate),e=new Date().getTime(),f=d-e,g=c(f/a.day),h=c(f%a.day/a.hour),i=c(f%a.hour/a.minute),j=c(f%a.minute/a.second),k=f%a.second,l="";switch(b.dataset.countdownGranularity){case"ms":l=k+" ms "+l;case"s":l=j+" seconds "+l;case"m":l=i+" minutes "+l;case"h":l=h+" hours "+l;case"d":l=g+" days "+l;}b.textContent=l.trim()}})();
//# sourceMappingURL=countdown.js.map