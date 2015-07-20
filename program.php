<?php
$rustart = getrusage();
$fileName = $argv[1];
if(empty($fileName)) {
	error_log("usage: Please pass the source file as parameter 1");
	exit();	
} 

$file = fopen($fileName, "r");
$fullWordsFile = fopen("fullwords.txt", "w");
$uniquesFile = fopen("uniques.txt", "w");

$uniques = array();
$fullWords = array();
$duplicates = array(); 

if($file) {
    while (($line = fgets($file)) !== false) {
	$word = trim($line); 
    $stringSize = strlen($word);
	// find uniques
    if($stringSize >= 4) {
	    $maxPosition = $stringSize - 4; 
	    for($start = 0; $start <= $maxPosition; $start++) {
	        $snip = substr($word, $start, 4);
			if(isset($uniques[$snip])) {			
				unset($uniques[$snip]);			
			} else {	    
			    $uniques[$snip] = $word;				
			}
		    //error_log("snip = " . $snip); 
		} 
    }
}
    
ksort($uniques);
foreach($uniques as $key => $value) {
	fwrite($uniquesFile, $key."\n");
	fwrite($fullWordsFile, $value."\n");
}


fclose($file);
fclose($fullWordsFile);
fclose($uniquesFile);
} else {
    error_log("Counldn't open file. Please try again.");
} 


function rutime($ru, $rus, $index) {
    return ($ru["ru_$index.tv_sec"]*1000 + intval($ru["ru_$index.tv_usec"]/1000))
     -  ($rus["ru_$index.tv_sec"]*1000 + intval($rus["ru_$index.tv_usec"]/1000));
}

$ru = getrusage();
echo "This process used " . rutime($ru, $rustart, "utime") .
    " ms for its computations\n";
echo "It spent " . rutime($ru, $rustart, "stime") .
    " ms in system calls\n"; 
