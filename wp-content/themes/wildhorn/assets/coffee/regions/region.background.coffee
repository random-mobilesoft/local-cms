class RegionBackground extends Region

    init : ->
        # move to #container
        $el = @$el.detach()
        $('#container').append($el)

        @type = @$el.attr "data-type"
        @setupImages() if @type is "images"
        @

    setupImages : ->
        @$images   = @$el.find '.background-image'
        @delay     = @$el.data 'delay'
        @imagesUrl = []
        tmp        = []

        @$images.addClass('state-init')

        for image, i in @$images
            if _.isElement image
                src = $(image).data('src')
                if _.isString src 
                    app.utils.imagePreload src, (img) =>
                        tmp.push(src)
                        if tmp.length is @$images.length
                            # reorder images url
                            for image, i in @$images
                                if _.isElement(image)
                                    src = $(image).data('src')
                                    @imagesUrl.push(src)
                            @setupLoop()
        @

    setupLoop : ->
        @$images.remove()
        @index = 0
        switch @imagesUrl.length
            when 0
                null
            when 1
                @setImage()
            else
                @events.on 'background.imageSet', => @setupTimeout()
                @setImage()
        @

    setImage : -> 
        src     = @imagesUrl[@index]
        el      = $("<div>").addClass("background-image state-init state-over").css("background-image", "url('#{src}')")
        $elPrev = @$el.find('.state-under');
        # @$el.find('#background-pattern').before(el)
        @$el.append el
        cb = => 
            if Modernizr.csstransitions
                app.animate {
                    element  : el
                    cssClass : 'state-in'
                    complete : =>
                        $elPrev.remove()
                        $(el).removeClass("state-over").addClass("state-under")
                        @events.trigger 'background.imageSet'
                }
            else
                events = @events
                cb = (el) ->
                    $elPrev.remove()
                    $(el).removeClass("state-over").addClass("state-under")
                    events.trigger 'background.imageSet'

                $(el).animate { opacity : 1 }, { duration : 1000, complete : -> cb(@) }

        setTimeout cb, 500
        @

    setupTimeout : ->
        cb = =>
            @index = if @index + 1 is @imagesUrl.length then 0 else @index + 1
            @setImage()
            null
        _.delay cb, @delay
        @

    enter : ->
        if Modernizr.cssanimations
            app.animate {
                element  : @$el[0]
                cssClass : 'state-in'
                complete : => @events.trigger('region.entered', [@])
            }
        else
            @$el.animate { opacity : 1 }, { duration : 500, complete : => @events.trigger('region.entered', [@]) }

            cb = => @events.trigger('region.entered', [@])
            _.delay(cb)
        @

    exit : ->
        if Modernizr.cssanimations
            app.animate {
                element  : @$el[0]
                cssClass : 'state-out'
                complete : =>
                    @events.trigger('region.quitted', [@])
                    @events.clear()
                    @$el.remove()
            }
        else
            cb = =>
                @events.trigger('region.quitted', [@])
                @events.clear()
                @$el.remove()

            @$el.animate { opacity : 0 }, { duration : 500, complete : => cb() }
        @
