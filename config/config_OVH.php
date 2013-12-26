<?php
	require dirname(__FILE__) .'/../src/facebook.php';
	//error_reporting(E_ALL);
	
	define('DSN', 'mysql:host=mysql51-114.bdb;dbname=innovatiface');
	define('DB_USERNAME', 'innovatiface');
	define('DB_PASSWORD', 'dash2014');

	define('APP_ID', '174160702793836');
	define('APP_SECRET', '9959cd0e1f1ec5c222a9bf873303e9f2');
	define('CALLBACK_URL', 'http://www.innovativepictures.fr/FB_Dashboard/');
	define('AUTHORIZATIONS', 'read_stream, user_friends, friends_relationships, user_likes, friends_likes, friends_birthday, friends_hometown, friends_birthday, friends_location, friends_relationship_details, friends_work_history, friends_education_history');

	define('SMTP_HOST','smtp.innovativepictures.fr');
	define('SMTP_PORT', '587');
	define('SMTP_USER', 'florianquattrocchi@innovativepictures.fr');
	define('SMTP_PASS', 'popoetmomo');
	

// Create our Application instance (replace this with your appId and secret).
	$facebook = new Facebook(array(
	  'appId'  => APP_ID,
	  'secret' => APP_SECRET,
	  'cookie' => true,
	));
?>