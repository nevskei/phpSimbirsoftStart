<?php 
echo'
<!DOCTYPE html>
<html>
   <head><meta charset="utf-8"></head>
   <body>';
$statFile = __DIR__."/text/stat.txt";
$numbFile = __DIR__."/text/numb.txt";
$freqFile = __DIR__."/text/freq.txt";
$freqFileOut = __DIR__."/text/freq_out.txt";
if(!file_exists($statFile))
{
   $stat = 1;
   $statText = fopen($statFile, 'xb');
   if(flock($statText, LOCK_EX))
   {
      fwrite($statText, '1');
      flock($statText, LOCK_UN);
   }
   fclose($statText);
}
else
{
   $statText = fopen($statFile, 'rb');
   if(flock($statText, LOCK_EX))
   {
      $stat = "";
      while(!feof($statText))
      {
         $stat .= fread($statText, 5);
      }
      flock($statText, LOCK_UN);
   }
   fclose($statText);
   $stat++;
   $statText = fopen($statFile, 'wb');
   if(flock($statText, LOCK_EX))
   {
      fwrite($statText, $stat);
      flock($statText, LOCK_UN);
   }
   fclose($statText);
}
echo "Счетчик посещений: {$stat}</br>";
$resNumbFile = file($numbFile);
function onlyNumb($line)
{
   return (strspn($line, "1234567890") == strlen($line));
}
$resNumbFile = array_map('trim', $resNumbFile);
$resNumbFile = array_filter($resNumbFile, "onlyNumb");
echo "Строки из входного файла, состоящие из цифр:<br>";
foreach($resNumbFile as $line)
   echo $line."<br>";

function selectionWords($line)
{
   $line = explode(" ",$line);
   $line = array_map('trimSeparator', $line);
   $line = array_filter($line, "noEmpy");
   return $line;
}
function noEmpy($var)
{
   return !empty($var);
}
function trimSeparator($var)
{
   return trim($var, ";:!,.<>?/\\ \t\n\r\0\x08");
}
$freqArray = array();
$freqText = "";
$lineArray = file($freqFile);
$lineArray = array_map('selectionWords', $lineArray);
foreach($lineArray as $line)
   foreach($line as $word)
      if(isset($freqArray[strtolower($word)]))
         $freqArray[strtolower($word)]++;
      else
         $freqArray[strtolower($word)]=1;
echo"<table><tr><td>Слово</td><td>Частота</td><tr>";
foreach($freqArray as $word=>$freq)
{
   echo "<tr><td>{$word}</td><td>{$freq}</td><tr>";
   $freqText .= $word.":".$freq."\n";
}
echo"</table>";
$resFreqFileOut = fopen($freqFileOut, 'wb');
   if(flock($resFreqFileOut, LOCK_EX))
   {
      fwrite($resFreqFileOut, $freqText);
      flock($resFreqFileOut, LOCK_UN);
   }
   fclose($resFreqFileOut);
echo '
   </body>
</html>';
?>