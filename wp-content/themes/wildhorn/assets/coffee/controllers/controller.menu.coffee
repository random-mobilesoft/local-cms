class ControllerMenu extends Controller

    init : ->
        # elements
        @$container = $( '#footer-menu' )
        @$bar       = $( '#footer-bar' )
        @$menu      = @$container.find '> * > ul'

        # properties
        selectId    = 'select-navigation'
        dataOptions = @getSelectData()
        htmlOptions = @getSelectHtml(dataOptions)
        htmlSelect  = "<select id='#{selectId}'>#{htmlOptions}</select>"
        @$bar.append htmlSelect

        @$select    = @$bar.find("##{selectId}")

        # events
        @$select.on 'change', (e) => @onSelectChange(e)
        app.events.on 'history.statechange', (state) => 
            url            = state.url
            optionSelected = false;
            $options       = @$select.find( 'option' )
            $options.each (i, el) =>
                $el = $(el)
                if $el.attr( 'value' ) is url
                    optionSelected = el
            
            if optionSelected
                $options.removeAttr( 'selected' )
                $(optionSelected).attr( 'selected', 'true' )

        @

    setMenuWidth : ->
        iconsDisplay = @$icons.css('display')
        if iconsDisplay is 'block'
            iconsWidth          = @$icons.width()
            menuContainerWidth  = @$menu.parent().parent().width()
            menuWidth           = menuContainerWidth - iconsWidth - 21
            @$menu.css( 'width', menuWidth )
        else
            @$menu.css('width', 'auto')

        # find second line 
        firstTop = null
        $li      = @$menu.find('> li')
        $li.removeClass('hide')
        $li.each (i , el) =>
            $el      = $(el)
            firstTop = $el.position().top if i is 0
            if $el.position().top > firstTop
                $el.addClass 'hide'
        @

    getSelectData : ($el) ->
        $el = @$menu if not $el?
        tmp = []

        if $el.length
            $anchors = $el.find '> li > a'
            $anchors.each (i, el) =>
                $a     = $ el
                href   = $a.attr 'href'
                href   = window.location.href if not href
                option = { value : href, label : $a.text() }
                if _.isElement($a.next()[0]) and ($a.next()[0].tagName is 'UL')
                    option.sub = @getSelectData( $a.next() )
                tmp.push option
        tmp

    getSelectHtml : (o) ->
        str = ''
        cb  = (o) =>
            selected = if o.value is location.href then 'selected' else ''
            option   = "<option #{selected} value='#{o.value}'>#{o.label}</option>" 
            if o.sub
                options = @getSelectHtml(o.sub)
                o.label = o.label.replace(/\'/g, '&apos;')
                option += "<optgroup label='#{o.label}'>#{options}</optgroup>"
            str += option

        _.each(o, (o) => cb(o))
        str

    onSelectChange : (e) ->
        $el   = $ e.target
        value = $el.val()
        if typeof(value) isnt 'undefined'
            if app.config.pushState and app.config.ajaxLoad
                state = { url : value }
                window.history.pushState( state, app.view.title, value )
                app.events.trigger 'history.statechange', [state]
            else
                window.location = value
        value