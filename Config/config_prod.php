<?php
	require 'src/facebook.php';
	
	define('DSN', 'mysql:host=localhost;dbname=fb_dashboard');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('APP_ID', '1432202276999843');
	define('APP_SECRET', '4fbdcab2455374d374560d045fb6df98');
	define('CALLBACK_URL', 'http://localhost/FB_Dashboard/FQL.php');
	define('AUTHORIZATIONS', 'read_stream, user_friends, friends_relationships, user_likes, friends_likes, friends_birthday');


// Create our Application instance (replace this with your appId and secret).
	$facebook = new Facebook(array(
	  'appId'  => APP_ID,
	  'secret' => APP_SECRET,
	  'cookie' => true,
	));
?>