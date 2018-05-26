window.DSO = window.DSO || {};

/**
 * @var jquery-typeahead
 */
let typeahead = require('jquery-typeahead');

/**
 *
 */
DSO.Search = (function($, ns, r) {

  ns.INPUT_SEARCH = '.typeahead';

  ns.init = function() {
    if (0 < $(ns.INPUT_SEARCH).length) {
      ns.launchAutoComplete();
    }
  };

  /**
   *
   */
  ns.launchAutoComplete = function() {

    $.typeahead({
      input: ns.INPUT_SEARCH,
      minLength: 2,
      dynamic: true,
      filter: false,
      dynamicFilter: null,
      maxItem: 20,
      maxItemPerGroup: 5,
      hint: true,
      group: {
        template: "{{group}}"
      },
      template: "{{value}} <small style='color:#999;'>{{info}}</small>",
      emptyTemplate: "No results for {{query}}",
      display: ["*"],
      source: {
        dso: {
          ajax: {
            type: "POST",
            url: r.generate('search_autocomplete'),
            path: "data.astronomy.dso",
            data: {
              search: '{{query}}'
            }
          }
        },
        constellations: {
          ajax: {
            type: "POST",
            url: r.generate('search_autocomplete'),
            path: "data.astronomy.constellations",
            data: {
              search: '{{query}}'
            }
          }
        }
      },
      callback: {
        onInit: function (node) {
        },
        onResult: function(node, query, result, resultCount, resultCountPerGroup) {
        },
        onClick: function(node, a, item, event) {
          window.location.href = r.generate('dso_full', {catalog: item.catalog, objectId: item.id});
        }
      }
    })
  };

  return ns;

})(jQuery, {}, Routing);
