<?php
	require 'src/facebook.php';
	
	define('DSN', 'mysql:host=localhost;dbname=fb_dashboard');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', '');
	define('APP_ID', '553248554770243');
	define('APP_SECRET', '97a1373ba6f66485c40bec5d764faf20');
	define('CALLBACK_URL', 'http://localhost/FB_Dashboard/');
	define('AUTHORIZATIONS', 'read_stream, user_friends, friends_relationships, user_likes, friends_likes, friends_birthday');
	


// Create our Application instance (replace this with your appId and secret).
	$facebook = new Facebook(array(
	  'appId'  => APP_ID,
	  'secret' => APP_SECRET,
	  'cookie' => true,
	));
?>