<?php
	require_once "SQL_login.php";
	$post_id = $_GET['post_id'];
	
	
	if($debug) require_once "header.php";
	else require_once "initFB.php";
	
	
	
	if($debug) echo '<pre>';
	
		if (isset($_SESSION['facebook_access_token'])) {
			
			$accessToken = $_SESSION['facebook_access_token'];
			
			///////////////////////// send request to the graph API
			
			$fb->setDefaultAccessToken($accessToken);
			
	
			try {
			  $response = $fb->get('/' . $post_id . '?fields=likes.limit(10000){id,profile_type}');
			  $Node = $response->getGraphObject();
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
			  // When Graph returns an error
			  echo 'Graph returned an error: ' . $e->getMessage();
			  exit;
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
			  // When validation fails or other local issues
			  echo 'Facebook SDK returned an error: ' . $e->getMessage();
			  exit;
			}
			
			if($debug)echo '
			
			
			
			';
			
			$pagesCount = 0;
			$max_pages = $_GET['max_pages']?$_GET['max_pages']:100;
			$likes=$Node->getField('likes');
			$oldCounter = 0; $newCounter=0;
			do{
				foreach($likes as $like){
					//print_r($like);
					if($debug) echo 'SELECT * FROM project_likes WHERE user="' . $like->getField('id') . '" AND post="' . $post_id . '"<br>';
					$result = mysql_query('SELECT * FROM project_likes WHERE user="' . $like->getField('id') . '" AND post="' . $post_id . '"');
					
					//echo mysql_error() . "\n";
					
					if(mysql_num_rows($result) == 0){
						$newCounter++;
						
						if($debug){
							echo 'id: ' . $like->getField('id'). "\n";
							echo "\n~~~~~~~~~~~~\n";
							echo "NEW ITEM!\n";
						}
						$query = "
							INSERT INTO
							 `project_likes` 
							(`user`, `post`, `profile_type`)
							 VALUES
							(
								'". $like->getField('id') ."',
								'". $post_id ."',
								'". $like->getField('profile_type') ."'
							)";
						$result = mysql_query($query);
						if($debug) echo $query . "\n";
						if(!$result) echo mysql_error() . "\n";
						
						if($debug) echo "\n~~~~~~~~~~~~\n";
					}else{
						$oldCounter++;
						if($debug) echo 'like from user ' . $like->getField('id') . " has already been inserted\n";
					}
				}
				if($debug) echo "\n\n";
				//print_r($likes);
				
				
				if($pagesCount > $max_pages)
					break;
				$pagesCount++;
			}while($likes = $fb->next($likes));
			echo "$oldCounter old likes passed, $newCounter new likes has been inserted";
			
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