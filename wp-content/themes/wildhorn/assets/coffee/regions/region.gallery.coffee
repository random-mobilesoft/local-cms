class RegionGallery extends Region

    init : ->
        # elements
        @$loader         = @$el.find( '#gallery-loader' )
        @$imageContainer = @$el.find( '#gallery-image-container' )
        @$caption        = @$el.find( '#gallery-image-caption' )
        @$buttonNext     = @$el.find( '#gallery-button-next' )
        @$buttonPrev     = @$el.find( '#gallery-button-prev' )
        @$title          = @$el.find( 'h1' )
        @$swipe          = @$el.find( '#gallery-image-swipe' )

        @loadImages() if typeof(mthemes_gallery_images) isnt 'undefined' and _.isArray(mthemes_gallery_images)

        # events
        @events.on( 'gallery.imageLoaded',   => @$loader.text( "#{@percent}%" ) )
        @events.on( 'gallery.galleryLoaded', => @buildGallery() )
        @events.on( 'gallery.next', => @next() )
        @events.on( 'gallery.prev', => @prev() )

        # dom events
        @$buttonNext.hammer().on('tap', => @events.trigger('gallery.next') )
        @$buttonPrev.hammer().on('tap', => @events.trigger('gallery.prev') )

        @$swipe.hammer().on('swipeleft',  => @events.trigger('gallery.next') )
        @$swipe.hammer().on('swiperight', => @events.trigger('gallery.previous') )
        @

    next : ->
        clearTimeout(@timeout) if @timeout
        @index     = if (@index + 1) < @images.length then @index + 1 else 0
        @direction = 1
        @setImage()
        @

    prev : ->
        clearTimeout(@timeout) if @timeout
        @index     = if (@index - 1) < 0 then @images.length - 1 else @index - 1
        @direction = 0
        @setImage()
        @

    loadImages : ->
        @images         = mthemes_gallery_images
        @index          = 0
        @percent        = 0
        
        imageLoadCb     = (el, i) => app.utils.imagePreload( el.src, imageLoadedCb )
        imageLoadedCb   = => 
            @index++
            @percent    = Math.ceil( ( 100 / @images.length ) * @index )
            @events.trigger( 'gallery.imageLoaded' )
            if( @index is @images.length )
                @events.trigger( 'gallery.galleryLoaded' )
        
        _.each(@images, imageLoadCb)
        @

    buildGallery : ->
        # remove loader
        if Modernizr.cssanimations
            app.animate {
                element  : @$loader[0]
                cssClass : 'state-out'
                complete : -> $(this).parent().remove()
            }
        else
            cb = => @$loader.parent().remove()
            @$loader.fadeOut(500, cb)

        @delay      = if typeof(mthemes_gallery_images_delay) isnt 'undefined' and _.isNumber(mthemes_gallery_images_delay) then mthemes_gallery_images_delay else 7500
        @autoplay   = if typeof(mthemes_gallery_autoplay) isnt 'undefined' and mthemes_gallery_autoplay then mthemes_gallery_autoplay else false
        @stop       = false
        @index      = 0
        @timeout    = null
        @direction  = 1
        @setImage( @autoplay )
        @

    setImage : (delay = true) ->
        if not @stop
            @stop = true
            image = @images[@index]
            $span = $('<span>').addClass('gallery-image-new').addClass('state-init').css('background-image', "url('#{image.src}')")
            @$imageContainer.append( $span )

            cb  = (type,animation,el) =>
                $(el).parent().find('span').not('.gallery-image-new').remove()
                $(el).removeClass( 'gallery-image-new' )
                @stop = false

            cb2 = => 
                @index      = if (@index + 1) < @images.length then @index + 1 else 0
                @direction  = 1
                @setImage()

            captionCb = =>
                if image.caption isnt ''
                    @$caption.text( image.caption )
                    if Modernizr.cssanimations
                        app.animate {
                            element  : @$caption[0]
                            cssClass : 'state-out'
                        }
                    else
                        @$caption.animate { opacity : 1 }, { duration : 500 }

            if not @$caption.hasClass( 'state-out' )
                if Modernizr.cssanimations
                    app.animate {
                        element  : @$caption[0]
                        cssClass : 'state-out'
                        complete : => captionCb()
                    }
                else
                   @$caption.animate { opacity : 0 }, { duration : 500, complete : => captionCb() } 

            else
                captionCb()

            if Modernizr.cssanimations
                app.animate {
                    element  : $span
                    cssClass : if @direction is 1 then 'state-in-left' else 'state-in-right'
                    complete : cb
                }
            else
                $span.animate { opacity : 1 }, { duration : 500, complete : cb }

            @timeout = _.delay(cb2, @delay) if delay
        @

    enter : -> 
        if Modernizr.cssanimations 
            app.animate {
                element  : @$title[0]
                cssClass : 'state-in'
                complete : => @events.trigger('region.entered', [@])
            }
        else
            @$title.animate { opacity : 1 }, { duration : 500, complete : => @events.trigger('region.entered', [@]) }
        @

    exit : -> 
        if Modernizr.cssanimations 
            app.animate {
                element  : @$title[0]
                cssClass : 'state-out'
            }

            app.animate {
                element  : @$imageContainer[0]
                cssClass : 'state-out'
                complete : =>
                    @events.trigger('region.quitted', [@])
                    @events.clear()
            }
        else
            @$title.fadeOut( 500 )
            
            @$imageContainer.fadeOut( 500, => 
                @events.trigger('region.quitted', [@])
                @events.clear() )            
        @
