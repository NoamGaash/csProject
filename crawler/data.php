<?php
	require_once "SQL_login.php";
	require_once "header.php";
?>

<script   src="https://code.jquery.com/jquery-2.2.2.min.js"   integrity="sha256-36cp2Co+/62rEAAYHLmRCPIych47CvdM+uTBJwSzWjI=" crossorigin="anonymous"></script>

	<script src='https://cdn.firebase.com/js/client/2.2.1/firebase.js'></script>


		<input type = 'checkbox' id='moveon'>read posts?</input>

		<p>
		<?php
		if (isset($_SESSION['facebook_access_token'])) {
			
			$accessToken = $_SESSION['facebook_access_token'];
			
			///////////////////////// send request to the graph API
			
			$fb->setDefaultAccessToken($accessToken);
			
	
			try {
			  $response = $fb->get('me?fields=name,likes.limit(200){name,id,about,likes,location,locations,phone}');
			  //$response = $fb->get('/109557632393531?fields=posts{likes{name},message},about');
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
			
			echo 'Logged in as ' . $Node->getField('name');
			echo '<br><br><br>
			
			
			
			';
			$likes=$Node->getField('likes');
			do{
				foreach($likes as $like){
					$result = mysql_query('SELECT * FROM project_pages WHERE id=' . $like->getField('id'));
					if(mysql_num_rows($result) == 0){
						echo 'id: ' . $like->getField('id'). "\n";
						echo "<br>\n~~~~~~~~~~~~<br>\n";
						echo "NEW ITEM!<br>\n";
						$query = "
							INSERT INTO
							 `project_pages` 
							(`id`, `name`, `likes`, `about`)
							 VALUES
							(
								". $like->getField('id') .",
								'". htmlspecialchars($like->getField('name'), ENT_QUOTES) ."',
								". $like->getField('likes') .",
								'". htmlspecialchars($like->getField('about'), ENT_QUOTES)."'
							)";
						$result = mysql_query($query);
						echo '<pre>' . $query . "</pre><br>\n";
						if(!$result) echo mysql_error() . "<br>\n";
						
						echo "<br>\n~~~~~~~~~~~~<br>\n";
					}else
						echo 'page id ' . $like->getField('id') . " has already been inserted. ";
					echo "<span class='pagePostsCrawling' id='page_".$like->getField('id')."_data'>waiting to read page's posts</span><br>";
				}
				echo "<br><br>";
				//print_r($likes);
			}while($likes = $fb->next($likes));
		}
		?>
		</p>
		
		
		
		<script>
		// reteive data from firebase about the already read data
		var PagesLastReadingRef = new Firebase("https://csnoamproject.firebaseio.com/").child("PagesLastReading");
		var PagesLastReading;
		PagesLastReadingRef.on("value", function(snapshot) {
		  PagesLastReading = snapshot.val() || [];
		}, function (errorObject) {
		  console.log("The read failed: " + errorObject.code);
		});
		
		
		var pageLoadingDataDivs = $(".pagePostsCrawling");
		var index = 0;
		var max_pages=5
		var currTime = new Date().valueOf();
		function scan_page_likes(){
			var page_id = pageLoadingDataDivs[index].id.split('_')[1];
			
			if($('#moveon').is(':checked')){
				  			  	//if V is marked
				if(!PagesLastReading){					//if Firabase not loaded yet
					window.setTimeout(function(){
						if(index<pageLoadingDataDivs.length)
							scan_page_likes()
					},300);
				}else if( latelyScanned(PagesLastReading[page_id]) ){			//if post has already been read
					$(pageLoadingDataDivs[index]).html("no need to load data - data already scanned at: "+formatTime(PagesLastReading[page_id]));
					index++;
					if(index<pageLoadingDataDivs.length)
						scan_page_likes();
				}else{
					$.get("data-posts.php?page_id="+page_id+"&max_pages="+max_pages, function(data){
						$(pageLoadingDataDivs[index]).html(data);
						PagesLastReadingRef.child(page_id).set(new Date().valueOf());
						index++;
						if(index<pageLoadingDataDivs.length)
							scan_page_likes()
					});
				}
			}else{
				window.setTimeout(scan_page_likes, 300);
			}
		}
		scan_page_likes();
		
		
		
		function latelyScanned(t){
			if(!t) return false;
			if(currTime - t < 432000000) // 1000 * 60 * 60 * 24 * 5- five days in milliseconds
				return true;
			return false;
		}
		
		function formatTime(t){
			t = new Date(t);
			return '  '+t.getDate() +"/"+(t.getMonth()+1)+"/"+t.getFullYear()+"  "+t.getHours()+":"+t.getMinutes()+":"+t.getSeconds();
		}
		</script>
	</body>
</html>
