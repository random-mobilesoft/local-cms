class RegionSidebar extends Region

    enter : ->
        if Modernizr.cssanimations
            app.animate {
                element  : @$el[0]
                cssClass : 'state-in',
                complete : => @events.trigger('region.entered', [@])
            }
        else
            @$el.animate { opacity : 1 }, { duration : 500, complete : => @events.trigger('region.entered', [@]) }
        @

    exit : ->
        if Modernizr.cssanimations
            sidebarDisplay = @$el.css('display')
            if sidebarDisplay is 'none'
                @events.trigger('region.quitted', [@])
                @events.clear() 

            app.animate {
                element  : @$el[0]
                cssClass : 'state-out',
                complete : => 
                    @events.trigger('region.quitted', [@])
                    @events.clear() 
            }
        else
            @$el.animate { opacity : 0 }, { duration : 500, complete : => 
                @events.trigger('region.quitted', [@])
                @events.clear()
            }
        @