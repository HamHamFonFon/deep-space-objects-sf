window.DSO = window.DSO || {};
/**
 * Set a cookie after swithing lang
 */
DSO.cookieLang = (function($, ns, r) {

  ns.listLanguages = ['en', 'fr', 'pt', 'de', 'es'];

  ns.maxDays = 3;

  ns.currentLang = $('main').data('lang');

  ns.init = function() {
    let selectedLang = ns.checkCookie();
    if (-1 !== ns.listLanguages.indexOf(selectedLang) && selectedLang !== ns.currentLang) {
      // let routeSwitch = r.generate('switchlang', {'language': selectedLang});
      // window.location.assign(routeSwitch);
    }
  };


  /**
   *
   * @param langSelected
   * @param exdays
   * @returns {*}
   */
  ns.setCookie = function(langSelected, exdays) {

    if (undefined === exdays || ns.maxDays < exdays) {
      exdays = ns.maxDays;
    }

    let d = new Date();
    d.setTime(d.getTime() + (exdays * 24 / 60 * 80 * 1000));
    let expires = "expires="+d.toUTCString();

    if (-1 !== ns.listLanguages.indexOf(langSelected)) {
      document.cookie = "dso_lang=" + langSelected + ";" + expires + ";path=/";
    }

    return langSelected;
  };

  /**
   *
   * @returns {string}
   */
  ns.getCookie = function() {
    let name = "dso_lang=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) === ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) === 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  };

  ns.checkCookie = function() {
    let lang = ns.getCookie();
    if (undefined === lang) {
      // set default cookie
      lang = ns.setCookie('en', ns.maxDays);
    }
    return lang;
  };

  return ns;
})(jQuery, {}, Routing);