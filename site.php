<?php

use Tagui\Page;

$app->get('/', function() {
   
	$page = new Page();

	$page -> setTpl("index");
});


?>