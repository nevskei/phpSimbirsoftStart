<?php 
$from = "";
$to = "";
$error = "";
if(isset($_POST["FROM"]) && strspn($_POST["FROM"], "-1234567890") == strlen($_POST["FROM"]))
{
	$from = (int)$_POST["FROM"];
}
else if(isset($_POST["FROM"]) && strspn($_POST["FROM"], "-1234567890") < strlen($_POST["FROM"]))
{
	$error .="� ���� '��' ������ ���� ������� ������ ����� <br/>";
}
if(isset($_POST["TO"]) && strspn($_POST["TO"], "-1234567890") == strlen($_POST["TO"]))
{
	$to = (int)$_POST["TO"];
}
else if(isset($_POST["TO"]) && strspn($_POST["TO"], "-1234567890") < strlen($_POST["TO"]))
{
	$error .="� ���� '��' ������ ���� ������� ������ �����  <br/>";
}
if(empty($error) && $from > $to) 
	$error .= "'��' ������ ���� ������  '��'";
?>
<form method='post'>
	<table>
	<tr>
		<td>
			��
		</td>
		<td>
			<input type='text' name='FROM' value="<?php echo $from?>">
		</td>
	</tr>
	<tr>
		<td>
			��
		</td>
		<td>
			<input type='text' name='TO' value="<?php echo $to?>">
		</td>
	</tr>
	<tr>
		<td>
		</td>
		<td>
			<input type='submit' name="Submit">
		</td>
	</tr>
	</table>
</form>
<div style="color:green;">
<?php 
	if(empty($error))
		for($i = ($from%2==1||$from%2==-1)?$from:$from+1; $i <= $to; $i+=2)
		{
			if($i > $from+1)
				echo ", ";
			else
				echo "������������������ �������� ����� ���������� �� {$from} �� {$to}: <br/>";
			echo $i;
		}
	else
		echo '<p style="color:red;">'.$error.'</p>'
?>
</div>
<?php
$users = array(array("name" => "������ ���������", "about" => "���: ����� '14<br/>���������: �������������� ������ � ����������<br/>�������: �������������� �������<br/>����� ��������: ������� ���������<br/>������: �������<br/>", "photo"=>"photo/user1.jpg"));
echo"<table>";
foreach($users as $value)
	echo"<tr><td><img width=200 src='{$value['photo']}'></td><td><p>{$value['name']}</p><p>{$value['about']}</p></td></tr>";
echo"</table>";
?>