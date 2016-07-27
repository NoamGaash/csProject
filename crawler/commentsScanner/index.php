<?php
	require_once "../SQL_login.php";
	
	
	require_once "../header.php";
	
	$start = isset($_GET['start'])?$_GET['start']:0;
	$limit = isset($_GET['limit'])?$_GET['limit']:30;
	?>

	<script   src="https://code.jquery.com/jquery-2.2.2.min.js"   integrity="sha256-36cp2Co+/62rEAAYHLmRCPIych47CvdM+uTBJwSzWjI=" crossorigin="anonymous"></script>
	<script src='https://cdn.firebase.com/js/client/2.2.1/firebase.js'></script>

	<input id = 'moveon' type="checkbox" checked>scan comments to the database</input><br>
	
	<form method="get">
		start: <input type = 'number' name="start" value="<?=$start?>"><br>
		limit: <input type = 'number' name="limit" value="<?=$limit?>"><br>
		<input type=submit>
	</form>
		
	<?php	
		if (isset($_SESSION['facebook_access_token'])) {
			session_write_close();
			echo '<table>';
			$query = 'SELECT * FROM  `project_posts` WHERE id IN (SELECT post FROM  `project_likes`) LIMIT ' .$start. ' , ' .$limit ;
			$result = mysql_query($query) or die("Query fail: " . mysqli_error());
			while($row = mysql_fetch_assoc($result)) {
				//print_r($row);
				?>
				<tr>
					<td><a href="http://facebook.com/<?=$row['id']?>"><?=$row['id']?></a></td>
					<td><?=$row['page_id']?></td>
					<td><?=$row['message']?></td>
					<td><?=$row['created_time']?></td>
					<td class="getcomments" id="commentsGetter_<?=$row['id']?>">waiting for results...</td>
				</tr>
				<?php
			}
			echo '</table>';
		}
		session_write_close();
		?>
		
		<script>
		var PostsLastReadingRef = new Firebase("https://csnoamproject.firebaseio.com/").child("PostCommentsLastReading");
		var PostsLastReading;
		PostsLastReadingRef.on("value", function(snapshot) {
		  PostsLastReading = snapshot.val()||[];
		}, function (errorObject) {
		  console.log("The read failed: " + errorObject.code);
		});
		
		var pageLoadingDataDivs = $(".getcomments");	// posts has to be scanned
		var index = 0;					// post index being scanned
		var currTime = new Date().valueOf();
		function scan_page_comments(){
			var page_id = pageLoadingDataDivs[index].id.split('_')[1]+"_"+pageLoadingDataDivs[index].id.split('_')[2];
			
			if($('#moveon').is(':checked')){  			  	//if V is marked
				if(!PostsLastReading){					//if Firabase not loaded yet
					window.setTimeout(function(){
						if(index<pageLoadingDataDivs.length)
							scan_page_comments()
					},300);
				}else if( latelyScanned(PostsLastReading[page_id]) ){			//if post has already been read
					$(pageLoadingDataDivs[index]).html("no need to load data - data already scanned at: "+formatTime(PostsLastReading[page_id]));
					index++;
					if(index<pageLoadingDataDivs.length)
						scan_page_comments();
				}else{
					$.get("data-post-comments.php?post_id="+page_id+"&max_pages=2", function(data){
						$(pageLoadingDataDivs[index]).html(data);
						PostsLastReadingRef.child(page_id).set(new Date().valueOf());
						index++;
						if(index<pageLoadingDataDivs.length)
							scan_page_comments()
					});
				}
			}else{
				window.setTimeout(scan_page_comments, 300);
			}
		}
		scan_page_comments();
		
		
		function latelyScanned(t){
			if(!t) return false;
			if(currTime - t < 2592000000) // 1000 * 60 * 60 * 24 * 30 - one month in milliseconds
				return true;
			return false;
		}
		
		function formatTime(t){
			t = new Date(t);
			return '<br>'+t.getDate() +"/"+(t.getMonth()+1)+"/"+t.getFullYear()+"<br>"+t.getHours()+":"+t.getMinutes()+":"+t.getSeconds();
		}
		
		
		</script>
		<style>
			td{
				width: 20%;
				text-align: center;
				border-bottom: 1px solid #999;
				padding-bottom: 15px
			}
		</style>
	</body>
</html>
<!--

SELECT * FROM `project_posts` WHERE id IN
(SELECT `post` FROM  `project_likes` )

-->