<!DOCTYPE html>
<html lang="en">
<head>
  <title>Usage of Deep Learning for social graph algorithmic research</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
  	h3, h1{text-align: center;}
  </style>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
	<hgroup>
	  <h1>Usage of Deep Learning for social graph algorithmic research</h1>
	  <h3 class="col-sm-6">Noam Gaash & Sapir Kahlon</h3><h3 class="col-sm-6">Supervisor: Dr. Ben Moshe </h3>
	</hgroup>
	<p>
		In our project we are researching algorithms based on artificial learning and natural networks that work with social networks, pull from them information about users, posts, comments and pages, cross-referenced them and looking for ways to conclude information about the various relationships between parameters relating the publication of the post and it’s success. Through in-depth analysis of this information we can catalog groups of users, examine their business potential and mapping target audience, what might help stakeholders in the industry to focus its business activities with the relevant population segments.
	</p> 
	<div class="col-sm-4">
		<a href = "getVectors.php">raw data vectors</a> - <br>
		<ul>
			<li>json formatted two dimentional array</li>
			<li>each row represent one post</li>
			<li>will be used as raw training data for the neural network.</li>
		</ul>
	</div>
	<div class="col-sm-4">
		<a href = "crawler/login.php">login the application (via Facebook authentication)</a> - <br>
		<ul>
			<li>nessecery step</li>
			<li>must be done <b>before</b> tring data retievement</li>
			<li>Facebook Authentication let us access to all data about pages you have liked.</li>
		</ul>
	</div>
	<div class="col-sm-4">
		<a href = "crawler/data.php">liked pages scanner</a> - <br>
		<ul>
			<li>must be maid <b>after</b> logging in</li>
			<li>visiting that link makes the system scan for pages you have liked, and pushing them to database tables</li>
			<li>checking the "read posts?" triggers javascript code to send XHRs to the "data-posts" service, that scans for posts published by each of the found pages</li>
			<li>the "data-posts" service recieves requests with URL formatted as crawler/data-posts.php?page_id=211530498890677&max_pages=2</li>
			<li>.</li>
		</ul>
	</div>
	<div class="col-sm-4">
		<a href = "crawler/data-see-posts.php">post likes scanner</a> - <br>
		<ul>
			<li>must be maid <b>after</b> logging in</li>
			<li>visiting that link makes the system scan for likes maid on posts scanned to the database, and pushing new likes to 'project_likes' table</li>
			<li>checking the "read likes?" triggers javascript code to send XHRs to the "data-post-likes.php" service, that scans for likes main on each of the found posts</li>
			
		</ul>
	</div>
	<div class="col-sm-4">
		<a href = "crawler/commentsScanner/">post comments scanner</a> - <br>
		<ul>
			<li>must be maid <b>after</b> logging in</li>
			<li>visiting that link makes the system scan for comments maid on posts scanned to the database, and pushing new likes to 'project_comments' table</li>
			<li>checking the "read comments?" triggers javascript code to send XHRs to the "data-post-comments.php" service, that scans for comments maid on each of the found posts</li>
			
		</ul>
	</div>
	<div class="col-sm-4">
		<a href = "convnetjs/hello_world.php">see the magic works!</a><br>
		<ul>
			
			
		</ul>
	</div>
</div>

</body>
</html>