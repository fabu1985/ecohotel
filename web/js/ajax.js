$(function(){ 
	$("#what").keypress(function(e){
	    if(e.which == 13) {
	    	var data = 'data='+$('#what').val();
	        search(data);
	    }
	});

	$('#where li a').click(function(){
		var data = 'data='+$(this).attr("value");		
		search(data);
	});

	var search = function(data){
		var url =  'ecohotel/reserva/ajax/search/_'+$("#where").attr('tag')
        $.ajax({
            data:  data,
            url:   url,
            type:  'post',
            beforeSend: function(){

            },
            success:  function(response){
                    $("#resultado").html(response);
            }
    	});
	}

	$(".savers").on('click', function(){
		var roomId = $(".savers").attr('tag');
		if (confirm('Confirmar Reserva')){
			var url =  'ecohotel/reservar/confirmar';
	        $.post(url, 
	        	{id: roomId,
	        	 habitacion: $(".savers").attr('habitacion'),
	        	 precio: $(".savers").attr('precio'),
	        	 since: $('#since'+roomId).text(),
	        	 to: $('#to'+roomId).text()
	            },
			    function(data,status){
			      window.location.href = "ecohotel/main/misreservas";
			    }
			);
	    }
	});

	$(".confirm").click(function(){
		var resNo = $(this).attr('tag');
		if (confirm('Reservar Habitación?')){
			var url =  'ecohotel/reservar/guardar';
	        $.post(url, 
	        	{id: resNo},
			    function(data,status){
			      location.reload();
			    }
			);
	    }
	});

	$(".decline").click(function(){
		var resNo = $(this).attr('tag');
		if (confirm('Declinar Reserva?')){
			var url =  'ecohotel/reservar/declinar';
	        $.post(url, 
	        	{id: resNo},
			    function(data,status){
			      location.reload();
			    }
			);
	    }
	});
});



