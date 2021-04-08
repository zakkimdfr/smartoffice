function fixHolder() {
    $('div.image-holder img').each(function() {
        var m  = Math.floor(($(this).parent('div').height() - $(this).height()) / 2);
        var mp = m + "px";
        $(this).css("margin-top", mp);
    });
}

$(document).ready(function() {
    fixHolder();
});