# @codekit-append  "./controllers/controller.layout.coffee"
# @codekit-append  "./controllers/controller.pageimage.coffee"
# @codekit-append  "./controllers/controller.tabs.coffee"
# @codekit-append  "./controllers/controller.menu.coffee"

app.events.on 'controllers.init', =>
    app.controllers.set new ControllerLayout
    # app.controllers.set new ControllerPageimage
    app.controllers.set new ControllerTabs
    app.controllers.set new ControllerMenu