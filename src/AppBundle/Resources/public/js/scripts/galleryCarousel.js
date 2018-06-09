window.DSO = window.DSO || {};

let bootstrap = require('bootstrap');

DSO.galleryCarousel = (function ($, ns) {
  ns.CAROUSEL_ID = '#multi-gallery-astrobin';
  ns.init = function() {
    $(ns.CAROUSEL_ID).carousel({
        interval: 2000
    });
  };

  return ns;
})(jQuery, {});