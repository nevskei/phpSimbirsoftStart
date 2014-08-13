<?php
$error = false; // по умолчанию ошибок нет
$value_from = ""; // значение 'От'
$value_to = ""; // значение 'До'


switch ($_POST['action']) {
    case 'odd_range':

        // приводим полученные переменный в нормальный вид
        $value_from = $_POST["from"];
        $value_to = $_POST["to"];

        if (empty($value_from)) //если пустое
            $error .= "Поле 'От' не может быть пустым <br/>";
        else if (!is_numeric($value_from)) // если не пустое, но не число
            $error .= "В поле 'От' должны быть введены только цифры <br/>";

        if (empty($value_to))
            $error .= "Поле 'До' не может быть пустым <br/>";
        else if (!is_numeric($value_to))
            $error .= "В поле 'До' должны быть введены только цифры <br/>";

        if (($value_from < -2147483648) || ($value_from > 2147483647))
            $error .= "Введеное значени 'От' превышает допустимый лимит <br/>";
        if (($value_to < -2147483648) || ($value_to > 2147483647))
            $error .= "Введеное значени 'До' превышает допустимый лимит <br/>";

        if (!$error && ($value_from > $value_to)) // если нет ошибок, но диапазон неправильный
            $error .= "'До' должно быть больше  'От'";
        break;
}
?>
<!DOCTYPE html>
<html>
<head>Задание по курсам SimbirSoft</head>
<body style="background-image: url(img/background.jpg); background-repeat: repeat-y; color: white">
<form method='POST'>
    <table>
        <tr>
            <td>
                От
            </td>
            <td>
                <input type="text" name="from" value="<?= $value_from ?>">
            </td>
        </tr>
        <tr>
            <td>
                До
            </td>
            <td>
                <input type="text" name="to" value="<?= $value_to ?>">
            </td>
        </tr>
        <tr>
            <td>
            </td>
            <td>
                <input type="hidden" name="action" value="odd_range">
                <input type='submit' name="Submit">
            </td>
        </tr>
    </table>
</form>
<div style="color:green;">
    <?php
    if (!$error && $_POST['action']) { // если ошибок нет
        if (!($value_from % 2)) // если число 'От' четное,
            $value_from++; // то ++, чтобы сделать стартовой точкой для цикла
        echo "Последовательность нечетных чисел в диапазоне от {$_POST['from']} до {$_POST['to']}: <br/>";
        for ($i = $value_from; $i <= $value_to; $i += 2) { // цикл "через 2"
            echo $i . " "; // запись значений в переменную
        }

    } else
        echo '<p style="color:red;">' . $error . '</p>'
    ?>
</div>
<?php
$users = array(array("name" => "Ивасев Александр", "about" => "ВУЗ: УлГТУ '14<br/>Факультет: Информационных систем и технологий<br/>Кафедра: Вычислительной техники<br/>Форма обучения: Дневное отделение<br/>Статус: Магистр<br/>", "photo" => "img/photo/user1.jpg"),
               array("name" => "Морозов Роман", "about" => "Окончил УлГТУ, ФИСТ. Работаю Веб-программистом. Играю на гитаре, занимаюсь спортом, выращиваю рыбок =)", "photo" => "img/photo/user2.jpg"),
               array("name" => "Тарыка Денис", "about" => "Окончил УлУ, ФМиИТ. Мое главное увлечение - музыка. Мечтаю стать как Диего Стокко - играть на всех инструментах. Также увлекаюсь спортивными мероприятиями, такими как лыжи, бассейн.", "photo" => "img/photo/user3.jpg")
        );

echo "<table>";
foreach ($users as $value)
    echo "<tr><td><img width=200 src='{$value['photo']}'></td><td  style='background-color: dimgray'><p>{$value['name']}</p><p>{$value['about']}</p></td></tr>";
echo "</table>";
?>

</body>
</html>
