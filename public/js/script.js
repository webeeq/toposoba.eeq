$(document).ready(function() {
    $("#menu li").click(function() {
        document.location = $(this).children().attr("href");
    });

    $(".swapObject").mouseover(function() {
        $(this).addClass("choose");
    });
    $(".swapObject").mouseout(function() {
        $(this).removeClass("choose");
    });
});
