<?php

function logger($data) {
	error_log("\r\n".$data, 3, "log.txt");    
}

// usage example: debug_to_console( "Test" );


?>