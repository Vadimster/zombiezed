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
					stats.leadership.currentValue  = response.leadership;
					stats.leadership.modifiers.fromBasePopulation = response.ld_base_pop;
					stats.leadership.modifiers.gained = response.leadershipGained;
					stats.drawAll(response);		

				} else {
					alert('Game data cannot be loaded, pelase refresh the page or try again later');
				}
			}
		});	
	},

	drawAll: function(stats){
		this.rank.draw(stats.rank);
		
		this.calendar.set(stats);
		this.calendar.draw(stats);


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

	calendar: {
		
		season: null,
		month: null,
		week: null,

		set: function(stats){
			this.season = stats.season;
			this.month = stats.month;
			this.week = stats.week;
		},

		draw: function(stats){ //will convert full month name into short name only for the navigation bar. The "Calendar" page will still refer to stats.calendar.season, etc.
			if(this.month === 'January'){
				var month = 'Jan';
			} else if (this.month === 'February'){
				var month = 'Feb';
			} else if (this.month === 'March'){
				var month = 'Mar';
			} else if (this.month === 'April'){
				var month = 'Apr';
			} else if (this.month === 'May'){
				var month = 'May';
			} else if (this.month === 'June'){
				var month = 'Jun';
			} else if (this.month === 'July'){
				var month = 'Jul';
			} else if (this.month === 'August'){
				var month = 'Aug';
			} else if (this.month === 'September'){
				var month = 'Sep';
			} else if (this.month === 'October'){
				var month = 'Oct';
			} else if (this.month === 'November'){
				var month = 'Nov';
			} else if (this.month === 'December'){
				var month = 'Dec';
			}
			$('#player-stats-item-calendar-val').html(month);
		}

	},


	leadership: {
		currentValue: null,
		gained: null,
		modifiers: {
			fromBasePopulation: null
		}
	}

};