<?php
require_once "../crawler/SQL_login.php";
require_once "metadata.php";
require_once "wordsStats.php";


$query = "SELECT  `project_posts`.`id` AS post_id,  `project_posts`.`page_id` , `project_posts`.`message` ,  `project_posts`.`created_time` ,  `project_pages`. * 
FROM  `project_posts` 
INNER JOIN  `project_pages` ON  `project_posts`.`page_id` =  `project_pages`.`id` 
WHERE  `project_posts`.`id` 
IN (
	SELECT  `post` 
	FROM  `project_likes`
)
AND  `project_posts`.`id` 
IN (
	SELECT  `post` 
	FROM  `project_comments`
)";

$result = mysql_query($query) or trigger_error(mysql_error()." ".$query);


$ans = [];
$count=0;
while($post = mysql_fetch_array($result)){
	$meta = getMetadata($post);
	$wordsStats = getWordsStats($post);

	$ans[$post['post_id']] = array_merge($meta, $wordsStats);
}


print_r(json_encode($ans));
