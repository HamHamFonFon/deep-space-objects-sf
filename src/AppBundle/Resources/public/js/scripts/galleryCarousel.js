window.DSO = window.DSO || {};

/**
 * Bootstrap Gallery Carousel
 */
DSO.galleryCarousel = (function ($, ns) {

  ns.CAROUSEL_ID = '#multi-gallery-astrobin';

  ns.init = function() {
    if (0 < $(ns.CAROUSEL_ID).length) {
      this.Carousel();
    }
  };

  ns.Carousel = function() {
    console.log('Init carousel...');
    $(ns.CAROUSEL_ID).carousel({
      interval: 2000
    });
  };

  return ns;
})(jQuery, {});