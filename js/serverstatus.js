var button = document.getElementById("players");
var map = document.getElementById("map");
var menu = document.getElementsByClassName("server-status-menu")[0];
var copybutton = document.getElementsByClassName("click-copy");
var copied = document.getElementsByClassName("copied")[0];

var players = document.getElementById("onlineplayers");
var CT_rounds = document.getElementById("ct_rounds");
var T_rounds = document.getElementById("t_rounds");
var actualmap = document.getElementById("actualmap");
var playerlist = document.getElementById("playerlist");

button.addEventListener("click", b_click);

if(copybutton.length > 0) for(let i = 0; i < copybutton.length; i++){
    copybutton[i].addEventListener("click", function(e){
        copy(e, copybutton[i].id === "ip_copy" ? true : false);
    });
}

function b_click(){
    map.classList.toggle("small");
    menu.classList.toggle("active");
    button.classList.toggle("active");
}

function copy(event, show_message = false){
    let copy_content = event.currentTarget.getAttribute("data-content");
    navigator.clipboard.writeText(copy_content);

    if(show_message === false) return true;

    copied.style.display = "block";
    copied.style.animation = "fadeIn .5s ease-in-out forwards";

    setTimeout(function(){
        copied.style.animation = "fadeOut .5s ease-in-out forwards";

        setTimeout(function(){
            copied.style.display = "none";
        }, 2000);
    }, 3000);
}

function loadJSON(path, success, error)
{
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function()
    {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                if (success)
                    success(JSON.parse(xhr.responseText));
            } else {
                if (error)
                    error(xhr);
            }
        }
    };
    xhr.open("GET", path, true);
    xhr.send();
}
