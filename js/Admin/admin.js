$("input[name=name]").on('keyup keydown', function(){
    if($(this).val().length > 2) $.ajax({
        url: '/admin/finduser/'+$(this).val(),
        method: 'get',
        success: function(data){
            $("#result").html(data).show();
        },
        error: function(error){
            alert("Nastala chyba");
        }
    });
    
    else $("#result").hide();
});