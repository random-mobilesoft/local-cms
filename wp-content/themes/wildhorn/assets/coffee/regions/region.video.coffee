class RegionVideo extends Region

    init : ->
        # elements
        @$title = @$el.find( 'h1' )
        @$video = @$el.find( '#video-content' )
        @

    enter : ->
        if Modernizr.cssanimations
            app.animate {
                element  : @$title[0]
                cssClass : 'state-in'
            }

            app.animate {
                element  : @$video[0]
                cssClass : 'state-in'
                complete : => @events.trigger('region.entered', [@])
            }
        else
            @$title.animate { opacity : 1 }, { duration : 500 }
            @$video.animate { opacity : 1 }, { duration : 500 }
        @

    exit : ->
        if Modernizr.cssanimations
            app.animate {
                element  : @$title[0]
                cssClass : 'state-out'
            }

            app.animate {
                element  : @$video[0]
                cssClass : 'state-out'
                complete : => 
                    @events.trigger('region.quitted', [@])
                    @events.clear()
            }
        else
            cb = =>
                @events.trigger('region.quitted', [@])
                @events.clear()

            @$title.animate { opacity : 0 }, { duration : 500 }
            @$video.animate { opacity : 0 }, { duration : 500, complete : cb }
        @