$(".responsive-tabs.inside-tab .card.tab-pane").click(function(){
    if($(window).width() <= 767){
        $(this).parent().find('.card.tab-pane').removeClass('show').removeClass('active');
        $(this).addClass('show').addClass('active');
    }
});

$(".responsive-tabs .card.tab-pane:not(.inside-tab .card.tab-pane)").click(function(){
    if($(window).width() <= 767){
        $('.responsive-tabs .card.tab-pane:not(.inside-tab .card.tab-pane)').removeClass('show').removeClass('active');
        $(this).addClass('show').addClass('active');
    }
});

