var timeout = null;

function typeWriter(text, n, i = 0) {
    if (n < (text.length)) {
      $('.hidden:eq('+i+")").html(text.substring(0, n+1));
      n++;
      timeout = setTimeout(function() {
        typeWriter(text, n, i)
      }, 5);
    }
  }

function untypeWriter(text, n, i = 0) {
    if (n < (text.length)) {
        $('.hidden:eq('+i+")").html(text.substring(0, text.length - n));
        n++;
        if(n === text.length) $(".hidden:eq("+i+")").html(text).hide();
        timeout = setTimeout(function() {
          untypeWriter(text, n, i)
        }, 5);
      }
  }

$(document).ready(function(){
  $(".hidden").show();
});


var texts = [];
var off = false;
for(let i = 0; i < $(".hidden").length; i++){
    texts[i] = $(".hidden:eq("+i+")").text();
}

$("#rules_button").click(function(){
    if(off === true) return;

    off = true;
    setTimeout(function(){
        off = false;
    }, 2000);

    var hidden = $(".hidden");
    for(let i = 0; i < hidden.length; i++){
        $(".hidden:eq("+i+")").html(texts[i]);
    }
    clearTimeout(timeout);

    if($(".hidden").is(":hidden")){
        $(this).html("Krátká verze").removeClass("btn-danger").addClass("btn-secondary");
        for(let i = 0; i < hidden.length; i++){
            typeWriter($(".hidden:eq("+i+")").text(), 0, i);
            $(".hidden:eq("+i+")").html('').show();
        }
    }else{
         $(this).html("Úplná verze").removeClass("btn-secondary").addClass("btn-danger");
         for(let i = 0; i < hidden.length; i++){
            untypeWriter($(".hidden:eq("+i+")").text(), 0, i);
        }
    }
});