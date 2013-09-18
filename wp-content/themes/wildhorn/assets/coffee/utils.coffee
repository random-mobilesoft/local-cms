app.utils.parseUrl = (str, component) ->
    key = ["source", "scheme", "authority", "userInfo", "user", "pass", "host", "port", "relative", "path", "directory", "file", "query", "fragment"]
    ini = (@php_js and @php_js.ini) or {}
    mode = (ini["phpjs.parse_url.mode"] and ini["phpjs.parse_url.mode"].local_value) or "php"
    parser =
        php: /^(?:([^:\/?#]+):)?(?:\/\/()(?:(?:()(?:([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?()(?:(()(?:(?:[^?#\/]*\/)*)()(?:[^?#]*))(?:\?([^#]*))?(?:#(.*))?)/
        strict: /^(?:([^:\/?#]+):)?(?:\/\/((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?))?((((?:[^?#\/]*\/)*)([^?#]*))(?:\?([^#]*))?(?:#(.*))?)/
        loose: /^(?:(?![^:@]+:[^:@\/]*@)([^:\/?#.]+):)?(?:\/\/\/?)?((?:(([^:@]*):?([^:@]*))?@)?([^:\/?#]*)(?::(\d*))?)(((\/(?:[^?#](?![^?#\/]*\.[^?#\/.]+(?:[?#]|$)))*\/?)?([^?#\/]*))(?:\?([^#]*))?(?:#(.*))?)/ # Added one optional slash to post-scheme to catch file:/// (should restrict this)

    m = parser[mode].exec(str)
    uri = {}
    i = 14

    while i--
        if m[i]
            uri[key[i]] = m[i]

    return uri[component.replace("PHP_URL_", "").toLowerCase()]  if component
        
    if mode isnt "php"
        name = (ini["phpjs.parse_url.queryKey"] and ini["phpjs.parse_url.queryKey"].local_value) or "queryKey"
        parser = /(?:^|&)([^&=]*)=?([^&]*)/g
        uri[name] = {}
        uri[key[12]].replace parser, ($0, $1, $2) ->
            uri[name][$1] = $2  if $1

    delete uri.source

    uri

app.utils.imagePreload = (img, callback) ->
    el  = document.createElement 'img'
    src = img
    
    if (not _.isString img) and _.isElement img
        src = $(img).attr "src"

    if _.isString src
        el.setAttribute "src", src
        $(el).on "load", ->
            app.events.trigger "utils.imagePreload", [src, el]
            if _.isFunction callback
                callback.apply @, [src, el]
        return true
    return false