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
					var data = {action:'turn'};			
					$.ajax({
						url: '../php/cronjobs/turn.php?', 
						dataType: 'json', 
						type: 'get',  
						cache: false, 
						data: data,
						success: function(response){
							if(response){
								console.log('Cron job run succcessfully. Season is ' + response.season);
							} else {
								alert('Turn cron job failed to respond, try again');
							}
						}
					});	



    				console.log('will run cron job now');
    			});

    			$('<li> <a href="#" title="Clean inactive users in DB">Clean inactive users</a> </li>').appendTo('#settings-list').click(function(event) {
    				console.log('will clean DB now');
    			});    

    			$('<li> <a href="#" >Logout</a> </li>').appendTo('#settings-list').click(function(event) {
    				user.logout();
    			});  


    			//sent ajax to server to get time
				var data = {action: 'getNextTurn'};
				$.ajax({
					url: '../php/ajaxhandler.php?', 
					dataType: 'json', 
					type: 'get',  
					cache: false, 
					data: data,
					success: function(response){
						if(response){
							$('<li> Time on server: '+ new Date(response.serverTime * 1000)+'</li>').appendTo('#settings-list');
							$('<li> Next turn on: '+ new Date(response.nextTurn * 1000)+'</li>').appendTo('#settings-list');
							$('<li> Next turn in: '+ response.timeDiff +' seconds</li>').appendTo('#settings-list');

						} else {
							$('<li> Time on server: Cannot get server time. </li>').appendTo('#settings-list');
							$('<li> Next turn on: Cannot get data from server. </li>').appendTo('#settings-list');
							$('<li> Next turn in: Cannot get data from server. </li>').appendTo('#settings-list');

						}
					}
				});	



				var now = new Date();
				$('<li> Time on client: '+now+' </li>').appendTo('#settings-list');  



		} else if(element.id === 'player-rank-detail'){
			console.log('class is .player-rank-detail');
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



		} else if(element.id === 'player-stats-item-leadership-img'){
    		$('<p>').appendTo('#modal-dialogue-content').html('Available leadership: ' +stats.leadership.currentValue);
    		$('<p>').appendTo('#modal-dialogue-content').html('leadership gained this turn: ' +stats.leadership.modifiers.gained);
    		$('<H3>').appendTo('#modal-dialogue-content').html('Breakdown');
    		$('<p>').appendTo('#modal-dialogue-content').html('From population: ' + stats.leadership.modifiers.fromBasePopulation);
    		$('<a href="#"> Spend 10 leadership </a>').appendTo('#modal-dialogue-content').click(function(event) {
    				console.log('will spend 10 leadership');
    		});

    	} else if (element.id === 'player-stats-item-calendar-img'){
    		console.log('will build page for calendar');
    		$('<ul id="calendar-list"> </ul>').appendTo('#modal-dialogue-content');
    			$('<li> Season: '+stats.calendar.season+'</li>').appendTo('#calendar-list');
    			$('<li> Month: '+stats.calendar.month+'</li>').appendTo('#calendar-list');
    			$('<li> Week: '+stats.calendar.week+' </li>').appendTo('#calendar-list');
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
