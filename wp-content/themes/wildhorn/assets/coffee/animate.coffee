app.animate = (o) ->

    if not _.isObject o
        return false

    if (not _.isString o.cssClass) or (not _.isObject o.element)
        return false

    # convert jquery object
    if o.element.length
        o.element = o.element[0]

    if _.isObject o.target and o.target.length and o.target instanceof jQuery
        o.target = o.target[0]

    o.$element = $ o.element

    events = ( ->
        el = document.createElement 'div'

        transitions = {
            'transition'        : 'transitionend'
            'OTransition'       : 'oTransitionEnd'
            'MozTransition'     : 'transitionend'
            'WebkitTransition'  : 'webkitTransitionEnd'
        }

        animations = {
            'animation'         : 'animationend'
            'OAnimation'        : 'oAnimationEnd'
            'MozAnimation'      : 'animationend'
            'WebkitAnimation'   : 'webkitAnimationEnd'
        }

        for t of transitions
            if el.style[t] isnt undefined
                transitionEventName = transitions[t]

        for a of animations
            if el.style[a] isnt undefined
                animationEventName = animations[a]

        {
            'transitionEnd' : transitionEventName
            'animationEnd'  : animationEventName
        }
    )()

    if not _.isString(events.transitionEnd) or not _.isString(events.animationEnd)
        return false

    transitionCompleted = (e) ->
        e.target.removeEventListener events.transitionEnd, transitionCompleted, false
        e.target.removeEventListener events.animationEnd, animationCompleted, false
        target = if _.isElement o.target then o.target else o.element
        if e.target is target and _.isFunction o.complete
            o.complete.apply o.element, ['transition', o.cssClass, o.element]
        null

    animationCompleted = (e) ->
        e.target.removeEventListener events.transitionEnd, transitionCompleted, false
        e.target.removeEventListener events.animationEnd, animationCompleted, false
        target = if _.isElement o.target then o.target else o.element
        if e.target is target and _.isFunction o.complete
            o.complete.apply o.element, ['animation', o.cssClass, o.element]
        null 

    # add events listeners

    if _.isElement o.element
        o.element.addEventListener events.transitionEnd, transitionCompleted, false
        o.element.addEventListener events.animationEnd, animationCompleted, false

        o.$element.toggleClass o.cssClass
    true