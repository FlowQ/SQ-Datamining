<?php
	//error_reporting(0);
	session_start();
	$_SESSION=array();//on efface toutes les variables de la session
	session_destroy();
	header('Location: index_cons.php');
?>