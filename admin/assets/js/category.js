!function(e){var n={};function t(i){if(n[i])return n[i].exports;var r=n[i]={i:i,l:!1,exports:{}};return e[i].call(r.exports,r,r.exports,t),r.l=!0,r.exports}t.m=e,t.c=n,t.d=function(e,n,i){t.o(e,n)||Object.defineProperty(e,n,{enumerable:!0,get:i})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,n){if(1&n&&(e=t(e)),8&n)return e;if(4&n&&"object"==typeof e&&e&&e.__esModule)return e;var i=Object.create(null);if(t.r(i),Object.defineProperty(i,"default",{enumerable:!0,value:e}),2&n&&"string"!=typeof e)for(var r in e)t.d(i,r,function(n){return e[n]}.bind(null,r));return i},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},t.p="",t(t.s=7)}({3:function(e,n){e.exports=jQuery},7:function(e,n,t){e.exports=t(8)},8:function(e,n,t){"use strict";t.r(n);var i=t(3),r=t.n(i);function o(e,n){for(var t=0;t<n.length;t++){var i=n[t];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}var a=wp.i18n.__,l=function(){function e(){!function(e,n){if(!(e instanceof n))throw new TypeError("Cannot call a class as a function")}(this,e),this.$mediaUpload=r()(".js-media-upload"),this.setupColorPicker(),this.setupMediaUploadFrame(),this.clearFormOnSubmit()}var n,t,i;return n=e,(t=[{key:"setupColorPicker",value:function(){r()(".js-color-picker").wpColorPicker()}},{key:"setupMediaUploadFrame",value:function(){this.$mediaUpload.length&&this.$mediaUpload.each((function(e,n){var t,i=r()(n),o=i.find(".js-media-container"),l=i.find(".js-media-select"),d=i.find(".js-media-remove"),u=i.find(".js-media-id");o.length&&l.length&&d.length&&u.length&&(l.on("click",(function(e){e.preventDefault(),t?t.open():((t=wp.media({title:a("Select or Upload Media Of Your Chosen Persuasion","devlog"),button:{text:a("Use this media","devlog")},multiple:!1,library:{order:"desc",orderby:"date",type:"image"}})).on("select",(function(){var e=t.state().get("selection").first().toJSON();o.append('<img src="'+e.url+'" alt="">'),l.addClass("is-hidden"),d.removeClass("is-hidden"),u.val(e.id)})),t.open())})),d.on("click",(function(e){e.preventDefault(),o.html(""),d.addClass("is-hidden"),l.removeClass("is-hidden"),u.val("")})))}))}},{key:"clearFormOnSubmit",value:function(){var e=this,n=r()("#submit");n.length&&this.$mediaUpload.length&&n.on("click",(function(n){var t=r()(n.currentTarget).parents("form");if(!validateForm(t))return!1;e.$mediaUpload.each((function(e,n){var t=r()(n),i=t.find(".js-media-container"),o=t.find(".js-mediaSelect"),a=t.find(".js-mediaRemove"),l=t.find(".js-mediaId");i.length&&o.length&&a.length&&l.length&&(i.html(""),o.removeClass("__hidden"),a.addClass("__hidden"),l.val(""))}))}))}}])&&o(n.prototype,t),i&&o(n,i),e}();r()((function(){new l}))}});