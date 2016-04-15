<?php

	// Include the requests library
	include('./Requests/library/Requests.php');

	$request = Requests::get('http://loklak.org/api/search.json?q=fossasia', array());

	// Check what we received
	var_dump($request->body);

?>