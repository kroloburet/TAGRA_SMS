<!-- Developer: Sergey Nizhnik kroloburet@gmail.com -->

<img src="https://raw.githubusercontent.com/kroloburet/TAGRA_CMS/master/img/tagra_share.jpg" style="max-width:900px;margin-bottom:1.5em;">

## Кратко о системе
Cистема управления контентом Tagra разработана для реализации веб-ресурсов (корпоративных, визиток, промо) с адаптивным дизайном и высокой производительностью. Также система может служить базой для разработки более масштабных и сложных веб-проектов. В отличии от более <q>тяжеловесных</q> CMS (Joomla, WordPress и т.д), использует меньше программного кода, библиотек, не перегружена лишним и проста в администрировании. Tagra написана на базе PHP-фреймворка [CodeIgniter](https://www.codeigniter.com).

## Требования к серверу
* PHP версии 7.0 или выше
* MySQL (5.1+) via the mysql (deprecated), mysqli и pdo драйвера

## Установка системы на удаленный хост
1. Скачайте архив с системой и распакуйте его. [Загрузить архив TAGRA_CMS-master.zip](https://github.com/kroloburet/TAGRA_CMS/archive/master.zip)
2. Выгрузите все перечисленные ниже файлы и папки вместе с их содержимым в рабочий каталог вашего сервера.
   ```
   application/
   css/
   img/
   instal/
   scripts/
   system/
   Tagra_UI/
   .htaccess
   favicon.ico
   403.html
   plug.html
   index.php
   robots.txt
   ```
3. Создайте базу данных MySQL если она еще не создана
4. Перейдите по адресу `www.вашсайт`
5. Заполните поля начальных установок для вашего сайта и жмите <q>Установить систему</q>. Инсталлятор создаст необходимые таблицы в базе данных, папку `/upload` для ваших медиафайлов, файлы конфигурации системы и базы данных.
6. Если установка прошла успешно, нажмите на <q>Удалить инсталлятор</q>

## Установка в локальном окружении

### Вам понадобится
* [Git](https://git-scm.com/doc)
* [Docker](https://docs.docker.com/) и [Docker Compose](https://docs.docker.com/compose/install/)

### Установка
1. В консоли перейдите в папку куда будет клонирована корневая папка системы и выполните следующие команды:
   ```
   git clone https://github.com/kroloburet/TAGRA_CMS.git
   cd ./TAGRA_CMS
   git checkout -b dev
   docker-compose up -b --build
   ```
2. Перейдите по адресу `localhost:80` и заполните поля начальных установок:
   ```
   Имя базы данных: tagra
   Хост базы данных: tagra_db
   Пользлватель базы данных: admin
   Пароль пользователя базы данных: admin
   ```
3. Инсталлятор создаст необходимые таблицы в базе данных, папку `/upload` для ваших медиафайлов, файлы конфигурации системы и базы данных.
4. Если установка прошла успешно, нажмите на <q>Удалить инсталлятор</q> или удалите папку `/instal` со всем ее содержимым вручную.
5. PhpMyadmin для управления базой доступен по адресу `localhost:8080`
   ```
   Root пользователь: root
   Пароль root пользователя: root
   ```
6. В `/local` находятся папки сервисов используемыми Docker. Контейнер базы данных использует для записи и хранения данных папку `/local/db/data`

## Лицензия
Свободная лицензия [MIT license](https://opensource.org/licenses/MIT).

***
Если возникли трудности или выше перечисленные инструкции для вас сложны, обратитесь к разработчику системы: <img src="https://raw.githubusercontent.com/kroloburet/TAGRA_CMS/master/img/i.jpg"> kroloburet@gmail.com