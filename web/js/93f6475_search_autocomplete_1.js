let Search = (function($,r) {

  let INPUT = 'input.typeahead';

  let launchAutoComplete = function() {
    $(INPUT).typeahead({
      source: function(q, p) {
        return $.ajax(r.generate('search_autocomplete'), function (data) {
          return p(data.options);
        });
      }
    });
  };

  return {
    search: launchAutoComplete
  };

})(jQuery, Routing);

Search.search();