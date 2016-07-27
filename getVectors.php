<?php

require_once "crawler/SQL_login.php";
if($debug) echo "SELECT * FROM project_likes\n";
//$resultlikes = mysql_query('SELECT * FROM project_likes');

$query = "SELECT  `project_posts`.`id` ,  `project_posts`.`page_id` ,  `project_posts`.`message` , `project_posts`.`created_time` , COUNT(  `project_likes`.`user` ) ,  `project_pages`. * 
FROM  `project_likes` 
INNER JOIN  `project_posts` ON  `project_posts`.`id` =  `project_likes`.`post` 
INNER JOIN  `project_pages` ON  `project_posts`.`page_id` =  `project_pages`.`id` 
GROUP BY  `project_posts`.`id` 
ORDER BY  `project_posts`.`created_time` DESC ";

$result = mysql_query($query);

$dataset = [];

while($post = mysql_fetch_array($result)){
	$vector = [];
	array_push(
		$vector,
		// $post[0],						// uncomment for debugging (let you see which post gets what values)
		strlen($post['message']),				// post length
		strlen($post['name']),					// page name length
		substr_count($post['message'], "#"),			// hashtags count
		substr_count($post['message'], "@"),			// taggings count
		countHappySmiles($post['message']),			// happy smiles count
		countSadSmiles($post['message']),			// happy smiles count
		substr_count($post['message'], " "),			//number of words
		substr_count($post['message'], "\n"),			//number of lines
		hasURL($post['message']),				// has url inside of the message
		strtotime($post['created_time']) %(24*60*60), 		// timestamp representing the time in the day when the post was published
		strtotime($post['created_time']),	 		// timestamp representing the date and time when the post was published
		intval($post['likes']),    				// likes to the PAGE were the post was published
		callToActionCount($post['message']),
		count_capitals($post['message']),
		count_punctuations($post['message'])
	
	);
	//$expectedVals=[
	//	intval($post["COUNT(  `project_likes`.`user` )"])	// count of likes
	//];
	//array_push($dataset, [$vector,$expectedVals]);
	//array_push($dataset, $vector);
	$dataset[$post[0]] = $vector;
}

function callToActionCount($msg){
	return preg_match('/share/',$msg)+
		preg_match('/שתפו/',$msg)+
		preg_match('/free/',$msg)+
		preg_match('/למסירה/',$msg)+
		preg_match('/חינם/',$msg)+
		preg_match('/win/',$msg)+
		preg_match('/האם אהבתם/',$msg);
}

function count_capitals($s) {
  	return strlen(preg_replace('![^A-Z]+!', '', $s));
}
function count_punctuations($s) {
  	return preg_match_all('/[[:punct:]]/', $s);
	
}

function countHappySmiles($mgs){
	return substr_count($mgs, "(:")
			+ substr_count($mgs, ":)")
			+ substr_count($mgs, ":-)")
			+ substr_count($mgs, "(-:")
			+ substr_count($mgs, ":D")
			+ substr_count($mgs, "P:")
			+ substr_count($mgs, ":P");
}
	
function countSadSmiles($mgs){
	return substr_count($mgs, "):")
			+ substr_count($mgs, ":(")
			+ substr_count($mgs, "D:")
			+ substr_count($mgs, ":/")
			+ substr_count($mgs, "/:")
			+ substr_count($mgs, ")-:")
			+ substr_count($mgs, ":-(");
}


function hasURL($msg){
	$urlRegex = '#([a-zA-Z0-9]+://)?([a-zA-Z0-9_]+:[a-zA-Z0-9_]+@)?([a-zA-Z0-9.-]+\\.[A-Za-z]{2,4})(:[0-9]+)?(/.*)?#';
	return preg_match_all($urlRegex, $msg);
}











echo json_encode ($dataset);


	
	
/************
Array
(
    [0] => 104905349600750_992332780857998
    [id] => 104905349600750
    [1] => 104905349600750
    [page_id] => 104905349600750
    [2] => #מחנה_מתוק

ההרשמה למחנה הקיץ החלה!!
אנא שמרו את מקומכם בהקדם!
http://www.jdrf.org.il/#!--2015/cp2b
    [message] => #מחנה_מתוק

ההרשמה למחנה הקיץ החלה!!
אנא שמרו את מקומכם בהקדם!
http://www.jdrf.org.il/#!--2015/cp2b
    [3] => 2016-05-01
    [created_time] => 2016-05-01
    [4] => 13
    [COUNT(  `project_likes`.`user` )] => 13
    [5] => 104905349600750
    [6] => האגודה לסוכרת נעורים
    [name] => האגודה לסוכרת נעורים
    [7] => 4018
    [likes] => 4018
    [8] => האגודה לסוכרת נעורים (סוג 1) בישראל הינה נציגות JDRF העולמי – ומשימתה מציאת מרפא לסוכרת סוג 1 וסיבוכיה ע&quot;י תמיכה במחקר.

    [about] => האגודה לסוכרת נעורים (סוג 1) בישראל הינה נציגות JDRF העולמי – ומשימתה מציאת מרפא לסוכרת סוג 1 וסיבוכיה ע&quot;י תמיכה במחקר.

    [9] => 2014-02-18
    [created] => 2014-02-18
)*/