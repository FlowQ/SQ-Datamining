<?php
	require dirname(__FILE__) .'/../src/facebook.php';
	//error_reporting(0);
	
	define('DSN', 'mysql:host=localhost;dbname=fb_dashboard');
	define('DB_USERNAME', 'root');
	define('DB_PASSWORD', 'miamece2013');
	define('APP_ID', '190148237843701');
	define('APP_SECRET', 'c65b9da55e98cb702059b532e2d878dd');
	define('CALLBACK_URL', 'http://ec2-54-229-202-97.eu-west-1.compute.amazonaws.com/FB_Dashboard/');
	define('AUTHORIZATIONS', 'read_stream, user_friends, friends_relationships, user_likes, friends_likes, friends_birthday, friends_hometown, friends_birthday, friends_location, friends_relationship_details, friends_work_history, friends_education_history');


// Create our Application instance (replace this with your appId and secret).
	$facebook = new Facebook(array(
	  'appId'  => APP_ID,
	  'secret' => APP_SECRET,
	  'cookie' => true,
	));
?>