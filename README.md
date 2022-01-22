# Ad API

## Как запустить

- Выкачать проект
- Скопировать файл .env.example в .env и поправить в новом файле настройки под себя
- docker-compose up --build -d
- зайти в корень докер-контейнера docker exec -it cmtt bash

В корне докер-контейнера выполнить:
- composer install
- ./vendor/bin/doctrine-migrations migrate
