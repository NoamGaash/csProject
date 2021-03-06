// declared outside -> global variable in window scope
var net;
var netWrapper = {};
var firstIndex;
var replaced = false;

$( document ).ready(function() {
	var i;
	// this gets executed on startup 
	SIZE = 24;
	
	max = [];
	for(i=0;i<SIZE;i++) max[i]=0;
	maxOut = [];
	for(i=0;i<2;i++) maxOut[i]=0;
	
	var dataCounter = 0;
	
	////////////////////////////////////// LOAD trainingInput /////////////////////////////////////
	$.get("../getVector", function(data){
	    netWrapper.trainingInput=JSON.parse(data);
	    
	    for(i in netWrapper.trainingInput){
		 if(!firstIndex) firstIndex = i;
		break;
	    }
	    for(j=0; j<SIZE; j++){
		for(i in netWrapper.trainingInput)
		     if(netWrapper.trainingInput[i][j] > max[j]) max[j] = netWrapper.trainingInput[i][j];
		for(i in netWrapper.trainingInput){
		     if(max[j]) netWrapper.trainingInput[i][j] /= max[j];
		}
	    }
	    if(dataCounter++){
	    	afterLoadData();
	    }
	});
	$.get("../getResults.php", function(data){
	    netWrapper.trainingOutput=JSON.parse(data);
	    
	    for(i in netWrapper.trainingOutput){
		firstIndex = i;
		break;
	    }
	    for(j=0; j<2; j++){
		for(i in netWrapper.trainingOutput)
		     if(netWrapper.trainingOutput[i][j] > maxOut[j]) maxOut[j] = netWrapper.trainingOutput[i][j];
		for(i in netWrapper.trainingOutput)
		     if(maxOut[j]) netWrapper.trainingOutput[i][j] /= maxOut[j];
	    }
	    if(dataCounter++){
	    	afterLoadData();
	    }
	});
	
	
	////////////////////////////////////// CREATING THE NET /////////////////////////////////
	netWrapper.createNet = function(inputSize, outputSize){
	  	var layer_defs = [];
		// input layer of size 1x1x2 (all volumes are 3D)
		layer_defs.push({type:'input', out_sx:1, out_sy:1, out_depth:inputSize});
		// some fully connected layers
		layer_defs.push({type:'fc', num_neurons:40, activation:'relu'});
		layer_defs.push({type:'fc', num_neurons:40, activation:'sigmoid'});
		
		layer_defs.push({type:'regression', num_neurons:outputSize});
		 
		// create a net out of it
		net = new convnetjs.Net();
		net.makeLayers(layer_defs);
		trainer = new convnetjs.SGDTrainer(net, {learning_rate:0.005, momentum:0.0, batch_size:1, l2_decay:0.001});
	}
	 
	 
	 netWrapper.normalize = function(arr){
	 	for(i=0; i<max.length; i++){
	 		if(max[i]) arr[i] /=max[i];
	 	}
	 	return arr;
	 }
	 netWrapper.normalizeOutput = function(arr){
	 	for(i=0; i<maxOut.length; i++){
	 		arr[i] /=maxOut[i];
	 	}
	 	return arr;
	 }
	 netWrapper.DeNormalizeOutput = function(arr){
	 	for(i=0; i<maxOut.length; i++){
	 		arr[i] *=maxOut[i];
	 	}
	 	return arr;
	 }
	 
	////////////////////////////////////// TRAINING THE NET /////////////////////////////////

	var trainer;
	netWrapper.count=0;
	netWrapper.train = function(){
		if(netWrapper.trainingInput)
			for(i=0; i<netWrapper.iterations; i++){
				for(j in netWrapper.trainingInput){
					if(netWrapper.trainingInput[j] && netWrapper.trainingOutput[j]){
						var x = new convnetjs.Vol(netWrapper.trainingInput[j]);
						trainer.train(x, netWrapper.trainingOutput[j]);
					}
				}
				netWrapper.count++;
			}
			
	}
	
	
	netWrapper.getMistakeLevels = function(){
		var prediction;
		var ans = [];
		for(j in netWrapper.trainingOutput){
			if(netWrapper.trainingInput[j] && netWrapper.trainingOutput[j]){
				prediction = netWrapper.predict(
					netWrapper.trainingInput[j]
				);
				result = netWrapper.trainingOutput[j];
				ans.push([
					Math.pow(Math.E, Math.abs(prediction[0]-result[0])),
					Math.pow(Math.E, Math.abs(prediction[1]-result[1]))
				]);
				//console.log(Math.log(prediction[0])-Math.log(result[0]),prediction[0],result[0]);
				
			}
		}
		return ans;
	}
	
	netWrapper.getMedianMistakeLevels = function(){
		error = netWrapper.getMistakeLevels();
		index = Math.floor(error.length/2);
		
		error.sort(function (a,b){
			return a[0] - b[0];
		});
		var medianlike = error[index][0];
		
		error.sort(function (a,b){
			return a[1] - b[1];
		});
		var mediancomment = error[index][0];
		return [medianlike, mediancomment];
	}
	
	netWrapper.toObject = function(){
		var json = net.toJSON();
		$("#output").html("data saved");
		return json;
	}
	
	netWrapper.replaceNet = function(obj){
		replaced = true;
		new convnetjs.Net();
		net.fromJSON(obj);
		$("#output").html("data loaded");
	}
		
	
	////////////////////////////////////// PREDICTING /////////////////////////////////
	// the network always works on Vol() elements. These are essentially
	// simple wrappers around lists, but also contain gradients and dimensions
	// line below will create a 1x1x(x.length) volume and fill it with x
	netWrapper.predict = function(x){
		x = new convnetjs.Vol(x);
		var probability_volume = net.forward(x);
		return probability_volume.w;
	}

});
