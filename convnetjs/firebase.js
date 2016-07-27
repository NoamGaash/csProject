
(function () {
	var netRef = new Firebase("https://csnoamproject.firebaseio.com/").child("net"),
	    netObj;
	
	window.setData = function(data){
		netRef.set(data);
	};
	
	window.getData = function(data){
		return netObj;
	};
	
	netRef.on("value", function(snapshot) {
	  netObj = snapshot.val();
	});
	
	
}());