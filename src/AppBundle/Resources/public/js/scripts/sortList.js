window.DSO = window.DSO || {};

DSO.sortList = function($, ns) {

  ns.FORM = 'form[name="list_order"]';
  ns.SELECT = 'select#list_order_order';

  ns.init = function() {

    $(ns.SELECT).on('change', function(e) {
      $(ns.FORM).submit();
    })
  };

  return ns;
}(jQuery, {});