<html>

	<head>
		<title>
			Words Sorter
		</title>
		<style>
			*{
				direction: rtl;
			}
			
			input, form{
				display: inline-block;
				width: 150px;
				padding: 15px;
				text-align: center;
			}
		</style>
	</head>
	
	<body>
	
	<?php
	if($_GET['word'] && $_GET['choise']){
		$arr = file($_GET['choise']);
		if(!in_array($_GET['word'], $arr)){
			$myfile = fopen($_GET['choise'], "a") or die("Unable to open file!");
			$txt = $_GET['word'];
			fwrite($myfile, "\n". $txt);
			fclose($myfile);
		}
	}
	
	
	
	
	require_once "../crawler/SQL_login.php";
	$query = "SELECT * 
		FROM  `project_posts` 
		LIMIT ". rand(1, 98000) ." , 1";
	$result = mysql_query($query);
	$post = mysql_fetch_array($result);
	$words = preg_split("/[\s,.\-\!\?\"\)\(@&#0-9]+/", $post['message']);
	$count = 0;
	do{
		$word = $words[rand(0, count($words)-1)];
	}while(is_numeric($word) || empty($word) || strlen($word)<3 || $count++>1000);
	
	?>
	
	<h1><?=$word?></h1>
	<?php
		$filenames = file("../words/list-of-files.txt");
		foreach ($filenames as $filename){
	?>
			<form>
				<input type = "hidden" name="word" value = "<?=$word ?>"/>
				<input type = "hidden" name="choise" value = "<?=explode(" ", trim($filename))[0] ?>"/>
				<input type = "submit" value = "<?=str_replace("-", " ", explode(" ", trim($filename))[1]) ?>"/>
			</form>
	<?php
		}
	?>
	<FORM>
	<a href = "http://noam-gaash.co.il/cs/project/words/wordSorter.php"><INPUT TYPE="button" VALUE="נקסט!"></a>
	</FORM>
<p>חשבת על מילה יותר מ3 שניות? דלג עליה! אנחנו רוצים רק מילים ברורות מאליהן</p>
<p>אם זה נתקע - אפשר לחוץ על "נקסט" וזה אמור לעזור (או שלא).</p>
<p>אם יש מילה שלא מתאימה לאף קטגוריה - לחצו על אחר, וננסה למצוא לה קטגוריה.</p>
<p>אם יש מילה חסרת אופי ומפגרת (כמו המילה "צילום", טקסט בג'יבריש, או "חברת") אפשר ללחוץ על כפתור "נקסט" ולעבור למילה אחרת.</p>
	
	</body>
	
</html>