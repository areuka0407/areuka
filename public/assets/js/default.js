$(function(){
    var drop = false;
    $(".list-btn").click(function(e){
        var target = $(e.target).find(".drop-down");
        if (target.is(":animated")) return false;
        if(drop) {
            target.fadeOut(250);
            drop = false;
        }
        else {
            target.fadeIn(250, function(){
                $(this).css("display", "flex");
            });
            drop = true;
        }
    });

    $(window).click(function(e){
        var d_down = $(".drop-down");
        if (d_down.find(e.target).length === 0 && $(e.target).find(".drop-down").length === 0){
            d_down.fadeOut(250);
            drop = false;
        }
    });
});