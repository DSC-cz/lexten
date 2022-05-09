$(".vip-price").each(function(){
	$(this).html($(this).attr("value").split('|')[0]);
});
$("#months").on('change', function(){
	var actual = $(this).val();
	$(".vip-price").each(function(){
		$(this).html($(this).attr("value").split('|')[actual]);
	});
});