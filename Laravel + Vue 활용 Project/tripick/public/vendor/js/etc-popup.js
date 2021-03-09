function popupOpen(name){

    event.preventDefault();

    var this_popup = $('#'+name);
    $(this_popup).addClass('is-active');

}

function popupClose(name){

    var this_popup = $('#'+name);
    $(this_popup).removeClass('is-active');

}

function popupMiniOpen(name){

    var this_mini_popup = $('.'+name);
    $(this_mini_popup).addClass('is-active');

}

function popupMiniClose(name){

    var this_mini_popup = $('.'+name);
    $(this_mini_popup).removeClass('is-active');

}