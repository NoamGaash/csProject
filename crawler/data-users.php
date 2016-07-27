<?php

	require_once "SQL_login.php";
	
	
	$debug=true;
	if($debug) require_once "header.php";
	else require_once "initFB.php";
	
	
	if($debug) echo '<pre>';
	
	if (isset($_SESSION['facebook_access_token'])) {
		
		$accessToken = $_SESSION['facebook_access_token'];
		
		///////////////////////// send request to the graph API
		
		$fb->setDefaultAccessToken($accessToken);
		
		
		if($debug) echo "SELECT * FROM project_likes\n";
		$resultlikes = mysql_query('SELECT * FROM project_likes');
		
		$users_passed = [];
		
		$count = 0;
		while($row = mysql_fetch_array($resultlikes)) {
			ob_end_flush();
			if($count++>20) exit;
	        	if(!$users_passed[$row['user']]){
	        		$users_passed[$row['user']] = true;
				if($debug) echo 'SELECT * FROM project_users WHERE id=' . $row['user'] . "\n";
				$result = mysql_query('SELECT * FROM project_users WHERE id=' . $row['user']);
	        		if(mysql_num_rows($result) == 0){
					if($debug) echo "NEW USER!!!!!\n";
					//10152080172971476?fields=name,id,gender,currency,locale
					
					try {
					if($debug) echo '/' .$row['user'] . '?fields=name,id,gender,currency,locale' . "\n";
					  $response = $fb->get( $result['id']); // '?fields=name,id,gender,currency,locale');
					  $Node = $response->getGraphObject();
					} catch(Facebook\Exceptions\FacebookResponseException $e) {
					  // When Graph returns an error
					  echo 'Graph returned an error: ' . $e->getMessage() . "\n\n";
					  //exit;
					} catch(Facebook\Exceptions\FacebookSDKException $e) {
					  // When validation fails or other local issues
					  echo 'Facebook SDK returned an error: ' . $e->getMessage();
					  exit;
					}
					if($debug) print_r($Node);
					/*if($debug) echo "INSERT INTO `noamgaas_wp1`.`project_users` (
						`id` ,
						`name` ,
						`gender`,
						`currency`,
						`locale`
						)
						VALUES (
						'" .  . "', '" .  . "', '" .  ."', '" .  . "', '" .  ."'
						);\n";
					$result = mysql_query('SELECT * FROM project_users');*/
	        		}
	        	}
	      	}
		
		
		
		if($debug) echo '</pre>';
	}
	session_write_close();
	if($debug){
		?>
		</pre>
	</body>
</html>
<?php
}