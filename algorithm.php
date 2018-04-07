<?php
	
	/*
	*	Just to avoid unfixed warnings
	*/
	error_reporting(E_ERROR | E_PARSE);

	/**
	 * Creates the graph of possible words 
	 *
	 * @param array $graph current leaf of the graph
	 * @param string $startWord current starting word
	 * @param integer $wordLength target string Length
	 * @param array $letter array with all the alphabetical letters
	 * @param array $dictionary array with all the words of the dictionary
	 * @param array $pos possitions of the word that are already being checked
	 *
	 * @author Gonzalo Redondo <gonzalo.redondomunoz@gmail.com>
	 * @return array current graph
	 */ 
	function checkNextWord ($graph, $startWord, $wordLength, $letters, $dictionary, $pos)
	{
		$graphAux = array();
		$word = $startWord;		
		for ($i = 0;  $i < $wordLength ; $i++)
		{
			if (in_array($i, $pos))
			{
				continue;
			}
			foreach ($letters as $letter) 
			{
				$word[$i] = $letter;
				//echo $word."</br>";
				if (array_key_exists($word, $dictionary)){
					$pos[] = $i;
					$graphAux[$word] = checkNextWord ($graph[$word], $word, $wordLength, $letters, $dictionary, $pos);					
				}
			}
		}
		return $graphAux;
	}

	/**
	 * Checks if there is a solution for the game proposed
	 *
	 * @param array $graph current leaf of the graph
	 * @param string $endWord target word to be checked
	 * @param string Upper string
	 *
	 * @author Gonzalo Redondo <gonzalo.redondomunoz@gmail.com>
	 * @return string solution or empty
	 */
	function checkSolution($graph, $endWord, $upperParent){
		if (empty($graph))
		{
			if($graph == $endWord)
			{
				return $parent;
			} else {
				return false;
			}
		}
		foreach($graph as $leaf)
		{
			$parent = key($graph);
			$partial = ";". $parent;
			$concat = $upperParent . $partial;
			if($parent == $endWord)
			{
				return $concat;
			} else 
			{
				$result = checkSolution($leaf, $endWord, $concat);
				if($result != false)
				{
					return $result;
				}
			}
			$concat = $upperParent;
			next($graph);
		}
		return $result;
	}	

	/*
	*	Call to serveGame, getting game rules and idContest
	*/
	$type = $_GET["type"];
	$url = "localhost/zankyou/newGame.php?type=".$type."";
	$ch = curl_init();
	
	$optArray = array(
	    CURLOPT_URL => $url,
	    CURLOPT_RETURNTRANSFER => true
	);
	curl_setopt_array($ch, $optArray);
	$result = curl_exec($ch);
	$game = json_decode($result, true);
	$contestId = $game["contestId"];
	$startWord = $game["startWord"];
	$endWord = $game["endWord"];

	/*
	*	Setting up the dictionary to be used in algorithm
	*/
	$language = $_GET["lang"];
	$dictionary_in ="words_dictionary.json";
	$dictionary_json = file_get_contents("dictionary/".$language."/".$dictionary_in);
	$dictionary = json_decode( $dictionary_json, true);
	

	/*
	*------------------------------
	*	Algorithm
	*------------------------------
	*/
	
	/*
	*	Initialize the vars
	*/
	echo "\n";
	$wordLength = strlen($startWord);
	$letters = range("a", "z");
	$graph = array();

	/*
	*	Start the algorithm
	*/
	$graph = checkNextWord($graph, $startWord, $wordLength, $letters, $dictionary,array());
	$wordResult = checkSolution($graph, $endWord, "");

	/*
	*	Checking the results
	*/

	if ($wordResult == '')
	{
		$gameWordArray = "There's no solution";
	} 
	else 
	{
		$wordResult = substr($wordResult, 1);
		$gameWordArray = explode(";", $wordResult);
	}
	$response = array(
		"contestId" => $contestId,
		"userId" => rand(1, 999999),
		"solution" => $gameWordArray
		);
	print("<pre>".print_r($gameResultArray, true)."<pre>");

	echo json_encode($response);
?>

