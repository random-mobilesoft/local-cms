class RegionHalfpage extends Region

    init : ->
        # elements
        @$title     = @$el.find( 'h1' )
        @$container = @$el.find( '#half-page-container' )
        @$column1   = @$el.find( '#half-page-column-1' )
        @$column2   = @$el.find( '#half-page-column-2' )
        @

    enter : ->
        if Modernizr.cssanimations
            app.animate {
                element  : @$title[0]
                cssClass : 'state-in'
            }

            app.animate {
                element  : @$column1[0]
                cssClass : 'state-in'
            }

            app.animate {
                element  : @$column2[0]
                cssClass : 'state-in'
                complete : => @events.trigger('region.entered', [@])
            }
        else
            @$title.animate { opacity : 1 }, { duration : 500 }
            @$column1.animate { opacity : 1 }, { duration : 500 }
            @$column2.animate { opacity : 1 }, { duration : 500 }

        @

    exit : ->
        if Modernizr.cssanimations
            app.animate {
                element  : @$title[0]
                cssClass : 'state-out'
            }

            app.animate {
                element  : @$column1[0]
                cssClass : 'state-out'
            }

            app.animate {
                element  : @$column2[0]
                cssClass : 'state-out'
                complete : => 
                    @events.trigger('region.quitted', [@])
                    @events.clear()
            }
        else
            cb = => 
                @events.trigger('region.quitted', [@])
                @events.clear()
            
            @$title.animate   { opacity : 0 }, { duration : 500 }
            @$column1.animate { opacity : 0 }, { duration : 500 }
            @$column2.animate { opacity : 0 }, { duration : 500, complete : cb }
        @