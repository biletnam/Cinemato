var TicketForm,
  __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

TicketForm = (function() {
  function TicketForm(config) {
    this.bind = __bind(this.bind, this);
    config = $.extend({
      hasClientField: '#',
      tarifField: '#',
      abonneField: '#'
    }, config);
    this.hasClientField = $(config.hasClientField);
    this.tarifField = $(config.tarifField);
    this.abonneField = $(config.abonneField);
    this.bind();
    return this;
  }

  TicketForm.prototype.bind = function() {
    this.hasClientField.on('change', (function(_this) {
      return function(e) {
        if (_this.hasClientField.is(':checked')) {
          _this.tarifField.addClass('hidden-field');
          return _this.abonneField.removeClass('hidden-field');
        } else {
          _this.abonneField.addClass('hidden-field');
          return _this.tarifField.removeClass('hidden-field');
        }
      };
    })(this));
    return this;
  };

  return TicketForm;

})();

$(function() {
  var __ticket_form__;
  return __ticket_form__ = new TicketForm({
    'hasClientField': '#ticket_form_hasClientAccount',
    'tarifField': '#ticket-form-tarif-container',
    'abonneField': '#ticket-form-abonne-container'
  });
});
