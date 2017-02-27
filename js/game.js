//user
//stats
//map
//Jquery event listeners mapping




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

var stats = {

	fetchAll: function(){
		var data = {action:'getStats'};			
		$.ajax({
			url: '../php/ajaxhandler.php?', 
			dataType: 'json', 
			type: 'get',  
			cache: false, 
			data: data,
			success: function(response){
				if(response){
					stats.rank.draw(response.rank);		

				} else {
					console.log('Game data cannot be loaded, pelase refresh the page or try later');
				}
			}
		});	
	},

	rank: {
		
		names: ['Chieftain', 'Esquire', 'Knight', 'Baron', 'Count', 'Marquis', 'Duke', 'Prince', 'King', 'Emperor'],

		//fetch()

		draw: function(rankID){
			$('#player-stats-rank-img').css('background-image','url(../img/game/main_stats/rank/'+rankID+'.png)').attr('title', this.names[rankID]);
		}
	}

};

var modalDialogue = {

	modalDialogueOpen: false,
	
	prepare: function(element){ //receive some element identifyer and prepare modal dialogue accodringly
		this.modalDialogueOpen = true,
		$('#game-area').addClass('blur');
		$('<div id="modal-dialogue" class="modal"></div>').appendTo($('BODY'));
		$('<div id="modal-dialogue-content"></div>').appendTo('#modal-dialogue');
    		var link = $('<a href="#" title="Close" class="modal-close-button">X</a>');
    		link.appendTo('#modal-dialogue-content').click(function(event) {
    			modalDialogue.close();
    		});

		if (element.id === 'player-stats-item-settings-img'){
			console.log('will build dialogue for Settings now');
    		$('<ul id="list"> </ul>').appendTo('#modal-dialogue-content');
    			$('<li> <a href="#" title="run cron job">Turn - Run cron Job</a> </li>').appendTo('#list').click(function(event) {
    				console.log('will run cron job now');
    			});

    			$('<li> <a href="#" title="Clean inactive users in DB">Clean inactive users</a> </li>').appendTo('#list').click(function(event) {
    				console.log('will clean DB now');
    			});    

    			$('<li> <a href="#" >Logout</a> </li>').appendTo('#list').click(function(event) {
    				user.logout();

    			});  


		}

		$('#modal-dialogue').css('display', 'flex');        
	},

	close: function(){
		modalDialogue.modalDialogueOpen = false;
		$('#game-area').removeClass('blur');
		$('#modal-dialogue').css('display', 'none');
		$('#modal-dialogue').remove();	
	}
};

/* =============================================================
                                MAP
===============================================================*/


var Tile = function(y,x,type,level,health){
	this.y = y*1; //multiplying *1 to convert string to mumber.
	this.x = x*1;
	this.type = type*1;
	this.level = level*1;
	this.health = health*1;
	if(health*1 == 1.00 ){ //building has 100% health
		this.image = 'url(../img/game/map/'+type+'-'+level+'-1.png)'; 
	}	
	this.clicked = function(){
		console.log("Tile has been clicked. Create dialogue");		
	};	
};


var map = {
	y: 7, //rows
	x: 7, //columns
	tiles: [], //array to contain tile objects.
	tileHeight: 80,
	tileWidth: 80,

	fetch: function(){
		//console.log(' -> map.fetch() lunched');
		var data = {action:'getMap'};			
		$.ajax({
			url: '../php/ajaxhandler.php?', 
			dataType: 'json', 
			type: 'get',  
			cache: false, 
			data: data,
			success: function(response){
				if(response.map){
					console.log('Map fetched from server');
					map.prepare(response.map);

				} else {
					console.log('Game data cannot be loaded, pelase refresh the page or try later');
				}
			}
		});	
	},

	prepare: function(map){
		//console.log(' -> map.prepare() lunched');
		map.sort(function(obj1, obj2){  //sort array ASC based on tile_id value in every object. It returns array in random order ot times, just to be sure.
			return obj1.tile_id - obj2.tile_id;
		});

		var mapLength = map.length;
		for(i=0; i<mapLength; i++){	//converting into 1D array with all objects properties	
			this.tiles[i] = new Tile(map[i].tile_y, map[i].tile_x, map[i].tile_type, map[i].tile_level, map[i].tile_health);		
		}
		this.draw();
	},


	draw: function(){
		//console.log(' -> map.draw() lunched');
		$('#map-container').empty();
		for(i=0; i <this.tiles.length; i++){
			var div = $('<div class ="tile"></div>');
			div.css('background-image', this.tiles[i].image);
			div.css('top', this.tileHeight*this.tiles[i].y);
			div.css('left',this.tileWidth*this.tiles[i].x);
			div.get(0).obj = this.tiles[i];
			div.click(function(){
					this.obj.clicked();
			});
			div.appendTo('#map-container');
			$('#map-container').fadeIn(2000, function(){
				$('#map-container').css('display','block');
			});
		}

	}


};//end of map object



$(document).ready(function() {
	$('#player-stats-item-leadership-img').click(function(){
		console.log('Leadership in the stats panel clicked');		
	});
	
	$('#player-stats-item-population-img').click(function(){
		console.log('Population in the stats panel clicked');
	});	
	
	$('#player-stats-item-food-img').click(function(){
		console.log('Food in the stats panel clicked');
	});	

	$('#player-stats-item-settings-img').click(function(){
		modalDialogue.prepare($(this)[0]);
	});	

	$(document).keyup(function(e){ //binding event listener to submit log in when Enter is pressed.
		if (e.which == 27){
			if(modalDialogue.modalDialogueOpen){
				modalDialogue.close();
			}
		}
	});

	stats.fetchAll();
	map.fetch();
	


	//load map
		//ajax call to server to fetch map
		//js draws map
		// once done spinner disappears and map fades in  $(element).fadeIn('slow', function(){
				//something happens when animation is complete.
			//});

});