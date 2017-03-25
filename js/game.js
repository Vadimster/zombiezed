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
					stats.leadership.value  = response.leadership;
					stats.drawAll(response);		

				} else {
					console.log('Game data cannot be loaded, pelase refresh the page or try later');
				}
			}
		});	
	},

	drawAll: function(stats){
		this.rank.draw(stats.rank);

		(function(){
  		$({countNum: $('#player-stats-item-leadership-val').text()}).animate({countNum: stats.leadership}, {
		  duration: 'slow', //8000
		  easing:'linear',
		  step: function() {
		    $('#player-stats-item-leadership-val').text(Math.floor(this.countNum));
		  },
		  complete: function() {
		    $('#player-stats-item-leadership-val').text(this.countNum);
		  }
		});
	  	})();  

	  (function(){
	  	$({countNum: $('#player-stats-item-population-val').text()}).animate({countNum: stats.population}, {
			duration: 'slow', //8000
		  	easing:'linear',
		  	step: function() {
		    	$('#player-stats-item-population-val').text(Math.floor(this.countNum));
		  	},
		  	complete: function() {
		    	$('#player-stats-item-population-val').text(this.countNum);
	  		}
		});
	  })();

	 (function(){
	  	$({countNum: $('#player-stats-item-food-val').text()}).animate({countNum: stats.food}, {
			duration: 'slow', //8000
		  	easing:'linear',
		  	step: function() {
		    	$('#player-stats-item-food-val').text(Math.floor(this.countNum));
		  	},
		  	complete: function() {
		    	$('#player-stats-item-food-val').text(this.countNum);
	  		}
		});
	  })();
	},

	rank: {
		
		names: ['Chieftain', 'Esquire', 'Knight', 'Baron', 'Count', 'Marquis', 'Duke', 'Prince', 'King', 'Emperor'],

		draw: function(rankID){
			$('#player-stats-rank-img').css('background-image','url(../img/game/main_stats/rank/'+rankID+'.png)').attr('title', 'Rank '+rankID+' : '+this.names[rankID]);

		}
	},

	leadership: {
		value: null
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
		$('<div id="modal-dialogue-title"></div>').appendTo('#modal-dialogue-content').html(element.title);

		if (element.id === 'player-stats-item-settings-img'){
			console.log('will build dialogue for Settings now');
    		$('<ul id="settings-list"> </ul>').appendTo('#modal-dialogue-content');
    			$('<li> <a href="#" title="run cron job">Turn - Run cron Job</a> </li>').appendTo('#settings-list').click(function(event) {
    				console.log('will run cron job now');
    			});

    			$('<li> <a href="#" title="Clean inactive users in DB">Clean inactive users</a> </li>').appendTo('#settings-list').click(function(event) {
    				console.log('will clean DB now');
    			});    

    			$('<li> <a href="#" >Logout</a> </li>').appendTo('#settings-list').click(function(event) {
    				user.logout();
    			});  


		} else if(element.id === 'player-stats-item-leadership-img'){
    		$('<p>').appendTo('#modal-dialogue-content').html('Gain per turn: ' +stats.leadership.value);

		} else if(element.id === 'player-rank-detail'){
			console.log('class is .player-rank-detail');
			//BULID LIST OF ALL RANKS
			$('<div id="modal-dialogue-ranks-wrapper"></div>').appendTo('#modal-dialogue-content');
				$('<div class="modal-dialogue-ranks-rank-box" id="rank1"></div>').appendTo('#modal-dialogue-ranks-wrapper');
					$('<div class="modal-dialogue-ranks-rank-img"></div>').appendTo('#rank1').css('background-image','url(../img/game/main_stats/rank/color_orig/0.png');
					$('<div class="modal-dialogue-ranks-rank-name"></div>').appendTo('#rank1').html(stats.rank.names[0]);

				$('<div class="modal-dialogue-ranks-rank-box" id="rank2"></div>').appendTo('#modal-dialogue-ranks-wrapper');
					$('<div class="modal-dialogue-ranks-rank-img"></div>').appendTo('#rank2').css('background-image','url(../img/game/main_stats/rank/color_orig/1.png');
					$('<div class="modal-dialogue-ranks-rank-name"></div>').appendTo('#rank2').html(stats.rank.names[1]);

				$('<div class="modal-dialogue-ranks-rank-box" id="rank3"></div>').appendTo('#modal-dialogue-ranks-wrapper');
					$('<div class="modal-dialogue-ranks-rank-img"></div>').appendTo('#rank3').css('background-image','url(../img/game/main_stats/rank/color_orig/2.png');
					$('<div class="modal-dialogue-ranks-rank-name"></div>').appendTo('#rank3').html(stats.rank.names[2]);

				$('<div class="modal-dialogue-ranks-rank-box" id="rank4"></div>').appendTo('#modal-dialogue-ranks-wrapper');
					$('<div class="modal-dialogue-ranks-rank-img"></div>').appendTo('#rank4').css('background-image','url(../img/game/main_stats/rank/color_orig/3.png');
					$('<div class="modal-dialogue-ranks-rank-name"></div>').appendTo('#rank4').html(stats.rank.names[3]);

				$('<div class="modal-dialogue-ranks-rank-box" id="rank5"></div>').appendTo('#modal-dialogue-ranks-wrapper');
					$('<div class="modal-dialogue-ranks-rank-img"></div>').appendTo('#rank5').css('background-image','url(../img/game/main_stats/rank/color_orig/4.png');
					$('<div class="modal-dialogue-ranks-rank-name"></div>').appendTo('#rank5').html(stats.rank.names[4]);
				
				$('<div class="modal-dialogue-ranks-rank-box" id="rank6"></div>').appendTo('#modal-dialogue-ranks-wrapper');
					$('<div class="modal-dialogue-ranks-rank-img"></div>').appendTo('#rank6').css('background-image','url(../img/game/main_stats/rank/color_orig/5.png');
					$('<div class="modal-dialogue-ranks-rank-name"></div>').appendTo('#rank6').html(stats.rank.names[5]);
				
				$('<div class="modal-dialogue-ranks-rank-box" id="rank7"></div>').appendTo('#modal-dialogue-ranks-wrapper');
					$('<div class="modal-dialogue-ranks-rank-img"></div>').appendTo('#rank7').css('background-image','url(../img/game/main_stats/rank/color_orig/6.png');
					$('<div class="modal-dialogue-ranks-rank-name"></div>').appendTo('#rank7').html(stats.rank.names[6]);
				
				$('<div class="modal-dialogue-ranks-rank-box" id="rank8"></div>').appendTo('#modal-dialogue-ranks-wrapper');
					$('<div class="modal-dialogue-ranks-rank-img"></div>').appendTo('#rank8').css('background-image','url(../img/game/main_stats/rank/color_orig/7.png');
					$('<div class="modal-dialogue-ranks-rank-name"></div>').appendTo('#rank8').html(stats.rank.names[7]);
				
				$('<div class="modal-dialogue-ranks-rank-box" id="rank9"></div>').appendTo('#modal-dialogue-ranks-wrapper');
					$('<div class="modal-dialogue-ranks-rank-img"></div>').appendTo('#rank9').css('background-image','url(../img/game/main_stats/rank/color_orig/8.png');
					$('<div class="modal-dialogue-ranks-rank-name"></div>').appendTo('#rank9').html(stats.rank.names[8]);
				
				$('<div class="modal-dialogue-ranks-rank-box" id="rank10"></div>').appendTo('#modal-dialogue-ranks-wrapper');
					$('<div class="modal-dialogue-ranks-rank-img"></div>').appendTo('#rank10').css('background-image','url(../img/game/main_stats/rank/color_orig/9.png');
					$('<div class="modal-dialogue-ranks-rank-name"></div>').appendTo('#rank10').html(stats.rank.names[9]);



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


var Tile = function(y,x,klass,type,level,health){
	this.y = y*1; //multiplying *1 to convert string to mumber.
	this.x = x*1;
	this.class = klass*1;
	this.type = type*1;
	this.level = level*1;
	this.health = health*1;
	if(health*1 == 1.00 ){ //building has 100% health
		this.image = 'url(../img/game/map/'+type+'-'+level+'-1.png)'; 
	}	
	this.clicked = function(){
		map.tileInfo.drawModalDialogue(this);
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
			this.tiles[i] = new Tile(map[i].tile_y, map[i].tile_x, map[i].tile_class, map[i].tile_type, map[i].tile_level, map[i].tile_health);		
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

	},

	tileInfo: {	
		
		names: {
			
			defense: ['Path', 'Border'],
			other: ['Grass'],
			buildings: ['HQ']
		},

		descriptions: {
			defense: [
				'This is an unprotected way to get into your camp from outside. Consider placing a gate here for access control.',
				'This area is unprotected. Consider building a wall here to stop potential intruders.',
				'This area is unprotected. You can build either a wall or a watch tower here to secure the perimeter.'
			]
		},

		drawModalDialogue: function(tile){
			modalDialogue.modalDialogueOpen = true,
			$('#game-area').addClass('blur');
			$('<div id="modal-dialogue" class="modal"></div>').appendTo($('BODY'));
			$('<div id="modal-dialogue-content"></div>').appendTo('#modal-dialogue');
	    		var link = $('<a href="#" title="Close" class="modal-close-button">X</a>');
	    		link.appendTo('#modal-dialogue-content').click(function(event) {
	    			modalDialogue.close();
	    		});
	    	
	    	if (tile.type === 13){
	    		var title = map.tileInfo.names.other[0];
	    	} else if (tile.type === 14){
	    		var title = map.tileInfo.names.buildings[0];
	    	} else if (tile.type === 9 || tile.type === 10 || tile.type === 11 || tile.type === 12){
	    		var title = map.tileInfo.names.defense[0];
	    		if (tile.level < 1){
	    			var description = map.tileInfo.descriptions.defense[0];
	    		}
	    	} else if (tile.type === 1 || tile.type === 2 || tile.type === 3 || tile.type === 4){
	    		var title = map.tileInfo.names.defense[1];
	    		if (tile.level < 1){
	    			var description = map.tileInfo.descriptions.defense[1];
	    		}    		

	    	} else if (tile.type === 5 || tile.type === 6 || tile.type === 7 || tile.type === 8){
	    		var title = map.tileInfo.names.defense[1];
	    		if (tile.level < 1){
	    			var description = map.tileInfo.descriptions.defense[2];
	    		} 
	    	}  




			$('<div id="modal-dialogue-title"></div>').appendTo('#modal-dialogue-content').html(title);
			
			$('<div id="modal-dialogue-description"></div>').appendTo('#modal-dialogue-content').html(description);


			//$('<p></p>').appendTo('#modal-dialogue-content').html(description);


    		$('<ul id="tileInfo-list"> </ul>').appendTo('#modal-dialogue-content');
    			$('<li> Tile class: '+tile.class+' </li>').appendTo('#tileInfo-list');
    			$('<li> Tile type: '+tile.type+' </li>').appendTo('#tileInfo-list');
    			$('<li> Tile level: '+tile.level+' </li>').appendTo('#tileInfo-list');
    			$('<li> Tile health: '+tile.health+' </li>').appendTo('#tileInfo-list');

			$('#modal-dialogue').css('display', 'flex');        
		}


	}

};//end of map object



$(document).ready(function() {
	$('.player-rank-detail').click(function(){
		modalDialogue.prepare($(this)[0]);
	});

	$('#player-stats-item-leadership-img').click(function(){
		modalDialogue.prepare($(this)[0]);
	});
	
	$('#player-stats-item-population-img').click(function(){
		modalDialogue.prepare($(this)[0]);
	});	
	
	$('#player-stats-item-food-img').click(function(){
		modalDialogue.prepare($(this)[0]);
	});	

	$('#player-stats-item-schedule-img').click(function(){
		modalDialogue.prepare($(this)[0]);
	});	

	$('#player-stats-item-supplies-img').click(function(){
		modalDialogue.prepare($(this)[0]);
	});	

	$('#player-stats-item-politics-img').click(function(){
		modalDialogue.prepare($(this)[0]);
	});	

	$('#player-stats-item-world-img').click(function(){
		modalDialogue.prepare($(this)[0]);
	});	

	$('#player-stats-item-messages-img').click(function(){
		modalDialogue.prepare($(this)[0]);
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
	
});