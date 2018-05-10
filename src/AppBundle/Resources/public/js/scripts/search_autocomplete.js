window.DSO = window.DSO || {};

/**
 * @var jquery-typeahead
 */
let typeahead = require('jquery-typeahead');

DSO.Search = (function($, ns, r) {

  ns.INPUT_SEARCH = '.typeahead';

  ns.init = function() {
    ns.launchAutoComplete();
  };

  ns.launchAutoComplete = function() {

    var searchTerm = 'test';
    $.typeahead({
      input: ns.INPUT_SEARCH,
      minLength: 3,
      maxItem: 20,
      template: "{{display}} <small style='color:#999;'>{{group}}</small>",
      source: {
        dso: {
          ajax: {
            url: r.generate('search_autocomplete'),
            data: {search: '{{query}}'}
          }
        }
      },
      callback: {
        onInit: function (node) {
          console.log('Typeahead Initiated on ' + node.selector);
        },
        onClick: function(node, a, item, event) {
          console.log(node);
          console.log(a);
          console.log(item);
          console.log(event);

          console.log('onClick function triggered');
          window.location.href = r.generate('messier_full', {objectId: item.id});
        }
      }
    })
  };

  return ns;

})(jQuery, {}, Routing);
