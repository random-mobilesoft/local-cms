class RegionSocials extends Region

    init : ->
        @events.on 'viewport.resize', => @switchDisplay()
        @

    switchDisplay : ->
        cb = =>
            $footer       = @$el.parent()
            if $footer.position().top > 100
                $menu         = $footer.find('#footer-menu > * > ul')
                footerClasses = if $footer.attr('class') then $footer.attr('class').split(' ') else []
                footerWidth   = $footer.width() / 2
                menuWidth     = 0
                socialsWidth  = @$el.width()

                $menu.find('li').each (i, el) =>
                    menuWidth += $(el).width() + 1
                
                if footerClasses.length > 0
                    audioplayerWidth = $footer.find('#audioplayer').width()
                    if( audioplayerWidth + socialsWidth + menuWidth ) > footerWidth
                        @$el.css('display', 'none')
                    else
                        @$el.css('display', 'block')

                if footerClasses.length is 0
                    if (socialsWidth + menuWidth) > footerWidth
                        @$el.css('display', 'none')
                    else
                        @$el.css('display', 'block')
            else
                @$el.css('display', 'none')

        _.delay(cb, 100)
        @