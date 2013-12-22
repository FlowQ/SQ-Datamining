<?php

	class Toolbox
	{

		function sendMail($name) {
		      $from = 'florianquattrocchi@innovativepictures.fr';
		      //$to = 'florianquattrocchi@gmail.com, constance.laborie@gmail.com';
		      $to = 'florianquattrocchi@gmail.com';
		      $subject = $name." s'est inscrit sur FB_Dash";
		      $body = "Hey les mecs, on a un nouvel inscrit : ".$name." s'est inscrit aujourd'hui : ".Date('d-m-y')." à ".Date('H\hi');

		      $headers = array(
		          'From' => $from,
		          'To' => $to,
		          'Subject' => $subject
		      );

		      $smtp = Mail::factory('smtp', array(
		              'host' => SMTP_HOST,
		              'port' => SMTP_PORT,
		              'auth' => true,
		              'username' => SMTP_USER,
		              'password' => SMTP_PASS
		      ));
		      if($name != 'Florian Quattrocchi')
		        $mail = $smtp->send($to, $headers, $body);
	    }

		//construit la Query de forme FQL en URL
		public function queryConstructor($query) {
			$result = 'fql?q=';
			return $result.str_replace(' ', '+', $query);
		}

			// test Flow
		public function queryRun($query, $access_token) {
			$fql_query_url = 'https://graph.facebook.com/'
			. $this -> queryConstructor($query)
			. '&access_token=' . $access_token;
			$fql_query_result = file_get_contents($fql_query_url);
			$fql_query_obj = json_decode($fql_query_result, true, 512, JSON_BIGINT_AS_STRING);
			return $fql_query_obj;
		}


		public function exists($var) {
			if(isset($var)) {
			  return $var;
			} else {
			  return null;
			}
		}

		public function dateFQLtoSQL($date) {
			$result = str_word_count($date, 1, '0123456789');
			if(count($result) > 2) {
			  $month = "01";
			  switch ($result[0]) {
			    case 'January':
			      $month = '01';
			    break;
			    case 'February':
			      $month = '02';
			    break;
			    case 'March':
			      $month = '03';
			    break;
			    case 'April':
			      $month = '04';
			    break;
			    case 'May':
			      $month = '05';
			    break;
			    case 'June':
			      $month = '06';
			    break;
			    case 'July':
			      $month = '07';
			    break;
			    case 'August':
			      $month = '08';
			    break;
			    case 'September':
			      $month = '09';
			    break;
			    case 'October':
			      $month = '10';
			    break;
			    case 'November':
			      $month = '11';
			    break;
			    case 'December':
			      $month = '12';
			    break;
			  }
			  return $result[2]."-".$month."-".$result[1];
			}
		}

	}
?>