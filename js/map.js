//console.log(response.map[0].tile_health);

var Tile = function(y,x,type,level,health){
	this.y = y*1; //multiplying *1 to convert string to mumber.
	this.x = x*1;
	this.type = type*1;
	this.level = level*1;
	this.health = health*1;
	if(health*1 == 1.00 ){
		this.image = 'url(img/map/'+type+'-'+level+'-1.png)'; 
	}	
	
	this.clicked = function(){
		console.log("Tile has been clicked. Create dialogue"); //call another funciton to build a relevant dialogue.		
	};	
};


var map = {
	y: 7, //rows
	x: 7, //columns
	tiles: [], //array to contain tile objects.
	tileHeight: 80,
	tileWidth: 80,
	prepare: function(array){ //array from AJAX response with tiles info from DB
		console.log(array);
		console.log('map.prepare() launched');
		for(i=0; i<array.length; i++){	//preparing a 1D array with all abojects properties	
			this.tiles[i] = new Tile(array[i].tile_y, array[i].tile_x, array[i].tile_type, array[i].tile_level, array[i].tile_health);		
		}
		console.log(this.tiles);
		this.draw();
		//alternatively turn 1D into 2D array. 			
	},
	draw: function(){
		console.log("map.draw() launched");
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
		}
	}
};					
/*
var arr = [1,2,3,4,5,6,7,8,9]

var newArr = [];
while(arr.length) newArr.push(arr.splice(0,3));

*/

					//this.tiles[i][j] = new Tile(i,j);			
			
		


var mapRender = function(){
	console.log('mapRender launched');

	//loop through the tilesData array and push new Tile objects into a 2D array.

}