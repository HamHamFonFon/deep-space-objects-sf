window.DSO = window.DSO || {};

DSO.switchLang = (function($, ns, r) {

  ns.linkLang = 'li > a.lang-lbl-full';

  ns.init = function() {
    if (0 < $(ns.linkLang).length) {
      ns.switchLang();
    }
  };

  ns.switchLang = function() {
    $(ns.linkLang).on('click', function() {

      let $this = $(this);
      let selectedLang = $this.attr('lang');
      let currentLang = $('main').data('lang');

      if (currentLang !== selectedLang && selectedLang !== undefined) {
        DSO.cookieLang.setCookie(selectedLang, 3);
        let routeSwitch = r.generate('switchlang', {'language': selectedLang});
        window.location.assign(routeSwitch);
      }
    });
  };

  return ns;
})(jQuery, {}, Routing);