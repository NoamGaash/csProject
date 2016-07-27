<?php

require_once "crawler/SQL_login.php";
if($debug) echo "SELECT * FROM project_likes\n";
//$resultlikes = mysql_query('SELECT * FROM project_likes');

$query = "SELECT T.`id` , T.likes, COUNT( DISTINCT  `project_comments`.`id` ) AS comments
FROM (

SELECT  `project_posts`.`id` , COUNT( DISTINCT  `project_likes`.`user` ) AS likes, `project_posts`.`created_time` 
FROM  `project_likes` 
INNER JOIN  `project_posts` ON  `project_posts`.`id` =  `project_likes`.`post` 
GROUP BY  `project_posts`.`id`
) AS T
INNER JOIN  `project_comments` ON T.`id` =  `project_comments`.`post` 
GROUP BY T.`id` 
ORDER BY T.`created_time` DESC ";

$result = mysql_query($query) or trigger_error(mysql_error()." ".$query);

$dataset = [];

while($post = mysql_fetch_array($result)){
	$vector = [];
	array_push(
		$vector,
		//$post[0],	// uncomment for debugging (let you see which post gets what values)
		log(intval($post['likes'])),
		log(intval($post['comments']))
		
	);
	/*$expectedVals=[
		intval($post["COUNT(  `project_likes`.`user` )"])	// count of likes
	];*/
	//array_push($dataset, [$vector,$expectedVals]);
	//array_push($dataset, $vector);
	$dataset[$post[0]] = $vector;
}

echo json_encode ($dataset);



	
/************

*/