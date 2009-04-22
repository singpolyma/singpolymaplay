<?php


    function is_value($string) {
        if (is_numeric($string) && $string>0) return true;
        return false;
    }

    function is_beginning($string) {
        if (is_numeric($string) && $string<0) return true;
        return false;
    }

    function is_end($string) {
        global $range2;
        $size=$last=strlen($string)-1;
        $range2=substr($string,0,$size);
        if ($string[$last] == '-' && is_value($range2)) return true;
        return false;
    }

    function is_mid($string) {
        $index = explode('-', $string);
        if (count($index) == 2 && is_value($index[0]) && is_value($index[1])) return true;
        return false;
    }

header("Content-type: text/plain");

$silentmode = $_GET["silentmode"];
$phrase = $_GET["phrase"];
    $phrase = trim($phrase);
    $words = explode(' ',$phrase);
    $inquot = -1;
    foreach($words as $id => $word) {
       if($word{0} == '"' && $inquot == -1) {
          $inquot = $id;
          $words[$inquot] = substr($word,1,strlen($word));
          continue;
       }//end if word{0} == "
       if($word{strlen($word)-1} == '"' && $word{strlen($word)-2} != "\\" && $inquot != -1) {
          $words[$inquot] .= ' '.substr($word,0,strlen($word)-1);
          unset($words[$id]);
          $inquot = -1;
          continue;
       }//end if lastchar == "
       if($inquot != -1) {
          $words[$inquot] .= ' '.$word;
          unset($words[$id]);
          continue;
       }//end if inquot != -1
    }//end foreach
    $words = array_values($words);
    foreach($words as $id => $word)
       $words[$id] = str_replace('\"','"',$word);
    $range = $words[0];
    $range2;




    // 4
    if ( is_value($range) ){
        echo $words[$range];
        return true;
    }
    // -4
    if (is_beginning($range)){
        for($i=1;($i<=abs($range) && $i<=count($words));$i++){
            echo $words[$i].' ';
        }
        return true;
    }
    // 4-
    if (is_end($range)){
        for($i=$range2;$i<=count($words);$i++){
            echo $words[$i].' ';
        }
        return true;
    }
    // 2-4
    if (is_mid($range)){
        $range = explode('-', $range);
        for($i=$range[0];($i<=$range[1] && $i<=count($words));$i++){
            echo $words[$i].' ';
        }
        return true;
    }

    if (!$silentmode) echo 'Error!, or you are using a YubNub command incorrectly.';
    return false;

?>