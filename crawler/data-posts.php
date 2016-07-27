<?php
	require_once "SQL_login.php";
	$page_id = $_GET['page_id'];
	
	
	if($debug) require_once "header.php";
	else require_once "initFB.php";
	
	
	
	if($debug) echo '<pre>';
	
		if (isset($_SESSION['facebook_access_token'])) {
			
			$accessToken = $_SESSION['facebook_access_token'];
			
			///////////////////////// send request to the graph API
			
			$fb->setDefaultAccessToken($accessToken);
			
	
			try {
			  $response = $fb->get('/' . $page_id . '?fields=posts{likes{name},message,created_time}');
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
			$max_pages = $_GET['max_pages'];
			$posts=$Node->getField('posts');
			$oldCounter = 0; $newCounter=0;
			do{
				foreach($posts as $post){
					//print_r($post);
					if($debug) echo 'SELECT * FROM project_posts WHERE id="' . $post->getField('id') . "\"\n";
					$result = mysql_query('SELECT * FROM project_posts WHERE id="' . $post->getField('id') . '"');
					
					if(!$result) continue;
					if(mysql_num_rows($result) == 0){
						$newCounter++;
						
						if($debug){
							echo 'id: ' . $post->getField('id'). "\n";
							echo "\n~~~~~~~~~~~~\n";
							echo "NEW ITEM!\n";
						}
						$query = "
							INSERT INTO
							 `project_posts` 
							(`id`, `page_id`, `message`, `created_time`)
							 VALUES
							(
								'". $post->getField('id') ."',
								". $page_id .",
								'". htmlspecialchars($post->getField('message'), ENT_QUOTES) ."',
								'". $post->getField('created_time')->format('Y-m-d H:i:s') ."'
							)";
						$result = mysql_query($query);
						if($debug) echo $query . "\n";
						if(!$result) echo mysql_error() . "\n";
						
						if($debug) echo "\n~~~~~~~~~~~~\n";
					}else{
						$oldCounter++;
						if($debug) echo 'page id ' . $post->getField('id') . " has already been inserted\n";
					}
				}
				if($debug) echo "\n\n";
				//print_r($posts);
				
				
				if($pagesCount > $max_pages)
					break;
				$pagesCount++;
			}while($posts = $fb->next($posts));
			echo "$oldCounter old posts passed, $newCounter new posts has been inserted";
			
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