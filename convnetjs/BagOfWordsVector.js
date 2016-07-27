var getBagOfWordsVector = function() {
	console.log("cant load the list");
};

bagOfWords = [];

$(document).ready(function(){
	$.get("../words/list-of-files.txt", function(data){
		var index = 0;
		var files = data.split("\n");
		var load = function(){
			if(index < files.length){
				var filename = files[index++].split(" ")[0];
				$.get("../words/"+filename, function(data){
					bagOfWords.push(data.split("\n"))
					load();
				}).fail(function() {
					getBagOfWordsVector();
				});
			}else{
				getBagOfWordsVector = function(str) {
					var ans = [];
					for(i=0; i<bagOfWords.length; i++){
						var count = 0;
						for(j=0; j<bagOfWords[i].length;j++){
							if(bagOfWords[i][j].length>1)
								count += str.split(bagOfWords[i][j]).length-1;
						}
						ans.push(count);
					}
					return ans;
				};
			}
		}
		
		load();
		
	}).fail(function() {
		getBagOfWordsVector();
	})
});