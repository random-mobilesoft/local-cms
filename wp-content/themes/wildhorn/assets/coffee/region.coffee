# region class

class Region

    constructor: (@$el, @global) ->
        @id = _.uniqueId 'region_'

        # events object
        @events = {

            listeners : []

            on : (event, callback) ->
                if _.isString(event) and _.isFunction(callback)
                    e = { 
                        event    : event
                        callback : callback
                    }

                    app.events.on e.event, e.callback
                    @listeners.push e
                @

            off : (event, callback) ->
                app.events.off event, callback
                @

            clear : ->
                console.log 
                _.each @listeners, (e) => 
                    app.events.off e.event, e.callback
                @

            trigger : (event, args) ->
                if _.isString event
                    app.events.trigger event, args
        }

        # events
        if not @global
            @events.on 'view.enter', => @enter()
            @events.on 'view.exit',  => @exit()

        @init()
        @

    init : -> 
        @

    enter : ->
        @events.trigger('region.entered', [@])
        @

    exit : ->
        @events.trigger('region.quitted', [@])
        @events.clear()
        @