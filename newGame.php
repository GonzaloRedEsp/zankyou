<?php

	$type = $_GET["type"];

	switch ($type) {
		case 1:
			$array = array("contestId" => 121, "startWord" => "dog", "endWord" => "put");
			break;
		case 2:
			$array = array("contestId" => 121, "startWord" => "book", "endWord" => "hock");
			break;
		case 3:
			$array = array("contestId" => 121, "startWord" => "sit", "endWord" => "log");
			break;
		case 4:
			$array = array("contestId" => 121, "startWord" => "love", "endWord" => "paid");
			break;
		default:
			break;
	}
	$jsonArray = json_encode($array);
	print_r($jsonArray);

?>

