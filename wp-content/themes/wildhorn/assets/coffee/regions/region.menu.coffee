class RegionMenu extends Region

    init : -> 
        # elements
        @$menu = @$el.find('> * > ul')

        # events
        @events.on 'view.enter', => @listSelect()

        @setSubMenus()
        @

    setSubMenus : ($el) ->
        $el = @$menu if $el is undefined
        $el.find('> li').each (i, el) => 
            ul = $(el).find('> ul')[0]
            if _.isElement(ul)
                $ul = $(ul)
                top = $ul.height() * -1
                $ul.css('top', top)
        @

    listSelect : ->
        # remove previous list class
        @$menu.find('li.nav-sel').removeClass('nav-sel')
        @$menu.find('li.nav-item-sel').removeClass('nav-item-sel')

        # set nav-sel
        url    = window.location.href
        anchor = @$el.find "a[href='#{url}']"
        if anchor.length
            list = null
            end  = false
            el   = anchor
            while not end
                elName = el[0].tagName
                if elName is 'LI'
                    list = el
                el     = el.parent()
                if el.attr 'data-region'
                    end = true

            list.addClass 'nav-sel'

            #set nav-item-sel
            anchor.parent().addClass 'nav-item-sel'
        @