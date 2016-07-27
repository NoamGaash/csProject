<?php

$filenames = file("../words/list-of-files.txt");
$words=[];

for($i=0; $i<count($filenames); $i++)
	$filenames[$i] = explode(" ", $filenames[$i])[0];

foreach ($filenames as $filename){
	$words[$filename] = file("../words/" . trim($filename));
	
	$wordsCount = count($words[$filename]);
	
	for($i=0; $i<$wordsCount; $i++){
		$words[$filename][$i] = explode(
			" ",
			trim(
				mb_convert_encoding(
					$words[$filename][$i],
					"UTF-8",
					"auto"
				)
			)
		)[0];
	}
	$words[$filename] = array_unique($words[$filename]);
}

function getWordsStats($post){
	global $filenames, $words;
	
	$ans = [];
	foreach ($filenames as $filename){
		$count = 0;
		
		foreach ($words[$filename] as $word){
			if(strlen($word) > 1)
				$count+=substr_count($post['message'], $word);
		}
		array_push($ans, $count);
	}
	
	return $ans;
}
