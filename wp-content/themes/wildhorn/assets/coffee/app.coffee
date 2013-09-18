# @codekit-prepend "region.coffee"
# @codekit-prepend "controller.coffee"
# 
# @codekit-append  "animate.coffee"
# @codekit-append  "utils.coffee"
# @codekit-append  "controllers.coffee"
# @codekit-append  "regions.coffee"



# app object

window.app = {
    config : {
        baseUrl     : mthemes_baseUrl
        contentId   : 'content'
        debug       : if jQuery.browser.msie and (parseInt(jQuery.browser.version) < 10) then false else true
        debugEvents : false
        pushState   : if Modernizr.history   then true else false
        ajaxLoad    : if mthemes_pagesReload then false else true
    }
    # events  : LucidJS.emitter()
    events  : new EventEmitter()
    log: (x) ->
        if(app.config.debug && console && console.log)
            console.log x
    utils   : {}
    vars    : {}
}



# jQuery

$ = window.jQuery



# popState event

if Modernizr.history
    window.history.replaceState { url : window.location.href }, ''
    window.addEventListener 'popstate', (e) =>
        if _.isObject(e.state) and _.isString(e.state.url)
            app.events.trigger 'history.statechange', [e.state]

    app.events.on 'history.statechange', (state) -> console.log "url changed in #{state.url}"


# view 

###
view state sequence:

history.statechange (anchor click / history forward / history back)
view.exit (regions begins to quit)
view.quitted (every $content region has quitted)
view.load (load new page)
view.rendered (new view content is appended to $content) or view.notRendered
view.enter (new view begin to enter)
view.entered (every $content region is entered)
###

app.view = {

    init : ->
        app.log 'app view init'
        @elements = {}

        # set events
        if app.config.pushState and app.config.ajaxLoad
            app.events.on 'history.statechange', ->
                cb = -> app.events.trigger 'view.exit'
                _.delay cb, 0

            app.events.on 'regions.contentRegionsQuitted', ->
                cb = -> app.events.trigger 'view.quitted'
                _.delay cb, 0

            app.events.on 'view.quitted',  => @load()
            app.events.on 'view.rendered', => @setAnchors()
        else
            app.log 'history not enabled'

        app.events.on 'regions.contentRegionsEntered', ->
            app.log 'view entered'
            cb = -> app.events.trigger 'view.entered'
            _.delay cb, 0

        app.events.on 'view.rendered', => 
            # remove useless elements
            @elements.$content.find('[type="application/rss+xml"],[type="application/rsd+xml"],[type="application/wlwmanifest+xml"],[name="generator"],[rel="canonical"],[rel="shortlink"]').remove();

            # scroll to top
            @elements.$content.animate {'scrollTop', 0}, 1000

            # move admin bar 
            adminBar = @elements.$content.find('#wpadminbar').detach()
            if adminBar.length
                $('#wpadminbar').remove()
                $('body:eq(0)').append adminBar

            cb = -> app.events.trigger 'view.enter'
            _.delay cb, 0

        app.events.on 'document.ready', =>
            @title = $('title').text()

            # elements
            if _.isString app.config.contentId
                @elements.$content = $ "##{app.config.contentId}"

            cb = -> app.events.trigger 'view.rendered'
            _.delay cb, 0

    setAnchors : ->
        anchors = $ 'a:not(.ab-item):not([data-history-bypass]):not([data-history-set]):not("#comments a")'
        title   = @title
        _.each anchors, (el, i) ->
            $el  = $ el
            url  = app.utils.parseUrl $el.attr('href')
            path = url.path
            host = url.host

            # test if is file (href ends with something like '.jpg') & test host 
            if ( _.isString(path) and _.isArray(path.match /\.[0-9a-z]{3,}$/) ) or (host isnt window.location.host and not _.isUndefined(host) ) 
                $el.attr({
                    'data-history-bypass' : 'true'
                    'target'              : '_blank'
                })
            else
                $el.attr('data-history-set', true).on 'click', (e) ->
                    href = $(this).attr 'href'
                    e.preventDefault()
                    if _.isString(href) and href.length > 0 and href isnt window.location.href
                        state = { url : href }
                        window.history.pushState(state, title, href)
                        app.events.trigger 'history.statechange', [state]

        app.log "#{anchors.length} anchors set"
        anchors

    load : ->
        app.log "load a new page"
        @elements.$content.load window.location.href, null, (responseText, textStatus, request) =>
            if textStatus is 'success'
                app.log "page successfully loaded"
                $('html,body').animate { scrollTop : 0 }
                app.events.trigger 'view.rendered'
            else
                app.events.trigger 'view.notRendered'

        null
}



