# Art_Gallery
Порядок запуска:
1. Запустить сервер Apache;
2. Запустить СУБД postgres;
3. Создать в СУБД пользователя "postgres"  с паролем "yo_password" или же обновить для существующего через команду: ALTER USER user_name WITH PASSWORD 'yo_password';
4. Создать базу данных "test";
5. Создать еще одного пользователя "superadm" с любым паролем;
6. Открыть Query Tool и запустить файл script.sql;
7. Скопировать файлы в htdocs/project (проверить что название папки проекта project совпадает с названием в ссылке, файл index.php, 27 строка);
8. Открыть проект в браузере по ссылке http://localhost/project/index.php;
9. Для того чтобы запустить администратора перейти по ссылке http://localhost/project_0/adm/adm_page.php;
P.S: при проблемах, попробывать презапустить Apache.
