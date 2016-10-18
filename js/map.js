//console.log(response.map[0].tile_health);

var map = {
	y: 7, //rows
	x: 7, //columns
	tiles: [], //array to contain tile objects.
	tileHeight: 50,
	tileWidth: 50,
	prepare: function(array){ //array from AJAX response with tiles info from DB
		console.log(array);
		console.log('map.prepare() launched');
		for(i=0; i<array.length; i++){			
			console.log('for loop in map.prepare() launched');
			if(array[i].tile_type == 0){ //double equal sign because data in JSON is text.
				console.log('tile ' +i+ ' is Defence');
			} else if(array[i].tile_type == 1){
				console.log('tile ' +i+ ' is Gate');
			}else if(array[i].tile_type == 2){
				console.log('tile ' +i+ ' is Grass');
			}else if(array[i].tile_type == 3){
				console.log('tile ' +i+ ' is HQ');
			}					
/*
var arr = [1,2,3,4,5,6,7,8,9]

var newArr = [];
while(arr.length) newArr.push(arr.splice(0,3));

*/

					//this.tiles[i][j] = new Tile(i,j);			
			
		}
	}
};

var mapRender = function(){
	console.log('mapRender launched');

	//loop through the tilesData array and push new Tile objects into a 2D array.

}