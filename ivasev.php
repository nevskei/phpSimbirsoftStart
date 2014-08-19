<?php
setLocale(LC_ALL, 'ru_RU.utf8');
class FileWordReader
{
   public $endWord = 0;
   public $selectWord = "";
   public $seporetWord = " ";
   public $inputFiel = "/text/inputText.txt";
   public function __construct($seporet = " ", $word = "", $index = 0)
   {
      $this->seporetWord = $seporet;
      $this->selectWord = $word;
      $this->endWord = $index;
   }
   public function current()
   {
       return $this->selectWord;
   }
   public function next()
   {
      $openInputFiel = fopen(__DIR__.$this->inputFiel, 'rb');
      if(flock($openInputFiel, LOCK_EX))
      {
         $index = 0;
         $symbol = "";
         $word = "";
         while(!feof($openInputFiel))
         {
            $index++;
            if($index > $this->endWord)
            {
               $byte = fread($openInputFiel, 1);
	       $symbol .= $byte;
	       if($byte > 0x7F)
	       {
                  $symbol .= fread($openInputFiel, 1);
	       }
	       if($symbol == $this->seporetWord && !empty($word))
	       {
	          break;
	       }
	       else if($symbol != $this->seporetWord)
	       {
	          $word .= $symbol;
	          $symbol = "";
	       }
            }
            else
               fread($openInputFiel, 1);
         }
	          $this->endWord = $index;
	          $this->selectWord = $word;
                  return $this->current();
         flock($openInputFiel, LOCK_UN);
      }
   fclose($openInputFiel);
   }
   public function reset()
   {
      $this->endWord = 0;
      return $this->next();
   }
}
$_POST['SEPORAIT'] = (isset($_POST['SEPORAIT']))?$_POST['SEPORAIT']:" ";
echo'
<!DOCTYPE html>
<html>
   <head><meta charset="utf-8"></head>
   <body><p>';
$fileWordReader = new FileWordReader($_POST['SEPORAIT'], $_POST['WORD'], $_POST['INDEX']);
if(isset($_POST['LASTCHANGE']) && !empty($_POST['LASTCHANGE']) && filemtime(__DIR__.$fileWordReader->inputFiel) > $_POST['LASTCHANGE'])
{
   unset($_POST);
   echo '<p style="color:red">Файл был изменен</p>';
}
if(isset($_POST['CURRENT']))
   echo 'Текущее слово из файла: '.$fileWordReader->current();
else if(isset($_POST['NEXT']))
   echo 'Следующее слово из файла: '.$fileWordReader->next();
else
{
   echo 'Указатель на первом слове: '.$fileWordReader->reset();
}
echo'</p>
   <form method="post">
      <input type="submit" name="CURRENT" value="Current">
      <input type="submit" name="NEXT" value="Next">
      <input type="submit" name="RESET" value="Reset">
      <input type="hidden" name="WORD" value="'.$fileWordReader->current().'">
      <input type="hidden" name="SEPORAIT" value=" ">
      <input type="hidden" name="LASTCHANGE" value="'.filemtime(__DIR__.$fileWordReader->inputFiel).'">
      <input type="hidden" name="INDEX" value="'.$fileWordReader->endWord.'">
   </form>
   </body>
</html>';
?>