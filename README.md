SimbirSoft Symfony Demo
=======================

1) Инсталляция
--------------

Для начала клонируем репозиторий:

    git clone https://github.com/hiend/simbirsoft_examples.git simsoftex

Переходим в новый каталог simsoftex и ставим туда composer:

    curl -s http://getcomposer.org/installer | php

После чего устанавливаем зависимости:

    php composer.phar install

На этом этапе уже можно запустить тестовый сервер:

   php app/console server:run

и открыть в браузере:

   http://localhost:8000/app_dev.php/

2) Проверка конфигурации системы
--------------------------------

Выполните из командной строки:

    php app/check.php

Если показывает какие-то ошибки - исправляем.

Далее открываем в браузере:

    http://localhost:8000/config.php

Если показывает какие-то ошибки - исправляем.

3) Подготовка демо
------------------

Создаем базу данных:

    php app/console doctrine:database:create

Создаем таблицы:

    php app/console doctrine:schema:update --force

Устанавливаем ресурсы:

    php app/console assets:install

Создаем фиктуры:

    php app/console doctrine:fixtures:load

Демо готово к использованию:

    http://localhost:8000/app_dev.php/demo/

Stay Tuned