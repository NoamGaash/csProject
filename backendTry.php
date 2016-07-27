<!DOCTYPE html>

<html>
	<head>
		<meta charset="utf-8">
		<title>hello backand!</title>
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.8/angular.min.js"></script>
		  <!-- Backand SDK for Angular -->
		  <script src="//cdn.backand.net/backand/dist/1.8.0/backand.min.js"></script>
		<script>
		  	angular.module('eliza', ['backand']);
		  	
			  //Update Angular configuration section
			  myApp.config(function (BackandProvider) {
			      BackandProvider.setAppName('eliza');
			      BackandProvider.setSignUpToken('15f745d1-b4fa-4c04-bf83-ae7d4da0ae4d');
			      BackandProvider.setAnonymousToken('1f881f92-a477-4ecf-9fe5-df47c2e84c1c');
			  })
		</sctipt>
	</head>
	<body ng-app="eliza">
		<h1>
			hello backand
		</h1>
		<p>
		</p>
	</body>
</html>