# app.regions

regionsQuitted = 0
regionsEntered = 0

app.regions = {

    viewRegions : {
        global  : []
        content : []
    }

    init : ->
        if app.config.pushState and app.config.ajaxLoad
            # events
            app.events.on 'history.statechange', =>
                regionsQuitted = 0
                regionsEntered = 0

            app.events.on 'region.quitted', (region) =>
                regionId   = region.id
                regionsTot = @viewRegions.content.length
                _.each @viewRegions.content, (r) ->
                    if r.id is regionId
                        regionsQuitted++
                if regionsQuitted is regionsTot
                    cb = -> app.events.trigger 'regions.contentRegionsQuitted'
                    _.delay cb, 0

        app.events.on 'region.entered', (region) =>
            regionId   = region.id
            regionsTot = @viewRegions.content.length
            _.each @viewRegions.content, (r) ->
                if r.id is regionId
                    regionsEntered++
            if regionsEntered is regionsTot
                cb = -> app.events.trigger 'regions.contentRegionsEntered'
                _.delay cb, 0

        app.events.on 'document.ready', =>
            @setRegions()

        app.events.on 'view.rendered', =>
            @setRegions(false)

        null

    setRegions : (global = true) ->
        regionsTot  = 0
        viewRegions = []
        
        global  = if _.isBoolean global then global else true
        regions = if global then $("[data-region]").not("##{app.config.contentId} [data-region]") else $("##{app.config.contentId} [data-region]")

        _.each regions,(el, i) ->
            $el = $ el
            regionType      = $el.attr('data-region').substr(0,1).toUpperCase() + $el.attr('data-region').substr(1).toLowerCase()
            regionClassName = "Region#{regionType}"

            if eval("typeof #{regionClassName}") is 'function'
                region   = new (eval regionClassName) $el, global
                viewRegions.push region
                $el.attr('data-region-id', region.id)
                regionsTot++
                null

        if global 
            app.regions.viewRegions.global  = viewRegions
        else
            app.regions.viewRegions.content = viewRegions

        app.log if global then "#{regionsTot} global regions set" else "#{regionsTot} content regions set"
        regionsTot
}



# app.controllers

controllers = []

app.controllers = {

    init : ->
        cb = =>
            app.events.trigger 'controllers.init'
            _.each controllers, (controller) ->
                if _.isFunction controller.init
                    controller.init()
            null

        app.events.on 'document.ready', cb
        null

    get : ->
        controllers

    set : (controller)  ->
        if( controller instanceof Controller )
            controllers.push controller
        null

}



# dom ready event

$( document ).on( 'ready', -> app.events.trigger 'document.ready' )

# resize event

_resizeX = 0
resizeCallback = ->
    _resizeX++
    _resizeY = _resizeX
    cb = ->
        if _resizeY is _resizeX 
            app.events.trigger 'viewport.resize'
    _.delay cb, 100

$(window).on 'resize', resizeCallback

# app init event

app.events.on 'app.init', -> 
    app.regions.init()
    app.view.init()
    app.controllers.init()



app.log 'app.init'
app.events.trigger 'app.init'