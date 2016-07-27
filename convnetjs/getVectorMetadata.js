function getVectorMetadata(msg, page_name, likes, timestamp){
	ans = [
		// post length:
		msg.length,
		
		// page name length:
		page_name.length,
		
		// count url's, numbers, and email addresses
		countURL(msg),
		countNumbers(msg),
		countEmail(msg),
		
		// timestamp representing the time in the day when the post was published:
		(+timestamp) %(24*60*60), 
		
		// timestamp representing the date and time when the post was published:
		+timestamp,
		
		// page likes:
		+likes,
		
		count_punctuations(msg),
		
		
	];
	
	// hashtags, taggings, words & lines count
	var signs = ["#", "@", " ","\n"];
	for ( i=0;i<signs.length; i++)
		ans.push(msg.split(signs[i]).length-1);
	
	return ans;

}


function countURL(msg){
	 return countRegex(msg,
	 	/([a-zA-Z0-9]+:\/\/)?([a-zA-Z0-9_]+:[a-zA-Z0-9_]+@)?([a-zA-Z0-9.-]+\\.[A-Za-z]{2,4})(:[0-9]+)?(\/.*)?/g
	 );
}

function countNumbers(msg){
	 return countRegex(msg, /[0-9]+/g);
}

function countEmail(msg){
	 return countRegex(msg, /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/g);
}

function countRegex(msg, reg){
	return (msg.match( reg ) || []).length;;
}


function count_punctuations(s) {
  	return (s.match( /[^\.!\?]+[\.!\?(?="|')]+(\s|$)/g ) || []).length;
}
