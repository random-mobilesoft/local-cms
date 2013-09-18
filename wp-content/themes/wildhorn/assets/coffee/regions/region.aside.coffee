class RegionAside extends Region

    init : ->
        app.events.on 'page.toggleAside', => @toggle()
        app.events.on 'view.exit', => @toggle() if @$el.hasClass('state-translate')
        @

    toggle : ->
        if Modernizr.cssanimations
            app.animate {
                element  : @$el[0]
                cssClass : 'state-translate'
            }
        else
            animateOptions = { duration : 500, easing : 'swing' }
            if @$el.hasClass 'news-displayed'
                @$el.removeClass( 'news-displayed' )
                @$el.animate { right : -300 }, animateOptions
            else
                @$el.animate { right : 0 }, animateOptions
                @$el.addClass( 'news-displayed' )
        @