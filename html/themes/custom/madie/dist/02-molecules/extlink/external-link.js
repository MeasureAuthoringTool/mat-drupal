((n,i,l)=>{i.behaviors.externalLink={attach:function(e,t){l("mat-external-link","a",e).forEach(function(e){t.data.extlink&&t.data.extlink.extAlertText&&n("#js-extlink-alert-text").html(t.data.extlink.extAlertText),n(".js-extlink-continue").on("click",function(e){n.magnificPopup.close()}),n(".js-extlink-decline").on("click",function(e){return e.preventDefault(),n.magnificPopup.close(),!1}),i.extlink.popupClickHandler=function(e,t){t=t.href;return n("#js-extlink-continue").attr("href",t),n("#js-extlink-preview").html(t),n.magnificPopup.open({items:{src:"#extlink-disclaimer"}}),!1}})}}})(jQuery,Drupal,once);