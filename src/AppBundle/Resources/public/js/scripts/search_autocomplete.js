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
      maxItem: 20,
      maxItemPerGroup: 5,
      hint: true,
      group: {
        template: "{{group}}"
      },
      debug: true,
      template: "{{value}} <small style='color:#999;'>{{id}}</small>",
      emptyTemplate: "No results for {{query}}",
      display: ["id", "value"],
      source: {
        messiers: {
          ajax: {
            type: "POST",
            url: r.generate('search_autocomplete'),
            path: "data.astronomy.messiers",
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
          window.location.href = r.generate('messier_full', {objectId: item.id});
        }
      }
    })
  };

  return ns;

})(jQuery, {}, Routing);
