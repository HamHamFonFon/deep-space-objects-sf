window.DSO = window.DSO || {};

$(function () {
  for(var module in DSO){
    if (DSO[module].init){
      DSO[module].init();
    }
  }
});