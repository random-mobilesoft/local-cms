class RegionHome extends Region

    init : ->
        @$boxes         = @$el.find('.home-box')
        @$buttonNews    = @$el.find('#home-news-display')
        @$claimMessage  = @$el.find('#home-claim h1 span')
        @$claimButton   = @$el.find('#home-claim h1 a')

        # events
        @events.on 'page.toggleAside', => @toggleAside()

        # dom events
        if @$buttonNews.length
            @$buttonNews.on 'click', (e) => @events.trigger('page.toggleAside')
        @

    toggleAside : ->
        if @$el.hasClass 'state-translate'
            app.events.trigger 'page.asideIn'
        else
            app.events.trigger 'page.asideOut'

        if Modernizr.cssanimations
            app.animate {
                element  : @$el[0]
                cssClass : 'state-translate'
            }
        else
            animateOptions = { duration : 500, easing : 'swing' }
            if @$el.hasClass 'news-displayed'
                @$el.removeClass( 'news-displayed' )
                @$el.animate { left : 0 }, animateOptions
            else
                @$el.animate { left : -300 }, animateOptions
                @$el.addClass( 'news-displayed' )


        @

    enter : ->
        if @$buttonNews.length
            if Modernizr.cssanimations
                app.animate {
                    element  : @$buttonNews[0]
                    cssClass : 'state-in'
                }
            else
                @$buttonNews.fadeIn(500)

        if @$claimMessage.length
            if Modernizr.cssanimations
                app.animate {
                    element  : @$claimMessage[0]
                    cssClass : 'state-in'
                    complete : => @events.trigger('region.entered', [@])

                }
            else
                messageCb = => @$claimMessage.animate { opacity : 1 }, { duration : 1000 }
                _.delay( messageCb, 1000 )

        if @$claimButton.length
            if Modernizr.cssanimations
                app.animate {
                    element  : @$claimButton[0]
                    cssClass : 'state-in'
                    complete : => @events.trigger('region.entered', [@]) if (@$boxes.length is 0) and (@$claimMessage.length is 0)
                }
            else
                buttonCb = => @$claimButton.animate { opacity : 1 }, { duration : 1500 }
                _.delay( buttonCb, 1500 )

        if @$boxes.length
            delay    = 350
            interval = 0;
            if Modernizr.cssanimations
                @$boxes.each (i, el) =>
                    cb = =>
                        app.animate {
                            element  : el,
                            cssClass : 'state-in',
                            complete : => @events.trigger('region.entered', [@]) if (i is @$boxes.length - 1) and (@$claimMessage.length is 0)
                        }
                    _.delay cb, interval
                    interval += delay
            else
                @$boxes.each (i, el) =>
                    cb = => 
                        $(el).animate { opacity : 1 }, { duration : 500, complete : => @events.trigger('region.entered', [@]) if i is @$boxes.length - 1 }

                    _.delay cb, interval
                    interval += delay
        
        if (@$claimMessage.length is 0) and (@$boxes.length is 0) and (@$claimButton.length is 0)
            @events.trigger('region.entered', [@])
        @

    exit : ->
        @$el.removeClass 'state-translate'

        if @$buttonNews.length
            if Modernizr.cssanimations
                app.animate {
                    element  : @$buttonNews[0]
                    cssClass : 'state-out'
                }
            else
                @$buttonNews.fadeOut(500)

        if @$claimMessage.length
            if Modernizr.cssanimations
                app.animate {
                    element  : @$claimMessage[0]
                    cssClass : 'state-out'
                }
            else
                @$claimMessage.fadeOut(500)

        if @$claimButton.length
            if Modernizr.cssanimations
                app.animate {
                    element  : @$claimButton[0]
                    cssClass : 'state-out'
                }
            else
                @$claimButton.fadeOut(500)

        if @$boxes.length
            delay    = 250
            interval = 0;
            if Modernizr.cssanimations
                @$boxes.each (i, el) =>
                    cb = =>
                        app.animate {
                            element  : el,
                            cssClass : 'state-out',
                            complete : => 
                                if i is @$boxes.length - 1
                                    @events.trigger('region.quitted', [@])
                                    @events.clear()
                                    
                        }
                    _.delay cb, interval
                    interval += delay
            else
                @$boxes.each (i, el) =>
                    cb = => 
                        $(el).animate { opacity : 0 }, { duration : 500, complete : => 
                            if i is @$boxes.length - 1 
                                @events.trigger('region.quitted', [@])
                                @events.clear()
                        }
                    _.delay cb, interval
                    interval += delay
        else
            @events.trigger('region.quitted', [@])
            @events.clear()
        @