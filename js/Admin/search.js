$(".result_action").click(function(){
    $("input[name=name]").val($(this).text());
    $("input[name=steamid]").val($(this).attr("data-steamid"));
    $("#result").hide().html();
});