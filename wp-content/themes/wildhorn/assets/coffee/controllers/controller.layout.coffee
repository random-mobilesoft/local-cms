class ControllerLayout extends Controller

    contentMinHeight : 550

    init : ->
        # elements
        @$container    = $ '#container:eq(0)'
        @$html         = $ 'html:eq(0)'
        @$header       = $ 'header:eq(0)'
        @$footer       = $ 'footer:eq(0)'
        @$body         = $ 'body:eq(0)'

        # vars
        @scrollLayout  = null

        @removeHtmlMargin()
        @

    removeHtmlMargin : ->
        if @$body.hasClass 'admin-bar'
            @$html[0].setAttribute('style', 'margin-top: 0 !important')
        @