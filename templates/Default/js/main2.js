function message(data) {
    return '<div class="message">' + data + '<a onclick="close_dialog()">close</a></div>'
}

function close_dialog() {
    $('.message').fadeOut('slow',function(){
        $(this).remove()
    })
    return false
}
$(document).ready(function(){
    var w_height = $(window).height()
    var w_width = $(window).width()

    var $wrapper = $('#wrapper')

    $('#timer').countdown({
        until: new Date(2013, 11, 25),
        format: 'dHMs'
    });

    $('#settings .colors a').click(function(e){
        e.preventDefault()
        $('body').removeClass('color-blue color-red color-violet color-orange color-green').addClass($(this).attr('href').substring(1))
    })
    $('#settings .backgrounds a').click(function(e){
        e.preventDefault()
        $('body').removeClass('bg1 bg2 bg3 bg4 bg5').addClass($(this).attr('href').substring(1))
    })

    $('#settings .button').click(function(e){
        var $settings = $('#settings')
        if($settings.hasClass('active')) {
            $('#settings .content').animate({
                'left':-35
            },'fast')
            $settings.removeClass('active')
        } else {
            $('#settings .content').animate({
                'left':0
            },'fast')
            $settings.addClass('active')
        }
        e.preventDefault()
    })

    $('#contact-form').submit(function(e){
        e.preventDefault()
        if($(this).valid()) {
            $.ajax({
                'url':'contact.php',
                'type': 'POST',
                'data': $(this).serialize()
            }).done(function(data) {
                $('#contact').append(message(data))
                $('.message').fadeIn('slow')
            })
        } else {
            $(this).showErrors()
        }
    })
    $('#newsletter-form').submit(function(e){
        e.preventDefault()
        if($(this).valid()) {
            $.ajax({
                'url':'newsletter.php',
                'type': 'POST',
                'data': $(this).serialize()
            }).done(function(data) {
                $('#newsletter').append(message(data))
                $('.message').fadeIn('slow')
            })
        } else {
            $(this).showErrors()
        }
    })


})