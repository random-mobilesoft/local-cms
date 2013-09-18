class RegionBlog extends Region

    init : ->
        # elements
        @$content    = @$el.find( '#blog-content' )
        @$articles   = @$el.find( 'article' )
        @$buttons    = @$el.find( '.blog-button' )
        @$bar        = @$el.find( '#blog-content-bar' )
        @$pagination = @$el.find( '#blog-pagination' )
        @$title      = @$bar.find( 'h1' )

        # dom events
        tapCb = (e) =>
            tagName = e.target.tagName.toLowerCase()
            while tagName isnt 'span'
                e.target = $(e.target).parent()[0]
                tagName = e.target.tagName.toLowerCase()
            @move(e)

        @$buttons.hammer().on( 'tap', tapCb )
        @

    move : (e) ->
        $el             = $( e.target )
        direction       = if $el.attr('id') is 'blog-button-up' then 0 else 1
        $currentArticle = @$el.find('article.blog-list-active')
        $nextArticle    = if direction is 1 then $currentArticle.next() else $currentArticle.prev()
        
        if $nextArticle.length is 1 and $nextArticle[0].tagName.toLowerCase() is 'article'
            position    = $nextArticle.position()
            $currentArticle.removeClass('blog-list-active')
            $nextArticle.addClass('blog-list-active')
            
            if Modernizr.cssanimations
                @$bar.css( 'top', position.top * -1 )
            else
                @$bar.animate { top : position.top * -1 }, { duration : 500 }
        @

    enter : ->
        delay    = 200
        interval = 0

        articlesCb = (i, el) => 
            animateCb = => 
                if Modernizr.cssanimations
                    app.animate {
                        element  : el
                        cssClass : 'state-in'
                        complete : => @events.trigger('region.entered', [@]) if i is @$articles.length - 1
                    }
                else 
                    $(el).animate { opacity : 1 }, { duration : 500, complete : => @events.trigger('region.entered', [@]) if i is @$articles.length - 1  }

            _.delay( animateCb, interval )
            interval += delay
        
        buttonsCb = (i, el) =>
            if Modernizr.cssanimations
                app.animate {
                    element  : el
                    cssClass : 'state-in'
                }
            else
                $(el).animate { opacity : 1 }, { duration : 500 }

        if Modernizr.cssanimations
            app.animate {
                element  : @$title[0]
                cssClass : 'state-in'
            }
        else
            @$title.animate { opacity : 1 }, { duration : 500 }

        if @$pagination.length
            if Modernizr.cssanimations
                app.animate {
                    element  : @$pagination[0]
                    cssClass : 'state-in'
                }
            else
                @$pagination.animate { opacity : 1 }, { duration : 500 }

        @$buttons.each( buttonsCb )
        @$articles.find('.blog-list-content').each( articlesCb )
        @

    exit : ->
        delay    = 200
        interval = 0

        articlesCb = (i, el) => 
            animateCb = => 
                if Modernizr.cssanimations
                    app.animate {
                        element  : el
                        cssClass : 'state-out'
                        complete : => 
                            if i is @$articles.length - 1
                                @events.trigger('region.quitted', [@])
                                @events.clear()
                    }
                else 
                    cb = =>
                        if i is @$articles.length - 1
                            @events.trigger('region.quitted', [@])
                            @events.clear()

                    $(el).animate { opacity : 0 }, { duration : 500, complete : => cb() }

            _.delay( animateCb, interval )
            interval += delay

        buttonsCb = (i, el) =>
            app.animate {
                element  : el
                cssClass : 'state-out'
            }

        if Modernizr.cssanimations
            app.animate {
                element  : @$title[0]
                cssClass : 'state-out'
            }
        else
            @$title.animate { opacity : 0 }, { duration : 500 }

        if @$pagination.length
            app.animate {
                element  : @$pagination[0]
                cssClass : 'state-out'
            }

        @$buttons.each( buttonsCb )
        @$articles.find('.blog-list-content').each( articlesCb )
        @