class RegionCollection extends Region

    init : ->
        # elements
        @$title           = @$el.find( 'h1' )
        @$content         = @$el.find( '#collection-content' )
        @$container       = @$el.find( '#collection-container' )
        @$elements        = @$el.find( '.collection-item' )
        @$elementsContent = @$elements.find( '.collection-item-content' )
        @$tagsTitle       = @$el.find( '#collection-tags-title' )
        @$tags            = @$el.find( '#collection-tags-list' )
        @$tagsLi          = @$tags.find( 'li' )

        # isotope
        @$content.isotope { 
            itemSelector : '.collection-item' 
            layoutMode   : 'masonry'
        }

        # preload
        @$elements.each (i, el) =>
            $el  = $(el)
            $img = $el.find( 'img.collection-item-thumb' )
            if $img.length
                src = $img.attr('src')
                app.utils.imagePreload src, (src) =>
                    if Modernizr.cssanimations
                        app.animate {
                            element  : el
                            cssClass : 'state-fade-out'
                            complete : => 
                                $el.css( 'background-image', "url('#{src}')" )
                                app.animate {
                                    element  : el
                                    cssClass : 'state-fade-in'
                                }
                        }
                    else
                        cb = =>
                            $el.css( 'background-image', "url('#{src}')" )
                            $el.animate { opacity : 1 }, { duration: 500 }

                        $el.animate { opacity : 0 }, { duration: 500, complete : cb }

        # dom events
        @$tagsLi.hammer().on( 'tap', (e) => @setFilter( $(e.target).attr('data-slug') ) )

        # attributes
        @filter = null

    setFilter : (slug) ->
        if slug isnt @filter
            @filter = if slug is '*' then slug else ".tag-#{slug}"
            @$tagsLi.removeClass 'collection-tags-list-sel'
            @$tagsLi.filter( "[data-slug='#{slug}']" ).addClass 'collection-tags-list-sel'
            @$content.isotope { filter : @filter }
        @

    enter : ->
        # title
        if Modernizr.cssanimations
            app.animate {
                element  : @$title[0]
                cssClass : 'state-in'
            }
        else
            @$title.animate { opacity : 1 }, { duration : 500 }

        # tags title
        if Modernizr.cssanimations
            app.animate {
                element  : @$tagsTitle[0]
                cssClass : 'state-in'
            }
        else
            @$tagsTitle.animate { opacity : 1 }, { duration : 500 }

        # tags 
        if Modernizr.cssanimations
            app.animate {
                element  : @$tags[0]
                cssClass : 'state-in'
            }
        else
            @$tags.animate { opacity : 1 }, { duration : 500 }

        # elements 
        delay    = 200
        interval = 0
        @$elements.each (i, el) =>
            el = $(el).find('.collection-item-content')
            cb = =>
                if Modernizr.cssanimations
                    app.animate {
                        element  : el
                        cssClass : 'state-in'
                        complete : => @events.trigger('region.entered', [@]) if i is @$elements.length - 1
                    }
                else
                    $(el).animate { opacity : 1 }, { duration : 1000, complete : => @events.trigger('region.entered', [@]) if i is @$elements.length - 1 }

            _.delay cb, interval
            interval += delay
        @

    exit : ->
        # title
        if Modernizr.cssanimations
            app.animate {
                element  : @$title[0]
                cssClass : 'state-out'
            }
        else
            @$title.animate { opacity : 0 }, { duration : 700 }

        # tags title
        if Modernizr.cssanimations
            app.animate {
                element  : @$tagsTitle[0]
                cssClass : 'state-out'
            }
        else
            @$tagsTitle.animate { opacity : 0 }, { duration : 700 }

        # tags 
        if Modernizr.cssanimations
            app.animate {
                element  : @$tags[0]
                cssClass : 'state-out'
            }
        else
            @$tags.animate { opacity : 0 }, { duration : 700 }

        if Modernizr.cssanimations
            app.animate {
                element  : @$container[0]
                cssClass : 'state-out'
                complete : =>
                    @events.trigger('region.quitted', [@])
                    @events.clear()
            }
        else
            cb = =>
                @events.trigger('region.quitted', [@])
                @events.clear()

            @$container.animate { opacity : 0 }, { duration : 500, complete : cb }
        @
