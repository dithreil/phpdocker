# Запуск #

1. Клонировать проект в любую директорию
2. В терминале директории выполнить: `docker-compose up -d`
3. Далее необходимо подключиться в контейнер `php-fpm` и выполнить миграцию:
    * используется команда `docker exec -it php_fpm bash` для подключения
    * выполнить `composer install`
    * далее нужно выполнить `php bin/console do:mi:mi`
4. Далее можно проверить работу API при помощи Postman и/или OpenApi:
`http://localhost:53000/api/doc`
