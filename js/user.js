
var user = {

	logout: function(){
		var data = {action:'logout'};			
		$.ajax({
			url: '../php/ajaxhandler.php?', 
			dataType: 'json', 
			type: 'get',  
			cache: false, 
			data: data,
			success: function(response){
				if(response.msg){
					window.location.href = '../index.php';
					console.log('Logget out');
				} 
			}
		});	
	}
};

