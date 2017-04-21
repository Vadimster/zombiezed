var Tile = function(tileID, y, x, tileClass, tileCategory, type, level, health){
	this.y = y*1; //multiplying *1 to convert string to mumber.
	this.x = x*1;
	this.id = tileID*1;
	this.category = tileCategory*1
	this.class = tileClass*1;
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
			this.tiles[i] = new Tile(map[i].tile_id, map[i].tile_y, map[i].tile_x, map[i].tile_class, map[i].tile_category, map[i].tile_type, map[i].tile_level, map[i].tile_health);		
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
		
		class: [
			 null, //first element offset since DB codes start with 1
			'Defensive tile', // DB code 1
			'Grass tile', //DB code 2
			'Production tile', //DB code 3
			'Administrative tile', //DB code 4
			'Resource tile' //DB code 5
		],

		title: [			
			null,  //first element offset since DB codes start with 1
			'Wall', //DB code 1
			'Gate',  //DBcode 2
			'HQ', // DB code 3
			'Watchtower' //DB code 4
		],

		descriptions: {
			defense: [
				'This area is unprotected. Consider placing a gate here for access control.',
				'This area is unprotected. Consider building a wall here to stop potential intruders.',
				'This area is unprotected. You can build either a wall or a watch tower here to secure the perimeter.',
				'The gate protects your camp and allows your people to move in and out securely.',
				'Walls protect your camp from surprise attacks, but they can be broken through.',
				'Watchtowers increase chances to spot intruders and open fire if you have weapons and ammo.'
			],
			admin: [
				'The HQ is the administrative center of your camp. It will accommodate up to 5 people.'
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
	    	
			if (tile.class === 1 && tile.level === 0){ //Border or Path
				if(tile.category === 1){ 
					var title = 'Camp border';
					$('<div id="modal-dialogue-title"></div>').appendTo('#modal-dialogue-content').html(title);
					$('<div class="tile-image"></div>').appendTo('#modal-dialogue-content').css('background-image', 'url(../img/game/map/tiles/icons/'+tile.type+'-'+tile.level+'-1.png)');


					if(tile.type === 5 || tile.type === 6 || tile.type === 7 || tile.type === 8){ //tile is corner (for watchtower)
						var description = this.descriptions.defense[2];
						$('<div id="modal-dialogue-description"></div>').appendTo('#modal-dialogue-content').html(description);

						$('<ul id="tileInfo-buildlist"> </ul>').appendTo('#modal-dialogue-content');
						   	$('<li><a href ="#"> Build a wall</a></li>').appendTo('#tileInfo-buildlist');
						   	$('<li><a href ="#"> Build a watch tower</a></li>').appendTo('#tileInfo-buildlist');
					}else {
						var description = this.descriptions.defense[1]; //tiel is not corner (a wall)
						$('<div id="modal-dialogue-description"></div>').appendTo('#modal-dialogue-content').html(description);

						$('<ul id="tileInfo-buildlist"> </ul>').appendTo('#modal-dialogue-content');
						   	$('<li><a href ="#"> Build a wall</a></li>').appendTo('#tileInfo-buildlist');
					}
				}else if(tile.category === 2){ 
					var title = 'Path into the Camp';
					$('<div id="modal-dialogue-title"></div>').appendTo('#modal-dialogue-content').html(title);
					$('<div class="tile-image"></div>').appendTo('#modal-dialogue-content').css('background-image', 'url(../img/game/map/tiles/icons/'+tile.type+'-'+tile.level+'-1.png)');
					var description = this.descriptions.defense[0];
					$('<div id="modal-dialogue-description"></div>').appendTo('#modal-dialogue-content').html(description);

					$('<ul id="tileInfo-buildlist"> </ul>').appendTo('#modal-dialogue-content');
						   	$('<li><a href ="#"> Build a gate</a></li>').appendTo('#tileInfo-buildlist');
				}
			} else if(tile.class === 2){ //Grass
					var title = 'Grass';
					$('<div id="modal-dialogue-title"></div>').appendTo('#modal-dialogue-content').html(title);
					$('<div class="tile-image"></div>').appendTo('#modal-dialogue-content').css('background-image', 'url(../img/game/map/tiles/icons/'+tile.type+'-'+tile.level+'-1.png)');

					var description = 'You can build on this tile.';
					$('<div id="modal-dialogue-description"></div>').appendTo('#modal-dialogue-content').html(description);

					$('<ul id="tileInfo-buildlist"> </ul>').appendTo('#modal-dialogue-content');
					   	$('<li><a href ="#"> Build administrative building</a></li>').appendTo('#tileInfo-buildlist');
					   	$('<li><a href ="#"> Build production building</a></li>').appendTo('#tileInfo-buildlist');
			} else {
				var title = this.title[tile.category];
				$('<div id="modal-dialogue-title"></div>').appendTo('#modal-dialogue-content').html(title);
				$('<div class="tile-image"></div>').appendTo('#modal-dialogue-content').css('background-image', 'url(../img/game/map/tiles/icons/'+tile.type+'-'+tile.level+'-1.png)');


				if(tile.category === 3){ //tile is an HQ
					var description = this.descriptions.admin[0];
					$('<div id="modal-dialogue-description"></div>').appendTo('#modal-dialogue-content').html(description);

				}else if(tile.category === 1){ //tile is a wall
					var description = this.descriptions.admin[4];
					$('<div id="modal-dialogue-description"></div>').appendTo('#modal-dialogue-content').html(description);

					$('<ul id="tileInfo-buildlist"> </ul>').appendTo('#modal-dialogue-content');
					   	$('<li><a href ="#"> Upgrade</a></li>').appendTo('#tileInfo-buildlist');
					   	$('<li><a href ="#"> Demolish</a></li>').appendTo('#tileInfo-buildlist');

				}else if (tile.category === 2){ //tile is a gate
					var description = this.descriptions.admin[3];
					$('<div id="modal-dialogue-description"></div>').appendTo('#modal-dialogue-content').html(description);

					$('<ul id="tileInfo-buildlist"> </ul>').appendTo('#modal-dialogue-content');
					   	$('<li><a href ="#"> Upgrade</a></li>').appendTo('#tileInfo-buildlist');
					   	$('<li><a href ="#"> Demolish</a></li>').appendTo('#tileInfo-buildlist');

				} else if (tile.category === 4){ //tile is a watchtower
					var description = this.descriptions.admin[5];
					$('<div id="modal-dialogue-description"></div>').appendTo('#modal-dialogue-content').html(description);

					$('<ul id="tileInfo-buildlist"> </ul>').appendTo('#modal-dialogue-content');
					   	$('<li><a href ="#"> Upgrade</a></li>').appendTo('#tileInfo-buildlist');
					   	$('<li><a href ="#"> Demolish</a></li>').appendTo('#tileInfo-buildlist');
				}
			}	

    		$('<ul id="tileInfo-list"> </ul>').appendTo('#modal-dialogue-content');
    			$('<li> Tile id: '+tile.id+' </li>').appendTo('#tileInfo-list');
    			$('<li> Tile class: '+this.class[tile.class]+' ('+tile.class+') </li>').appendTo('#tileInfo-list');
    			$('<li> Tile category: '+tile.category+' </li>').appendTo('#tileInfo-list');    			
    			$('<li> Tile type: '+tile.type+' </li>').appendTo('#tileInfo-list');
    			$('<li> Tile level: '+tile.level+' </li>').appendTo('#tileInfo-list');
    			$('<li> Tile health: '+tile.health+' </li>').appendTo('#tileInfo-list');

			$('#modal-dialogue').css('display', 'flex');        
		}


	}

};//end of map object
