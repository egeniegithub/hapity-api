$(document).ready(function(){
    $('#following-btn').click(function() {
        $('#following-popup').quickModal('open', {
            animation: 'fade-down',
            speed: 250,
            timing: 'ease-in',
            enableClickAway: true,
            appendBackgroundTo: 'body'
        });
        return false;
    });
    
    $('.close-button').click(function() {
        $('#following-popup').quickModal('close', {
            speed: 1250,
            timing: 'linear'
        });

         $('#followers-popup').quickModal('close', {
            speed: 1250,
            timing: 'linear'
        });
        return false;
    });

    $('#followers-btn').click(function() {
        $('#followers-popup').quickModal('open', {
            animation: 'fade-down',
            speed: 250,
            timing: 'ease-in',
            enableClickAway: true,
            appendBackgroundTo: 'body'
        });
        return false;
    });
    
    // $('#close-button').click(function() {
    //     $('#followers-popup').quickModal('close', {
    //         speed: 1250,
    //         timing: 'linear'
    //     });
    //     return false;
    // });
});