<html>
	<head>
		<title>minimal demo</title>
		 <meta charset = "utf-8"/>
		 
		<!-- import convnetjs library -->
		<script src="convnet-min.js"></script>
		
		<!-- import jquery -->
		<script src="https://code.jquery.com/jquery-2.2.3.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/flot/0.8.3/jquery.flot.min.js"></script>
		
		
		<!-- javascript goes here -->
		<script src="getVectorMetadata.js"></script>
		<script src="BagOfWordsVector.js"></script>
		<script src="https://cdn.firebase.com/js/client/2.4.2/firebase.js"></script>
		<script src="netWrapper.js"></script>
		<script src="script.js"></script>
		<script src="firebase.js"></script>

		<style>
		b {
		    max-width: 77%;
		    display: inline-block;
		}
		</style>
		
		<!-- Latest compiled and minified bootstrap CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	</head>
	 
	<body>
		<div class = "col-sm-6">
			<h1 class="text-capitalize">graph - error rate as function of iterations number</h1>
			<div id="placeholder" style="width:600px;height:300px"></div>
	 		<input id="page_name" placeholder="page name"></input>
	 		<input id="page_likes" placeholder="page likes"></input>
			<textarea id="input"></textarea>
			<button onclick="setData(netWrapper.toObject());">save net!</button>
			<button onclick="netWrapper.replaceNet(getData());">load net!</button>
			<div id="output"></div>
		</div>
		<div class = "col-sm-6" id = "dataTable">
			<h1>predictions</h1>
		</div>
	</body>
</html>