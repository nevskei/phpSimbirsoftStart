<?php
if ($_POST['action'] == 'generate_word') {

    $symbols_word = $_POST['symbols'];
    $length_word = $_POST['length'];
    $count_word = $_POST['count'];


    $symbols_word = iconv("UTF-8", "windows-1251", $symbols_word);
    $count_symbols = strlen($symbols_word);
    for ($j = 0; $j < $count_word; $j++) {
        $temp_str = '';
        for ($i = 0; $i < $length_word; $i++) {
            $temp_str .= $symbols_word[rand(0, $count_symbols - 1)];
        }
        $temp_str = iconv("windows-1251", "UTF-8", $temp_str);
        echo "<b>".($j+1)."</b> ".$temp_str . "</br>";
    }
    exit();
}
?>
    <!DOCTYPE html>
    <html>
    <head>
        <script src="js/jquery-1.11.1.min.js"></script>
    </head>
    <body>
    <div>
        <table>
            <tr>
                <td>
                    <form method="POST" action="">

                        Символы:<br>
                        <input type="text" size="100" id="chars"
                               value="1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz"><br>
                        Варианты:
                        <script>
                            function Check() {
                                $('#chars')[0].value = '';
                                if ($('#Csigns')[0].checked) $('#chars')[0].value += '!";%:?*()_+=-~/<>,.[]{}';
                                if ($('#Cnum')[0].checked) $('#chars')[0].value += '1234567890';
                                if ($('#Cbig')[0].checked) $('#chars')[0].value += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                                if ($('#Csmall')[0].checked) $('#chars')[0].value += 'abcdefghijklmnopqrstuvwxyz';
                            }

                            function send(act) {
                                $.ajax({
                                    type: 'POST',
                                    url: 'generate_words.php?',
                                    data: 'action=' + act + '&symbols=' + $('#chars')[0].value + '&length=' + $('#length')[0].value + '&count=' + $('#count')[0].value,
                                    success: function (data) {
                                        $('#results').html(data);
                                    }
                                });
                            }

                        </script>
                        <br><input type="checkbox" id="Csmall" onclick="Check();" checked="">Маленькие буквы
                        <br><input type="checkbox" id="Cbig" onclick="Check();" checked="">Заглавные буквы
                        <br><input type="checkbox" id="Cnum" onclick="Check();" checked="">Цифры
                        <br><input type="checkbox" id="Csigns" onclick="Check();">Знаки
                        <br>
                        <label>Длина слова:
                            <input type="text" id="length" size="5" value="10"></label><br>
                        <label>
                            Количество слов:
                            <input type="text" id="count" size="5" value="10"></label><br><br>
                        <br>
                        <input type="button" value="Создать слово!" onclick="send('generate_word')">
                        </p></form>
                </td>
            </tr>
        </table>
        <div id="results"></div>
    </div>
    <body>
    </html>
<?php
?>