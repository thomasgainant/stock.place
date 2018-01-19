<?php
	ini_set('display_errors', 1);
	error_reporting(~0);
	include('engine/engine.php');
	connectToDB();	
	include('web.queries.php');
?><html>
	<head>
		<title><?php loc('stock.place - Logiciel en ligne de gestion de stock:|:stock.place - Online stock management software'); ?></title>
		<link rel="stylesheet" type="text/css" href="style.css">
		
		<script src='https://www.google.com/recaptcha/api.js'></script>
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript" src="jquery.scrolling.js"></script>
		<script type="text/javascript" src="script.js"></script>
	</head>
	
	<body>