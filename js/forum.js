$(function () {
    $('[data-toggle="tooltip"]').tooltip()
  });

$("button[name=edit]").click(function(e){
    $('html, body').animate({
        scrollTop: $("#add").offset().top
    }, 2000);
    var content = $(this).parent().parent().find(".content").html();

    tinymce.get("tinymce").execCommand('mceInsertContent', false, content);
    $("#add button.btn").html("Upravit komentář").attr({"value": $(this).parent().parent().parent().attr("id"), "name":"commentedit"});
    e.preventDefault();
});

$(".reactions span").click(function(){
    let reactionid = $(this).index() + 1;
    const reaction = $(this);
    if($(this).hasClass("selected")){
        reactionid = 0;
        $(this).parent().find(".selected").attr("count", parseInt($(this).parent().find(".selected").attr("count"))-1).removeClass("selected");
    }else{
        $(this).parent().find(".selected").attr("count", parseInt($(this).parent().find(".selected").attr("count"))-1).removeClass("selected");
        $(this).attr("count", parseInt($(this).attr("count"))+1).addClass("selected");
    }
    $(this).parent().find("#r_"+$("#login_dropdown").attr("logged-user")).remove();

    $.ajax({
        url: window.location.href.split('/').slice(0,6).join('/') + "/react/"+reactionid+"/"+$(this).closest('section.post').attr("id"),
        method: 'post',
        success: function(){
            if(reactionid !== 0){
                reaction.children(".title").append("<li id=\"r_"+$("#login_dropdown").attr("logged-user")+"\">"+$("#login_dropdown").html()+"</li>");
                $("#r_"+$("#login_dropdown").attr("logged-user")+" img").css({'width':'20px','border-radius':'100%'});
            }
        },
        error: function(){
            alert("Bohužel se nepodařilo uložit reakci. Zkuste to později.");
        }
    });
});

$("#time_modal_open").click(function(){
    $("#time_modal").fadeIn();
    $("body").css('overflow', 'hidden');
});

$("#session_modal_open").click(function(){
    $("#session_modal").fadeIn();
    $("body").css('overflow', 'hidden');
});

$(".time-modal--content .close").click(function(){
    $("#time_modal:visible, #session_modal:visible").fadeOut();
    $("body").css('overflow', '');
});

$("#search").on('keydown keyup', function(){
    if($(this).val().length >= 3){
        $.ajax({
            url: '/stats/search/'+$(this).val(),
            method: 'get',
            success: function(data){
                if(data == 0) return $("#result").html("Nenalezen žádný hráč.");
                else{
                    let d = JSON.parse(data);
                    let list = '';
                    for(let i = 0; i < d.length; i++){
                        list += '<a href="/stats/'+d[i].communityid+'"><li>'+d[i].name+'</li>';
                    }
                    $("#result").html(list).fadeIn();
                }
                $("#result").fadeIn();
            }
        });
    }else{
        $("#result").html("").fadeOut();
    }
});

$(".close_flash").click(function(){
    $(this).parent().fadeOut(function(){
        $(this).remove();
    });
});

$(".cookies_warning").click(function(){
    var d = new Date();
	d.setTime(d.getTime() + (365*24*60*60*1000));
	document.cookie = "cookies_warning=1; expires="+d.toUTCString()+"; path=/";
});

window.onbeforeunload = checkTextarea;

function checkTextarea(){
    if($("#topic_add textarea[name=text]").val().length > 0) return "Opravdu chceš zavřít tuhle stránku?";
    else return true;
}
