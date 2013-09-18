class RegionLoader extends Region
    init : ->
        @events.on 'view.quitted', =>
            @display()

        @events.on 'view.enter', =>
            @hide()
        @

    display : ->
        @$el.removeClass().addClass 'loader-display'
        @

    hide : ->
        @$el.removeClass().addClass 'loader-hide'
        @