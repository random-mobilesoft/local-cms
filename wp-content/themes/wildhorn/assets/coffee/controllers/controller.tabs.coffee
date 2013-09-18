class ControllerTabs extends Controller 

    init : ->
        app.events.on 'view.rendered', => @set()
        # app.events.on 'viewport.resize', => console.log 'tab resize'

        @tabs = []
        @

    set : ->
        @$tabs = $ '.tabs'

        if @$tabs.length
            @$tabs.each (index, el) =>
                $el       = $ el
                $labels   = $el.find 'span.tab-label'
                $contents = $el.find 'div.tab'

                if ($labels.length is $contents.length) and ($labels.length > 0)

                    # hide contents
                    $contents.filter(':gt(0)').addClass 'state-hide'

                    # build tabs
                    $div = $('<div></div>')
                    $ul  = $('<ul></ul>')
                    $div.addClass('tabs-label')
                    
                    $labels.each (i, el) ->
                        $span = $ el
                        $li   = $ "<li>#{$span.text()}</li>"
                        $li.attr 'data-index', i
                        if( i > 0)
                            $li.addClass 'tab-unsel'
                        $ul.append($li)

                    $ul.append '<li>&nbsp;</li>'
                    $div.append($ul)
                    $el.prepend($div)
                    $labels.remove()

                    $labels = $el.find( ".tabs-label ul li" )

                    # dom events
                    lcb = (e) =>
                        $el = $ e.target
                        if $el.hasClass 'tab-unsel'
                            index = $el.attr 'data-index'
                            $tabs = $el.parent().parent().parent()
                            @switch $tabs,index

                    $labels.on( 'click', (e) => lcb(e) )

                    # add select
                    $select = $ '<select />'
                    $labels.each (index, el) =>
                        labelText = $(el).text()
                        $select.append "<option value='#{index}'>#{labelText}</option>" if index < $labels.length - 1

                    scb = (e) =>
                        $el   = $ e.target
                        $tabs = $el.parent()
                        index = $el.val()
                        @switch $tabs,index

                    $select.on 'change', (e) => scb(e)
                    $el.prepend($select)

                # set tab line width
                # $lastLi = $ul.find 'li:last-child'
                # widthLi = 0
                # $ul.find('li:not(li:last-child)').each (i,el) =>
                    # $el   = $ el
                    # width = $el.width() + parseInt($el.css('padding-left')) * 2 + 1 - 20
                    # widthLi += width

                # widthLi += parseInt($lastLi.css('padding-left')) * 2 + 2
                # $lastLi.width( $ul.width() - widthLi )
        @

    switch : ($el, i) ->
        
        # labels
        $contents = $el.find( '.tab' )
        $labels   = $el.find( '.tabs-label ul li' )
        console.log $contents

        $contents.not('.state-hide').addClass('state-hide')
        $contents.filter(":eq(#{i})").removeClass('state-hide')

        $labels.not('.tab-unsel').not(':last-child').addClass('tab-unsel')
        $labels.filter(":eq(#{i})").removeClass('tab-unsel')

        # select
        $el.find('select:eq(0)').val(i)
        @
