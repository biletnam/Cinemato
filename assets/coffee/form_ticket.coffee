class TicketForm

    constructor: (config) ->
        config = $.extend {
            hasClientField: '#'
            tarifField: '#'
            abonneField: '#'
        }, config
        @hasClientField = $ config.hasClientField
        @tarifField = $ config.tarifField
        @abonneField = $ config.abonneField
        @bind()
        return @

    bind: () =>
        @hasClientField.on 'change', (e) =>
            if @hasClientField.is ':checked'
                @tarifField.addClass 'hidden-field'
                @abonneField.removeClass 'hidden-field'
            else
                @abonneField.addClass 'hidden-field'
                @tarifField.removeClass 'hidden-field'
        @

$ ->

    __ticket_form__ = new TicketForm {
        'hasClientField': '#ticket_form_hasClientAccount'
        'tarifField': '#ticket-form-tarif-container'
        'abonneField': '#ticket-form-abonne-container'
    }
