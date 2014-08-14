<?php 

header('Content-Type: text/html; charset=utf-8');

function prime($a,$b){
	for(;$a<$b;$a++){
		if ($a<2) continue;
		$flag = true;
		for($j=2;$j<$a;$j++){
			if ($a%$j==0){ 
				$flag = false;
				break;
			}
		}
		if ($flag==true) $arr[] = $a;
    }
	return $arr;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$result = prime($_POST['one'],$_POST['two']);	
}
?>

  <style>
  .ar { 
	margin-left:5p;
  }
  .prime{
	padding:5px;
	display:inline-block;
	border:1px solid #c3c;
	background:#3cc;
  }
  </style>
  
 <div class="prime">
	<h2>Prime number</h2>
	<form method="post">
		<input type="text" name="one" size="7">
		<input type="text" name="two" size="7"><br>
		<input type="submit"><input type="reset">
		<div class="ov">
		<?php if(isset($result)){?>
			<?php foreach($result as $ar){?>
				<span class="ar"><i><?php echo $ar;?></i></span>
			<?php } ?>
		<?php } ?>
		</div>
	</form>
 </div>
