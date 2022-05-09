var actual = parseInt($("#actual").text(),10);
var max = parseInt($("#max").text(),10);
var count = 0;
var procent = actual/max*100;

var load = setInterval(function(){
	if(count > procent) clearInterval(load);
	else count++;
	$("div.goal").width(count+"%");
	$(".counter-space").html("<p>"+count+"%</p>");
},60);