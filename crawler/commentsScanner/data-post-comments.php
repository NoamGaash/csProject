<?php
	require_once "../SQL_login.php";
	$post_id = $_GET['post_id'];
	
	//$debug=1;
	if($debug) require_once "../header.php";
	else require_once "../initFB.php";
	
	
	if($debug) echo '<pre>';
	
		if (isset($_SESSION['facebook_access_token'])) {
			
			$accessToken = $_SESSION['facebook_access_token'];
			
			///////////////////////// send request to the graph API
			
			$fb->setDefaultAccessToken($accessToken);
			
	
			try {
			  $response = $fb->get('/' . $post_id . '?fields=comments.limit(1000){created_time,id,message,comment_count,like_count}');
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
			$comments=$Node->getField('comments');
			$oldCounter = 0; $newCounter=0;
			do{
				foreach($comments as $comment){
					//print_r($comment);
					//{created_time,id,message,comment_count,like_count}
					$result = mysql_query('SELECT * FROM project_comments WHERE id="' . $comment->getField('id') . '"');
					
					//echo mysql_error() . "\n";
					
					if(mysql_num_rows($result) == 0){
						$newCounter++;
						
						if($debug){
							echo 'id: ' . $comment->getField('id'). "\n";
							echo "\n~~~~~~~~~~~~\n";
							echo "NEW ITEM!\n";
						}
						$query = "
							INSERT INTO `project_comments` (`id`, `post`, `message`, `like_count`, `comment_count`, `created_time`) VALUES
							(
								'". $comment->getField('id') ."',
								'". $post_id ."',
								'". addslashes(htmlentities($comment->getField('message'), ENT_QUOTES)) ."',
								'". $comment->getField('like_count') ."',
								'". $comment->getField('comment_count') ."',
								'". $comment->getField('created_time')->format('Y-m-d H:i:s') ."'
							)";
							
						$result = mysql_query($query);
						if($debug) echo $query . "\n";
						if(!$result) echo mysql_error() . "\n";
						
						if($debug) echo "\n~~~~~~~~~~~~\n";
					}else{
						$oldCounter++;
						if($debug) echo 'comment from user ' . $comment->getField('id') . " has already been inserted\n";
					}
				}
				if($debug) echo "\n\n";
				//print_r($comments);
				
				
				if($pagesCount > $max_pages)
					break;
				$pagesCount++;
			}while($comments = $fb->next($comments));
			echo "$oldCounter old comments passed, $newCounter new comments has been inserted";
			
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
