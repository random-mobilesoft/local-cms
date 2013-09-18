class RegionAudioplayer extends Region

    init : ->
        @supports = {
            mp3 : Modernizr.audio.mp3
            ogg : Modernizr.audio.ogg
        }
        
        @audio     = if Modernizr.audio.mp3 or Modernizr.audio.ogg then true else false
        @tracks    = if (typeof(mthemes_audioPlayerTracks) is 'object' and _.isArray(mthemes_audioPlayerTracks)) then mthemes_audioPlayerTracks else null
        @setup     = if @audio and @tracks and @tracks.length then true else false
        @index     = if @setup then 0 else null

        # elements
        @$audio    = @$el.find( 'audio:eq(0)' )
        @trackname = @$el.find( '#audioplayer-text' )
        @$previous = @$el.find( '#audioplayer-previous' )
        @$next     = @$el.find( '#audioplayer-next' )
        @$pause    = @$el.find( '#audioplayer-pause' )
        @$play     = @$el.find( '#audioplayer-play' )
        @$text     = @$el.find( '#audioplayer-text' )

        # set footer class
        @$el.parent().addClass( 'footer-with-player' )

        # load first track
        @setTrack()

        # set state pause
        @$el.addClass( 'state-pause' )

        # dom events
        
        @$play.hammer().on     'tap', => @events.trigger('audioplayer.play')
        @$pause.hammer().on    'tap', => @events.trigger('audioplayer.pause')
        @$previous.hammer().on 'tap', => @events.trigger('audioplayer.previous')
        @$next.hammer().on     'tap', => @events.trigger('audioplayer.next')

        @$audio.on 'ended', => @next()

        # events
        @events.on 'audioplayer.play',     => @play()
        @events.on 'audioplayer.pause',    => @pause()
        @events.on 'audioplayer.previous', => @previous()
        @events.on 'audioplayer.next',     => @next()
        @events.on 'audioplayer.autoplay', => @events.trigger('audioplayer.play')
        @events.on 'viewport.resize',      => @switchDisplay()

        # auto play
        if typeof(mthemes_audioPlayerAutoplay) is 'boolean' and mthemes_audioPlayerAutoplay
            @events.trigger 'audioplayer.play'

        @switchDisplay()
        @

    switchDisplay : ->
        $footer     = @$el.parent()
        $menu       = $footer.find('#footer-menu > * > ul')
        menuWidth   = 0
        playerWidth = @$el.width()
        footerWidth = $footer.width() / 2
        socialWidth = $footer.find('#footer-socials').width() + 7
        
        $menu.find('li').each (i, el) =>
            menuWidth += $(el).width() + 1

        if (playerWidth + menuWidth + socialWidth) > footerWidth
            @$text.css('display', 'none')
            $footer.addClass( 'footer-with-audioplayer-without-text' )
            $footer.removeClass( 'footer-with-audioplayer-with-text' )
        else 
            @$text.css('display', 'block')
            $footer.addClass( 'footer-with-audioplayer-with-text' )
            $footer.removeClass( 'footer-with-audioplayer-without-text' )
        @

    resize : ->
        boxWidth      = @$el.width()
        elementsWidth = 0 
        callback      = (i, el) =>
            $el            = $ el
            elementsWidth += $el.width()
            elementsWidth += parseInt $el.css 'margin-left'

        @$el.find(" > * ").not('audio').each callback
        differenceWidth = boxWidth - elementsWidth
        tracknameWidth  = (@trackname.width() + differenceWidth) - 2
        @trackname.width( tracknameWidth )
        @

    setTrack : ->
        if @setup 

            # set track
            track   = @tracks[@index]
            if @supports.mp3
                @$audio.attr 'src', track.mp3
            else
                @$audio.attr 'src', track.ogg

            # set name & animation
            el = "<span>#{track.title}</span>"
            @trackname.find('span').remove()
            @trackname.append el

            if Modernizr.cssanimations
                @trackname.find('span').addClass 'state-in'
            else
                @trackname.find('span').animate { opacity : 1 }, { duration : 500 }

        @

    play : ->
        @$audio[0].play()
        @$el.removeClass().addClass 'state-play'
        @

    pause : ->
        @$audio[0].pause()
        @$el.removeClass().addClass 'state-pause'
        @

    previous : ->
        @index = if @index - 1 < 0 then @tracks.length - 1 else @index - 1
        @setTrack()
        @play()
        @

    next : ->
        @index = if @index + 1 is @tracks.length then 0 else @index + 1
        @setTrack()
        @play()
        @