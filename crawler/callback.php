<?php

	$DO_NOT_CLOSE_SESSION = 1;
	require_once "header.php";
?>
		<?php
		try {
		  $accessToken = $helper->getAccessToken();
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  // When Graph returns an error
		  echo 'Graph returned an error: ' . $e->getMessage();
		  exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  // When validation fails or other local issues
		  echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  exit;
		}
		
		if (isset($accessToken)) {
			// Logged in!
			$_SESSION['facebook_access_token'] = (string) $accessToken;
			
			echo "successfully logged in!<br>";
			echo '<a href = "data.php">view data</a>';
			// Now you can redirect to another page and use the
			// access token from $_SESSION['facebook_access_token']
		}
		session_write_close();
		?>
	</body>
</html>