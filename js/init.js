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

	$('#player-stats-item-market-img').click(function(){
		modalDialogue.prepare($(this)[0]);
	});	

	$('#player-stats-item-calendar-img').click(function(){
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