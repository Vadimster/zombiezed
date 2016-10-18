var playerStatsCounterMassUpdate = function(population, food, water){
	(function(){
  		$({countNum: $('#player-stats-item-population-val').text()}).animate({countNum: population}, {
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
  	$({countNum: $('#player-stats-item-food-val').text()}).animate({countNum: food}, {
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

 (function(){
  	$({countNum: $('#player-stats-item-water-val').text()}).animate({countNum: water}, {
		duration: 'slow', //8000
	  	easing:'linear',
	  	step: function() {
	    	$('#player-stats-item-water-val').text(Math.floor(this.countNum));
	  	},
	  	complete: function() {
	    	$('#player-stats-item-water-val').text(this.countNum);
  		}
	});
  })();

};
