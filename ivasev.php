<?php
echo '
<!DOCTYPE html>
<html>
   <head><meta charset="utf-8"></head>
   <body>';

define('STAT_FILE', __DIR__ . "/text/stat.txt");
define('NUMB_FILE', __DIR__ . "/text/numb.txt");
define('FREQ_FILE', __DIR__ . "/text/freq.txt");
define('FREQ_FILE_OUT', __DIR__ . "/text/freq_out.txt");

define('ERROR_LOCK_FILE', "Failed to lock the file!\n");
define('ERROR_OPEN_FILE', "Failed to open the file!\n");


function write_result($filename, $stat, $mode)
{
    $statText = fopen($filename, $mode);

    if ($statText == false)
        return ERROR_OPEN_FILE;

    if (flock($statText, LOCK_EX)) {
        fwrite($statText, $stat);
        flock($statText, LOCK_UN);
    } else
        return ERROR_LOCK_FILE;

    fclose($statText);
    return $stat;
}

function count_visit()
{
    if (!file_exists(STAT_FILE)) {
        $stat = 1;
        return write_result(STAT_FILE, $stat, 'xb');
    }

    $stat = "";
    $statText = fopen(STAT_FILE, 'rb');
    if ($statText == false)
        return ERROR_OPEN_FILE;

    if (flock($statText, LOCK_EX)) {
        while (!feof($statText)) {
            $stat .= fread($statText, 5);
        }
        flock($statText, LOCK_UN);
        $stat++;
    } else
        return ERROR_LOCK_FILE;
    fclose($statText);

    if ($stat != "") {
        return write_result(STAT_FILE, $stat, 'wb');
    }

}

function onlyNumb($line)
{
    return (is_numeric($line));
}

function selectionWords($line)
{
    $line = explode(" ", $line);
    $line = array_map('trimSeparator', $line);
    $line = array_filter($line, "noEmpty");
    return $line;
}

function noEmpty($var)
{
    return !empty($var);
}

function trimSeparator($var)
{
    return trim($var, ";:!,.<>?/\\ \t\n\r\0\x08");
}


$result = count_visit();
echo "Счетчик посещений: " . $result . "</br>";

$resNumbFile = file(NUMB_FILE);
$resNumbFile = array_map('trim', $resNumbFile);
$resNumbFile = array_filter($resNumbFile, "onlyNumb");
echo "<br>Строки из входного файла, состоящие из цифр:<br>";
foreach ($resNumbFile as $line)
    echo $line . "<br>";


$freqArray = array();
$freqText = "";
$lineArray = file(FREQ_FILE);
$lineArray = array_map('selectionWords', $lineArray);
foreach ($lineArray as $line)
    foreach ($line as $word)
        if (isset($freqArray[strtolower($word)]))
            $freqArray[strtolower($word)]++;
        else
            $freqArray[strtolower($word)] = 1;
echo "<br><table><tr><td>Слово</td><td>Частота</td><tr>";
foreach ($freqArray as $word => $freq) {
    echo "<tr><td>{$word}</td><td>{$freq}</td><tr>";
    $freqText .= $word . ":" . $freq . "\n";
}
echo "</table>";
write_result(FREQ_FILE_OUT, $freqText, 'wb');

echo '
   </body>
</html>';
?>