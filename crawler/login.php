<?php
	$DO_NOT_CLOSE_SESSION=1;
	require_once "header.php";
?>
		<?php
		$permissions = ['user_likes'];	
		$loginUrl = $helper->getLoginUrl('http://localhost/csFinalProject/crawler/callback.php', $permissions);
		echo '<a href = "'. $loginUrl .'">Log In With Facebook</a>';
		session_write_close();
		?>
	</body>
</html>
