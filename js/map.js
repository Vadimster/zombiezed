
var tilesData = []; //array populated with data from DB; received in AJAX callback on game.php
var tiles = [] //array which contains tile objects.

var mapRender = function(){
	console.log('mapRender launched');
	tilesData.sort(function(obj1, obj2){  //sort array ASC based on tile_id value in every object
		return obj1.tile_id - obj2.tile_id;
	}); 	
	console.log(tilesData);
	//loop through the tilesData array and push new Tile objects into a 2D array.

}