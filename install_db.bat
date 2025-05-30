@echo off
set name_db=shop
echo Внимание! Если БД с именем %name_db% уже существует, она будет удалена и создана заново!
echo Поэтому, если у вас есть важные данные в БД %name_db%, вам необходимо прервать работу 
echo данного скрипта (Ctrl+C) и сделать вручную резервную копию БД.
echo ====================================================================================
echo Этап 0. Создание бэкапа БД %name_db%.
c:\xampp\mysql\bin\mysqldump.exe -uroot -hlocalhost %name_db% >old_db.sql -vvv
echo ====================================================================================
echo Этап 1. Удаление существующей БД %name_db%.
c:\xampp\mysql\bin\mysql -uroot -hlocalhost -vvv -e "DROP DATABASE IF EXISTS %name_db%"
echo ====================================================================================
echo Этап 2. Создание новой БД %name_db%.
c:\xampp\mysql\bin\mysql -uroot -hlocalhost -vvv -e "SET NAMES 'utf8'"
c:\xampp\mysql\bin\mysql -uroot -hlocalhost -vvv -e "CREATE DATABASE %name_db%  CHARACTER SET utf8 COLLATE utf8_general_ci;"
echo ====================================================================================
echo Этап 3. Импорт данных в БД %name_db% из файла db.sql.
c:\xampp\mysql\bin\mysql -uroot -hlocalhost -vvv %name_db% <db.sql
echo ====================================================================================
echo Импорт успешно завершен.
pause