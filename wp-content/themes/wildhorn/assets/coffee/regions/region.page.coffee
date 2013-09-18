class RegionPage extends Region

    init : ->
        # elements
        @$title   = @$el.find('> h1:eq(0)')
        @$content = @$el.find('> .page-content:eq(0)')
        @$thumb   = @$el.find('#page-featured img')
        @

    enter : ->
        # image preload
        if @$thumb.length
            app.utils.imagePreload @$thumb[0], (src) => 
                if Modernizr.cssanimations
                    app.animate {
                        element  : @$thumb[0]
                        cssClass : 'state-loaded'
                    }
                else
                    @$thumb.animate { opacity : 1 }, { duration : 500 }

        if Modernizr.cssanimations
            app.animate {
                element  : @$title[0]
                cssClass : 'state-in'
            }
            app.animate {
                element  : @$content[0]
                cssClass : 'state-in',
                complete : => @events.trigger('region.entered', [@])
            }
        else
            @$title.animate { opacity : 1 }, { duration : 500 }
            @$content.animate { opacity : 1 }, { duration : 500, complete : => @events.trigger('region.entered', [@]) }
        @

    exit : ->
        if Modernizr.cssanimations
            app.animate {
                element  : @$title[0]
                cssClass : 'state-out'
            }
            app.animate {
                element  : @$content[0]
                cssClass : 'state-out',
                complete : =>
                    @events.trigger 'region.quitted', [@]
                    @events.clear()
            }
        else
            @$title.animate { opacity : 0 }, { duration : 500 }
            @$content.animate { opacity : 0 }, { duration : 500, complete : => 
                @events.trigger('region.quitted', [@])
                @events.clear() 
            }
        @