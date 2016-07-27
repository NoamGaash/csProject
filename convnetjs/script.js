data =[[], []];
E = Math.E;
Eroot = Math.pow(E, 1/3);

var afterLoadData =function(){
	netWrapper.createNet(
		netWrapper.trainingInput[firstIndex].length,
		netWrapper.trainingOutput[firstIndex].length
	);
	
	netWrapper.iterations = 2;
	window.setInterval(function(){
		netWrapper.train();
		var error = netWrapper.getMedianMistakeLevels();
		data[0].push([netWrapper.count, error[0]]);
		data[1].push([netWrapper.count, error[1]]);
		//console.log(error, netWrapper.count);
		$.plot($("#placeholder"), data, {
			yaxis: {
				ticks: [1/Eroot, 1, Eroot, Eroot*Eroot, E],
                 		transform:  function(v) {return Math.log(v+0.0001);}
                 	}
                 });
	}, 100);
	
};




function getUserPostVector($msg){

	var text = $msg || $("#input").val();
	var time = Math.floor(new Date().getTime()/1000);
	var meta = getVectorMetadata(
		text,
		$("#page_name").val(),
		+$("#page_likes").val(),
		Math.floor(new Date().getTime()/1000)
	);
	
	var bag = getBagOfWordsVector(text);

	
		
	return meta.concat(bag);
}


function humenTranslate(num){
	return Math.floor(Math.pow(
		Math.E,
		num
	));
}


var countChanges = 0;
$(document).ready(function(){
	$("#input, #page_name, #page_likes").change(function(){
		vector = getUserPostVector();
		prediction = netWrapper.DeNormalizeOutput(netWrapper.predict(netWrapper.normalize(vector)));
		$("#output").html(++countChanges+": likes: "+humenTranslate(prediction[0]) + ", comments: "+humenTranslate(prediction[1]));
	});
	
	setTestings();
	
});
var examplesPlottingData;
var replace;
function setTestings(){
	var i;
	var tests = [
		"", "(:", "):",
		"ראשי כבד עלי, כושלות הן שתי רגליי. אני אומלל, אני מובטל, אני גלמוד, אני צרוד, אני נמוך, כולי יתום, אני מוכרח עכשיו לנשום. איני יכול לנשום, חיי הם גיהינום. איזה עולם חרם, חרם על העולם.",
		"עכשיו מבצע! קנו, תנו בלייק, שתפו הגיבו ועופו על המוצר הנהדר הזה! זה כל כך טוב! מומלץ (:",
		"אין לנו זכות בכלל להתלונן, הכל טפו חמסה ברוך השם כי החיים שלנו תותים, החיים שלנו תותים",
		"אין לנו זכות בכלל להתלונן,\nהכל טפו חמסה ברוך השם\nכי החיים שלנו תותים,\nהחיים שלנו תותים",
		"די - די!\nלמה אני צריכה את כל זה? די!\nללכת לברכה כדאי!\n חייב לשלם - חייב לשלם, את חשבון הטלפון.\n אמן",
		"הי, תגידי לי בוא - תגידי לי קח\nוקחי את הכל.\n בואי נברח - האוטו שלי, היופי שלך, ויש לנו זמן.",
		"במסיבה של הבחור עם השיער הצבעוני,\nהיה בליין אבל עכשיו הוא רציני,\nאיש עסקים, שעכשיו הוא טבעוני.\nואולי בשבילי לנשמה\nהמתוקים הם סוג של נחמה",
	]
	
	examplesPlottingData = [];

	$( "#dataTable" ).append( '<ul class="list-group"></ul>' );
	$( "#dataTable" ).append( '<h1>Graph - Examples Likes As Function Of Iterations Number</h1>' );
	$( "#dataTable" ).append( '<div id="examples-plot" style="height: 300px"></div>' );
	$( "#dataTable" ).append( '<button type="button" class="btn btn-danger">reset graph</button>' ).click(function () {replace = true;});
	
	
	var li = [];
	for (i = 0; i < tests.length; i++) {
		li[tests[i]] = $('<li class="list-group-item"><b>no data</b><span class="badge likes">-</span><span class="badge comms">-</span></li>');
		$( "#dataTable ul" ).append( li[tests[i]] );
		examplesPlottingData.push({ label: tests[i].length>20 ? tests[i].substring(0, 20)+"...":(tests[i]?tests[i]:"empty") , data: []});
	}
	
	
	var lastchange = 0;
	
	setInterval(function () {
		if(replace){
			examplesPlottingData=[];
			for (i = 0; i < tests.length; i++)
				examplesPlottingData.push({ label: tests[i].length>20 ? tests[i].substring(0, 20)+"...":(tests[i]?tests[i]:"empty") , data: []});
			replace=false;
		}
		var i;
		if(!net /*|| data[0].length === lastchange*/){
			return;
		}
		lastchange = data[0].length;
		for (i = 0; i < tests.length; i++) {
			//console.log(tests[i]);
			vector = getUserPostVector(tests[i]);
			prediction = netWrapper.DeNormalizeOutput(netWrapper.predict(netWrapper.normalize(vector)));
			li[tests[i]].children("b").html(tests[i]?tests[i].replace(/\n/g, "<br>"):"empty status");
			li[tests[i]].children(".likes").html(humenTranslate(prediction[0]) + ' <span class="glyphicon glyphicon-thumbs-up"></span>');
			li[tests[i]].children(".comms").html(humenTranslate(prediction[1]) + ' <span class="glyphicon glyphicon-comment"></span>');
			examplesPlottingData[i].data.push([lastchange, humenTranslate(prediction[0])]);
		}
		$.plot("#examples-plot", examplesPlottingData, {legend: {
		    	sorted: function(a, b) {
			    // sort alphabetically in ascending order
			    return a.label == b.label ? 0 : (
			        a.label > b.label ? 1 : -1
			    )
			}
		}});
		
	}, 200);
	
	
	
}













